<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Colaborador;

class FilterSector
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $setor = $user->setor;

            // Verifica se o usuário está em um setor específico e filtra os colaboradores em conformidade
            if ($setor) {
                $colaboradores = Colaborador::where('setor', $setor)->get();
                view()->share('colaboradores', $colaboradores);
            }
        }

        return $next($request);
    }
}
