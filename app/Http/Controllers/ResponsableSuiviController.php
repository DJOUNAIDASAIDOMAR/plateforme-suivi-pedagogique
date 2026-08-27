<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsableSuiviController extends Controller
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

        $etudiants = Etudiant::with([
            'user',
            'filier',
            'resultats.quiz',
        ])
            ->withCount('resultats')
            ->get()
            ->sortBy(
                fn ($etudiant) =>
                    $etudiant->user?->nom ?? ''
            );

        return view(
            'responsable.suivi.index',
            compact('etudiants')
        );
    }
}