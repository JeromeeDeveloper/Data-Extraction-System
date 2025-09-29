<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get record counts for dashboard stats
            $cifCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.CIF")[0]->count ?? 0;
            $lnaccCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.LNACC")[0]->count ?? 0;
            $relaccCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.RELACC")[0]->count ?? 0;
            $trnhistCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.TRNHIST")[0]->count ?? 0;
            $brparmsCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.BRPARMS")[0]->count ?? 0;
            $userlookupCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.USERLOOKUP")[0]->count ?? 0;
            $lnhistCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.LNHIST")[0]->count ?? 0;

            return view('admin.dashboard.dashboard', compact(
                'cifCount', 'lnaccCount', 'relaccCount', 'trnhistCount',
                'brparmsCount', 'userlookupCount', 'lnhistCount'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('admin.dashboard.dashboard', [
                'cifCount' => 'Error',
                'lnaccCount' => 'Error',
                'relaccCount' => 'Error',
                'trnhistCount' => 'Error',
                'brparmsCount' => 'Error',
                'userlookupCount' => 'Error',
                'lnhistCount' => 'Error'
            ]);
        }
    }
}
