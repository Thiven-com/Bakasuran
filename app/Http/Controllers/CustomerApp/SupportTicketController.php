<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportTicketCollection;
use App\Models\SupportCategory;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    //
    public function raiseSupportTicket(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        $v = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
        ]);
        if ($v->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $v->errors()->first(),
                'errors' => $v->errors()
            ]);
        }
        $support = new SupportTicket();
        $support->user_id = $user->id;
        $support->activity_id = $request->activity_id;
        $support->date = Carbon::now();
        $support->status = 'Requested';
        $support->name = $user->name;
        $support->fill(array_merge($request->input()));

        if (!empty($request->image)) {
            $support->image = $request->image;
        }
        $support->save();
        $collection = new SupportTicketCollection(SupportTicket::where('id', $support->id)->get());
        return response()->json([
            'success' => 1,
            'message' => 'We Have Received Your Message , Our Team  Will Reach You In SomeTime !',
            'data' => $collection,
        ]);
    }

    // public function support_categories(Request $request)
    // {

    //     $data = SupportCategory::where('status', 'show')->get();
    //     if (count($data) > 0) {
    //         return response()->json([
    //             'success' => 1,
    //             'message' => 'Fetched  Support Categories',
    //             'data' => $data,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'No Data Found',
    //         ]);
    //     }
    // }

    public function supportTickets(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        $data = SupportTicket::query();

        $data = $data->where('user_id', $user->id);

        if (!empty($request->status)) {
            $data = $data->where('status', $request->status);
        }

        $collection = new SupportTicketCollection($data->latest()->paginate(30));
        if (count($collection) > 0) {
            return response()->json([
                'success' => 1,
                'message' => 'Fetched Customer Supports',
                'data' => $collection,
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'No Data Found',
            ]);
        }
    }
}
