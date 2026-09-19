<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_home_uses_one_canonical_url_for_quote_parameters(): void
    {
        $this->app['env'] = 'production';

        $response = $this->get('/?service=cloud&utm_source=test');

        $response->assertSee('<link rel="canonical" href="https://aci-informatique.com/">', false)
            ->assertSee('index, follow, max-image-preview:large', false)
            ->assertSee('Services informatiques à Dakar | ACI Informatique');
    }

    public function test_service_pages_have_unique_metadata_and_valid_structured_data(): void
    {
        $titles = [];
        $descriptions = [];
        foreach (['cloud', 'reseaux', 'securite', 'support', 'collaboration'] as $slug) {
            $response = $this->get('/expertises/'.$slug);
            $response->assertOk();
            $html = $response->getContent();
            preg_match('/<title>(.*?)<\/title>/s', $html, $title);
            preg_match('/<meta name="description" content="(.*?)">/s', $html, $description);
            preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $json);
            $data = json_decode($json[1], true, 512, JSON_THROW_ON_ERROR);
            $this->assertSame('https://schema.org', $data['@context']);
            $this->assertSame('Service', $data['@graph'][2]['@type']);
            $this->assertSame('https://aci-informatique.com/expertises/'.$slug, $data['@graph'][2]['url']);
            $this->assertSame('BreadcrumbList', $data['@graph'][3]['@type']);
            $titles[] = $title[1];
            $descriptions[] = $description[1];
        }
        $this->assertCount(5, array_unique($titles));
        $this->assertCount(5, array_unique($descriptions));
    }

    public function test_sitemap_contains_seven_real_canonical_pages(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $this->assertCount(7, $xml->url);
        foreach ($xml->url as $url) {
            $this->assertStringStartsWith('https://aci-informatique.com/', (string) $url->loc);
            $this->get(parse_url((string) $url->loc, PHP_URL_PATH))->assertOk();
        }
    }

    public function test_local_preview_is_not_indexable(): void
    {
        $this->get('/')->assertSee('content="noindex, nofollow"', false);
    }
}
