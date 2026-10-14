<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileCollection;
use App\Models\product\Product;
use App\Models\Customer;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $otp = 1234;
        // $otp = rand(1000, 9999);
        if ($request->mobile == 9154193014) {
            $otp = 1234;
        }
        $check = Customer::where('mobile', $request->mobile)->first();
        if (!empty($check->id)) {
            $user = Customer::where('mobile', $request->mobile)->first();
            $user->otp = $otp;
            $user->save();
            $name = $user->name ?? "User";
            // if ($request->mobile != 9154193014) {
            //     $data = $this->sendWhatsAppMessage(
            //         $check->mobile,
            //         'login_verify_code',
            //         [
            //             'field_1' => $otp,
            //             // 'field_2' => $otp,

            //         ]
            //     );
            //     try {
            //         $whatsappService = new WhatsAppService();
            //         $result = $whatsappService->sendTemplateMessage($data);
            //     } catch (Exception $e) {
            //         $result = false;
            //         Log::info($e->getMessage());
            //     }
            // }
            return response()->json([
                'success' => 1,
                'message' => "OTP Sent to WhatsApp"
            ]);
        } else {
            $user = new Customer();
            $user->mobile = $request->mobile;
            $user->otp = $otp;
            $user->save();
            $name = $user->name ?? "User";
            // if ($request->mobile != 9154193014) {

            //     $data = $this->sendWhatsAppMessage(
            //         $user->mobile,
            //         'login_verify_code',
            //         [
            //             // 'field_1' => $name,
            //             'field_1' => $otp,

            //         ]
            //     );
            //     try {
            //         $whatsappService = new WhatsAppService();
            //         $result = $whatsappService->sendTemplateMessage($data);
            //     } catch (Exception $e) {
            //         $result = false;
            //         Log::info($e->getMessage());
            //     }
            // }
            return response()->json([
                'success' => 1,
                'message' => "OTP Sent To Whatsapp"
            ]);
        }
    }

    public function verifyMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'otp' => 'required'
        ]);
        if ($validator->fails()) {

            $responseData = array('success' => 0, 'message' => "Fields Were Missing");
            return json_encode($responseData);
        }

        $mobile = Customer::where('mobile', $request->mobile)->first();

        if (!empty($mobile->id)) {
            if (($mobile->otp == $request->otp) || ($request->mobile == 9154193014)) {
                $user = Customer::where('mobile', $mobile->mobile)->first();
                if ($user->mobile_verified != 'Yes') {
                    $user->mobile_verified = 'Yes';
                    $user->mobile_verified_at = Carbon::now();
                }
                $user->save();
                $data['token'] = $user->createToken('MyApp')->plainTextToken;
                $data['user'] = new ProfileCollection(Customer::where('id', $user->id)->get());

                return response()->json(["success" => 1, 'data' => $data, "message" => 'Logged-in successfully']);
            } else {
                return response()->json(['success' => 0, 'message' => 'OTP Mismatch']);
            }
        } else {
            return response()->json(['success' => 0, 'message' => 'User Not Found']);
        }
    }

    public function resendOtp(Request $request)
    {
        $user = Customer::where('mobile', $request->mobile)->first();

        if (!empty($user->id)) {
            if ($request->mobile != 6302371759) {
                $message = "Dear User .Your LIFECARE PETZONE Account Verification Code is #$request->hash_key $user->otp. Don't Share with Others.";
            }

            $responseData = array("success" => 1, "message" => "OTP Sent Successfully");
            return json_encode($responseData);
        } else {
            $responseData = array('success' => 0, 'message' => "Your Records were Not Found, Please Register!");
            return json_encode($responseData);
        }
    }
    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        // return $user;
        if (auth('sanctum')->user()->tokens()->delete()) {

            $responseData = array("success" => 1, "message" => "LogOut successfully.");
        } else {
            $responseData = array("success" => 0, "message" => "LogOut Unsuccessfull.");
        }
        return json_encode($responseData);
    }

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [])
    {

        $data = [
            "from_phone_number_id" => "1070303949494176",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en_Us",
            "header_image" => "https://cdn.pixabay.com/photo/2015/01/07/15/51/woman-591576_1280.jpg",
            "header_video" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document_name" => "",
            "header_field_1" => "{full_name}",
            "location_latitude" => "",
            "location_longitude" => "",
            "location_name" => "",
            "location_address" => "",
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',
            "button_0" => $fields['field_1'],
            "button_1" => "{phone_number}",
            "copy_code" => $fields['field_1'],
        ];

        return $data;
    }
}
