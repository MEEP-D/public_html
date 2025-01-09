<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if(session('data')): ?>
    <pre><?php echo e(print_r(session('data'), true)); ?></pre>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\public_html\public_html\resources\views/admin/quizzes/import.blade.php ENDPATH**/ ?>