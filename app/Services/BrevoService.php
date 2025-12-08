<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
    }

    /**
     * Send a simple HTML email with a download link using Brevo SMTP API.
     * Returns true on success, false otherwise.
     */
    public function sendBarcodeLink(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        if (empty($this->apiKey)) {
            return false;
        }

        $senderEmail = env('BREVO_SENDER_EMAIL', 'no-reply@yourdomain.local');
        $senderName = env('BREVO_SENDER_NAME', 'SmartDoorz');

        $payload = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail,
            ],
            'to' => [
                ['email' => $toEmail, 'name' => $toName]
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        try {
            $res = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

            return $res->successful();
        } catch (\Exception $e) {
            // log if needed
            return false;
        }
    }
}
