<?php

namespace App\Http\Controllers\Web;

use App\Exports\QuizResultsExport;
use App\Exports\QuizzesAdminExport;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Translation\QuizTranslation;
use App\Models\Section;
use App\Models\Answer;
use App\Models\Group;
use App\Models\Webinar;
use App\Models\Question;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExamController extends Controller
{
//     public function index(Request $request)
// {
//     removeContentLocale();

//     $query = Quiz::query();

//     // Các biến khác
//     $totalQuizzes = deepClone($query)->count();
//     $totalActiveQuizzes = deepClone($query)->where('status', 'active')->count();
//     $totalStudents = QuizzesResult::groupBy('user_id')->count();
//     $totalPassedStudents = QuizzesResult::where('status', 'passed')->groupBy('user_id')->count();

//     // Lọc quiz
//     $query = $this->filters($query, $request);

//     // Lấy danh sách quiz
//     $quizzes = $query->with([
//         'webinar',
//         'teacher',
//         'quizQuestions',
//         'quizResults',
//     ])->paginate(10);

//     // Lấy danh sách câu hỏi (nếu cần)
//     $questions = QuizzesQuestion::all(); // Hoặc một truy vấn phù hợp với yêu cầu của bạn

//     // Lấy danh sách nhóm (nếu cần)
//     $groups = Group::all(); // Hoặc một truy vấn phù hợp với yêu cầu của bạn

//     $data = [
//         'pageTitle' => trans('admin/pages/quiz.admin_quizzes_list'),
//         'quizzes' => $quizzes,
//         'totalQuizzes' => $totalQuizzes,
//         'totalActiveQuizzes' => $totalActiveQuizzes,
//         'totalStudents' => $totalStudents,
//         'totalPassedStudents' => $totalPassedStudents,
//         'questions' => $questions, // Truyền biến questions đến view
//         'groups' => $groups, // Truyền biến groups đến view
//     ];

//     // Các biến khác
//     $teacher_ids = $request->get('teacher_ids');
//     $webinar_ids = $request->get('webinar_ids');

//     if (!empty($teacher_ids)) {
//         $data['teachers'] = User::select('id', 'full_name')
//             ->whereIn('id', $teacher_ids)->get();
//     }

//     if (!empty($webinar_ids)) {
//         $data['webinars'] = Webinar::select('id', 'title')
//             ->whereIn('id', $webinar_ids)->get();
//     }

//     return view('web.default.includes.webinar.exam', $data);
// }



//     private function filters($query, $request)
//     {
//         $from = $request->get('from', null);
//         $to = $request->get('to', null);
//         $title = $request->get('title', null);
//         $sort = $request->get('sort', null);
//         $teacher_ids = $request->get('teacher_ids', null);
//         $webinar_ids = $request->get('webinar_ids', null);
//         $status = $request->get('status', null);

//         $query = fromAndToDateFilter($from, $to, $query, 'created_at');

//         if (!empty($title)) {
//             $query->whereTranslationLike('title', '%' . $title . '%');
//         }

//         if (!empty($sort)) {
//             switch ($sort) {
//                 case 'have_certificate':
//                     $query->where('certificate', true);
//                     break;
//                 case 'students_count_asc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('result_count', 'asc');
//                     break;

//                 case 'students_count_desc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('result_count', 'desc');
//                     break;
//                 case 'passed_count_asc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
//                         ->where('quizzes_results.status', 'passed')
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('result_count', 'asc');
//                     break;

//                 case 'passed_count_desc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
//                         ->where('quizzes_results.status', 'passed')
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('result_count', 'desc');
//                     break;

//                 case 'grade_avg_asc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('grade_avg', 'asc');
//                     break;

//                 case 'grade_avg_desc':
//                     $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
//                         ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
//                         ->groupBy('quizzes_results.quiz_id')
//                         ->orderBy('grade_avg', 'desc');
//                     break;

//                 case 'created_at_asc':
//                     $query->orderBy('created_at', 'asc');
//                     break;

//                 case 'created_at_desc':
//                     $query->orderBy('created_at', 'desc');
//                     break;
//             }
//         } else {
//             $query->orderBy('created_at', 'desc');
//         }

//         if (!empty($teacher_ids)) {
//             $query->whereIn('creator_id', $teacher_ids);
//         }

//         if (!empty($webinar_ids)) {
//             $query->whereIn('webinar_id', $webinar_ids);
//         }

//         if (!empty($status) and $status !== 'all') {
//             $query->where('status', strtolower($status));
//         }

//         return $query;
//     }
public function index()
    {
        try {
            // Lấy tất cả các bài thi với dữ liệu liên quan
            $quizzes = Quiz::with([
                'sections',
                'sections.groups',
                'sections.groups.questions',
                'sections.groups.questions.answers'
            ])->get();

            $data = [
                'pageTitle' => 'Danh sách bài thi',
                'quizzes' => $quizzes,
                
                // Tổng hợp dữ liệu từ tất cả các quiz
                'sections' => $quizzes->pluck('sections')->flatten(),
                'groups' => $quizzes->pluck('sections')
                    ->flatten()
                    ->pluck('groups')
                    ->flatten(),
                'questions' => $quizzes->pluck('sections')
                    ->flatten()
                    ->pluck('groups')
                    ->flatten()
                    ->pluck('questions')
                    ->flatten(),
                'answers' => $quizzes->pluck('sections')
                    ->flatten()
                    ->pluck('groups')
                    ->flatten()
                    ->pluck('questions')
                    ->flatten()
                    ->pluck('answers')
                    ->flatten()
            ];

            return view('web.default.includes.webinar.exam', $data);

        } catch (\Exception $e) {
            \Log::error('Exam loading error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Không thể tải danh sách bài thi. Vui lòng thử lại sau.');
        }
    }

    public function submitExam(Request $request)
    {
        try {
            $answers = $request->except('_token');
            $correctAnswers = 0;
            $totalQuestions = 0;

            foreach ($answers as $questionId => $answerId) {
                $questionId = str_replace('q', '', $questionId);
                $answer = Answer::where('question_id', $questionId)
                    ->where('id', $answerId)
                    ->first();

                if ($answer && $answer->is_correct) {
                    $correctAnswers++;
                }
                $totalQuestions++;
            }

            $result = [
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'percentage' => ($totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0)
            ];

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra khi nộp bài.'
            ], 500);
        }
    }
}
