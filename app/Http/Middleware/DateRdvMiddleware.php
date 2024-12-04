<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;

// use App\Models\rdvpatient;

class DateRdvMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // // Récupérer tous les lits
        // $rdvs = rdvpatient::all(); 

        // foreach ($rdvs as $value) {

        //     // Comparer la date du rendez-vous avec la date actuelle en utilisant Carbon
        //     $today = Carbon::today(); // Obtenir la date actuelle avec Carbon
        //     $rdvDate = Carbon::parse($value->date); // Convertit la date du rendez-vous en objet Carbon

        //     // Si la date du rendez-vous est inférieure à la date du jour
        //     if ($rdvDate->lessThan($today)) {
        //         // Changer le statut
        //         $value->statut = 'terminer'; // Met à jour le statut (par exemple 'Expiré')
        //         $value->save(); // Sauvegarde les changements dans la base de données
        //     }
            
        // }

        return $next($request);
    }
}
