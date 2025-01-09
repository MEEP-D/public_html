@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/css/admin/quiz.min.css">
@endpush

@section('content')
    <section class="section">
        {{-- Header Section --}}
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>{{ $pageTitle }}</h1>
            
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{ getAdminPanelUrl() }}/quizzes">{{ trans('admin/main.quizzes') }}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.edit') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ trans('admin/main.quiz_information') }}</h4>
                        </div>

                        <div class="card-body">
                            <form method="post" action="{{ getAdminPanelUrl() }}/quizzes/{{ $quiz->id }}/update" 
                                  id="quizForm" 
                                  class="quiz-form">
                                {{ csrf_field() }}

                                <div class="row">
                                    {{-- Basic Information Card --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card border shadow-sm">
                                            <div class="card-header bg-light">
                                                <h4 class="text-primary mb-0">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    {{ trans('admin/main.basic_information') }}
                                                </h4>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="input-label font-weight-bold">{{ trans('public.title') }}</label>
                                                    <input type="text" 
                                                           name="title"
                                                           class="form-control @error('title') is-invalid @enderror"
                                                           value="{{ !empty($quiz) ? $quiz->title : old('title') }}"
                                                           placeholder="Enter quiz title"/>
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="input-label font-weight-bold">Info</label>
                                                    <textarea name="info" 
                                                              rows="4" 
                                                              class="form-control @error('info') is-invalid @enderror"
                                                              placeholder="Enter quiz information">{{ !empty($quiz) ? $quiz->info : old('info') }}</textarea>
                                                    @error('info')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Additional Settings Card --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card border shadow-sm">
                                            <div class="card-header bg-light">
                                                <h4 class="text-primary mb-0">
                                                    <i class="fas fa-cog mr-2"></i>
                                                    {{ trans('admin/main.additional_information') }}
                                                </h4>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="input-label font-weight-bold">URL</label>
                                                    <input type="text" 
                                                           name="url"
                                                           class="form-control @error('url') is-invalid @enderror"
                                                           value="{{ !empty($quiz) ? $quiz->url : old('url') }}"
                                                           placeholder="Enter quiz URL"/>
                                                    @error('url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="input-label font-weight-bold">{{ trans('admin/main.status') }}</label>
                                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                                        <option value="active" {{ (!empty($quiz) and $quiz->status === 'active') ? 'selected' : '' }}>
                                                            {{ trans('admin/main.active') }}
                                                        </option>
                                                        <option value="inactive" {{ (!empty($quiz) and $quiz->status === 'inactive') ? 'selected' : '' }}>
                                                            {{ trans('admin/main.inactive') }}
                                                        </option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i>
                                            {{ trans('admin/main.save_change') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/js/admin/quiz.min.js"></script>
@endpush
