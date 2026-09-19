<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('aci:demandes {--id= : Consulter une demande précise}', function () {
    if ($id = $this->option('id')) {
        $request = DB::table('contact_requests')->find($id);
        if (! $request) {
            $this->error('Demande introuvable.');

            return 1;
        }
        $this->table(['Champ', 'Valeur'], collect((array) $request)->map(fn ($value, $key) => [$key, $value])->values()->all());

        return 0;
    }
    $this->table(['ID', 'Nom', 'E-mail', 'Besoin', 'Date'], DB::table('contact_requests')->latest('id')->limit(50)->get(['id', 'name', 'email', 'service', 'created_at'])->map(fn ($row) => (array) $row)->all());
})->purpose('Consulter les demandes de devis ACI enregistrées (50 dernières)');
