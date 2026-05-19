<?php

namespace App\Services;

use GuzzleHttp\Client;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;

class BrevoService
{
    protected $api;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', env('BREVO_API_KEY'));

        $this->api = new TransactionalEmailsApi(
            new Client(),
            $config
        );
    }

    public function sendCodigo($to, $nombre, $codigo)
    {
        try {

            $html = view('emails.codigo-acceso', [
                'nombre' => $nombre,
                'codigo' => $codigo
            ])->render();

            $email = new SendSmtpEmail([
                'sender' => [
                    'name' => env('MAIL_FROM_NAME'),
                    'email' => env('MAIL_FROM_ADDRESS')
                ],

                'to' => [
                    [
                        'email' => $to,
                        'name' => $nombre
                    ]
                ],

                'subject' => '🔐 Código de verificación',

                'htmlContent' => $html
            ]);

            return $this->api->sendTransacEmail($email);

        } catch (\Exception $e) {

            \Log::error('Brevo Error: ' . $e->getMessage());

            return false;
        }
    }
}