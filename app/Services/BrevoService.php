<?php

namespace App\Services;

use GetBrevo\Client\Configuration;
use GetBrevo\Client\Api\TransactionalEmailsApi;
use GetBrevo\Client\Model\SendSmtpEmail;

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
        $html = view('emails.codigo-acceso', [
            'nombre' => $nombre,
            'codigo' => $codigo
        ])->render();

        $email = new SendSmtpEmail([
            'to' => [
                ['email' => $to]
            ],
            'sender' => [
                'name' => env('MAIL_FROM_NAME', 'Sistema'),
                'email' => env('MAIL_FROM_ADDRESS')
            ],
            'subject' => '🔐 Tu código de verificación',
            'htmlContent' => $html
        ]);

        return $this->api->sendTransacEmail($email);
    }
}