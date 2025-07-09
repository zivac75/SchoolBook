<?php

namespace App\Http\Controllers;

use App\Models\CodeInscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Attributes\Middleware;
use App\Notifications\CodeInscriptionNotification;
use Illuminate\Support\Facades\Notification;

#[Middleware(['auth', 'isAdmin'])]
class CodeController extends Controller
{
    public function index()
    {
        $codes = CodeInscription::with('creePar')->latest()->paginate(10);
        return view('codes.index', compact('codes'));
    }

    public function create()
    {
        return view('codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:api,etudiant'],
        ]);

        $code = \App\Models\CodeInscription::create([
            'code' => \Illuminate\Support\Str::random(10),
            'role' => $request->role,
            'utilise' => false,
            'cree_par' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Envoi du mail automatique avec le code
        Notification::route('mail', $request->email)
            ->notify(new CodeInscriptionNotification($code->code));

        return redirect()->route('codes.index')
            ->with('success', "Code d'inscription généré pour {$request->name} ({$request->email})");
    }

    public function toggleStatus(CodeInscription $code)
    {
        $code->update(['utilise' => !$code->utilise]);
        return back()->with('success', 'Statut du code mis à jour avec succès.');
    }
}
