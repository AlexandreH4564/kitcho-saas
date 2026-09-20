<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $this->authorizeEstablishment($establishment);

        /*
        |--------------------------------------------------------------------------
        | Período
        |--------------------------------------------------------------------------
        */

        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Indicadores principais
        |--------------------------------------------------------------------------
        */

        $completedOrders = $establishment->orders()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalRevenue = (clone $completedOrders)->sum('total');

        $totalOrders = (clone $completedOrders)->count();

        $averageTicket = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Faturamento por mês
        |--------------------------------------------------------------------------
        */

        $revenueByMonth = $establishment->orders()
            ->select(
                DB::raw("DATE_TRUNC('month', created_at) as month"),
                DB::raw('SUM(total) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_TRUNC('month', created_at)"))
            ->orderBy('month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Pedidos por mês
        |--------------------------------------------------------------------------
        */

        $ordersByMonth = $establishment->orders()
            ->select(
                DB::raw("DATE_TRUNC('month', created_at) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_TRUNC('month', created_at)"))
            ->orderBy('month')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Produtos mais vendidos
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.establishment_id', $establishment->id)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Categorias mais vendidas
        |--------------------------------------------------------------------------
        */

        $topCategories = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.establishment_id', $establishment->id)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Horários com maior movimento
        |--------------------------------------------------------------------------
        */

        $ordersByHour = $establishment->orders()
            ->select(
                DB::raw("EXTRACT(HOUR FROM created_at) as hour"),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw("EXTRACT(HOUR FROM created_at)"))
            ->orderBy('hour')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Pedidos por status
        |--------------------------------------------------------------------------
        */

        $ordersByStatus = $establishment->orders()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Mesas mais utilizadas
        |--------------------------------------------------------------------------
        */

        $tablesUsage = DB::table('orders')
            ->join('tables', 'tables.id', '=', 'orders.table_id')
            ->where('orders.establishment_id', $establishment->id)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'tables.number',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total) as revenue')
            )
            ->groupBy('tables.id', 'tables.number')
            ->orderByDesc('total_orders')
            ->get();

        return view('reports.index', compact(
            'establishment',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'averageTicket',
            'revenueByMonth',
            'ordersByMonth',
            'topProducts',
            'topCategories',
            'ordersByHour',
            'ordersByStatus',
            'tablesUsage'
        ));
    }

    private function authorizeEstablishment(
        Establishment $establishment
    ): void {
        abort_unless(
            auth()->user()
                ->establishments()
                ->whereKey($establishment->id)
                ->exists(),
            403
        );
    }
}