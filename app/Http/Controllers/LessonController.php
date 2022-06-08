<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $lesson = Lesson::query();

        $chapterId = $request->query('chapter_id');

        if ($chapterId) {
            $lesson->where('chapter_id', $chapterId);
        }

        return response()->json([
            'message' => 'success',
            'data' => $lesson->get()
        ], 200);
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Lesson not found'
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $lesson
        ], 200);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);

        return response()->json([
            'message' => 'success',
            'data' => $lesson
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Lesson not found'
            ], 404);
        }

        $chapterId = $request->input('chapter_id');
        if ($chapterId) {
            $chapter = Chapter::find($chapterId);
            if (!$chapter) {
                return response()->json([
                    'message' => 'error',
                    'errors' => 'Chapter not found'
                ], 404);
            }
        }

        $lesson->update($data);

        return response()->json([
            'message' => 'success',
            'data' => $lesson
        ]);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'message' => 'error',
                'errors' => 'Lesson not found'
            ], 404);
        }

        $lesson->delete();

        return response()->json([
            'message' => 'success',
            'data' => 'Lesson deleted'
        ]);
    }
}
