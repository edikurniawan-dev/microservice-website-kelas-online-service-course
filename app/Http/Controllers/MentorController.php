<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{

    public function index()
    {
        $mentors = Mentor::all();

        return response()->json([
            'message' => 'success',
            'data' => $mentors
        ], 200);
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Mentor not found'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $mentor
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' =>    'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return response()->json([
            'message' => 'success',
            'data' => $mentor
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' =>    'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Mentor not found'
            ], 404);
        }

        $mentor->update($data);

        return response()->json([
            'message' => 'success',
            'data' => $mentor
        ], 200);
    }

    public function destroy($id)
    {
        $mentor  = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Mentor not found'
            ], 404);
        }

        $mentor->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Mentor deleted'
        ], 200);
    }
}
