<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
@php
    $seo = request()->routeIs('services.show') ? config('seo.services.'.request()->route('slug'), []) : [];
    $pageTitle = trim($__env->yieldContent('title', $seo['title'] ?? config('seo.title')));
    $pageDescription = trim($__env->yieldContent('description', $seo['description'] ?? config('seo.description')));
    $baseUrl = config('seo.url');
    $canonical = $baseUrl.(request()->routeIs('home') ? '/' : '/'.request()->path());
    $organization = [
        '@type' => 'Organization', '@id' => $baseUrl.'/#organization',
        'name' => 'ACI Informatique', 'url' => $baseUrl.'/',
        'logo' => $baseUrl.'/images/logo-aci-green.png',
        'email' => config('aci.email'), 'telephone' => config('aci.phone'),
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Dakar', 'addressCountry' => 'SN'],
        'areaServed' => ['@type' => 'City', 'name' => 'Dakar'],
    ];
    $graph = [$organization, ['@type' => 'WebSite', '@id' => $baseUrl.'/#website', 'url' => $baseUrl.'/', 'name' => 'ACI Informatique', 'inLanguage' => 'fr-SN', 'publisher' => ['@id' => $baseUrl.'/#organization']]];
    if (request()->routeIs('services.show')) {
        $graph[] = ['@type' => 'Service', '@id' => $canonical.'#service', 'url' => $canonical, 'name' => $seo['heading'], 'description' => $service['description'], 'provider' => ['@id' => $baseUrl.'/#organization'], 'areaServed' => ['@type' => 'City', 'name' => 'Dakar']];
        $graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => $baseUrl.'/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $service['title'], 'item' => $canonical],
        ]];
    }
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="{{ app()->environment('production') ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
<meta property="og:type" content="website">
<meta property="og:locale" content="fr_SN">
<meta property="og:site_name" content="ACI Informatique">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $baseUrl }}/images/122658.webp">
<meta property="og:image:alt" content="Solutions informatiques pour les entreprises — ACI Informatique">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $baseUrl }}/images/122658.webp">
<script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
<meta name="theme-color" content="#c4ee18">
<link rel="icon" href="{{ asset('images/logo-aci-green.png') }}">
<link rel="stylesheet" href="{{ asset('assets/lib/bootstrap/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/common-style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/aci.css').'?v=seo-1' }}">
<script src="{{ asset('assets/lib/gsap/gsap.min.js') }}" defer></script>
<script src="{{ asset('assets/lib/gsap/scroll-trigger.min.js') }}" defer></script>
<script src="{{ asset('assets/js/aci.js').'?v=seo-1' }}" defer></script>
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
<footer><div class="container"><div class="footer-top"><a class="footer-logo" href="{{ route('home') }}"><img src="{{ asset('images/logo-aci-green.png') }}" alt="ACI Informatique" width="118" height="70"></a><p>La technologie au service<br>de votre performance.</p><a href="{{ route('home') }}#contact">Construisons la suite ensemble ↗</a></div><div class="footer-services" aria-label="Nos services à Dakar">@foreach(config('aci.services') as $serviceSlug => $footerService)<a href="{{ route('services.show', $serviceSlug) }}">{{ $footerService['title'] }}</a>@endforeach</div><div class="footer-bottom"><span>© {{ date('Y') }} ACI Informatique · Dakar, Sénégal</span><a href="{{ route('privacy') }}">Confidentialité</a><a href="#main">Retour en haut ↑</a></div></div></footer>
</body></html>