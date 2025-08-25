<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartController extends Controller
{
    public function index()
    {
        // Esempio di dati per i charts
        $chartData = [
            'sales' => [
                'labels' => ['Gen', 'Feb', 'Mar', 'Apr'],
                'data' => [120, 150, 170, 200],
            ],
            'stock' => [
                'labels' => ['Prodotto A', 'Prodotto B', 'Prodotto C'],
                'data' => [30, 45, 25],
            ],
        ];

        return Inertia::render('Admin/Charts/Index', [
            'chartData' => $chartData,
        ]);
    }
}