<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Faq::query();
        $data = $data->latest()->paginate(10);
        return view('admin.faq.all', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $count = Faq::count();
        if ($count >= 30) {
            Alert::toast("Maximum 30 FAQs allowed", "error");
            return redirect()->back();
        }
        $data = new Faq();
        $data->question = $request->question;
        $data->answer = $request->answer;
        $data->save();
        Alert::toast("Faq saved successfully", "success");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json(['error', 'Faq not found'], 404);
        }

        return response()->json([
            'faq' => $faq,
            'update_url' => route('admin.faqs.update', [$faq->id])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = Faq::find($id);
        if (!$data) {
            Alert::toast("Faq Details Not FOund", 'warning');
            return redirect()->back();
        }
        $data->question = $request->question;
        $data->answer = $request->answer;
        $data->save();
        Alert::toast("Faq Updated successfully", "success");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = Faq::findOrFail($id);

        if ($data) {
            $data->delete();
            Alert::toast("Faq Deleted successfully", "success");
            return response()->json([
                'status' => true,
                'message' => 'Faq deleted successfully'
            ]);
        }
    }
}
