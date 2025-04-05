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
        $divisions = Division::all(['id', 'code', 'name', 'description'])
            ->map(function ($division) {
                // Normalisasi nama divisi
                $division->name = $this->normalizeDivisionName($division->name);
                return $division;
            });

        return response()->json([
            'status' => 'success',
            'data' => $divisions
        ]);
    }

    public function checkUserExamStatus(Request $request)
    {
        $user = Auth::user();

        // Check for ongoing exam
        $ongoingExam = Exam::where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->first();

        if ($ongoingExam) {
            return response()->json([
                'success' => true,
                'data' => [
                    'has_taken_exam' => false, // Masih dalam proses
                    'exam_status' => 'ongoing',
                    'exam_id' => $ongoingExam->id,
                    'division_id' => $ongoingExam->division_id,
                    'division_name' => $ongoingExam->division->name
                ]
            ]);
        }

        // Check if user has completed any exam
        if ($user->has_taken_exam) {
            $latestExam = Exam::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'expired'])
                ->latest()
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'has_taken_exam' => true,
                    'exam_status' => 'completed',
                    'exam_id' => $latestExam->id,
                    'division_id' => $latestExam->division_id,
                    'division_name' => $latestExam->division->name,
                    'exam_result' => [
                        'score' => $latestExam->score,
                        'correct_answers' => $latestExam->correct_answers,
                        'incorrect_answers' => $latestExam->incorrect_answers,
                        'start_time' => $latestExam->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $latestExam->end_time?->format('Y-m-d H:i:s'),
                    ]
                ]
            ]);
        }

        // User hasn't taken any exam
        return response()->json([
            'success' => true,
            'data' => [
                'has_taken_exam' => false
            ]
        ]);
    }

    private function normalizeDivisionName($name)
    {
        // Contoh normalisasi nama
        $map = [
            'programming' => 'Programming',
            'multimedia' => 'Multimedia & Desain',
            'sistem komputer' => 'Sistem Komputer & Jaringan'
        ];

        $lowerName = strtolower($name);

        foreach ($map as $key => $value) {
            if (str_contains($lowerName, $key)) {
                return $value;
            }
        }

        return $name;
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

            // Update user's exam status
            Auth::user()->updateExamStatus();

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
            'duration' => 'nullable|integer|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if user has already completed any exam
        if ($user->has_taken_exam) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah menyelesaikan ujian sebelumnya'
            ], 400);
        }

        // Check for ongoing exam
        $ongoingExam = Exam::where('user_id', $user->id)
            ->where('status', 'ongoing')
            ->first();

        if ($ongoingExam) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda memiliki ujian yang sedang berlangsung'
            ], 400);
        }

        // Create new exam
        $exam = Exam::create([
            'user_id' => $user->id,
            'division_id' => $request->division_id,
            'duration' => $request->duration ?? 60,
            'start_time' => Carbon::now(),
            'status' => 'ongoing'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ujian berhasil dimulai',
            'data' => [
                'exam_id' => $exam->id,
                'start_time' => $exam->start_time,
                'duration' => $exam->duration
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

        // Check if exam has expired based on duration
        $startTime = new Carbon($exam->start_time);
        $currentTime = Carbon::now();
        $elapsedMinutes = $startTime->diffInMinutes($currentTime);

        if ($elapsedMinutes >= $exam->duration) {
            // Auto-finish the exam
            $this->autoFinishExpiredExam($exam->id);

            return response()->json([
                'status' => 'error',
                'message' => 'Your exam has expired',
                'expired' => true
            ], 400);
        }

        // Get questions for the division
        $questions = Question::where('division_id', $exam->division_id)
            ->with(['options' => function ($query) {
                $query->select('id', 'question_id', 'option_text');
            }])
            ->get(['id', 'question_text']);

        $userAnswers = ExamAnswer::where('exam_id', $exam->id)
            ->pluck('option_id', 'question_id')
            ->toArray();

        // Add user answers to questions
        $questions->each(function ($question) use ($userAnswers) {
            $question->user_answer = $userAnswers[$question->id] ?? null;
        });

        // Calculate remaining time in seconds
        $expiryTime = $startTime->addMinutes($exam->duration);
        $remainingSeconds = max(0, $currentTime->diffInSeconds($expiryTime, false));

        return response()->json([
            'status' => 'success',
            'data' => [
                'exam_id' => $exam->id,
                'division' => $exam->division->name,
                'start_time' => $exam->start_time->format('Y-m-d H:i:s'),
                'duration' => $exam->duration,
                'remaining_seconds' => $remainingSeconds,
                'questions' => $questions
            ]
        ]);
    }

    private function autoFinishExpiredExam($examId)
    {
        $exam = Exam::find($examId);

        if (!$exam || $exam->status !== 'ongoing') {
            return;
        }

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
        $exam->status = 'expired';
        $exam->save();
    }
}
