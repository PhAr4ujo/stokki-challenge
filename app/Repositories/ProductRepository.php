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
        $agg = \Cache::remember('dash_agg', now()->addMinutes(15), function () {
            return (array) \DB::table('products')
                ->selectRaw('
                    COUNT(*) as order_count,
                    SUM(total) as total_sold,
                    AVG(total) as average_order_value,
                    SUM(amount) as total_quantity_sold
                ')
                ->first(); 
        });
        

        $uniqueCustomers = \Cache::remember('dash_unique_customers', now()->addMinutes(15), function () {
            return (int) \DB::table('products')
                ->distinct('customer_name')
                ->count('customer_name');
        });

        $topProducts = \Cache::remember('dash_top_products', now()->addMinutes(15), function () {
            return \DB::table('products')
                ->selectRaw('name, SUM(total) as total_sold, SUM(amount) as quantity_sold')
                ->groupBy('name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get()
                ->toArray();
        });

        $mostPopularProduct = collect($topProducts)->sortByDesc('quantity_sold')->first();

        $topCustomers = \Cache::remember('dash_top_customers', now()->addMinutes(15), function () {
            return \DB::table('products')
                ->selectRaw('customer_name, SUM(total) as total_spent, COUNT(*) as order_count')
                ->groupBy('customer_name')
                ->orderByDesc('total_spent')
                ->limit(5)
                ->get()
                ->toArray();
        });

        $monthlySales = \Cache::remember('dash_monthly_sales', now()->addMinutes(15), function () {
            $sixMonthsAgo = now()->subMonths(6)->startOfMonth()->toDateString();
            return \DB::table('products')
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as total_sold, SUM(amount) as quantity_sold, COUNT(*) as orders")
                ->where('created_at', '>=', $sixMonthsAgo)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->toArray();
        });

        $totalSold         = (float) ($agg['total_sold'] ?? 0);
        $totalQuantitySold = (int)   ($agg['total_quantity_sold'] ?? 0);

        return [
            'order_count'          => (int)   ($agg['order_count'] ?? 0),
            'total_sold'           => $totalSold,
            'average_order_value'  => (float) ($agg['average_order_value'] ?? 0),
            'unique_customers'     => $uniqueCustomers,
            'top_products'         => $topProducts,
            'total_quantity_sold'  => $totalQuantitySold,
            'average_item_price'   => $totalQuantitySold > 0 ? ($totalSold / $totalQuantitySold) : 0,
            'most_popular_product' => $mostPopularProduct,
            'top_customers'        => $topCustomers,
            'monthly_sales'        => $monthlySales,
        ];
    }
}
