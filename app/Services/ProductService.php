<?php

namespace App\Services;

use App\Repositories\Interfaces\IProductRepository;
use App\Services\Interfaces\IProductService;
use App\Services\Service;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\Storage;

class ProductService extends Service implements IProductService
{
 
    public function __construct(
        IProductRepository $repository,
    ) {
        parent::__construct($repository);
    }

    public function insert($data)
    {
        \Cache::forget('dashboard_data');
        return parent::insert($data);
    }

    public function edit($id, $data)
    {
        \Cache::forget('dashboard_data');
        return parent::edit($id, $data);
    }

    public function delete($id)
    {
        \Cache::forget('dashboard_data');
        return parent::delete($id);
    }

    public function mountDashboardData(): array
    {
        return $this->repository->mountDashboardData();
    }

    public function exportData()
    {
        $products = $this->repository->getAll();

        $headers = [
            'ID',
            'Nome',
            'Descrição',
            'Cliente',
            'Preço',
            'Quantidade',
            'Total',
            'Criado em',
            'Atualizado em',
        ];

        $callback = function() use ($products, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers, ';');

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->description,
                    $product->customer_name,
                    $product->price,
                    $product->amount,
                    $product->total ?? $product->price * $product->amount,
                    $product->created_at,
                    $product->updated_at,
                ], ';');
            }
            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            [
                "Content-Type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => 'attachment; filename="produtos.csv"',
            ]
        );
    }
}
