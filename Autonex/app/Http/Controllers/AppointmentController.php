<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\AdminHelpers;
use App\Mail\AppointmentConfirmationMail;
use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\Car;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    use AdminHelpers;

    // Megtekintesnel csak tulajdonos vagy admin lathatja az adott idopontot.
    private function ensureAppointmentOwnership(Appointment $appointment): void
    {
        if (!$this->isAdmin() && $appointment->user_id !== $this->currentUserId()) {
            abort(403);
        }
    }

    // Itt keszul a valaszthato auto lista: usernel sajat, adminnal teljes.
    // userCarsQuery() is now provided by AdminHelpers trait.

    // Tarolt idopont adatok validalasa.
    private function validateStoreData(Request $request): array
    {
        return $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);
    }

    // Nem admin felhasznalo csak a sajat autojara foglalhat idopontot.
    private function ensureCarOwnershipById(int $carId, int $userId): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $ownsCar = Car::where('id', $carId)
            ->where('user_id', $userId)
            ->exists();

        if (!$ownsCar) {
            abort(403);
        }
    }

    // Ugyanarra az idopontra csak egy megerositett foglalas lehet.
    private function hasConfirmedConflict(string $date, string $time): bool
    {
        return Appointment::where('date', $date)
            ->where('time', $time)
            ->where('status', 'confirmed')
            ->exists();
    }

    // Foglalasi utkozes eseten ugyanazt a validacios hibat dobjuk vissza, mint korabban.
    private function throwTimeConflictValidationError(): void
    {
        throw ValidationException::withMessages([
            'time' => 'Erre az időpontra már van megerősített foglalás',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Appointment::with(['car', 'user'])->latest();

        if (!$this->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $appointments = $query->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = $this->userCarsQuery()->get();

        return view('appointments.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = $this->currentUserId();

        if (!$userId) {
            return redirect()->route('login');
        }

        $validated = $this->validateStoreData($request);

        $this->ensureCarOwnershipById((int) $validated['car_id'], $userId);

        try {
            if ($this->hasConfirmedConflict($validated['date'], $validated['time'])) {
                $this->throwTimeConflictValidationError();
            }
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->errors());
        }

        $appointment = Appointment::create([
            ...$validated,
            'user_id' => $userId,
            'status' => 'pending',
        ]);

        try {
            $appointment->load(['user', 'car']);
            Mail::to($appointment->user->email)
                ->send(new AppointmentConfirmationMail($appointment));
        } catch (\Throwable $e) {
            Log::error('APPOINTMENT_MAIL: send FAILED.', [
                'appointment_id' => $appointment->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Időpont sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $this->ensureAppointmentOwnership($appointment);

        $appointment->load(['car', 'user', 'servicePhotos']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Cancel an upcoming appointment.
     */
    public function cancel(Appointment $appointment)
    {
        $this->ensureAppointmentOwnership($appointment);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Csak függőben lévő vagy megerősített időpont mondható le.');
        }

        $appointment->update(['status' => 'cancelled']);

        // Értesítés küldése a felhasználónak
        AdminNotification::create([
            'user_id' => $appointment->user_id,
            'title'   => 'Időpont lemondva',
            'message' => 'A(z) ' . ($appointment->car?->make_model ?? 'ismeretlen autó') . ' szerviz időpontja (' . Carbon::parse($appointment->date)->format('Y.m.d') . ' ' . $appointment->time . ') lemondásra került.',
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Időpont sikeresen lemondva.');
    }

    /**
     * Reschedule an upcoming appointment.
     */
    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->ensureAppointmentOwnership($appointment);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Csak függőben lévő vagy megerősített időpont ütemezhető át.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        if ($this->hasConfirmedConflict($validated['date'], $validated['time'])) {
            return back()->withErrors(['time' => 'Erre az időpontra már van megerősített foglalás.']);
        }

        $oldDate = Carbon::parse($appointment->date)->format('Y.m.d') . ' ' . $appointment->time;

        $appointment->update([
            'date'   => $validated['date'],
            'time'   => $validated['time'],
            'status' => 'pending',
        ]);

        // Értesítés küldése a felhasználónak
        AdminNotification::create([
            'user_id' => $appointment->user_id,
            'title'   => 'Időpont átütemezve',
            'message' => 'A(z) ' . ($appointment->car?->make_model ?? 'ismeretlen autó') . ' szerviz időpontja átütemezésre került: ' . $oldDate . ' → ' . $validated['date'] . ' ' . $validated['time'] . '. Státusz: függőben.',
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Időpont sikeresen átütemezve.');
    }

    /**
     * Download the work order as PDF (only when service_stage = ready).
     */
    public function downloadWorkOrderPdf(Appointment $appointment)
    {
        $this->ensureAppointmentOwnership($appointment);

        if ($appointment->service_stage !== 'ready') {
            abort(403, 'A számla csak akkor tölthető le, ha az autó készen áll az átvételre.');
        }

        $appointment->load(['car', 'user']);

        $pdf = Pdf::loadView('appointments.work-order-pdf', compact('appointment'))
            ->setPaper('a4', 'portrait');

        $filename = 'szamla-' . ($appointment->work_number ?? $appointment->id) . '.pdf';

        return $pdf->download($filename);
    }

}
