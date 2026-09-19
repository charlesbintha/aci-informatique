# ACI Informatique

Site vitrine Laravel 13 / PHP 8.3+, adapté du modèle fourni `index-branding-agency.html`. Les fichiers originaux restent dans le dossier parent. Les images du client sont optimisées en WebP ; le logo conserve son graphisme original.

## Démarrer

```sh
composer install
cp .env.example .env # uniquement sur une nouvelle installation
php artisan key:generate # uniquement sur une nouvelle installation
# Créer database/database.sqlite si absent, puis :
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8000
```

Sur cette installation, `.env`, la clé et SQLite sont déjà configurés. Aucun build Node n’est nécessaire : CSS et JavaScript sont servis depuis `public/assets`.

## Contenu

- Accueil : présentation, cinq expertises, engagements, tarifs et formulaire.
- Cinq pages de détail `/expertises/{slug}` et confidentialité.
- Textes et tarifs : `config/aci.php`, provenant de l’offre commerciale fournie.
- Vues Blade : `resources/views`. Adaptation graphique : `public/assets/css/aci.css`.
- Les liens de tarifs présélectionnent la prestation dans le formulaire.

## Demandes de devis

Le formulaire valide les données, exige le consentement et utilise CSRF, un piège antispam et une limite de cinq tentatives par minute. Les demandes sont enregistrées dans `contact_requests`. Elles ne sont pas exposées sur une page publique.

```sh
php artisan aci:demandes
php artisan aci:demandes --id=1
```

Les coordonnées ACI et le SMTP Hostinger (port 465, SMTPS) sont préconfigurés dans `.env.example`. Renseigner `MAIL_PASSWORD` uniquement dans `.env`, puis exécuter `php artisan config:clear` et `php artisan aci:test-mail` pour envoyer un message de test. Le formulaire enregistre les demandes en base ; ses notifications automatiques restent à connecter. Aucun secret ni base locale ne doit être versionné.

## Vérifier

```sh
php artisan test
vendor/bin/pint --test
```

## Mise en production

Configurer le domaine, HTTPS et un hébergement PHP compatible. La racine web doit être `public/`. Définir `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `SESSION_SECURE_COOKIE=true` et les coordonnées réelles. Préserver `.env`, `storage` et la base SQLite hors de toute exposition publique. Configurer sauvegardes et droits d’accès ; lancer migrations et `php artisan optimize`. Définir la durée de conservation des demandes et compléter la page de confidentialité avec les informations validées par ACI. Vérifier la licence du template pour le domaine utilisé.
