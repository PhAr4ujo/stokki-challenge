<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarrierRequest;
use App\Models\Carrier;
use App\Services\Interfaces\ICarrierService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarrierController extends Controller
{
    public function __construct(
        protected ICarrierService $iCarrierService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $filters = [
            'name:like' => $request->input('name'),
        ];

        $carriers = $this->iCarrierService->listRecords($filters, $perPage)
            ->appends($request->query());

        return Inertia::render('Carriers/index', [
            'carriers' => $carriers,
            'filters' => [
                'name' => $request->input('name'),
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Carriers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarrierRequest $request)
    {
        $this->iCarrierService->insert($request->validated());

        return redirect()
            ->route('carriers.index')
            ->with('success', 'Transportadora cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Carrier $carrier)
    {
        return Inertia::render('Carriers/Show', [
            'carrier' => $carrier,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carrier $carrier)
    {
        return Inertia::render('Carriers/Edit', [
            'carrier' => $carrier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarrierRequest $request, Carrier $carrier)
    {
        $this->iCarrierService->edit($carrier->id, $request->validated());

        return redirect()
            ->route('carriers.index')
            ->with('success', 'Transportadora atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carrier $carrier)
    {
        $this->iCarrierService->delete($carrier->id);

        return redirect()
            ->route('carriers.index')
            ->with('success', 'Transportadora excluída com sucesso.');
    }
}
