<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// use App\Models\lit;
// use App\Models\chambre;

class StatutChambremiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // // Récupérer tous les lits
        // $lits = lit::all(); 

        // // Grouper les lits par chambre
        // $groupedLits = $lits->groupBy('chambre_id');

        // // Parcourir chaque chambre
        // foreach ($groupedLits as $chambre_id => $litsInChambre) {
        //     // Compter le nombre de lits occupés dans la chambre
        //     $nbre_lit_occupes = $litsInChambre->where('statut', '!=', 'disponible')->count();

        //     // Récupérer la chambre correspondante
        //     $ch = chambre::find($chambre_id);

        //     // Si la chambre existe
        //     if ($ch) {
        //         // Si le nombre de lits occupés est supérieur ou égal au nombre total de lits de la chambre
        //         if ($nbre_lit_occupes >= $ch->nbre_lit) {
        //             // Marquer la chambre comme 'indisponible'
        //             $ch->statut = 'indisponible';
        //         } else {
        //             // Sinon, la chambre est 'disponible'
        //             $ch->statut = 'disponible';
        //         }

        //         // Sauvegarder le nouveau statut de la chambre
        //         $ch->save();
        //     }
        // }

        return $next($request);
    }

}
