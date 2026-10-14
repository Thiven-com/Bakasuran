<?php

namespace App\Services\Order;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DelhiveryService
{
    protected string $baseUrl;
    protected string $token;
    protected int $timeout;
    protected int $retries;
    protected int $retryMs;

    public function __construct()
    {
        $this->baseUrl = 'https://track.delhivery.com';
        // $this->token   = '00592b7a8f1703adb12dc6d2059f191042699256';
        $this->token   = '00sddfuguwygefugwfbewhb7458384584385';
        $this->timeout = 30;
        $this->retries = 2;
        $this->retryMs = 200;

        if (empty($this->token)) {
            throw new \RuntimeException('Delhivery token is not configured.');
        }
    }

    /**
     * Create CMU (pickup + shipments). Supports MPS.
     *
     * @param  array{ name:string, code?:string, add?:string, city?:string, pin?:string, phone?:string } $pickupLocation
     * @param  array<int, array<string, mixed>> $shipments
     * @return array{ success:bool, data:array|null, raw:array|null }
     *
     * @throws RequestException|ConnectionException|\Throwable
     */
    public function createShipments(array $pickupLocation, array $shipments): array
    {
        $payload = [
            'pickup_location' => $pickupLocation,
            'shipments'       => $shipments,
        ];

        $url = "{$this->baseUrl}/api/cmu/create.json";

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Token ' . $this->token,
            ])
                ->timeout($this->timeout)
                ->retry($this->retries, $this->retryMs)
                // IMPORTANT: Delhivery expects form fields, not a JSON body
                ->asForm()
                ->post($url, [
                    'format' => 'json',
                    'data'   => json_encode([
                        'pickup_location' => $pickupLocation, // same arrays you already build
                        'shipments'       => $shipments,
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);

            // Throw on 4xx/5xx so we can handle uniformly
            $response->throw();

            $json = $response->json();

            return [
                'success' => true,
                'data'    => $json,
                'raw'     => $json,
            ];
        } catch (RequestException $e) {
            // 4xx/5xx with response
            $status  = optional($e->response())->status();
            $body    = optional($e->response())->json() ?? optional($e->response())->body();
            Log::warning('Delhivery CMU create failed', [
                'url'     => $url,
                'status'  => $status,
                'payload' => $payload,
                'error'   => $e->getMessage(),
                'body'    => $body,
            ]);
            throw $e;
        } catch (ConnectionException $e) {
            // DNS/TLS/timeouts etc.
            Log::error('Delhivery CMU connection error', [
                'url'     => $url,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Delhivery CMU unexpected error', [
                'url'     => $url,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function track(string $awb): array
    {
        $url = "{$this->baseUrl}/api/v1/packages/json/?waybill={$awb}";

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Token ' . $this->token,
                'Accept'        => 'application/json',
            ])
                ->timeout($this->timeout)
                ->retry($this->retries, $this->retryMs)
                ->get($url);

            $response->throw();
            return $response->json();
        } catch (\Throwable $e) {
            \Log::error('Delhivery track error', [
                'awb'   => $awb,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function calculateShipping(array $params): array
    {
        $url = "{$this->baseUrl}/api/kinko/v1/invoice/charges/.json";

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Token ' . $this->token,
                'Accept'        => 'application/json',
            ])
                ->timeout($this->timeout)
                ->retry($this->retries, $this->retryMs)
                ->get($url, [
                    'md'    => $params['mode'] ?? 'S', // E = Express
                    'ss'    => 'Delivered',
                    'd_pin' => $params['destination_pin'],
                    'o_pin' => $params['origin_pin'],
                    'cgm'   => $params['weight'], // in grams
                    'pt'    => $params['payment_type'], // Pre-paid or COD
                    'cod'   => $params['cod_amount'] ?? 0,
                ]);

            $response->throw();

            return [
                'success' => true,
                'data'    => $response->json(),
            ];
        } catch (\Throwable $e) {
            \Log::error('Delhivery shipping calc error', [
                'params' => $params,
                'error'  => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
