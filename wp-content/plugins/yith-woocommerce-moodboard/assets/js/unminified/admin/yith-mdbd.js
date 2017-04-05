jQuery( document ).ready( function( $ ){
    /* === COLORS TAB === */
    $('input#yith_mdbd_frontend_css').on('change',function () {
        if ($(this).is(':checked')) {
            $('#yith_mdbd_styles_colors').hide();
            $('#yith_mdbd_rounded_corners').parents('tr').hide();
            $('#yith_mdbd_add_to_moodboard_icon').parents('tr').hide();
            $('#yith_mdbd_add_to_cart_icon').parents('tr').hide();
        } else {
            $('#yith_mdbd_styles_colors').show();
            if ($('#yith_mdbd_use_button').is(':checked')) {
                $('#yith_mdbd_rounded_corners').parents('tr').show();
                $('#yith_mdbd_add_to_moodboard_icon').parents('tr').show();
                $('#yith_mdbd_add_to_cart_icon').parents('tr').show();
            }
        }
    }).change();

    $('input#yith_mdbd_use_button').on('change',function () {
        if ($(this).is(':checked') && !$('#yith_mdbd_frontend_css').is(':checked')) {
            $('#yith_mdbd_rounded_corners').parents('tr').show();
            $('#yith_mdbd_add_to_moodboard_icon').parents('tr').show();
            $('#yith_mdbd_add_to_cart_icon').parents('tr').show();
        } else {
            $('#yith_mdbd_rounded_corners').parents('tr').hide();
            $('#yith_mdbd_add_to_moodboard_icon').parents('tr').hide();
            $('#yith_mdbd_add_to_cart_icon').parents('tr').hide();
        }
    }).change();

    $('#yith_mdbd_multi_moodboard_enable').on('change', function () {
        if ($(this).is(':checked')) {
            $('#yith_mdbd_moodboard_create_title').parents('tr').show();
            $('#yith_mdbd_moodboard_manage_title').parents('tr').show();
        }
        else{
            $('#yith_mdbd_moodboard_create_title').parents('tr').hide();
            $('#yith_mdbd_moodboard_manage_title').parents('tr').hide();
        }
    }).change();

    /* === SETTINGS TAB === */
    $('input#yith_mdbd_disable_moodboard_for_unauthenticated_users').on('change',function () {
        if ($(this).is(':checked')) {
            $('#yith_mdbd_show_login_notice').parents('tr').hide();
            $('#yith_mdbd_login_anchor_text').parents('tr').hide();
        }
        else{
            $('#yith_mdbd_show_login_notice').parents('tr').show();
            $('#yith_mdbd_login_anchor_text').parents('tr').show();
        }
    }).change();

    $('input#yith_mdbd_show_estimate_button').on('change',function () {
        if ($(this).is(':checked')) {
            var additional_info = $('#yith_mdbd_show_additional_info_textarea');

            additional_info.parents('tr').show();
            additional_info.on( 'change', function(){
                if ($(this).is(':checked')) {
                    $('#yith_mdbd_additional_info_textarea_label').parents('tr').show()
                }
                else{
                    $('#yith_mdbd_additional_info_textarea_label').parents('tr').hide()
                }
            }).change();
        }
        else{
            $('#yith_mdbd_show_additional_info_textarea').parents('tr').hide();
            $('#yith_mdbd_additional_info_textarea_label').parents('tr').hide()
        }
    }).change();
} );