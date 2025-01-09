@extends('admin.layouts.app')

@section('content')
<div data-action="{{ getAdminPanelUrl() }}/quizzes/{{ !empty($quiz) ? $quiz->id .'/update' : 'store' }}" class="js-content-form quiz-form webinar-form">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <a href="{{ getAdminPanelUrl() }}/quizzes/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ trans('admin/main.add_new_question') }}
            </a>
        </div>

        <div class="card-body">
            <!-- Quiz Structure -->
            @if(!empty($quiz->sections))
                <div class="mb-4">
                    @foreach($quiz->sections as $section)
                        <div class="section-block mb-4">
                            <h5 class="section-title">{{ $section->title }}</h5>
                            @foreach($section->groups as $group)
                                <div class="group-block ml-4 mb-3">
                                    <h6 class="group-title">{{ $group->title }}</h6>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Questions List -->
            @if(!empty($quiz) && !empty($allQuestions))
                <section class="mt-5">
                    <div class="d-flex justify-content-between align-items-center pb-20">
                        <h2 class="section-title after-line">{{ trans('public.questions') }}</h2>
                        <div>
                            <button id="add_multiple_question" 
                                    data-quiz-id="{{ $quiz->id }}" 
                                    type="button" 
                                    class="btn btn-primary btn-sm ml-2 mt-3"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#multipleQuestionModal">
                                {{ trans('quiz.add_multiple_choice') }}
                            </button>

                            <button id="add_descriptive_question" 
                                    data-quiz-id="{{ $quiz->id }}" 
                                    type="button" 
                                    class="btn btn-primary btn-sm ml-2 mt-3"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#descriptiveQuestionModal">
                                {{ trans('quiz.add_descriptive') }}
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/main.id') }}</th>
                                    <th>{{ trans('admin/main.section') }}</th>
                                    <th>{{ trans('admin/main.group') }}</th>
                                    <th>{{ trans('admin/main.question') }}</th>
                                    <th>{{ trans('admin/main.context') }}</th>
                                    <th>{{ trans('admin/main.answers') }}</th>
                                    <th>{{ trans('admin/main.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allQuestions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>{{ $question->section_title }}</td>
                                        <td>{{ $question->group_title }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $question->title }}</strong>
                                                <p>{{ $question->content }}</p>
                                                @if($question->imageUrl)
                                                    <img src="{{ $question->imageUrl }}" alt="Question Image" class="img-thumbnail" width="100">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($question->contextHtml)
                                                <div class="context-html">
                                                    {!! $question->contextHtml !!}
                                                </div>
                                            @endif
                                            @if($question->contextImageUrl)
                                                <img src="{{ $question->contextImageUrl }}" alt="Context Image" class="img-thumbnail" width="100">
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach($question->answers as $answer)
                                                    <li class="{{ $answer->is_correct ? 'text-success' : '' }}">
                                                        {{ $answer->content }}
                                                        @if($answer->is_correct)
                                                            <i 
                                                            class="fas fa-check-circle"></i>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu text-left">
                                                    <button type="button" 
                                                            data-question-id="{{ $question->id }}" 
                                                            class="edit_question btn btn-sm btn-transparent"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#multipleQuestionModal{{ $question->id }}">
                                                        {{ trans('public.edit') }}
                                                    </button>
                                                    @include('admin.includes.delete_button', ['url' => getAdminPanelUrl('/quizzes-questions/'. $question->id .'/delete'), 'btnClass' => 'btn-sm btn-transparent', 'btnText' => trans('public.delete')])
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $allQuestions->links() }}
                    </div>
                </section>
            @endif

            <div class="mt-20 mb-20">
                <button type="button" class="js-submit-quiz-form btn btn-sm btn-primary">
                    {{ !empty($quiz) ? trans('public.save_change') : trans('public.create') }}
                </button>
                @if(empty($quiz) && !empty($inWebinarPage))
                    <button type="button" class="btn btn-sm btn-danger ml-10 cancel-accordion">
                        {{ trans('public.close') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Question Modal -->
@if(!empty($quiz))
    @include('admin.quizzes.modals.multiple_question')
    @include('admin.quizzes.modals.descriptive_question')
@endif

@endsection
@push('scripts_bottom')
    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/default/js/admin/quiz.min.js"></script>
@endpush