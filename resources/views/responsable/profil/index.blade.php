@extends('layouts.dashboard')

@section('title', 'Mon profil')

@section('content')

<div style="
    min-height:650px;
    padding:50px 30px;
    background:#f3f6fb;
">

<div style="max-width:800px;margin:auto;">

    <div style="
        margin-bottom:25px;
        padding:30px;
        border-radius:20px;
        background:linear-gradient(135deg,#1e3a8a,#2563eb);
        color:white;
    ">

        <h1>Mon profil</h1>

        <p>
            Gérez vos informations personnelles.
        </p>

    </div>

    @if (session('success'))

        <div style="
            margin-bottom:20px;
            padding:15px;
            border-radius:10px;
            background:#f0fdf4;
            color:#15803d;
        ">
            {{ session('success') }}
        </div>

    @endif

    <div style="
        padding:35px;
        border-radius:20px;
        background:white;
    ">

        <form
            method="POST"
            action="{{ route('responsable.profil.update') }}"
        >

            @csrf
            @method('PUT')

            <label>Nom complet</label>

            <input
                type="text"
                name="nom"
                value="{{ old('nom', $user->nom) }}"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin:8px 0 18px;
                "
            >

            <label>Adresse e-mail</label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                style="
                    width:100%;
                    padding:13px;
                    margin:8px 0 18px;
                "
            >

            <hr style="
                margin:25px 0;
                border:0;
                border-top:1px solid #e5e7eb;
            ">

            <h3>Changer le mot de passe</h3>

            <p>
                Laissez vide si vous ne souhaitez
                pas le modifier.
            </p>

            <input
                type="password"
                name="password"
                placeholder="Nouveau mot de passe"
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:15px;
                "
            >

            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirmer le mot de passe"
                style="
                    width:100%;
                    padding:13px;
                    margin-bottom:20px;
                "
            >

            <button
                style="
                    width:100%;
                    padding:14px;
                    border:0;
                    border-radius:10px;
                    background:#1e3a8a;
                    color:white;
                    font-weight:800;
                "
            >
                Enregistrer les modifications
            </button>

        </form>

    </div>

</div>

</div>

@endsection