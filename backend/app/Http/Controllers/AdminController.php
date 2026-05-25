<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Affiche la page de connexion
    public function showLoginForm()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // Traite la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Identifiants admin (à adapter selon vos besoins)
        $adminEmail = 'admin@hotel-etoile.com';
        $adminPassword = 'admin123'; // À remplacer par un hash

        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            Session::put('admin_logged_in', true);
            Session::put('admin_name', 'Administrateur');
            return redirect()->route('admin.dashboard')->with('success', 'Connexion réussie');
        }

        return redirect()->back()->withErrors(['email' => 'Identifiants invalides']);
    }

    // Affiche le dashboard
    public function dashboard()
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $reservations = DB::table('reservations')
            ->select(
                'id',
                DB::raw('CONCAT(prenom, " ", nom) as client'),
                'date_arrivee as checkin',
                'date_depart as checkout',
                'chambre as room',
                'nb_adultes as adults',
                'nb_enfants as children',
                'prix_total as total',
                DB::raw('CASE statut WHEN "en_attente" THEN "en attente" WHEN "confirmee" THEN "confirmée" WHEN "annulee" THEN "annulée" ELSE statut END as status')
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('reservations'));
    }

    // Déconnexion
    public function destroyReservation($reservation)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 401);
        }

        $deleted = DB::table('reservations')->where('id', $reservation)->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation introuvable',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Réservation supprimée',
        ]);
    }

    public function updateReservationStatus(Request $request, $reservation)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 401);
        }

        $validated = $request->validate([
            'status' => 'required|in:en_attente,confirmee,annulee',
        ]);

        $exists = DB::table('reservations')->where('id', $reservation)->exists();

        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'Réservation introuvable',
            ], 404);
        }

        DB::table('reservations')
            ->where('id', $reservation)
            ->update([
                'statut' => $validated['status'],
                'updated_at' => now(),
            ]);

        $labels = [
            'en_attente' => 'en attente',
            'confirmee' => 'confirmée',
            'annulee' => 'annulée',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'status' => $labels[$validated['status']],
        ]);
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        Session::forget('admin_name');
        return redirect('/')->with('success', 'Déconnexion réussie');
    }
}

