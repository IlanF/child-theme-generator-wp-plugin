;(function($) {
    
    // since we rely on ajax to do the button actions we wait for the js to initialize before showing them
    $(function() {
        $('.if-child-theme-notice .if-child-theme-button').animate({
            opacity: 1
        }, 'fast');
    });
    
    $(document).on('click', '.if-child-theme-button.hide-notice', function(e) {
        e.preventDefault();
        var $button = $(this);
        
        $.ajax({
            'url': ajaxurl,
            'method': 'POST',
            'data': {
                'action': 'if_child_theme_hide_notice',
                '_wpnonce': if_child_gen._nonce
            },
            'success': function(data) {
                if(data == 1) {
                    return $button.parents('.if-child-theme-notice').fadeOut('fast');
                }
                
                alert(if_child_gen.i18n.ajax_error);
            }
        });
    });
    
}(jQuery));