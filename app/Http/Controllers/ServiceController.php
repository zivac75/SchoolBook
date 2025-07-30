<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        // Middleware auth géré dans les routes
    }

    /**
     * Vérifie que l'utilisateur est admin.
     */
    protected function checkAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Afficher la liste des services.
     */
    public function index()
    {
        $this->checkAdmin();
        $services = Service::orderBy('name')->paginate(10);
        return view('services.index', compact('services'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $this->checkAdmin();
        return view('services.create');
    }

    /**
     * Enregistrer un nouveau service.
     */
    public function store(ServiceRequest $request)
    {
        $this->checkAdmin();
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'Service créé avec succès.');
    }

    /**
     * Afficher un service spécifique.
     */
    public function show(Service $service)
    {
        $this->checkAdmin();
        return view('services.show', compact('service'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Service $service)
    {
        $this->checkAdmin();
        return view('services.edit', compact('service'));
    }

    /**
     * Mettre à jour un service.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->checkAdmin();
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $service->update($data);

        return redirect()->route('services.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    /**
     * Supprimer un service.
     */
    public function destroy(Service $service)
    {
        $this->checkAdmin();
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service supprimé avec succès.');
    }
}
