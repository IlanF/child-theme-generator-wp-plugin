;(function($) {
    var $document = $(document)
        themeLastDefaultName = "";

    $document.on('click', '.postbox.collapsible h2, .postbox.collapsible .toggle-indicator', function() {
        $(this).parents('.postbox').toggleClass('closed');
    });

    var disable_form = function() {
        $('.if-child-gen-form').addClass('submitting')
            .find('.spinner').addClass('is-active').end()
            .find('.if-child-gen-submit-btn').prop('disabled', true);
    };
    var enable_form = function() {
        $('.if-child-gen-form').removeClass('submitting')
            .find('.spinner').removeClass('is-active').end()
            .find('.if-child-gen-submit-btn').prop('disabled', false);
    };


    $document.on('change input blur', '.if-child-gen-form', function() {
        var header_string = '\
/*\n\
 Theme Name:   {$theme_name}\n\
 Theme URI:    {$theme_uri}\n\
 Description:  {$theme_description}\n\
 Author:       {$theme_author}\n\
 Author URI:   {$theme_author_uri}\n\
 Template:     {$parent_theme}\n\
 Version:      {$theme_version}\n\
 License:      {$theme_license}\n\
 License URI:  {$theme_license_uri}\n\
 Tags:         {$theme_tags}\n\
 Text Domain:  {$theme_text_domain}\n\
*/\n\
{$license_text}\n\
 \n\
{$extra_comments}';
        var theme_tags = [];

        $(this).find('[name]').each(function(index, el) {
            var $el = $(el),
                value = $el.val();

            if(el.name == 'license_text' || el.name == 'extra_comments') {
                if(value.length > 0) {
                    value = "/* \n" + value + " */";
                }
            }

            if(el.name == 'theme_tags[]') {
                if($el.is(':checked')) {
                    theme_tags.push(value);
                }
                // no need to continue for theme-tags
                return;
            }

            header_string = header_string.replace('{$' + el.name + '}', value);
        });

        header_string = header_string.replace('{$theme_tags}', theme_tags.join(','));

        $('#style-css-header-code').text(header_string)
            .attr('rows', header_string.split(/\r\n|\n/).length);
        $('.style-css-header-code-wrapper').css('height', $('#style-css-header-code').outerHeight() + 'px');
    });

    $document.on('click', '#style-css-header-code', function() {
        $(this).focus().select();
    });

    $document.on('click', '.expand-style-css-header-code-button', function(e) {
        e.preventDefault();
        var $wrapper = $('.style-css-header-code-wrapper');

        $wrapper.toggleClass('slide-out');
        if(!$wrapper.hasClass('slide-out')) {
            $wrapper.one('transitionend', function() {
                $('#style-css-header-code').trigger('click');
            });
        }
    });

    $document.on('change', '[name="parent_theme"]', function() {
        var $name = $('[name="theme_name"]'),
            defaultName = $(this).val() + '-child';

        if($name.val().length <= 0 || ! themeLastDefaultName || $name.val() == themeLastDefaultName) {
            $name.val(defaultName);
            themeLastDefaultName = defaultName;
        }
    });

    // ajax responses follows the following format:
    // 1: everything went great
    // 0: some error occurred
    $document.on('submit', '.if-child-gen-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        disable_form();

        $.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'if_child_theme_create_child_theme',
                form_data: $form.serialize()
            },
            success: function(data) {
                console.log(data);
                if( data == 1) {
                    swal({
                        title: if_child_gen.i18n.success,
                        text: if_child_gen.i18n.child_theme_created,
                        showCancelButton: true,
                        confirmButtonText: if_child_gen.i18n.switch_to_theme,
                        cancelButtonText: if_child_gen.i18n.close,
                        type: 'success',
                        allowOutsideClick: false,
                        preConfirm: function() {
                            return new Promise(function(resolve) {
                                swal.enableLoading();

                                $.ajax({
                                    url: ajaxurl,
                                    method: 'post',
                                    data: {
                                        action: 'if_child_theme_activate_theme',
                                        '_wpnonce': if_child_gen._nonce,
                                        parent_theme: $('[name=parent_theme]', $form).val()
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        resolve(data);
                                    },
                                    error: function() {
                                        resolve("0");
                                    }
                                });
                            });
                        }
                    }).then(function(isConfirm) {
                        if (isConfirm === "1") {
                            swal({
                                title: if_child_gen.i18n.success,
                                text: if_child_gen.i18n.child_theme_activated,
                                type: 'success'
                            });
                        } else if (isConfirm === "0") {
                            swal({
                                title: if_child_gen.i18n.failure,
                                text: if_child_gen.i18n.child_theme_not_activated,
                                type: 'error'
                            });
                        }

                        // data received not matching the ajax format
                        // the user closed the alert
                    });

                    return;
                }

                swal({
                    title: if_child_gen.i18n.failure,
                    text: if_child_gen.i18n.child_theme_failed,
                    type: 'error'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    title: if_child_gen.i18n.failure,
                    text: if_child_gen.i18n.ajax_error,
                    type: 'error'
                });
            },
            complete: function() {
                enable_form();
            }
        });
    });

    $(function() {
        $('.if-child-gen-form').trigger('change');
        $('[name="parent_theme"]').trigger('change');
    });

}(jQuery));
