<?php $__env->startSection('content'); ?>
<div data-action="<?php echo e(getAdminPanelUrl()); ?>/quizzes/<?php echo e(!empty($quiz) ? $quiz->id .'/update' : 'store'); ?>" class="js-content-form quiz-form webinar-form">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><?php echo e($pageTitle); ?></h4>
            <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> <?php echo e(trans('admin/main.add_new_question')); ?>

            </a>
        </div>

        <div class="card-body">
            <!-- Quiz Structure -->
            <?php if(!empty($quiz->sections)): ?>
                <div class="mb-4">
                    <?php $__currentLoopData = $quiz->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="section-block mb-4">
                            <h5 class="section-title"><?php echo e($section->title); ?></h5>
                            <?php $__currentLoopData = $section->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="group-block ml-4 mb-3">
                                    <h6 class="group-title"><?php echo e($group->title); ?></h6>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Questions List -->
            <?php if(!empty($quiz) && !empty($allQuestions)): ?>
                <section class="mt-5">
                    <div class="d-flex justify-content-between align-items-center pb-20">
                        <h2 class="section-title after-line"><?php echo e(trans('public.questions')); ?></h2>
                        <div>
                            <button id="add_multiple_question" 
                                    data-quiz-id="<?php echo e($quiz->id); ?>" 
                                    type="button" 
                                    class="btn btn-primary btn-sm ml-2 mt-3"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#multipleQuestionModal">
                                <?php echo e(trans('quiz.add_multiple_choice')); ?>

                            </button>

                            <button id="add_descriptive_question" 
                                    data-quiz-id="<?php echo e($quiz->id); ?>" 
                                    type="button" 
                                    class="btn btn-primary btn-sm ml-2 mt-3"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#descriptiveQuestionModal">
                                <?php echo e(trans('quiz.add_descriptive')); ?>

                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(trans('admin/main.id')); ?></th>
                                    <th><?php echo e(trans('admin/main.section')); ?></th>
                                    <th><?php echo e(trans('admin/main.group')); ?></th>
                                    <th><?php echo e(trans('admin/main.question')); ?></th>
                                    <th><?php echo e(trans('admin/main.context')); ?></th>
                                    <th><?php echo e(trans('admin/main.answers')); ?></th>
                                    <th><?php echo e(trans('admin/main.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $allQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($question->id); ?></td>
                                        <td><?php echo e($question->section_title); ?></td>
                                        <td><?php echo e($question->group_title); ?></td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($question->title); ?></strong>
                                                <p><?php echo e($question->content); ?></p>
                                                <?php if($question->imageUrl): ?>
                                                    <img src="<?php echo e($question->imageUrl); ?>" alt="Question Image" class="img-thumbnail" width="100">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($question->contextHtml): ?>
                                                <div class="context-html">
                                                    <?php echo $question->contextHtml; ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if($question->contextImageUrl): ?>
                                                <img src="<?php echo e($question->contextImageUrl); ?>" alt="Context Image" class="img-thumbnail" width="100">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php $__currentLoopData = $question->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="<?php echo e($answer->is_correct ? 'text-success' : ''); ?>">
                                                        <?php echo e($answer->content); ?>

                                                        <?php if($answer->is_correct): ?>
                                                            <i 
                                                            class="fas fa-check-circle"></i>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </td>
                                        <td>
                                            
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu text-left">
                                                    <button type="button" 
                                                            data-question-id="<?php echo e($question->id); ?>" 
                                                            class="edit_question btn btn-sm btn-transparent"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#multipleQuestionModal<?php echo e($question->id); ?>">
                                                        <?php echo e(trans('public.edit')); ?>

                                                    </button>
                                                    <?php echo $__env->make('admin.includes.delete_button', ['url' => getAdminPanelUrl('/quizzes-questions/'. $question->id .'/delete'), 'btnClass' => 'btn-sm btn-transparent', 'btnText' => trans('public.delete')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($allQuestions->links()); ?>

                    </div>
                </section>
            <?php endif; ?>

            <div class="mt-20 mb-20">
                <button type="button" class="js-submit-quiz-form btn btn-sm btn-primary">
                    <?php echo e(!empty($quiz) ? trans('public.save_change') : trans('public.create')); ?>

                </button>
                <?php if(empty($quiz) && !empty($inWebinarPage)): ?>
                    <button type="button" class="btn btn-sm btn-danger ml-10 cancel-accordion">
                        <?php echo e(trans('public.close')); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Question Modal -->
<?php if(!empty($quiz)): ?>
    <?php echo $__env->make('admin.quizzes.modals.multiple_question', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.quizzes.modals.descriptive_question', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
    </script>

    <script src="/assets/default/js/admin/quiz.min.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\public_html\public_html\resources\views/admin/quizzes/create_quiz_form.blade.php ENDPATH**/ ?>