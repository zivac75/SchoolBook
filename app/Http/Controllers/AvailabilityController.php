<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Service;
use App\Http\Requests\AvailabilityRequest;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
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
     * Afficher la liste des créneaux.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $serviceId = $request->query('service_id');
        $apiId = $request->query('api_id');
        $week = $request->query('week');
        $services = Service::orderBy('name')->get();
        $apis = \App\Models\User::where('role', 'api')->orderBy('name')->get();
        $isAdmin = $user && $user->role === 'admin';

        if ($isAdmin && $serviceId && $apiId) {
            $weekStart = $week ? \Carbon\Carbon::parse($week)->startOfWeek() : now()->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $availabilities = Availability::with('service', 'api')
                ->where('service_id', $serviceId)
                ->where('user_id', $apiId)
                ->whereBetween('start_datetime', [$weekStart, $weekEnd])
                ->orderBy('start_datetime')
                ->get();
            $calendar = [];
            foreach ($availabilities as $a) {
                $date = $a->start_datetime->format('Y-m-d');
                $calendar[$date][] = $a;
            }
            $stats = [
                'disponibles' => $availabilities->where('status', 'available')->count(),
                'reserves' => $availabilities->where('status', 'reserved')->count(),
                'desactives' => $availabilities->whereNotIn('status', ['available', 'reserved'])->count(),
            ];
            return view('availabilities.index', compact(
                'services',
                'serviceId',
                'apis',
                'apiId',
                'isAdmin',
                'weekStart',
                'weekEnd',
                'calendar',
                'stats'
            ));
        }
        if ($isAdmin) {
            // Vue admin : liste paginée filtrable
            $query = Availability::with('service', 'api');
            if ($apiId) {
                $query->where('user_id', $apiId);
            }
            if ($serviceId) {
                $query->where('service_id', $serviceId);
            }
            $availabilities = $query->orderBy('start_datetime', 'desc')->paginate(20);
            return view('availabilities.index', compact('availabilities', 'services', 'serviceId', 'apis', 'apiId', 'isAdmin'));
        }

        // Vue étudiant : calendrier semaine
        if (!$apiId && $apis->count() > 0) {
            $apiId = $apis->first()->id;
        }
        $weekStart = $week ? \Carbon\Carbon::parse($week)->startOfWeek() : now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        $query = Availability::with('service', 'api')
            ->where('user_id', $apiId)
            ->whereBetween('start_datetime', [$weekStart, $weekEnd]);
        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        $availabilities = $query->orderBy('start_datetime')->get();
        $calendar = [];
        foreach ($availabilities as $a) {
            $date = $a->start_datetime->format('Y-m-d');
            $calendar[$date][] = $a;
        }
        return view('availabilities.index', compact('calendar', 'services', 'serviceId', 'apis', 'apiId', 'user', 'weekStart', 'weekEnd', 'isAdmin'));

        // Préparer les stats pour chaque service et API (pour affichage des cartes)
        if ($isAdmin && !$serviceId && !$apiId) {
            $serviceStats = [];
            foreach ($services as $service) {
                foreach ($apis as $api) {
                    $all = Availability::where('service_id', $service->id)->where('user_id', $api->id)->count();
                    $dispo = Availability::where('service_id', $service->id)->where('user_id', $api->id)->where('status', 'available')->count();
                    $res = Availability::where('service_id', $service->id)->where('user_id', $api->id)->where('status', 'reserved')->count();
                    $serviceStats[$service->id][$api->id] = [
                        'total' => $all,
                        'disponibles' => $dispo,
                        'reserves' => $res,
                    ];
                }
            }
            return view('availabilities.index', compact('services', 'apis', 'isAdmin', 'serviceStats'));
        }
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $this->checkAdmin();
        $services = Service::orderBy('name')->get();
        $apis = \App\Models\User::where('role', 'api')->orderBy('name')->get();
        return view('availabilities.create', compact('services', 'apis'));
    }

    /**
     * Enregistrer un nouveau créneau.
     */
    public function store(AvailabilityRequest $request)
    {
        $this->checkAdmin();
        $data = $request->validated();
        dd($data);
        $data['user_id'] = auth()->user()->id;
        Availability::create($data);
        return redirect()->route('availabilities.index')
            ->with('success', 'Créneau créé avec succès.');
    }

    /**
     * Afficher un créneau spécifique.
     */
    public function show(Availability $availability)
    {
        $this->checkAdmin();
        $availability->load('service');
        return view('availabilities.show', compact('availability'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Availability $availability)
    {
        $this->checkAdmin();
        $services = Service::orderBy('name')->get();
        $apis = \App\Models\User::where('role', 'api')->orderBy('name')->get();
        return view('availabilities.edit', compact('availability', 'services', 'apis'));
    }

    /**
     * Mettre à jour un créneau.
     */
    public function update(AvailabilityRequest $request, Availability $availability)
    {
        $this->checkAdmin();
        $availability->update($request->validated());
        return redirect()->route('availabilities.index')
            ->with('success', 'Créneau mis à jour avec succès.');
    }

    /**
     * Supprimer un créneau.
     */
    public function destroy(Availability $availability)
    {
        $this->checkAdmin();
        $availability->delete();
        return redirect()->route('availabilities.index')
            ->with('success', 'Créneau supprimé avec succès.');
    }

    public function updateWeek(Request $request)
    {
        // TODO: traiter la sauvegarde groupée des créneaux
        return redirect()->back()->with('success', 'Modifications enregistrées !');
    }
}
