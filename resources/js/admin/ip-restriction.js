(function ($) {
    "use strict";

    function handleModal(path) {
        loadingSwl();

        $.get(path, function (result) {
            if (result && result.html) {

                Swal.fire({
                    html: result.html,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '32rem',
                    onOpen: () => {
                        const editModal = $('.restriction-form');
                        editModal.find('.js-select2').select2()
                    }
                });
            }
        })
    }

    $('body').on('click', '.js-add-restriction', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");
        handleModal(path)
    });

    $('body').on('click', '.js-edit-restriction', function (e) {
        e.preventDefault();

        const path = $(this).attr("href");
        handleModal(path)
    });

    $('body').on('change', '#restrictionType', function () {
        const value = $(this).val();

        $('.js-type-fields').addClass('d-none');
        $(`.js-type-${value}`).removeClass('d-none');
    })

    $('body').on('click', '.js-save-restriction', function (e) {
        e.preventDefault()

        const $this = $(this);
        let form = $this.closest('.restriction-form');

        let data = serializeObjectByTag(form);
        let action = form.attr('data-action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('input').removeClass('is-invalid');
        form.find('textarea').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                //window.location.reload();
                Swal.fire({
                    icon: 'success',
                    title: result.title,
                    html: '<p class="font-16 text-center text-gray py-2">' + result.msg + '</p>',
                    showConfirmButton: false,
                    width: '25rem',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        }).fail(function (err) {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = form.find('.js-ajax-' + key);

                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        })
    })

})(jQuery);
