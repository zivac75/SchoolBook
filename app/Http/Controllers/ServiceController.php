<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Afficher la liste des services.
     */
    public function index()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('services.index', compact('services'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Enregistrer un nouveau service.
     */
    public function store(ServiceRequest $request)
    {
        Service::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Service créé avec succès.');
    }

    /**
     * Afficher un service spécifique.
     */
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Mettre à jour un service.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    /**
     * Supprimer un service.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service supprimé avec succès.');
    }
}
