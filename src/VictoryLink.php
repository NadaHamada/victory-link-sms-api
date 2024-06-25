<?php

namespace Bellal\VictoryLinkSMS;

use Bellal\VictoryLinkSMS\Helpers\Response;
use Bellal\VictoryLinkSMS\Interfaces\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VictoryLink implements Message
{
    /**
     * Vodafone API Credentails
     * @var array
     */
    protected $credentials;

    /**
     * API Endpont
     * @var string
     */
    protected $api = "https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMS";

    /**
     * Request Params
     * @var string
     */
    protected $params = "";

    /**
     * Message Send Response
     * @var Response
     */
    protected $response;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Send SMS
     * @param  array  $data message details
     * @return VictoryLink
     */
    public function send(array $data): VictoryLink
    {
        $this->buildRequestParams($data);

        $response = Http::post($this->api, $this->params);

        $this->handleResponse($response);

        return $this;
    }

    /**
     * Handle Message Sending response
     * @param  object $response
     * @return void
     */
    protected function handleResponse($response): void
    {
        $this->response = new Response($response);
    }

    public function response()
    {
        return $this->response;
    }

    /**
     * Build GET Request Params
     * @param  array  $data requesr parameters
     * @return string       request parameters for the get request
     */
    protected function buildRequestParams(array $data): array
    {
        return $this->params = [
            'UserName' => $this->credentials['username'],
            'Password' => $this->credentials['password'],
            'SMSText' => $data['message'],
            'SMSLang' => $this->credentials['language'],
            'SMSSender' => $this->credentials['sender'],
            'SMSReceiver' => $data['to'],
            'SMSID' => Str::uuid(),
        ];
    }
}
