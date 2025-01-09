<!-- Modal -->
<div class="d-none" id="bundleWebinarsModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25"><?php echo e(trans('update.add_new_course')); ?></h3>

    <div class="js-form" data-action="<?php echo e(getAdminPanelUrl()); ?>/bundle-webinars/store">
        <input type="hidden" name="bundle_id" value="<?php echo e(!empty($bundle) ? $bundle->id :''); ?>">

        <div class="form-group mt-15">
            <label class="input-label d-block"><?php echo e(trans('panel.select_course')); ?></label>
            <select name="webinar_id" class="js-ajax-webinar_id form-control bundleWebinars-select"
                    data-placeholder="<?php echo e(trans('update.search_and_select_class')); ?>">

            </select>

            <div class="invalid-feedback"></div>
        </div>

        <div class="mt-30 d-flex align-items-center justify-content-end">
            <button type="button" id="saveBundleWebinar" class="btn btn-primary"><?php echo e(trans('public.save')); ?></button>
            <button type="button" class="btn btn-danger ml-2 close-swl"><?php echo e(trans('public.close')); ?></button>
        </div>
    </div>
</div>
<?php /**PATH /home/xs166855/4exam.online/public_html/resources/views/admin/bundles/modals/bundle-webinar.blade.php ENDPATH**/ ?>