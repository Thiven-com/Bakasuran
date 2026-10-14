<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DTDCService
{
    // protected $tracking_token = "BO13369_trk_json:be1455d94140eefccc1bb16445751363";
    protected $tracking_token = "bhgdvfg_thr_json:jdnfijnsdivjnsjnvjnvn";

    // protected $apiKey = "d63f14e780c2a49d5cd7f2af69700a";

    protected $apiKey = "bysucsyg6q73rbfiu34if874fheufh";
    // protected $customerCode = "BO13369";

    protected $customerCode = "dbuf3i43";

    /*
    |--------------------------------------------------------------------------
    | Create Shipment
    |--------------------------------------------------------------------------
    */
    public function createShipment($data)
    {
        try {

            $payload = [

                "consignments" => [

                    [

                        "customer_code" => $this->customerCode,

                        "service_type_id" => $data['service_type_id'] ?? 'STD EXP-S',

                        "load_type" => $data['load_type'] ?? 'NON-DOCUMENT',

                        "description" => $data['description'],

                        "dimension_unit" => "cm",

                        "length" => (string) $data['length'],

                        "width" => (string) $data['width'],

                        "height" => (string) $data['height'],

                        "weight_unit" => "kg",

                        "weight" => (string) $data['weight'],

                        "declared_value" => (string) $data['declared_value'],

                        "num_pieces" => "1",

                        "origin_details" => [

                            "name" => $data['sender_name'],

                            "phone" => $data['sender_phone'],

                            "alternate_phone" => $data['sender_phone'],

                            "address_line_1" => $data['sender_address'],

                            "address_line_2" => "",

                            "pincode" => $data['sender_pincode'],

                            "city" => $data['sender_city'],

                            "state" => $data['sender_state']

                        ],

                        "destination_details" => [

                            "name" => $data['receiver_name'],

                            "phone" => $data['receiver_phone'],

                            "alternate_phone" => $data['receiver_phone'],

                            "address_line_1" => $data['receiver_address'],

                            "address_line_2" => "",

                            "pincode" => $data['receiver_pincode'],

                            "city" => $data['receiver_city'],

                            "state" => $data['receiver_state']

                        ],

                        "return_details" => [

                            "address_line_1" => $data['sender_address'],

                            "address_line_2" => "",

                            "city_name" => $data['sender_city'],

                            "name" => $data['sender_name'],

                            "phone" => $data['sender_phone'],

                            "pincode" => $data['sender_pincode'],

                            "state_name" => $data['sender_state'],

                            "email" => $data['sender_email'] ?? '',

                            "alternate_phone" => $data['sender_phone']

                        ],

                        "customer_reference_number" => $data['reference_number'],

                        "cod_collection_mode" => '',

                        "cod_amount" => '',

                        "commodity_id" => $data['commodity_id'] ?? '99',

                        "eway_bill" => $data['eway_bill'] ?? '',

                        "is_risk_surcharge_applicable" => false,

                        "invoice_number" => $data['invoice_number'] ?? $data['reference_number'],

                        "invoice_date" => date('d M Y'),

                        "reference_number" => ''

                    ]

                ]

            ];

            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'api-key' => $this->apiKey,
            ])
                ->timeout(60)
                ->post(
                    'https://dtdcapi.shipsy.io/api/customer/integration/consignment/softdata',
                    // 'https://alphademodashboardapi.shipsy.io/api/customer/integration/consignment/softdata',
                    $payload
                );

            Log::info('DTDC Create Shipment', [
                'request' => $payload,
                'api-key' => $this->apiKey,
                'response' => $response->json()
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'response' => $response->json(),
            ];

        } catch (\Exception $e) {

            Log::error('DTDC Create Shipment Error', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Shipment
    |--------------------------------------------------------------------------
    */
    public function cancelShipment($awbNumber)
    {
        try {

            $payload = [

                "AWBNo" => [
                    $awbNumber
                ],

                "customerCode" => $this->customerCode

            ];

            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'api-key' => $this->apiKey,
            ])
                ->timeout(60)
                ->post(
                    'http://dtdcapi.shipsy.io/api/customer/integration/consignment/cancel',
                    $payload
                );

            Log::info('DTDC Cancel Shipment', [
                'request' => $payload,
                'response' => $response->json()
            ]);

            return [

                'success' => $response->successful(),

                'status' => $response->status(),

                'response' => $response->json(),

            ];

        } catch (\Exception $e) {

            Log::error('DTDC Cancel Shipment Error', [

                'message' => $e->getMessage()

            ]);

            return [

                'success' => false,

                'message' => $e->getMessage()

            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Authenticate Tracking
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Track Shipment
    |--------------------------------------------------------------------------
    */
    public function trackShipment($awb)
    {
        try {

            $token = $this->tracking_token;

            if (!$token) {

                return [
                    'success' => false,
                    'message' => 'Unable to get DTDC tracking token'
                ];
            }

            $response = Http::withoutVerifying()->withHeaders([
                'X-Access-Token' => $token
            ])
                ->post(
                    'https://blktracksvc.dtdc.com/dtdc-api/rest/JSONCnTrk/getTrackDetails',
                    [
                        'trkType' => 'cnno',
                        'strcnno' => $awb,
                        'addtnlDtl' => 'Y'
                    ]
                );

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'response' => $response->json(),
            ];

        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Label
    |--------------------------------------------------------------------------
    */
    public function generateLabel(
        $referenceNumber,
        $labelCode = 'SHIP_LABEL_4X6',
        $labelFormat = 'pdf'
    ) {
        try {

            $response = Http::withoutVerifying()->withHeaders([
                'api-key' => $this->apiKey
            ])
                ->get(
                    'https://dtdcapi.shipsy.io/api/customer/integration/consignment/shippinglabel/stream',
                    [
                        'reference_number' => $referenceNumber,
                        'label_code' => $labelCode,
                        'label_format' => $labelFormat
                    ]
                );

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'response' => $response->body(),
            ];

        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}