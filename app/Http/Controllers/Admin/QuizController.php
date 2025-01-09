<?php

namespace App\Http\Controllers\Admin;


use App\Exports\QuizResultsExport;
use App\Exports\QuizzesAdminExport;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Translation\QuizTranslation;
use App\Models\Section;
use App\Models\Answer;
use App\Models\Chapter;
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

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_quizzes_list');
    
        removeContentLocale();
    
        $query = Quiz::query();
    
        $totalQuizzes = deepClone($query)->count();
        $totalActiveQuizzes = deepClone($query)->where('status', 'active')->count();
        $totalStudents = QuizzesResult::groupBy('user_id')->count();
        $totalPassedStudents = QuizzesResult::where('status', 'passed')->groupBy('user_id')->count();
    
        $query = $this->filters($query, $request);
    
        // $quizzes = $query->with([
        //     'webinar',
        //     'quizQuestions',
        //     'translations',
        //     ])->paginate(10);
        $quizzes = $query->select('id', 'title', 'info', 'url')->paginate(10);
        
        $data = [
            'pageTitle' => trans('admin/pages/quiz.admin_quizzes_list'),
            'quizzes' => $quizzes,
            'totalQuizzes' => $totalQuizzes,
            'totalActiveQuizzes' => $totalActiveQuizzes,
            'totalStudents' => $totalStudents,
            'totalPassedStudents' => $totalPassedStudents,
        ];
    
        $teacher_ids = $request->get('teacher_ids');
        $webinar_ids = $request->get('webinar_ids');
    
        if (!empty($teacher_ids)) {
            $data['teachers'] = User::select('id', 'full_name')
                ->whereIn('id', $teacher_ids)->get();
        }
    
        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::select('id', 'title')
                ->whereIn('id', $webinar_ids)->get();
        }
    
        return view('admin.quizzes.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $sort = $request->get('sort', null);
        $teacher_ids = $request->get('teacher_ids', null);
        $webinar_ids = $request->get('webinar_ids', null);
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'have_certificate':
                    $query->where('certificate', true);
                    break;
                case 'students_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'students_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;
                case 'passed_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'passed_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;

                case 'grade_avg_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'asc');
                    break;

                case 'grade_avg_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'desc');
                    break;

                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($teacher_ids)) {
            $query->whereIn('creator_id', $teacher_ids);
        }

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($status) and $status !== 'all') {
            $query->where('status', strtolower($status));
        }

        return $query;
    }

    public function create()
    {
        $this->authorize('admin_quizzes_create');

        $data = [
            'pageTitle' => trans('quiz.new_quiz'),
        ];

        return view('admin.quizzes.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_quizzes_create');

        $data = $request->get('ajax')['new'];
        $locale = $data['locale'] ?? getDefaultLocale();

        $rules = [
            'title' => 'required|max:255',
            'webinar_id' => 'required|exists:webinars,id',
            'pass_mark' => 'required',
        ];

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }


        $webinar = Webinar::where('id', $data['webinar_id'])
            ->first();

        if (!empty($webinar)) {
            $chapter = null;

            if (!empty($data['chapter_id'])) {
                $chapter = WebinarChapter::where('id', $data['chapter_id'])
                    ->where('webinar_id', $webinar->id)
                    ->first();
            }

            $quiz = Quiz::create([
                'webinar_id' => $webinar->id,
                'chapter_id' => !empty($chapter) ? $chapter->id : null,
                'creator_id' => $webinar->creator_id,
                'attempt' => $data['attempt'] ?? null,
                'pass_mark' => $data['pass_mark'],
                'time' => $data['time'] ?? null,
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
                'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
                'display_questions_randomly' => (!empty($data['display_questions_randomly']) and $data['display_questions_randomly'] == 'on'),
                'expiry_days' => (!empty($data['expiry_days']) and $data['expiry_days'] > 0) ? $data['expiry_days'] : null,
                'created_at' => time(),
            ]);

            QuizTranslation::updateOrCreate([
                'quiz_id' => $quiz->id,
                'locale' => mb_strtolower($locale),
            ], [
                'title' => $data['title'],
            ]);

            if (!empty($quiz->chapter_id)) {
                WebinarChapterItem::makeItem($webinar->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
            }

            // Send Notification To All Students
            $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);

            if ($request->ajax()) {

                $redirectUrl = '';

                if (empty($data['is_webinar_page'])) {
                    $redirectUrl = getAdminPanelUrl('/quizzes/' . $quiz->id . '/edit');
                }

                return response()->json([
                    'code' => 200,
                    'redirect_url' => $redirectUrl
                ]);
            } else {
                return redirect()->route('adminEditQuiz', ['id' => $quiz->id]);
            }
        } else {
            return back()->withErrors([
                'webinar_id' => trans('validation.exists', ['attribute' => trans('admin/main.course')])
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_quizzes_edit');

        $quiz = Quiz::query()->where('id', $id)
            ->with([
                'quizQuestions' => function ($query) {
                    $query->orderBy('order', 'asc');
                    $query->with('quizzesQuestionsAnswers');
                },
            ])
            ->select('id', 'title', 'info', 'url', 'status', )
            ->first();

        if (empty($quiz)) {
            abort(404);
        }

        $creator = $quiz->creator;

        // $webinars = Webinar::where('status', 'active')
        //     ->where(function ($query) use ($creator) {
        //         $query->where('teacher_id', $creator->id)
        //             ->orWhere('creator_id', $creator->id);
        //     })->get();

        $locale = $request->get('locale', app()->getLocale());
        if (empty($locale)) {
            $locale = app()->getLocale();
        }
        storeContentLocale($locale, $quiz->getTable(), $quiz->id);

        $quiz->title = $quiz->getTitleAttribute();
        $quiz->locale = mb_strtoupper($locale);

        $chapters = collect();

        if (!empty($quiz->webinar)) {
            $chapters = $quiz->webinar->chapters;
        }

        $data = [
            'pageTitle' => trans('public.edit') . ' ' . $quiz->title,
            //  'webinars' => $webinars,
            'quiz' => $quiz,
            'quizQuestions' => $quiz->quizQuestions,
            'creator' => $creator,
            'chapters' => $chapters,
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
        ];

        return view('admin.quizzes.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_quizzes_edit');

        $quiz = Quiz::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'info' => 'nullable',
            'url' => 'nullable',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cập nhật thông tin cơ bản của quiz
            $quiz->update([
                'title' => $request->input('title'),
                'info' => $request->input('info'),
                'url' => $request->input('url'),
                'status' => $request->input('status')
            ]);

            // Cập nhật các Section, Group, Question, và Answer
            if ($request->has('sections')) {
                $sectionIds = collect($request->sections)->pluck('id')->filter();
                foreach ($request->sections as $sectionData) {
                    $section = Section::updateOrCreate(
                        ['id' => $sectionData['id']],
                        ['name' => $sectionData['name'], 'quiz_id' => $quiz->id]
                    );

                    if (isset($sectionData['groups'])) {
                        $groupIds = collect($sectionData['groups'])->pluck('id')->filter();
                        foreach ($sectionData['groups'] as $groupData) {
                            $group = Group::updateOrCreate(
                                ['id' => $groupData['id']],
                                ['name' => $groupData['name'], 'section_id' => $section->id]
                            );

                            if (isset($groupData['questions'])) {
                                $questionIds = collect($groupData['questions'])->pluck('id')->filter();
                                foreach ($groupData['questions'] as $questionData) {
                                    $question = Question::updateOrCreate(
                                        ['id' => $questionData['id']],
                                        ['content' => $questionData['content'], 'group_id' => $group->id]
                                    );

                                    if (isset($questionData['answers'])) {
                                        $answerIds = collect($questionData['answers'])->pluck('id')->filter();
                                        foreach ($questionData['answers'] as $answerData) {
                                            Answer::updateOrCreate(
                                                ['id' => $answerData['id']],
                                                [
                                                    'content' => $answerData['content'],
                                                    'is_correct' => $answerData['is_correct'],
                                                    'question_id' => $question->id
                                                ]
                                            );
                                        }
                                        // Xóa các answers không còn tồn tại
                                        Answer::where('question_id', $question->id)
                                            ->whereNotIn('id', $answerIds)
                                            ->delete();
                                    }
                                }
                                // Xóa các questions không còn tồn tại
                                Question::where('group_id', $group->id)
                                    ->whereNotIn('id', $questionIds)
                                    ->delete();
                            }
                        }
                        // Xóa các groups không còn tồn tại
                        Group::where('section_id', $section->id)
                            ->whereNotIn('id', $groupIds)
                            ->delete();
                    }
                }
                // Xóa các sections không còn tồn tại
                Section::where('quiz_id', $quiz->id)
                    ->whereNotIn('id', $sectionIds)
                    ->delete();
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'code' => 200,
                    'message' => trans('admin/main.changes_saved_successfully')
                ]);
            }

            return redirect()->back()->with('success', trans('admin/main.changes_saved_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'code' => 500,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function questions(Request $request,$id)
    {
    
        $quiz = Quiz::with([
            'sections',
            'sections.groups',
            'sections.groups.questions' => function($query) {
                $query->select([
                    'id',
                    'group_id',
                    'contextHtml',
                    'contextImageUrl',
                    'order',
                    'title',
                    'content',
                    'mean',
                    'imageUrl'
                ])->orderBy('order', 'asc');
            },
            'sections.groups.questions.answers' => function($query) {
                $query->select(['id', 'question_id', 'content', 'is_correct'])
                      ->orderBy('id', 'asc');
            }
        ])->findOrFail($id);
        
        // Lấy tất cả questions thuộc quiz này qua query builder
        $allQuestions = Question::select([
            'questions.id',
            'questions.group_id',
            'questions.contextHtml',
            'questions.contextImageUrl',
            'questions.order',
            'questions.title',
            'questions.content',
            'questions.mean',
            'questions.imageUrl',
            'groups.title as group_title',
            'sections.title as section_title'
        ])
        ->join('groups', 'questions.group_id', '=', 'groups.id')
        ->join('sections', 'groups.section_id', '=', 'sections.id')
        ->where('sections.quiz_id', $id)
        ->with(['answers' => function($query) {
            $query->select(['id', 'question_id', 'content', 'is_correct'])
                  ->orderBy('id', 'asc');
        }])
        ->orderBy('sections.id', 'asc')
        ->orderBy('groups.id', 'asc')
        ->orderBy('questions.order', 'asc')
        ->paginate(10); // Thêm phân trang, 10 items mỗi trang


        // Debug
        \Log::info('Quiz structure:', [
            'quiz_id' => $id,
            'sections_count' => $quiz->sections->count(),
            'questions_count' => $allQuestions->total()
        ]);

        $data = [
            'pageTitle' => 'Edit Quiz: ' . $quiz->title,
            'quiz' => $quiz,
            'allQuestions' => $allQuestions,

        ];

        return view('admin.quizzes.create_quiz_form', $data);
    }


    public function updateQuestions(Request $request, $quizId)
    {
        try {
            DB::transaction(function () use ($request, $quizId) {
                // Lưu lại danh sách Section IDs từ frontend
                $sectionIds = collect($request->sections)->pluck('id')->filter();
                
                foreach ($request->sections as $sectionData) {
                    // Cập nhật hoặc tạo mới Section
                    $section = Section::updateOrCreate(
                        ['id' => $sectionData['id']],
                        ['name' => $sectionData['name'], 'quiz_id' => $quizId]
                    );

                    // Lưu lại danh sách Group IDs từ frontend
                    $groupIds = collect($sectionData['groups'])->pluck('id')->filter();
                    
                    foreach ($sectionData['groups'] as $groupData) {
                        // Cập nhật hoặc tạo mới Group
                        $group = Group::updateOrCreate(
                            ['id' => $groupData['id']],
                            ['name' => $groupData['name'], 'section_id' => $section->id]
                        );

                        // Lưu lại danh sách Question IDs từ frontend
                        $questionIds = collect($groupData['questions'])->pluck('id')->filter();
                        
                        foreach ($groupData['questions'] as $questionData) {
                            // Cập nhật hoặc tạo mới Question
                            $question = Question::updateOrCreate(
                                ['id' => $questionData['id']],
                                ['content' => $questionData['content'], 'group_id' => $group->id]
                            );

                            // Lưu lại danh sách Answer IDs từ frontend
                            $answerIds = collect($questionData['answers'])->pluck('id')->filter();
                            
                            foreach ($questionData['answers'] as $answerData) {
                                // Cập nhật hoặc tạo mới Answer
                                Answer::updateOrCreate(
                                    ['id' => $answerData['id']],
                                    [
                                        'content' => $answerData['content'],
                                        'is_correct' => $answerData['is_correct'],
                                        'question_id' => $question->id
                                    ]
                                );
                            }
                            
                            // Xóa các Answer không còn tồn tại
                            Answer::where('question_id', $question->id)
                                  ->whereNotIn('id', $answerIds)
                                  ->delete();
                        }
                        
                        // Xóa các Question không còn tồn tại
                        Question::where('group_id', $group->id)
                               ->whereNotIn('id', $questionIds)
                               ->delete();
                    }
                    
                    // Xóa các Group không còn tồn tại
                    Group::where('section_id', $section->id)
                         ->whereNotIn('id', $groupIds)
                         ->delete();
                }
                
                // Xóa các Section không còn tồn tại
                Section::where('quiz_id', $quizId)
                      ->whereNotIn('id', $sectionIds)
                      ->delete();
            });

            return response()->json(['message' => 'Quiz updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        $this->authorize('admin_quizzes_delete');

        $quiz = Quiz::findOrFail($id);

        $quiz->delete();

        $checkChapterItem = WebinarChapterItem::where('item_id', $id)
            ->where('type', WebinarChapterItem::$chapterQuiz)
            ->first();

        if (!empty($checkChapterItem)) {
            $checkChapterItem->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ], 200);
        }

        return redirect()->back();
    }

    public function results($id)
    {
        $this->authorize('admin_quizzes_results');

        $quizzesResults = QuizzesResult::where('quiz_id', $id)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/quizResults.quiz_result_list_page_title'),
            'quizzesResults' => $quizzesResults,
            'quiz_id' => $id
        ];

        return view('admin.quizzes.results', $data);
    }

    public function resultsExportExcel($id)
    {
        $this->authorize('admin_quiz_result_export_excel');

        $quizzesResults = QuizzesResult::where('quiz_id', $id)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $export = new QuizResultsExport($quizzesResults);

        return Excel::download($export, 'quiz_result.xlsx');
    }

    public function resultDelete($result_id)
    {
        $this->authorize('admin_quizzes_results_delete');

        $quizzesResults = QuizzesResult::where('id', $result_id)->first();

        if (!empty($quizzesResults)) {
            $quizzesResults->delete();
        }

        return redirect()->back();
    }
    public function import(Request $request)
    {
        // Xác thực file
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);
    
        // Đọc nội dung file
        $path = $request->file('file')->getRealPath();
        $data = array_map(function ($line) {
            return str_getcsv($line, ",");
        }, file($path));
    
        // Nhóm dữ liệu theo Quiz -> Section -> Group -> Question -> Answer
        $groupedData = [];
        foreach ($data as $row) {
            $quizTitle = $row[0];      // Tiêu đề Quiz
            $sectionTitle = $row[3];   // Tiêu đề Section
            $groupTitle = $row[5];     // Tiêu đề Group
            $questionTitle = $row[7];  // Tiêu đề Question
    
            // Khởi tạo Quiz nếu chưa tồn tại
            if (!isset($groupedData[$quizTitle])) {
                $groupedData[$quizTitle] = [
                    'quiz_info' => $row[1],
                    'quiz_url' => $row[2],
                    'sections' => []
                ];
            }
    
            // Khởi tạo Section nếu chưa tồn tại
            if (!isset($groupedData[$quizTitle]['sections'][$sectionTitle])) {
                $groupedData[$quizTitle]['sections'][$sectionTitle] = [
                    'groups' => []
                ];
            }
    
            // Khởi tạo Group nếu chưa tồn tại
            if (!isset($groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle])) {
                $groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle] = [
                    'group_info' => $row[6],
                    'questions' => []
                ];
            }
    
            // Khởi tạo Question nếu chưa tồn tại
            if (!isset($groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle]['questions'][$questionTitle])) {
                $groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle]['questions'][$questionTitle] = [
                    'question_content' => $row[8],
                    'question_mean' => $row[12],
                    'answers' => []
                ];
            }
    
            // Thêm câu trả lời vào câu hỏi
            $answersCount = count($groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle]['questions'][$questionTitle]['answers']);
            if ($answersCount < 4) {
                $groupedData[$quizTitle]['sections'][$sectionTitle]['groups'][$groupTitle]['questions'][$questionTitle]['answers'][] = [
                    'answer_content' => $row[13],
                    'is_correct' => strtolower($row[14]) === 'true'
                ];
            }
        }
    
        // Lưu dữ liệu vào cơ sở dữ liệu
        foreach ($groupedData as $quizTitle => $quizData) {
            // Lưu Quiz
           
            $quiz = Quiz::updateOrCreate([
                'title' => $quizTitle,
                'info' => $quizData['quiz_info'],
                'url' => $quizData['quiz_url'],
                'pass_mark' => rand(1,100),                    // Thêm trường pass_mark
                'certificate' => rand(0,1),                    // Thêm trường certificate
                'creator_id' => auth()->id(),
                'created_at' => time(),                 // Thêm trường creator_id
            ]);
    
            // Lưu Sections
            foreach ($quizData['sections'] as $sectionTitle => $sectionData) {
                $section = $quiz->sections()->create([
                    'title' => $sectionTitle,
                ]);
    
                // Lưu Groups
                foreach ($sectionData['groups'] as $groupTitle => $groupData) {
                    $group = $section->groups()->create([
                        'title' => $groupTitle,
                        'creator_id' => auth()->id(),
                        'info' => $groupData['group_info'],
                        'created_at' => time(),                 // Thêm trường creator_id

                    ]);
    
                    // Lưu Questions
                    foreach ($groupData['questions'] as $questionTitle => $questionData) {
                        $question = $group->questions()->create([
                            'title'=> $questionTitle,
                            'content' => $questionData['question_content'],
                            'mean' => $questionData['question_mean'],
                        ]);
    
                        // Lưu Answers
                        foreach ($questionData['answers'] as $answerData) {
                            $question->answers()->create([
                                'content' => $answerData['answer_content'],
                                'is_correct' => $answerData['is_correct'],
                            ]);
                        }
                    }
                }
            }
        }
    
        // Ghi log dữ liệu hoặc xử lý tiếp
        \Log::info('Dữ liệu đã được nhập vào cơ sở dữ liệu thành công!');
    
        return redirect()->route('admin.quizzes.import')->with([
            'success' => 'Dữ liệu đã được nhập và lưu thành công vào cơ sở dữ liệu!',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('admin_quizzes_lists_excel');

        $query = Quiz::query();

        $query = $this->filters($query, $request);

        $quizzes = $query->with([
            'webinar',
            'teacher',
            'quizQuestions',
            'quizResults',
        ])->get();

        return Excel::download(new QuizzesAdminExport($quizzes), trans('quiz.quizzes') . '.xlsx');
    }

    public function orderItems(Request $request, $quizId)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'items' => 'required',
            'table' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $quiz = Quiz::query()->where('id', $quizId)->first();

        if (!empty($quiz)) {
            $tableName = $data['table'];
            $itemIds = explode(',', $data['items']);

            if (!is_array($itemIds) and !empty($itemIds)) {
                $itemIds = [$itemIds];
            }

            if (!empty($itemIds) and is_array($itemIds) and count($itemIds)) {
                switch ($tableName) {
                    case 'quizzes_questions':
                        foreach ($itemIds as $order => $id) {
                            QuizzesQuestion::where('id', $id)
                                ->where('quiz_id', $quiz->id)
                                ->update(['order' => ($order + 1)]);
                        }
                        break;
                }
            }
        }

        return response()->json([
            'title' => trans('public.request_success'),
            'msg' => trans('update.items_sorted_successful')
        ]);
    }
}
