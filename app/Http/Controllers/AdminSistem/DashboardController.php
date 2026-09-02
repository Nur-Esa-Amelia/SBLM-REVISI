<?php

namespace App\Http\Controllers\AdminSistem;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard Admin Sistem.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count(); 
        $totalProdi = Prodi::count();

        return view('adminsistem.dashboard', compact('totalUsers', 'totalProdi'));
    }
}
