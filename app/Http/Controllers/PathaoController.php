<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PathaoController extends Controller
{
    private $baseUrl ;
    public function __construct()
    {
        $this->baseUrl = env('PATHAO_BASE_URL');
    }

    /* ==============================
       MAIN CALLBACK FLOW
    ===============================*/
    public function createStoreFlow()
    {
        $tokenData = $this->accessPathaoInfo();

        if (!$tokenData || !isset($tokenData['access_token'])) {
            return response()->json(['error' => 'Token failed']);
        }

        $accessToken = $tokenData['access_token'];

        // Example: fetch city → zone → area (demo IDs)
        $cities = $this->getCities($accessToken);
        $cityId = $cities['data']['data']['0']['city_id'] ?? null;

        $zones = $this->getZones($cityId, $accessToken);
        $zoneId = $zones['data']['data'][0]['zone_id'] ?? null;

        $areas = $this->getAreas($zoneId, $accessToken);
        $areaId = $areas['data']['data'][0]['area_id'] ?? null;

        return $this->createStore($accessToken, $cityId, $zoneId, $areaId);
    }

    /* ==============================
       ACCESS TOKEN
    ===============================*/
    public function accessPathaoInfo()
    {
        $payload = [
            'client_id'     => 'olej84BbjN',
            'client_secret' => 'km2XA83eejXTxzCAekcdUfwBRfPHYRfyAiX74pPn',
            'username'      => 'aalmahbub89@gmail.com',
            'password'      => 'M@hbub1576',
            'grant_type'    => 'password',
        ];

        return $this->curlRequest(
            $this->baseUrl . '/aladdin/api/v1/issue-token',
            'POST',
            $payload
        );
    }

    /* ==============================
       GET CITIES
    ===============================*/
//    public function getCities($token)
//    {
//        return $this->curlRequest(
//            $this->baseUrl . '/aladdin/api/v1/city-list',
//            'GET',
//            [],
//            $token
//        );
//    }

    public function getCities()
    {
        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Call Pathao city list API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . '/aladdin/api/v1/city-list',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=UTF-8",
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        // ✅ Return ONLY city list (clean)
        return response()->json([
            'success' => true,
            'data' => $decoded['data']['data'] ?? []
        ]);
    }

    /* ==============================
       GET ZONES BY CITY
    ===============================*/
    public function getZones($cityId)
    {
        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Call Pathao zone list API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . "/aladdin/api/v1/cities/{$cityId}/zone-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=UTF-8",
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        // ✅ Return ONLY zone list
        return response()->json([
            'success' => true,
            'data' => $decoded['data']['data'] ?? []
        ]);
    }

    /* ==============================
       GET AREAS BY ZONE
    ===============================*/
    public function getAreas($zoneId)
    {
        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Call Pathao area list API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . "/aladdin/api/v1/zones/{$zoneId}/area-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=UTF-8",
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        // ✅ Return ONLY area list
        return response()->json([
            'success' => true,
            'data' => $decoded['data']['data'] ?? []
        ]);
    }

    /* ==============================
       CREATE STORE
    ===============================*/
    public function createStore($token, $cityId, $zoneId, $areaId)
    {
        $payload = [
            "name"              => "100Tk Shop Demo Store",
            "contact_name"      => "Test Merchant",
            "contact_number"    => "01317120335",
            "secondary_contact" => "01317120335",
            "otp_number"        => "01317120335",
            "address"           => "House 123, Road 4, Sector 10, Uttara, Dhaka",
            "city_id"           => $cityId,
            "zone_id"           => $zoneId,
            "area_id"           => $areaId,
        ];

        return $this->curlRequest(
            $this->baseUrl . '/aladdin/api/v1/stores',
            'POST',
            $payload,
            $token
        );
    }

    /* ==============================
       CURL HELPER (REUSABLE)
    ===============================*/
    private function curlRequest($url, $method = 'GET', $data = [], $token = null)
    {
        $headers = ['Content-Type: application/json'];

        if ($token) {
            $headers[] = "Authorization: Bearer {$token}";
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $method === 'POST' ? json_encode($data) : null,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return ['error' => curl_error($curl)];
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    public function getStores()
    {
        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Call Pathao stores API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl."/aladdin/api/v1/stores",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=UTF-8",
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        // Decode Pathao response
        $decoded = json_decode($response, true);

        // Return ONLY store data
        return response()->json([
            'success' => true,
            'data' => $decoded['data'] ?? []
        ]);
    }

    public function createOrder(Request $request)
    {
        // Optional: basic validation
        $request->validate([
            'store_id'            => 'required|integer',
            'merchant_order_id'   => 'required|string',
            'recipient_name'      => 'required|string',
            'recipient_phone'     => 'required|string',
            'recipient_address'   => 'required|string',
            'delivery_type'       => 'required|integer',
            'item_type'           => 'required|integer',
            'item_quantity'       => 'required|integer',
            'item_weight'         => 'required',
            'item_description'    => 'required|string',
            'amount_to_collect'   => 'required|numeric',
        ]);

        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Prepare order payload (Pathao format)
        $payload = [
            "store_id"            => $request->store_id,
            "merchant_order_id"   => $request->merchant_order_id,
            "recipient_name"      => $request->recipient_name,
            "recipient_phone"     => $request->recipient_phone,
            "recipient_address"   => $request->recipient_address,
            "delivery_type"       => $request->delivery_type,
            "item_type"           => $request->item_type,
            "special_instruction" => $request->special_instruction ?? '',
            "item_quantity"       => $request->item_quantity,
            "item_weight"         => $request->item_weight,
            "item_description"    => $request->item_description,
            "amount_to_collect"   => $request->amount_to_collect,
        ];

        // Step 3: Call Pathao order API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl."/aladdin/api/v1/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        return response()->json([
            'success' => true,
            'order' => json_decode($response, true)
        ]);
    }

    public function getOrderShortInfo($consignment_id)
    {
        // Step 1: Get access token
        $tokenData = $this->accessPathaoInfo();

        if (!isset($tokenData['access_token'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access token error'
            ], 401);
        }

        $accessToken = $tokenData['access_token'];

        // Step 2: Call Pathao order info API
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl."/aladdin/api/v1/orders/{$consignment_id}/info",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}"
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return response()->json([
                'success' => false,
                'error' => curl_error($curl)
            ], 500);
        }

        curl_close($curl);

        return response()->json([
            'success' => true,
            'order_info' => json_decode($response, true)
        ]);
    }


}
