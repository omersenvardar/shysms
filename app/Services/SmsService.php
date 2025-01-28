<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api.organikhaberlesme.com/sms/send';
        $this->apiKey = '2fedf9d2ba43f485dbcb751afb2702246222c764'; // cURL'de belirtilen API Key
    }

    public function sendSms($data)
    {
        $payload = [
            'message' => $data['message'],
            'recipients' => [$data['recipient']],
            'header' => 101737,
            'commercial' => false,
            'type' => 'sms',
            'otp' => false,
            'appeal' => true,
            'validity' => 48
        ];

        $response = Http::withHeaders([
            'X-Organik-Auth' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $payload);

        return $response->json();
    }
}
