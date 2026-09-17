<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\View\View;

class TableQrCodeController extends Controller
{
    public function index(Establishment $establishment): View
    {
        $user = auth()->user();

        abort_unless(
            $user->establishments()
                ->whereKey($establishment->id)
                ->exists(),
            403
        );

        $tables = $establishment->tables()
            ->orderBy('number')
            ->get();

        $tableQrs = $tables->map(function ($table) use ($establishment) {
            $url = route('menu.show', [
                'slug' => $establishment->slug,
                'table' => $table->id,
            ]);

            return [
                'table' => $table,
                'url' => $url,
            ];
        });

        return view('establishments.qr-codes', [
            'establishment' => $establishment,
            'tableQrs' => $tableQrs,
        ]);
    }
}