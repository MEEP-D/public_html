<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/css/admin/quiz.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1><?php echo e($pageTitle); ?></h1>
            
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item">
                    <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes"><?php echo e(trans('admin/main.quizzes')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.edit')); ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0"><?php echo e(trans('admin/main.quiz_information')); ?></h4>
                        </div>

                        <div class="card-body">
                            <form method="post" action="<?php echo e(getAdminPanelUrl()); ?>/quizzes/<?php echo e($quiz->id); ?>/update" 
                                  id="quizForm" 
                                  class="quiz-form">
                                <?php echo e(csrf_field()); ?>


                                <div class="row">
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card border shadow-sm">
                                            <div class="card-header bg-light">
                                                <h4 class="text-primary mb-0">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <?php echo e(trans('admin/main.basic_information')); ?>

                                                </h4>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="input-label font-weight-bold"><?php echo e(trans('public.title')); ?></label>
                                                    <input type="text" 
                                                           name="title"
                                                           class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(!empty($quiz) ? $quiz->title : old('title')); ?>"
                                                           placeholder="Enter quiz title"/>
                                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="input-label font-weight-bold">Info</label>
                                                    <textarea name="info" 
                                                              rows="4" 
                                                              class="form-control <?php $__errorArgs = ['info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                              placeholder="Enter quiz information"><?php echo e(!empty($quiz) ? $quiz->info : old('info')); ?></textarea>
                                                    <?php $__errorArgs = ['info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-12 col-md-6">
                                        <div class="card border shadow-sm">
                                            <div class="card-header bg-light">
                                                <h4 class="text-primary mb-0">
                                                    <i class="fas fa-cog mr-2"></i>
                                                    <?php echo e(trans('admin/main.additional_information')); ?>

                                                </h4>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="input-label font-weight-bold">URL</label>
                                                    <input type="text" 
                                                           name="url"
                                                           class="form-control <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(!empty($quiz) ? $quiz->url : old('url')); ?>"
                                                           placeholder="Enter quiz URL"/>
                                                    <?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                                <div class="form-group mb-0">
                                                    <label class="input-label font-weight-bold"><?php echo e(trans('admin/main.status')); ?></label>
                                                    <select name="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                        <option value="active" <?php echo e((!empty($quiz) and $quiz->status === 'active') ? 'selected' : ''); ?>>
                                                            <?php echo e(trans('admin/main.active')); ?>

                                                        </option>
                                                        <option value="inactive" <?php echo e((!empty($quiz) and $quiz->status === 'inactive') ? 'selected' : ''); ?>>
                                                            <?php echo e(trans('admin/main.inactive')); ?>

                                                        </option>
                                                    </select>
                                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="fas fa-save mr-2"></i>
                                            <?php echo e(trans('admin/main.save_change')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/assets/default/js/admin/quiz.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\public_html\public_html\resources\views/admin/quizzes/edit.blade.php ENDPATH**/ ?>