<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Repository;

class ProductRepository extends Repository implements IProductRepository
{
    public function model(): string
    {
        return Product::class;
    }

    public function mountDashboardData(): array
    {
        return \Cache::remember('dashboard_data', now()->addMinutes(15), function () {

            $result = \DB::table('products')
                ->selectRaw('
                    COUNT(*) as order_count,
                    SUM(amount * price) as total_sold,
                    AVG(amount * price) as average_order_value,
                    COUNT(DISTINCT customer_name) as unique_customers,
                    SUM(amount) as total_quantity_sold
                ')
                ->first();

            $orderCount = (int) ($result->order_count ?? 0);
            $totalSold = (float) ($result->total_sold ?? 0);
            $averageOrderValue = (float) ($result->average_order_value ?? 0);
            $uniqueCustomers = (int) ($result->unique_customers ?? 0);
            $totalQuantitySold = (int) ($result->total_quantity_sold ?? 0);
            $averageItemPrice = $totalQuantitySold > 0 ? ($totalSold / $totalQuantitySold) : 0;

            $topProducts = \DB::table('products')
                ->selectRaw('name, SUM(amount * price) as total_sold, SUM(amount) as quantity_sold')
                ->groupBy('name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get()
                ->toArray();

            $mostPopularProduct = \DB::table('products')
                ->selectRaw('name, SUM(amount) as total_quantity')
                ->groupBy('name')
                ->orderByDesc('total_quantity')
                ->limit(1)
                ->first();

            $topCustomers = \DB::table('products')
                ->selectRaw('customer_name, SUM(amount * price) as total_spent, COUNT(*) as order_count')
                ->groupBy('customer_name')
                ->orderByDesc('total_spent')
                ->limit(5)
                ->get()
                ->toArray();

            $sixMonthsAgo = now()->subMonths(6)->startOfMonth()->toDateString();
            $monthlySales = \DB::table('products')
                ->selectRaw("
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    SUM(amount * price) as total_sold,
                    SUM(amount) as quantity_sold,
                    COUNT(*) as orders
                ")
                ->where('created_at', '>=', $sixMonthsAgo)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->toArray();

            return [
                'order_count'          => $orderCount,
                'total_sold'           => $totalSold,
                'average_order_value'  => $averageOrderValue,
                'unique_customers'     => $uniqueCustomers,
                'top_products'         => $topProducts,
                'total_quantity_sold'  => $totalQuantitySold,
                'average_item_price'   => $averageItemPrice,
                'most_popular_product' => $mostPopularProduct,
                'top_customers'        => $topCustomers,
                'monthly_sales'        => $monthlySales,
            ];
        });
    }
}
