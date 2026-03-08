<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Issue;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;

class IssueController extends Controller
{
    // Ellenorzi, hogy a felhasznalo admin jogosultsaggal rendelkezik-e.
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    // A sajat auto listat adja vissza; adminnal teljes lista, usernel szurt lista.
    private function userCarsQuery()
    {
        $query = Car::orderBy('make_model');

        if (!$this->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    // Karbantarthatosag miatt kulon metodusban kezeljuk az auto tulajdonjog-ellenorzest.
    private function ensureCarOwnershipById(int $carId): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $ownsCar = Car::where('id', $carId)
            ->where('user_id', auth()->id())
            ->exists();

        if (!$ownsCar) {
            abort(403);
        }
    }

    // A hiba megtekintese/szerkesztese csak a tulajdonosnak vagy adminnak engedelyezett.
    private function ensureIssueOwnership(Issue $issue): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $ownsIssue = $issue->car()->where('user_id', auth()->id())->exists();

        if (!$ownsIssue) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Issue::with('car')->latest();

        if (!$this->isAdmin()) {
            $query->whereHas('car', function ($carQuery) {
                $carQuery->where('user_id', auth()->id());
            });
        }

        $issues = $query->get();

        return view('issues.index', compact('issues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = $this->userCarsQuery()->get();

        return view('issues.create', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        $validated = $request->validated();

        $this->ensureCarOwnershipById((int) $validated['car_id']);

        Issue::create($validated);

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $this->ensureIssueOwnership($issue);

        $issue->load('car');

        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        $this->ensureIssueOwnership($issue);

        $cars = $this->userCarsQuery()->get();

        return view('issues.edit', compact('issue', 'cars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $this->ensureIssueOwnership($issue);

        $validated = $request->validated();

        $this->ensureCarOwnershipById((int) $validated['car_id']);

        $issue->update($validated);

        return redirect()->route('issues.index')
            ->with('success', 'Hiba sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $this->ensureIssueOwnership($issue);

        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Hiba törölve!');
    }
}
