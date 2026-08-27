<?php

namespace App\Http\Controllers;

use App\Models\Resultat;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsableResultatController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless(
            $user
            && $user->role === 'responsable_pedagogique'
            && $user->responsablePedagogique,
            403,
            'Accès réservé au responsable pédagogique.'
        );

        $resultats = Resultat::with([
            'etudiant.user',
            'etudiant.filier',
            'quiz.cours',
        ])
            ->orderByDesc('date_resultat')
            ->get();

        return view(
            'responsable.resultats.index',
            compact('resultats')
        );
    }
}