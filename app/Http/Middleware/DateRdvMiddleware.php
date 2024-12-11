<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $rdvs = DB::table('rdvpatients')->select('rdvpatients.*')->get(); 

        foreach ($rdvs as $value) {

            $today = Carbon::today();
            $rdvDate = Carbon::parse($value->date);

            if ($rdvDate->lessThan($today)) {

                $updateData =[
                    'statut' => 'terminer',
                    'updated_at' => now(),
                ];

                $Update = DB::table('rdvpatients')
                    ->where('id', '=', $value->id)
                    ->update($updateData);
            }
            
        }

        return $next($request);
    }
}
