<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    /**
     * Get all divisions
     */
    public function getDivisions()
    {
        $divisions = Division::all(['id', 'code', 'name', 'description']);

        return response()->json([
            'status' => 'success',
            'data' => $divisions
        ]);
    }

    /**
     * Finish the exam and calculate the score
     */
    public function finishExam(Request $request)
    {
        // Check if user has an ongoing exam
        $exam = Exam::where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->first();

        if (!$exam) {
            return response()->json([
                'status' => 'error',
                'message' => 'You don\'t have an ongoing exam'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Calculate score
            $answers = ExamAnswer::where('exam_id', $exam->id)->get();
            $correctAnswers = $answers->where('is_correct', true)->count();
            $incorrectAnswers = $answers->where('is_correct', false)->count();

            // Total questions
            $totalQuestions = Question::where('division_id', $exam->division_id)->count();

            // Calculate score percentage
            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

            // Update exam
            $exam->end_time = Carbon::now();
            $exam->score = $score;
            $exam->correct_answers = $correctAnswers;
            $exam->incorrect_answers = $incorrectAnswers;
            $exam->status = 'completed';
            $exam->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Exam completed successfully',
                'data' => [
                    'exam_id' => $exam->id,
                    'division' => $exam->division->name,
                    'score' => $exam->score,
                    'correct_answers' => $exam->correct_answers,
                    'incorrect_answers' => $exam->incorrect_answers,
                    'start_time' => $exam->start_time->format('Y-m-d H:i:s'),
                    'end_time' => $exam->end_time->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to complete exam: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the result of an exam
     */
    public function getExamResult($examId = null)
    {
        if ($examId) {
            $exam = Exam::where('id', $examId)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->first();
        } else {
            // Get the most recent completed exam
            $exam = Exam::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->latest()
                ->first();
        }

        if (!$exam) {
            return response()->json([
                'status' => 'error',
                'message' => 'No completed exam found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam_id' => $exam->id,
                'division' => $exam->division->name,
                'score' => $exam->score,
                'correct_answers' => $exam->correct_answers,
                'incorrect_answers' => $exam->incorrect_answers,
                'start_time' => $exam->start_time->format('Y-m-d H:i:s'),
                'end_time' => $exam->end_time->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * Get user's exam history
     */
    public function getExamHistory()
    {
        $exams = Exam::where('user_id', Auth::id())
            ->with('division:id,name,code')
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'division_id',
                'start_time',
                'end_time',
                'score',
                'correct_answers',
                'incorrect_answers',
                'status'
            ]);

        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }

    public function startExam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'division_id' => 'required|exists:divisions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Check if user has an ongoing exam
        $ongoingExam = Exam::where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->first();

        if ($ongoingExam) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already have an ongoing exam'
            ], 400);
        }

        // Create a new exam
        $exam = Exam::create([
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'start_time' => Carbon::now(),
            'status' => 'ongoing'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Exam started successfully',
            'data' => [
                'exam_id' => $exam->id,
                'start_time' => $exam->start_time
            ]
        ]);
    }

    /**
     * Submit an answer for a question
     */
    public function submitAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Check if user has an ongoing exam
        $exam = Exam::where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->first();

        if (!$exam) {
            return response()->json([
                'status' => 'error',
                'message' => 'You don\'t have an ongoing exam'
            ], 400);
        }

        // Check if question belongs to the exam's division
        $question = Question::where('id', $request->question_id)
            ->where('division_id', $exam->division_id)
            ->first();

        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Question does not belong to your exam'
            ], 400);
        }

        // Check if option belongs to the question
        $option = $question->options()->where('id', $request->option_id)->first();
        if (!$option) {
            return response()->json([
                'status' => 'error',
                'message' => 'Option does not belong to the question'
            ], 400);
        }

        // Save or update answer
        $answer = ExamAnswer::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'question_id' => $request->question_id,
            ],
            [
                'option_id' => $request->option_id,
                'is_correct' => $option->is_correct,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted successfully',
            'data' => [
                'is_correct' => $answer->is_correct
            ]
        ]);
    }

    /**
     * Get questions for the exam
     */
    public function getExamQuestions(Request $request)
    {
        // Check if user has an ongoing exam
        $exam = Exam::where('user_id', Auth::id())
            ->where('status', 'ongoing')
            ->first();

        if (!$exam) {
            return response()->json([
                'status' => 'error',
                'message' => 'You don\'t have an ongoing exam'
            ], 400);
        }

        // Get questions for the division
        $questions = Question::where('division_id', $exam->division_id)
            ->with(['options' => function ($query) {
                $query->select('id', 'question_id', 'option_text'); // Don't include is_correct
            }])
            ->get(['id', 'question_text']);

        // Get user's answers if any
        $userAnswers = ExamAnswer::where('exam_id', $exam->id)
            ->pluck('option_id', 'question_id')
            ->toArray();

        // Add user answers to questions
        $questions->each(function ($question) use ($userAnswers) {
            $question->user_answer = $userAnswers[$question->id] ?? null;
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam_id' => $exam->id,
                'division' => $exam->division->name,
                'start_time' => $exam->start_time->format('Y-m-d H:i:s'),
                'questions' => $questions
            ]
        ]);
    }
}
