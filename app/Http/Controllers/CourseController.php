<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query();

        $q = request()->query('q');

        if ($q) {
            $courses->where('name', 'like', "%{$q}%");
        }

        $status = request()->query('status');

        if ($status) {
            $courses->where('status', $status);
        }

        return response()->json([
            'message' => 'success',
            'data' => $courses->paginate(5)
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draf,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advanced',
            'description' => 'required|string',
            'mentor_id' => 'required|integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $mentorId = $data['mentor_id'];
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Mentor not found'
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            'message' => 'success',
            'data' => $course
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draf,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advanced',
            'description' => 'string',
            'mentor_id' => 'integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $course  = Course::find($id);
        if (!$course) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Course not found'
            ], 404);
        }

        $mentorId = $request->input('mentor_id');
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);

            if (!$mentor) {
                return response()->json([
                    'message' => 'error',
                    'errors' => 'Mentor not found'
                ], 404);
            }
        }

        $course->update($data);

        return response()->json([
            'message' => 'success',
            'data' => $course
        ], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Course deleted'
        ], 200);
    }
}
