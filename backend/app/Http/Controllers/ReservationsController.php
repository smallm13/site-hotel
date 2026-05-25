<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationsController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'room_type' => 'required|in:single,double,twin,triple',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $roomRates = [
                'single' => 32000,
                'double' => 39200,
                'twin' => 39200,
                'triple' => 54000
            ];

            $checkinDate = Carbon::parse($request->input('checkin'));
            $checkoutDate = Carbon::parse($request->input('checkout'));
            $nights = $checkinDate->diffInDays($checkoutDate);

            if ($nights <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dates invalides'
                ], 400);
            }

            $roomType = $request->input('room_type');
            $baseRate = $roomRates[$roomType] ?? 0;
            $totalAmount = $baseRate * $nights;

            // Colonnes alignées avec la table MySQL
            DB::table('reservations')->insert([
                'date_arrivee' => $request->input('checkin'),
                'date_depart' => $request->input('checkout'),
                'nb_adultes' => $request->input('adults'),
                'nb_enfants' => $request->input('children'),
                'chambre' => $roomType,
                'prenom' => $request->input('first_name'),
                'nom' => $request->input('last_name'),
                'email' => $request->input('email'),
                'telephone' => $request->input('phone'),
                'prix_total' => $totalAmount,
                'statut' => 'en_attente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation enregistrée avec succès',
                'data' => [
                    'prix_total' => $totalAmount,
                    'nuits' => $nights
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur réservation: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            if (config('app.debug')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur serveur',
                    'debug' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors de l\'enregistrement de la réservation'
            ], 500);
        }
    }
}