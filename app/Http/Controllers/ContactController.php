<?php

namespace App\Http\Controllers;

use App\Mail\QuoteRequestReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;

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

        $requestId = DB::table('contact_requests')->insertGetId([
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

        try {
            $recipient = config('aci.email');
            if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Missing quote recipient');
            }
            Mail::to($recipient)->send(new QuoteRequestReceived($validated, $requestId));
        } catch (Throwable $exception) {
            Log::warning('Quote email delivery failed; request remains saved.', [
                'request_id' => $requestId,
                'exception_type' => $exception::class,
            ]);

            return redirect(route('home').'#contact')->with('warning', 'Votre demande n° '.$requestId.' est enregistrée, mais la notification par e-mail n’a pas pu être envoyée. Vous pouvez nous contacter directement par téléphone ou par e-mail en rappelant ce numéro.');
        }

        return redirect(route('home').'#contact')->with('success', 'Merci ! Votre demande a bien été enregistrée. ACI Informatique pourra vous recontacter avec les coordonnées fournies.');
    }
}
