<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestAciMail extends Command
{
    protected $signature = 'aci:test-mail';

    protected $description = 'Envoyer un e-mail de test SMTP à l’adresse de contact ACI';

    public function handle(): int
    {
        if (! config('mail.mailers.smtp.password')) {
            $this->error('Renseignez MAIL_PASSWORD dans .env, puis lancez php artisan config:clear. Aucun e-mail envoyé.');

            return self::FAILURE;
        }

        $recipient = config('aci.email');
        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->error('Renseignez une adresse ACI_EMAIL valide dans .env.');

            return self::FAILURE;
        }

        try {
            Mail::mailer('smtp')->raw(
                'Bonjour Oumar, ceci est un test d’envoi SMTP depuis le site ACI Informatique. Si vous recevez ce message, la configuration fonctionne.',
                function (Message $message) use ($recipient): void {
                    $message->to($recipient)->subject('ACI Informatique — Test de configuration SMTP');
                }
            );
        } catch (Throwable) {
            $this->error('Échec SMTP. Vérifiez le mot de passe, les paramètres Hostinger et l’accès réseau au port 465.');

            return self::FAILURE;
        }

        $this->info('Message accepté par le serveur SMTP pour '.$recipient.'. Vérifiez la boîte de réception et les indésirables.');

        return self::SUCCESS;
    }
}
