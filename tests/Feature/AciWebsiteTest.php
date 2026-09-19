<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AciWebsiteTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['name' => 'Client Test', 'email' => 'client@example.com', 'service' => 'cloud', 'message' => 'Nous souhaitons migrer vingt comptes de messagerie.', 'consent' => '1'];
    }

    public function test_pages_and_all_service_details_are_available(): void
    {
        $this->get('/')->assertOk()->assertSee('Performance.')->assertSee('150 000');
        foreach (config('aci.services') as $slug => $service) {
            $this->get('/expertises/'.$slug)->assertOk()->assertSee($service['title']);
        }
        $this->get('/confidentialite')->assertOk();
        $this->get('/expertises/inconnu')->assertNotFound();
    }

    public function test_valid_request_is_saved_and_redirected(): void
    {
        Mail::fake();
        config(['aci.email' => 'oumar.sow@aci-informatique.com']);
        $this->post('/contact', $this->payload())->assertRedirect(route('home').'#contact')->assertSessionHas('success');
        $this->assertDatabaseHas('contact_requests', ['email' => 'client@example.com', 'service' => 'cloud']);
    }

    public function test_invalid_request_is_rejected_without_saving(): void
    {
        $this->post('/contact', ['email' => 'invalid', 'service' => 'invalid'])->assertSessionHasErrors(['name', 'email', 'message', 'service', 'consent']);
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_honeypot_rejects_automated_submissions(): void
    {
        $this->post('/contact', [...$this->payload(), 'website' => 'spam'])->assertSessionHasErrors('website');
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_contact_endpoint_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', []);
        }
        $this->post('/contact', $this->payload())->assertStatus(429);
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_message_is_escaped_when_validation_redisplays_it(): void
    {
        $this->from('/')->post('/contact', ['message' => '<script>alert(1)</script>']);
        $this->get('/')->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }
}
