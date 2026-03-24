<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Interfaces\IProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{

    public function __construct
    (
        protected IProductService $iProductService,
    ) 
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        $filters = [
            'customer_name:like' => $request->input('customer'),
        ];

        $products = $this->iProductService->listRecords($filters, $perPage)
            ->appends($request->query());

        return Inertia::render('Products/index', [
            'products' => $products,
            'filters' => [
                'customer' => $request->input('customer'),
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
