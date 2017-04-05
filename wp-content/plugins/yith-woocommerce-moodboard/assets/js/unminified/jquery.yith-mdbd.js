jQuery( document ).ready( function( $ ){

    var cart_redirect_after_add = typeof( wc_add_to_cart_params ) !== 'undefined' ? wc_add_to_cart_params.cart_redirect_after_add : '',
        this_page = window.location.toString(),
        checkboxes = $( '.moodboard_table tbody input[type="checkbox"]:not(:disabled)');

    $(document).on( 'yith_mdbd_init', function(){
        var t = $(this),
            checkboxes = $( '.moodboard_table tbody input[type="checkbox"]:not(:disabled)');

        t.on( 'click', '.add_to_moodboard', function( ev ) {
            var t = $( this);

            ev.preventDefault();

            call_ajax_add_to_moodboard( t );

            return false;
        } );

        t.on( 'click', '.remove_from_moodboard', function( ev ){
            var t = $( this );

            ev.preventDefault();

            remove_item_from_moodboard( t );

            return false;
        } );

        t.on( 'adding_to_cart', 'body', function( ev, button, data ){
            if( typeof button != 'undefined' && typeof data != 'undefined' && button.closest( '.moodboard_table' ).length != 0 ){
                data.remove_from_moodboard_after_add_to_cart = button.closest( '[data-row-id]' ).data( 'row-id' );
                data.moodboard_id = button.closest( '.moodboard_table' ).data( 'id' );
                wc_add_to_cart_params.cart_redirect_after_add = yith_mdbd_l10n.redirect_to_cart;
            }
        } );

        t.on( 'added_to_cart', 'body', function( ev ){
            wc_add_to_cart_params.cart_redirect_after_add = cart_redirect_after_add;

            var moodboard = $( '.moodboard_table');

            moodboard.find( '.added' ).removeClass( 'added' );
            moodboard.find( '.added_to_cart' ).remove();
        } );

        t.on( 'added_to_cart', 'body', print_add_to_cart_notice );

        t.on( 'cart_page_refreshed', 'body', init_handling_after_ajax );

        t.on( 'click', '.show-title-form', show_title_form );

        t.on( 'click', '.moodboard-title-with-form h2', show_title_form );

        t.on( 'click', '.hide-title-form', hide_title_form );

        t.on( 'change', '.change-moodboard', function( ev ){
            var t = $(this);

            move_item_to_another_moodboard( t );

            return false;
        } );

        t.on( 'change', '.yith-mdbd-popup-content .moodboard-select', function( ev ){
            var t = $(this);

            if( t.val() == 'new' ){
                t.parents( '.yith-mdbd-first-row' ).next( '.yith-mdbd-second-row' ).css( 'display', 'table-row' );
            }
            else{
                t.parents( '.yith-mdbd-first-row' ).next( '.yith-mdbd-second-row' ).hide();
            }
        } );

        t.on( 'change', '#bulk_add_to_cart', function(){
            var t = $(this);

            if( t.is( ':checked' ) ){
                checkboxes.attr( 'checked','checked').change();
            }
            else{
                checkboxes.removeAttr( 'checked').change();
            }
        } );

        t.on( 'click', '#custom_add_to_cart', function(ev){
            var t = $(this),
                table = t.parents( '.cart.moodboard_table' );

            if( ! yith_mdbd_l10n.ajax_add_to_cart_enabled ){
                return;
            }

            ev.preventDefault();

            if( typeof $.fn.block != 'undefined' ) {
                table.fadeTo('400', '0.6').block({message: null,
                    overlayCSS                           : {
                        background    : 'transparent url(' + yith_mdbd_l10n.ajax_loader_url + ') no-repeat center',
                        backgroundSize: '16px 16px',
                        opacity       : 0.6
                    }
                });
            }

            $( '#yith-mdbd-form' ).load( yith_mdbd_l10n.ajax_url + t.attr( 'href' ) + ' #yith-mdbd-form', {action: yith_mdbd_l10n.actions.bulk_add_to_cart_action}, function(){

                if( typeof $.fn.unblock != 'undefined' ) {
                    table.stop(true).css('opacity', '1').unblock();
                }

                if( typeof $.prettyPhoto != 'undefined' ) {
                    $('a[data-rel="prettyPhoto[ask_an_estimate]"]').prettyPhoto({
                        hook              : 'data-rel',
                        social_tools      : false,
                        theme             : 'pp_woocommerce',
                        horizontal_padding: 20,
                        opacity           : 0.8,
                        deeplinking       : false
                    });
                }

                checkboxes.off('change');
                checkboxes = $( '.moodboard_table tbody input[type="checkbox"]');

                if( typeof $.fn.selectBox != 'undefined' ) {
                    $('select.selectBox').selectBox();
                }

                handle_moodboard_checkbox();
            } );
        } );

        t.on('click', '.yith-wfbt-add-moodboard', function(e){
            e.preventDefault();
            var t    = $(this),
                form = $( '#yith-mdbd-form' );

            $('html, body').animate({
                scrollTop: ( form.offset().top)
            },500);

            // ajax call
            reload_moodboard_and_adding_elem( t, form );
        });

        add_moodboard_popup();

        handle_moodboard_checkbox();

    } ).trigger('yith_mdbd_init');

    /**
     * Adds selectbox where needed
     */
    if( typeof $.fn.selectBox != 'undefined' ) {
        $('select.selectBox').selectBox();
    }

    /**
     * Init js handling on moodboard table items after ajax update
     *
     * @return void
     * @since 2.0.7
     */
    function init_handling_after_ajax(){
        if( typeof $.prettyPhoto != 'undefined' ) {
            $('a[data-rel="prettyPhoto[ask_an_estimate]"]').prettyPhoto({
                hook              : 'data-rel',
                social_tools      : false,
                theme             : 'pp_woocommerce',
                horizontal_padding: 20,
                opacity           : 0.8,
                deeplinking       : false
            });
        }

        checkboxes.off('change');
        checkboxes = $( '.moodboard_table tbody input[type="checkbox"]');

        if( typeof $.fn.selectBox != 'undefined' ) {
            $('select.selectBox').selectBox();
        }

        handle_moodboard_checkbox();
    }

    /**
     * Print "Product added to cart" notice
     *
     * @return void
     * @since 2.0.11
     */
    function print_add_to_cart_notice(){
        var messages = $( '.woocommerce-message');

        if( messages.length == 0 ){
            $( '#yith-mdbd-form').prepend( yith_mdbd_l10n.labels.added_to_cart_message );
        }
        else{
            messages.fadeOut( 300, function(){
                $(this).replaceWith( yith_mdbd_l10n.labels.added_to_cart_message ).fadeIn();
            } );
        }
    }

    /**
     * Add a product in the moodboard.
     *
     * @param object el
     * @return void
     * @since 1.0.0
     */
    function call_ajax_add_to_moodboard( el ) {
        var product_id = el.data( 'product-id' ),
            el_wrap = $( '.add-to-moodboard-' + product_id ),
            data = {
                add_to_moodboard: product_id,
                product_type: el.data( 'product-type' ),
                action: yith_mdbd_l10n.actions.add_to_moodboard_action
            };

        if( yith_mdbd_l10n.multi_moodboard && yith_mdbd_l10n.is_user_logged_in ){
            var moodboard_popup_container = el.parents( '.yith-mdbd-popup-footer' ).prev( '.yith-mdbd-popup-content' ),
                moodboard_popup_select = moodboard_popup_container.find( '.moodboard-select' ),
                moodboard_popup_name = moodboard_popup_container.find( '.moodboard-name' ),
                moodboard_popup_visibility = moodboard_popup_container.find( '.moodboard-visibility' );

            data.moodboard_id = moodboard_popup_select.val();
            data.moodboard_name = moodboard_popup_name.val();
            data.moodboard_visibility = moodboard_popup_visibility.val();
        }

        if( ! is_cookie_enabled() ){
            alert( yith_mdbd_l10n.labels.cookie_disabled );
            return;
        }

        $.ajax({
            type: 'POST',
            url: yith_mdbd_l10n.ajax_url,
            data: data,
            dataType: 'json',
            beforeSend: function(){
                el.siblings( '.ajax-loading' ).css( 'visibility', 'visible' );
            },
            complete: function(){
                el.siblings( '.ajax-loading' ).css( 'visibility', 'hidden' );
            },
            success: function( response ) {
                var msg = $( '#yith-mdbd-popup-message' ),
                    response_result = response.result,
                    response_message = response.message;

                if( yith_mdbd_l10n.multi_moodboard && yith_mdbd_l10n.is_user_logged_in ) {
                    var moodboard_select = $( 'select.moodboard-select' );
                    if( typeof $.prettyPhoto != 'undefined' && typeof $.prettyPhoto.close != 'undefined' ) {
                        $.prettyPhoto.close();
                    }

                    moodboard_select.each( function( index ){
                        var t = $(this),
                            moodboard_options = t.find( 'option' );

                        moodboard_options = moodboard_options.slice( 1, moodboard_options.length - 1 );
                        moodboard_options.remove();

                        if( typeof( response.user_moodboards ) != 'undefined' ){
                            var i = 0;
                            for( i in response.user_moodboards ) {
                                if ( response.user_moodboards[i].is_default != "1" ) {
                                    $('<option>')
                                        .val(response.user_moodboards[i].ID)
                                        .html(response.user_moodboards[i].moodboard_name)
                                        .insertBefore(t.find('option:last-child'))
                                }
                            }
                        }
                    } );
                }

                $( '#yith-mdbd-message' ).html( response_message );
                msg.css( 'margin-left', '-' + $( msg ).width() + 'px' ).fadeIn();
                window.setTimeout( function() {
                    msg.fadeOut();
                }, 2000 );

                if( response_result == "true" ) {
                    if( ! yith_mdbd_l10n.multi_moodboard || ! yith_mdbd_l10n.is_user_logged_in || ( yith_mdbd_l10n.multi_moodboard && yith_mdbd_l10n.is_user_logged_in && yith_mdbd_l10n.hide_add_button ) ) {
                        el_wrap.find('.yith-mdbd-add-button').hide().removeClass('show').addClass('hide');
                    }

                    el_wrap.find( '.yith-mdbd-moodboardexistsbrowse').hide().removeClass('show').addClass('hide').find('a').attr('href', response.moodboard_url);
                    el_wrap.find( '.yith-mdbd-moodboardaddedbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.moodboard_url);
                } else if( response_result == "exists" ) {
                    if( ! yith_mdbd_l10n.multi_moodboard || ! yith_mdbd_l10n.is_user_logged_in || ( yith_mdbd_l10n.multi_moodboard && yith_mdbd_l10n.is_user_logged_in && yith_mdbd_l10n.hide_add_button ) ) {
                        el_wrap.find('.yith-mdbd-add-button').hide().removeClass('show').addClass('hide');
                    }

                    el_wrap.find( '.yith-mdbd-moodboardexistsbrowse' ).show().removeClass('hide').addClass('show').find('a').attr('href', response.moodboard_url);
                    el_wrap.find( '.yith-mdbd-moodboardaddedbrowse' ).hide().removeClass('show').addClass('hide').find('a').attr('href', response.moodboard_url);
                } else {
                    el_wrap.find( '.yith-mdbd-add-button' ).show().removeClass('hide').addClass('show');
                    el_wrap.find( '.yith-mdbd-moodboardexistsbrowse' ).hide().removeClass('show').addClass('hide');
                    el_wrap.find( '.yith-mdbd-moodboardaddedbrowse' ).hide().removeClass('show').addClass('hide');
                }

                $('body').trigger('added_to_moodboard');
            }

        });
    }

    /**
     * Remove a product from the moodboard.
     *
     * @param object el
     * @return void
     * @since 1.0.0
     */
    function remove_item_from_moodboard( el ) {
        var table = el.parents( '.cart.moodboard_table' ),
            pagination = table.data( 'pagination' ),
            per_page = table.data( 'per-page' ),
            current_page = table.data( 'page' ),
            row = el.parents( '[data-row-id]' ),
            pagination_row = table.find( '.pagination-row'),
            data_row_id = row.data( 'row-id'),
            moodboard_id = table.data( 'id' ),
            moodboard_token = table.data( 'token' ),
            data = {
                action: yith_mdbd_l10n.actions.remove_from_moodboard_action,
                remove_from_moodboard: data_row_id,
                pagination: pagination,
                per_page: per_page,
                current_page: current_page,
                moodboard_id: moodboard_id,
                moodboard_token: moodboard_token
            };

        $( '#yith-mdbd-message' ).html( '&nbsp;' );

        if( typeof $.fn.block != 'undefined' ) {
            table.fadeTo('400', '0.6').block({message: null,
                overlayCSS                           : {
                    background    : 'transparent url(' + yith_mdbd_l10n.ajax_loader_url + ') no-repeat center',
                    backgroundSize: '16px 16px',
                    opacity       : 0.6
                }
            });
        }

        $( '#yith-mdbd-form' ).load( yith_mdbd_l10n.ajax_url + ' #yith-mdbd-form', data, function(){

            if( typeof $.fn.unblock != 'undefined' ) {
                table.stop(true).css('opacity', '1').unblock();
            }

            init_handling_after_ajax();

            $('body').trigger('removed_from_moodboard');
        } );
    }

    /**
     * Remove a product from the moodboard.
     *
     * @param object el
     * @return void
     * @since 1.0.0
     */
    function reload_moodboard_and_adding_elem( el, form ) {

        var product_id = el.data( 'product-id' ),
            table = $(document).find( '.cart.moodboard_table' ),
            pagination = table.data( 'pagination' ),
            per_page = table.data( 'per-page' ),
            moodboard_id = table.data( 'id' ),
            moodboard_token = table.data( 'token' ),
            data = {
                action: yith_mdbd_l10n.actions.reload_moodboard_and_adding_elem_action,
                pagination: pagination,
                per_page: per_page,
                moodboard_id: moodboard_id,
                moodboard_token: moodboard_token,
                add_to_moodboard: product_id,
                product_type: el.data( 'product-type' )
            };

        if( ! is_cookie_enabled() ){
            alert( yith_mdbd_l10n.labels.cookie_disabled );
            return
        }

        $.ajax({
            type: 'POST',
            url: yith_mdbd_l10n.ajax_url,
            data: data,
            dataType    : 'html',
            beforeSend: function(){
                if( typeof $.fn.block != 'undefined' ) {
                    table.fadeTo('400', '0.6').block({message: null,
                        overlayCSS                           : {
                            background    : 'transparent url(' + yith_mdbd_l10n.ajax_loader_url + ') no-repeat center',
                            backgroundSize: '16px 16px',
                            opacity       : 0.6
                        }
                    });
                }
            },
            success: function(res) {
                var obj      = $(res),
                    new_form = obj.find('#yith-mdbd-form'); // get new form

                form.replaceWith( new_form );
                init_handling_after_ajax();
            }
        });
    }

    /**
     * Move item to another moodboard
     *
     * @param object el
     * @return void
     * @since 2.0.7
     */
    function move_item_to_another_moodboard( el ){
        var table = el.parents( '.cart.moodboard_table'),
            moodboard_token = table.data( 'token'),
            moodboard_id = table.data( 'id' ),
            item = el.parents( '[data-row-id]'),
            item_id = item.data( 'row-id'),
            to_token = el.val(),
            pagination = table.data( 'pagination' ),
            per_page = table.data( 'per-page' ),
            current_page = table.data( 'page' ),
            data = {
                action: yith_mdbd_l10n.actions.move_to_another_moodboard_action,
                moodboard_token: moodboard_token,
                moodboard_id: moodboard_id,
                destination_moodboard_token: to_token,
                item_id: item_id,
                pagination: pagination,
                per_page: per_page,
                current_page: current_page
            };

        if( to_token == '' ){
            return;
        }

        if( typeof $.fn.block != 'undefined' ) {
            table.fadeTo('400', '0.6').block({message: null,
                overlayCSS                           : {
                    background    : 'transparent url(' + yith_mdbd_l10n.ajax_loader_url + ') no-repeat center',
                    backgroundSize: '16px 16px',
                    opacity       : 0.6
                }
            });
        }

        $( '#yith-mdbd-form' ).load( yith_mdbd_l10n.ajax_url + ' #yith-mdbd-form', data, function(){

            if( typeof $.fn.unblock != 'undefined' ) {
                table.stop(true).css('opacity', '1').unblock();
            }

            init_handling_after_ajax();

            $('body').trigger('moved_to_another_moodboard');
        } );
    }

    /**
     * Show form to edit moodboard title
     *
     * @param ev event
     * @return void
     * @since 2.0.0
     */
    function show_title_form( ev ){
        var t = $(this);
        ev.preventDefault();

        t.parents( '.moodboard-title' ).next().show();
        t.parents( '.moodboard-title' ).hide();
    }

    /**
     * Hide form to edit moodboard title
     *
     * @param ev event
     * @return void
     * @since 2.0.0
     */
    function hide_title_form( ev ) {
        var t = $(this);
        ev.preventDefault();

        t.parents( '.hidden-title-form').hide();
        t.parents( '.hidden-title-form').prev().show ();
    }

    /**
     * Check if cookies are enabled
     *
     * @return bool
     * @since 2.0.0
     */
    function is_cookie_enabled() {
        if (navigator.cookieEnabled) return true;

        // set and read cookie
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") != -1;

        // delete cookie
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

        return ret;
    }

    /**
     * Add moodboard popup message
     *
     * @return void
     * @since 2.0.0
     */
    function add_moodboard_popup() {
        if( $('.yith-mdbd-add-to-moodboard').length != 0 && $( '#yith-mdbd-popup-message' ).length == 0 ) {
            var message_div = $( '<div>' )
                .attr( 'id', 'yith-mdbd-message' ),
                popup_div = $( '<div>' )
                    .attr( 'id', 'yith-mdbd-popup-message' )
                    .html( message_div )
                    .hide();

            $( 'body' ).prepend( popup_div );
        }
    }

    /**
     * Handle "Add to cart" checkboxes events
     *
     * @return void
     * @since 2.0.5
     */
    function handle_moodboard_checkbox() {
        checkboxes.on( 'change', function(){
            var ids = '',
                table = $(this).parents( '.cart.moodboard_table'),
                moodboard_id = table.data( 'id'),
                moodboard_token = table.data( 'token'),
                url = document.URL;

            checkboxes.filter(':checked').each( function(){
                var t = $(this);
                ids += ( ids.length != 0 ) ? ',' : '';
                ids += t.parents('[data-row-id]').data( 'row-id' );
            } );

            url = add_query_arg( url, 'moodboard_products_to_add_to_cart', ids );
            url = add_query_arg( url, 'moodboard_token', moodboard_token );
            url = add_query_arg( url, 'moodboard_id', moodboard_id );

            $('#custom_add_to_cart').attr( 'href', url );
        } );
    }

    /**
     * Add a query arg to an url
     *
     * @param purl  original url
     * @param key   query argr key
     * @param value query arg value
     * @return string
     * @since 2.0.7
     */
    function add_query_arg(purl, key,value){
        var s = purl;
        var pair = key+"="+value;

        var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

        s = s.replace(r,"$1"+pair);

        if(s.indexOf(key + '=')>-1){


        }
        else{
            if(s.indexOf('?')>-1){
                s+='&'+pair;
            }else{
                s+='?'+pair;
            }
        }

        return s;
    }
});