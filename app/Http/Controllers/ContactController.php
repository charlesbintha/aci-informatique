<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:180'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service' => ['required', Rule::in([...array_keys(config('aci.services')), 'conseil'])],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string', 'max:0'],
        ], [
            'required' => 'Le champ :attribute est obligatoire.',
            'email.email' => 'Veuillez renseigner une adresse e-mail valide.',
            'max' => 'Le champ :attribute est trop long.',
            'message.min' => 'Décrivez votre projet en au moins 20 caractères.',
            'service.in' => 'Veuillez sélectionner une expertise valide.',
            'consent.accepted' => 'Votre accord est nécessaire pour traiter la demande.',
            'website.max' => 'Votre demande ne peut pas être envoyée.',
        ], ['name' => 'nom', 'email' => 'e-mail', 'service' => 'besoin', 'message' => 'projet']);

        DB::table('contact_requests')->insert([
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'service' => $validated['service'],
            'message' => $validated['message'],
            'consented_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('home').'#contact')->with('success', 'Merci ! Votre demande a bien été enregistrée. ACI Informatique pourra vous recontacter avec les coordonnées fournies.');
    }
}
