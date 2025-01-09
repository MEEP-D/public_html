<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h1><?php echo e(trans('admin/main.quizzes')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/admin/"><?php echo e(trans('admin/main.dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?php echo e(trans('admin/main.quizzes')); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.total_quizzes')); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php echo e($totalQuizzes); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.active_quizzes')); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php echo e($totalActiveQuizzes); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.total_students')); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php echo e($totalStudents); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4><?php echo e(trans('admin/main.passed_students')); ?></h4>
                        </div>
                        <div class="card-body">
                            <?php echo e($totalPassedStudents); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><?php echo e(trans('admin/main.quizzes')); ?></h4>
                        <div class="card-header-action">
                            <a href="<?php echo e(url('/admin/quizzes/create')); ?>" class="btn btn-primary"><?php echo e(trans('admin/main.create')); ?></a>
                            <button href="<?php echo e(url('admin.quizzes.import')); ?>" type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                                <?php echo e(trans('admin/main.import_csv')); ?>

                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if($quizzes->isEmpty()): ?>
                            <div class="empty-state" data-height="400">
                                <img class="img-fluid" src="<?php echo e(asset('img/empty.svg')); ?>" alt="image">
                                <h2><?php echo e(trans('admin/main.no_result')); ?></h2>
                                <p class="lead">
                                    <?php echo e(trans('admin/main.no_result_hint')); ?>

                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Info</th>
                                            <th>URL</th>
                                            <th><?php echo e(trans('admin/main.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                            <td>
                                            <a href="<?php echo e(route('adminQuizCreateForm', ['id' => $quiz->id])); ?>" class="text-primary">
                            <?php echo e($quiz->title); ?>

                        </a>
                                            </td>
                                            <td><?php echo e($quiz->info); ?></td>
                                            <td><?php echo e($quiz->url); ?></td>
                                            <td>
                                                    <a href="<?php echo e(route('adminEditQuiz', $quiz->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(trans('admin/main.edit')); ?></a>
                                                    <a href="<?php echo e(url('admin/quizzes/'.$quiz->id.'/delete')); ?>" class="btn btn-sm btn-danger"><?php echo e(trans('admin/main.delete')); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center">
                        <?php echo e($quizzes->appends(request()->input())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form upload file CSV -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel"><?php echo e(trans('admin/main.import_csv')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('admin.quizzes.import')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file"><?php echo e(trans('admin/main.upload_csv_file')); ?></label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('admin/main.close')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('admin/main.import')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\public_html\public_html\resources\views/admin/quizzes/lists.blade.php ENDPATH**/ ?>