<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'ACI Informatique — Solutions informatiques & services cloud à Dakar')</title>
<meta name="description" content="ACI Informatique accompagne les entreprises, PME, écoles et ONG à Dakar : Microsoft 365, systèmes et réseaux, cybersécurité, maintenance et automatisation.">
<meta name="theme-color" content="#c4ee18">
<link rel="icon" href="{{ asset('images/logo-aci-green.png') }}">
<link rel="stylesheet" href="{{ asset('assets/lib/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/common-style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/aci.css').'?v=motion-1' }}">
<script src="{{ asset('assets/lib/gsap/gsap.min.js') }}" defer></script>
<script src="{{ asset('assets/lib/gsap/scroll-trigger.min.js') }}" defer></script>
<script src="{{ asset('assets/js/aci.js').'?v=motion-1' }}" defer></script>
</head>
<body>
<a class="skip-link" href="#main">Aller au contenu</a>
<header class="aci-header"><div class="container header-inner">
<a class="brand" href="{{ route('home') }}" aria-label="ACI Informatique — Accueil"><img src="{{ asset('images/logo-aci-green.png') }}" width="118" height="70" alt="ACI Informatique"></a>
<button class="menu-toggle" aria-expanded="false" aria-controls="navigation">Menu <span>☰</span></button>
<nav id="navigation" aria-label="Navigation principale">
<a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Accueil</a><a href="{{ route('home') }}#apropos">À propos</a><a href="{{ route('home') }}#expertises">Nos expertises</a><a href="{{ route('home') }}#offres">Nos offres</a><a href="{{ route('home') }}#contact" class="nav-cta">Parlons de votre projet <span>↗</span></a>
</nav></div></header>
<main id="main">@yield('content')</main>
<footer><div class="container"><div class="footer-top"><a class="footer-logo" href="{{ route('home') }}"><img src="{{ asset('images/logo-aci-green.png') }}" alt="ACI Informatique" width="118" height="70"></a><p>La technologie au service<br>de votre performance.</p><a href="{{ route('home') }}#contact">Construisons la suite ensemble ↗</a></div><div class="footer-bottom"><span>© {{ date('Y') }} ACI Informatique · Dakar, Sénégal</span><a href="{{ route('privacy') }}">Confidentialité</a><a href="#main">Retour en haut ↑</a></div></div></footer>
</body></html>