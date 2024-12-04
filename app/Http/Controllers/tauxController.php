<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\taux;


class tauxController extends Controller
{
    public function taux_new()
    {
        $today = Carbon::today();
        $tauxs = taux::whereDate('created_at', $today)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('assurance.nouveau.taux',['tauxs' => $tauxs]);
    }

    public function insert_taux(Request $request)
    {

    }

    public function update_taux(Request $request, $id)
    {

    }
}
