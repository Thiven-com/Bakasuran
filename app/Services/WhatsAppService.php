<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $endpoint = 'https://brandbooster.app/api/b93cdd8a-e4c7-425c-98b0-ed7826b64043/contact/send-template-message';
    // protected string $endpoint = 'https://brandbooster.app/api/b258d0fe-e304-4161-9917-fecd7ac12faf/contact/send-template-message';
    // protected string $token = 'hdAnsDGbkeTXYhzpSrgBOOC9ym2oaX6aAS2UO5sc14uICprbcUgXyDxbXbG4wqD8';

    protected string $token = 'e167gf3iyugiyolvfu2ooyi';
    // protected string $token = 'z6ZTWtbnQcUUzxB1nb5XLfv0O9fiIYSXY0pCaic0skAu69cXZisddbavdDkgSJHM';


    public function sendTemplateMessage(array $data)
    {
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->endpoint}?token={$this->token}", $data);
        // dd($response->json());
        return $response->json();
    }

}
