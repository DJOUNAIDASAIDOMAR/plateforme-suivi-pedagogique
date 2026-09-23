<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cours;

class CoursController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES COURS
    |--------------------------------------------------------------------------
    |
    | Retourne tous les cours pour l'application Flutter.
    |
    */
    public function index()
    {
        $cours = Cours::with([
            'filier',
            'formateur.user',
        ])
            ->orderBy('titre_cours')
            ->get();

        return response()->json([
            'cours' => $cours,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DÉTAIL D'UN COURS
    |--------------------------------------------------------------------------
    |
    | Retourne un cours avec sa filière, son formateur,
    | ses leçons et ses quiz.
    |
    */
    public function show($id)
    {
        $cours = Cours::with([
            'filier',
            'formateur.user',
            'lecons',
            'quizzes',
        ])->find($id);

        if (!$cours) {
            return response()->json([
                'message' => 'Cours introuvable.',
            ], 404);
        }

        return response()->json([
            'cours' => $cours,
        ]);
    }
}