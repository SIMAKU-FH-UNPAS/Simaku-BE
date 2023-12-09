<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Wa
{
    public function waSend($nama, $hp, $pesan, $fileUrl)
    {
        $user = [
            'username' => getenv("WA_USERNAME"),
            'password' => getenv("WA_PASSWORD"),
            'grant_type' => 'password',
            'client_id' => getenv("WA_CLIENT_ID"),
            'client_secret' => getenv("WA_CLIENT_SECRET"),
        ];

        $getToken = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://service-chat.qontak.com/oauth/token', $user);

        $getChannel = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $getToken['access_token']
        ])->get('https://service-chat.qontak.com/api/open/v1/integrations?target_channel=wa&limit=10');

        $message = [
            "to_number" => $hp,
            "to_name" => $nama,
            "message_template_id" => 'e3c4372d-545d-4717-8944-da7f3b9484e3',
            "channel_integration_id" => $getChannel['data'][0]['id'],
            "language" => [
                "code" => "id"
            ],
            "parameters" => [
                "format" => "DOCUMENT",
                "header"=> [
                    "format"=> "DOCUMENT",
                    "params" => [
                        [
                            "key" => "url",
                            "value" => "$fileUrl",
                            "value_text" => ""
                        ],
                        [
                            "key" => "filename",
                            "value" => "Slip-Gaji.pdf",
                            "value_text" => ""
                        ]
                    ]
                    ],
                "body" => [
                    [
                        "key" => "1",
                        "value" => "nama",
                        "value_text" => "$nama"
                    ],
                    [
                        "key" => "2",
                        "value" => "pesan",
                        "value_text" => "$pesan"
                ]
                ]
                    ]
                    ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . $getToken['access_token'],
            'Content-Type' => 'application/json',
        ])->post('https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct', $message);

        return $response['status'];
    }
}
