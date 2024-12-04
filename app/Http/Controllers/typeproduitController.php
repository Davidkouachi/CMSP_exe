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
use App\Models\produit;
use App\Models\typeproduit;

class typeproduitController extends Controller
{
    public function typeproduit_new()
    {
        $today = Carbon::today();
        $typeproduits = typeproduit::whereDate('created_at', $today)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $tauxs = taux::all();
        $produits = produit::all();

        return view('assurance.nouveau.typeproduit',['typeproduits' => $typeproduits,'tauxs' => $tauxs,'produits' => $produits]);
    }

    public function insert_typeproduit(Request $request)
    {

    }

    public function update_typeproduit(Request $request, $id)
    {

    }
}
