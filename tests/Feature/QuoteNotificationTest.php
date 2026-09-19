<?php

namespace Tests\Feature;

use App\Mail\QuoteRequestReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class QuoteNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['name' => 'Client Démonstration', 'email' => 'client@example.com', 'organization' => 'Entreprise Exemple', 'phone' => '+221 70 000 00 00', 'service' => 'cloud', 'message' => "Nous souhaitons migrer 20 comptes Microsoft 365.\nMerci de nous proposer un accompagnement.", 'consent' => '1'];
    }

    public function test_quote_is_saved_and_notified_to_aci_with_client_reply_address(): void
    {
        Mail::fake();
        config(['aci.email' => 'info@aci-informatique.com']);

        $this->post('/contact', $this->payload())->assertRedirect(route('home').'#contact')->assertSessionHas('success');

        $id = DB::table('contact_requests')->value('id');
        $this->assertDatabaseHas('contact_requests', ['email' => 'client@example.com', 'service' => 'cloud']);
        Mail::assertSent(QuoteRequestReceived::class, function (QuoteRequestReceived $mail) use ($id) {
            return $mail->hasTo('info@aci-informatique.com')
                && $mail->requestId === $id
                && $mail->envelope()->replyTo[0]->address === 'client@example.com'
                && $mail->quote['message'] === $this->payload()['message'];
        });
        Mail::assertSentCount(1);
    }

    public function test_smtp_failure_preserves_quote_and_returns_honest_notice(): void
    {
        config(['aci.email' => 'info@aci-informatique.com']);
        Mail::shouldReceive('to')->once()->andThrow(new RuntimeException('SMTP unavailable'));
        Log::spy();

        $this->post('/contact', $this->payload())->assertRedirect(route('home').'#contact')->assertSessionHas('warning')->assertSessionMissing('success');

        $this->assertDatabaseHas('contact_requests', ['email' => 'client@example.com']);
        Log::shouldHaveReceived('warning')->once()->with('Quote email delivery failed; request remains saved.', ['request_id' => DB::table('contact_requests')->value('id'), 'exception_type' => RuntimeException::class]);
    }

    public function test_invalid_submission_never_sends_mail(): void
    {
        Mail::fake();

        $this->post('/contact', ['email' => 'invalid'])->assertSessionHasErrors();

        Mail::assertNothingSent();
        $this->assertDatabaseCount('contact_requests', 0);
    }

    public function test_email_escapes_client_content_and_includes_plain_text(): void
    {
        $quote = $this->payload();
        $quote['name'] = '<script>alert(1)</script>';
        $quote['organization'] = '<img src=x onerror=alert(1)>';
        $quote['message'] = "Bonjour\n<script>alert(2)</script>";
        $mail = new QuoteRequestReceived($quote, 42);

        $html = $mail->render();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;img', $html);
        $this->assertStringContainsString('mailto:client@example.com', $html);
        $this->assertSame('emails.quote-request-text', $mail->content()->text);
        $text = view('emails.quote-request-text', ['quote' => $quote, 'requestId' => 42, 'serviceName' => $mail->serviceName])->render();
        $this->assertStringContainsString($quote['message'], $text);
    }

    public function test_advice_request_and_missing_optional_fields_render(): void
    {
        $quote = $this->payload();
        $quote['service'] = 'conseil';
        unset($quote['phone'], $quote['organization']);

        $mail = new QuoteRequestReceived($quote, 43);
        $html = $mail->render();

        $this->assertSame('Audit, conseil & formation', $mail->serviceName);
        $this->assertStringContainsString('Non renseignée', $html);
        $this->assertStringContainsString('Non renseigné', $html);
    }
}
