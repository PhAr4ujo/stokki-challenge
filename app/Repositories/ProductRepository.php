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

    /**
     * Returns data for the dashboard:
     * - total number of product orders
     * - total amount sold in R$
     *
     * @return array
     */
    /**
     * Returns comprehensive data and KPIs for the dashboard:
     * - Total number of product orders
     * - Total amount sold in R$ (sum of amount * price)
     * - Average order value (R$)
     * - Number of unique customers (based on customer_name)
     * - Product with the highest total sold
     * - Product with the lowest total sold
     * - Total quantity of items sold
     * - Average price per item
     * - Most popular product by quantity sold
     * - Sales per customer leaderboard (top 5)
     * - Monthly sales (last 6 months, for time series charts)
     *
     * @return array
     */
    public function mountDashboardData(): array
    {
        // Total number of product orders (records)
        $orderCount = Product::count();

        // Total amount sold (amount * price for each row, then sum)
        $totalSold = Product::selectRaw('SUM(amount * price) as sum')->value('sum') ?? 0;

        // Average order value (average of amount * price across all orders)
        $averageOrderValue = Product::selectRaw('AVG(amount * price) as avg')->value('avg') ?? 0;

        // Number of unique customers
        $uniqueCustomers = Product::distinct('customer_name')->count('customer_name');

        // Top 5 products with highest total sold (amount * price)
        $topProducts = Product::selectRaw('name, SUM(amount * price) as total_sold, SUM(amount) as quantity_sold')
            ->groupBy('name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get()
            ->toArray();

        // Total quantity of items sold
        $totalQuantitySold = Product::sum('amount');

        // Average price per item (total revenue / total quantity)
        $averageItemPrice = $totalQuantitySold > 0 ? $totalSold / $totalQuantitySold : 0;

        // Most popular product by quantity sold
        $mostPopularProduct = Product::selectRaw('name, SUM(amount) as total_quantity')
            ->groupBy('name')
            ->orderByDesc('total_quantity')
            ->first();

        // Top 5 customers by total purchases
        $topCustomers = Product::selectRaw('customer_name, SUM(amount * price) as total_spent, COUNT(*) as order_count')
            ->groupBy('customer_name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get()
            ->toArray();

        // Monthly sales in the last 6 months (for time series chart)
        $monthlySales = Product::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(amount * price) as total_sold,
                SUM(amount) as quantity_sold,
                COUNT(*) as orders
            ")
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();

        return [
            'order_count'         => $orderCount,
            'total_sold'          => $totalSold,
            'average_order_value' => $averageOrderValue,
            'unique_customers'    => $uniqueCustomers,
            'top_products'        => $topProducts, // changed key from 'top_product' to 'top_products'
            'total_quantity_sold' => $totalQuantitySold,
            'average_item_price'  => $averageItemPrice,
            'most_popular_product'=> $mostPopularProduct,
            'top_customers'       => $topCustomers,
            'monthly_sales'       => $monthlySales,
        ];
    }
}
