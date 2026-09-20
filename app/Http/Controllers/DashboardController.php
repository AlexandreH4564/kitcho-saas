<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $establishment = auth()->user()
            ->establishments()
            ->first();

        if (!$establishment) {
            return view('dashboard', [
                'establishment' => null,
                'ordersToday' => 0,
                'revenueToday' => 0,
                'occupiedTables' => 0,
                'pendingOrders' => 0,
                'recentOrders' => collect(),
            ]);
        }

        $ordersToday = $establishment->orders()
            ->whereDate('created_at', today())
            ->count();

        $revenueToday = $establishment->orders()
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');

        $occupiedTables = $establishment->tables()
            ->where('status', 'occupied')
            ->count();

        $pendingOrders = $establishment->orders()
            ->whereIn('status', [
                'pending',
                'preparing',
                'ready',
            ])
            ->count();

        $recentOrders = $establishment->orders()
            ->with('table')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'establishment',
            'ordersToday',
            'revenueToday',
            'occupiedTables',
            'pendingOrders',
            'recentOrders'
        ));
    }
}