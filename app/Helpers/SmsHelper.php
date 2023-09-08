<?php
namespace App\Helpers;


use GuzzleHttp\Client;


class SmsHelper {

    private $endpoint = "http://api.sparrowsms.com/v2/sms";

    private $token = 'v2_HFoqjmoQcF1phx8eTmFBWGgnAdg.Spuy';
    private $client;



    function __construct() {
        $this->client = new Client();
    }


    public function send($to, $message) {

        $isEnabled = env('SMS_ENABLE', FALSE);
        if (!$isEnabled) {
            return;
        }
        
        $payload = [
            'token' => $this->token,
            'from'  => 'TheAlert',
            'to'    => $to,
            'text'  => $message];

        try {
            $response = $this->client->post($this->endpoint, [
                'form_params' => $payload
            ]);
            $body = $response->getBody();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
        // return json_decode((string) $body);
    }


}