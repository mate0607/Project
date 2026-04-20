<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleImage;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    // Egy helyen tartjuk a create/edit nezethez szukseges valaszthato adatokat.
    private function getFormDependencies(): array
    {
        return [];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['car', 'buyer', 'seller', 'images'])->latest()->paginate(10);
        $vehicleConfig = config('vehicles');

        return view('sales.index', compact('sales', 'vehicleConfig'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dependencies = $this->getFormDependencies();

        return view('sales.create', $dependencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $data = $request->validated();
        unset($data['images']);

        $sale = Sale::create([
            ...$data,
            'seller_id' => $userId,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $sale->images()->create([
                    'path' => $file->store('sales', 'public'),
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('sales.index')
            ->with('success', 'Eladás sikeresen létrehozva!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['car', 'buyer', 'seller', 'images']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale->load('images');
        $dependencies = $this->getFormDependencies();
        $dependencies['sale'] = $sale;

        return view('sales.edit', $dependencies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $data = $request->validated();
        unset($data['images']);

        $sale->update($data);

        if ($request->hasFile('images')) {
            $maxSort = $sale->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $i => $file) {
                $sale->images()->create([
                    'path' => $file->store('sales', 'public'),
                    'sort_order' => $maxSort + 1 + $i,
                ]);
            }
        }

        return redirect()->route('sales.index')
            ->with('success', 'Eladás sikeresen frissítve!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Eladás törölve!');
    }

    public function destroyImage(Sale $sale, SaleImage $image)
    {
        if ($image->sale_id !== $sale->id) {
            abort(403);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Kép törölve!');
    }
}
