<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapter::query();

        $courseId = $request->query('course_id');
        if ($courseId) {
            $chapters->where('course_id', $courseId);
        }

        return response()->json([
            'message' => 'success',
            'data' => $chapters->get()
        ]);
    }

    public function show($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Chapter not found'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $chapter
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course  = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Course not found'
            ], 404);
        }

        $chapter = Chapter::create($data);

        return response()->json([
            'message' => 'success',
            'data' => $chapter
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'course_id' => 'integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Chapter not found'
            ], 404);
        }

        $courseId = $request->input('course_id');
        if ($courseId) {
            $course = Course::find($courseId);

            if (!$course) {
                return response()->json([
                    'message' => 'error',
                    'errors' => 'Course not found'
                ], 404);
            }
        }

        $chapter->update($data);

        return response()->json([
            'message' => 'success',
            'data' => $chapter
        ]);
    }

    public function destroy($id)
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Chapter not found'
            ], 404);
        }

        $chapter->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Chapter deleted'
        ]);
    }
}
