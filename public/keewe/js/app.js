/*  ---------------------------------------------------
Template Name: Ashion
Description: Ashion ecommerce template
Author: Colorib
Author URI: https://colorlib.com/
Version: 1.0
Created: Colorib
---------------------------------------------------------  */

'use strict';

(function ($) {

    /*------------------
        Preloader
    --------------------*/
    $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");
    });

    /*------------------
        Background Set
    --------------------*/
    $('.set-bg').each(function () {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')');
    });

    /*------------------
        Scroll to Top
    --------------------*/
    $('#back-to-top').on('click', function(){
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

    //Canvas Menu
    $(".canvas__open").on('click', function () {
        $(".offcanvas-menu-wrapper").addClass("active");
        $(".offcanvas-menu-overlay").addClass("active");
    });

    $(".offcanvas-menu-overlay, .offcanvas__close").on('click', function () {
        $(".offcanvas-menu-wrapper").removeClass("active");
        $(".offcanvas-menu-overlay").removeClass("active");
    });

    /*------------------
		Navigation
	--------------------*/
    $(".header__menu").slicknav({
        prependTo: '#mobile-menu-wrap',
        allowParentLinks: true
    });

    /*------------------
		Magnific
    --------------------*/
    $('.image-popup').magnificPopup({
        type: 'image'
    });

    /*
    $(".nice-scroll").niceScroll({
        cursorborder:"",
        cursorcolor:"#dddddd",
        boxzoom:false,
        cursorwidth: 5,
        background: 'rgba(0, 0, 0, 0.2)',
        cursorborderradius:50,
        horizrailenabled: false
    });
    */

    /*-------------------
		Range Slider
	--------------------- */
    if( rangeSlider )
    {
        var rangeSlider = $(".price-range"),
        minamount = $("#minamount"),
        maxamount = $("#maxamount"),
        minPrice = rangeSlider.data('min'),
        maxPrice = rangeSlider.data('max');

        rangeSlider.slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function (event, ui) {
                minamount.val('$' + ui.values[0]);
                maxamount.val('$' + ui.values[1]);
            }
        });

        minamount.val('$' + rangeSlider.slider("values", 0));
        maxamount.val('$' + rangeSlider.slider("values", 1));
    }

    /*------------------
		Single Product
	--------------------*/
	$('.product__thumb .pt').on('click', function(){
		var imgurl = $(this).data('imgbigurl');
		var bigImg = $('.product__big__img').attr('src');
		if(imgurl != bigImg) {
			$('.product__big__img').attr({src: imgurl});
		}
    });

    /*-------------------
		Quantity change
	--------------------- */
    var proQty = $('.pro-qty');
	proQty.prepend('<span class="dec qtybtn">-</span>');
	proQty.append('<span class="inc qtybtn">+</span>');
	proQty.on('click', '.qtybtn', function () {
		var $button = $(this);
		var oldValue = $button.parent().find('input').val();
		if ($button.hasClass('inc')) {
			var newVal = parseFloat(oldValue) + 1;
		} else {
			// Don't allow decrementing below zero
			if (oldValue > 0) {
				var newVal = parseFloat(oldValue) - 1;
			} else {
				newVal = 0;
			}
		}
		$button.parent().find('input').val(newVal);
    });

    /*-------------------
		Radio Btn
	--------------------- */
    $(".size__btn label").on('click', function () {
        $(".size__btn label").removeClass('active');
        $(this).addClass('active');
    });

    /*-------------------
		Prevent image download
	--------------------- */
    $('img').on('contextmenu', function(event){
        event.preventDefault();
        return false;
    });
    $('img').on('click', function(event){
        if( event.which != 1 )
        {
            event.preventDefault();
            return false;
        }
    });

    /*-------------------
		Tooltips
	--------------------- */
    $('[data-toggle="tooltip"]').tooltip();

    /*-------------------
		Sub Categories
	--------------------- */
    $('#sub-categories').find('.item-parent').off().on('mouseenter', function(){
        //
        var _target = $(this).attr('data-target');
        //console.log( _target );
        $(_target).stop(true, false).slideDown();
    });

    $('#sub-categories').find('.card').off().on('mouseleave', function(){
        //
        var $target = $(this).find('.collapse');

        if( $target.is(':visible') )
            $target.stop(true, false).slideUp();
    });

    /*-------------------
		Input Masks
	--------------------- */
    $('.msk-cep').mask('00000-000');
    $('.msk-cpf').mask('000.000.000-00');
    $('.msk-cnpj').mask('00.000.000/0000-00');
    $('.msk-date').mask('00/00/0000');
    $('.msk-int').mask("#.##0", {reverse: true});
    $('.msk-dec').mask("#.##0,00", {reverse: true});
    $('.msk-num').mask("#0", {reverse: true});
    $('.msk-phone').mask("(00) 90000-0000");
    // Credit card
    $('.msk-mmyy').mask('00/00');
    $('.msk-cvv').mask('0009');

	// Credit Card Mask
    var ccBehavior = function( val ){
        // Get value length
        var size = val.length;
        var mask;

        if( size <= 16 )
            mask = '0000 000000 00009';
        else if( size == 17 )
            mask = '0000 000000 000009';
        else
            mask = '0000 0000 0000 0000';

        return mask;
    };

    var cc_opts = {
        onKeyPress: function( val, e, field, options ){
            field.mask( ccBehavior.apply({}, arguments), options );
        }
    };
    $('.msk-cc').mask( ccBehavior, cc_opts );
	
	// Hybrid document Mask
	var docBehavior = function( val ){
		// Get value length
		var size = val.length;
		var mask;
		
		if( size <= 14 )
			mask = '000.000.000-009';
		else
			mask = '00.000.000/0000-00';
		
		return mask;
	};
	
	var doc_opts = {
		onKeyPress: function( val, e, field, options ) {
			field.mask( docBehavior.apply({}, arguments), options );
		}
	};
	$('.msk-doc').mask( docBehavior, doc_opts );
	

    /*-------------------
		Select 2
	--------------------- */
    $('.select2').select2({
        allowClear: true,
        placeholder: 'Selecione uma opção',
        selectionCssClass: 'form-control',
        width: '100%'
    });

    /*-------------------
		Summernote
	--------------------- */
    if( jQuery().summernote )
    {
        $('.editor').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                //['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            lang: 'pt-BR'
        });

        $('.editor-sm').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                //['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            lang: 'pt-BR'
        });
    }

    /*-------------------
		Image Slider
	--------------------- */
    /*
    if( jQuery().imageslider )
    {
        $('#scroller').imageslider({
            slideItems: '.slider-item',
            slideContainer: '.slider-list',
            slideDistance: 5,
            slideDuratin: 800,
            resizable: true,
            pause: true
        });
    }
    */

    /*-------------------
		Uploader
	--------------------- */
    if( jQuery().dropzone )
    {
        //Uploader.init();
    }

})(jQuery);

/**
 * Toogle Categories megamenu visibility
 */
function toogleCategories()
{
    if( $('#mega').is(':visible') )
    {
        $('#mega').stop(true, false).slideUp();
    }
    else
    {
        $('#mega').stop(true, false).slideDown();
    }
}

/**
 * Add item to Wishlist
 */
function addToWishlist( item_id, uid )
{
    console.log( 'addToWishlist( '+ item_id +', '+ uid +' )' );

    if( (item_id == null) || (item_id == undefined) || (item_id == 'undefined') || (item_id == 0) || (item_id == '') )
    {
        console.log( 'No item given' );
        return false;
    }

    if( (uid == null) || (uid == undefined) || (uid == 'undefined') || (uid == 0) || (uid == '') )
    {
        // Redirect to login
        document.location.href = BASE_URL + '/entrar';
    }

    var url = BASE_URL + '/wishlist';
    var prs = { product_id: item_id, user_id: uid };

    Common.ajax( url, 'POST', prs, null, Common.saveHandler );
}

/**
 * Add item to cart
 */
function addToCart( _sender )
{
    //console.log( 'addToCart('+ _sender +')' );

    if( ! _sender )
        return false;

    // Get the form
    var $form = $(_sender).closest('div.product__item').find('form');

    Common.postForm($form, countCartItems);
}

/**
 * Request to get cart items count
 */
function countCartItems( json )
{
    //console.log( 'countCartItems(', json, ')' );

    Common.responseHandler( json );

    if( json.success !== true )
        return;

    // Update cart info on header
    var url = BASE_URL + '/carts/count';

    Common.ajax( url, 'GET', [], null, updateCartCount );
}

/**
 * Updates the Cart counter indicator on header
 */
function updateCartCount( json )
{
    //console.log( 'updateCartCount(', json, ')' );

    if( json.success !== true )
        return;

    var $counter = $('<div class="tip">'+ json.count +'</div>');

    // Off canvas menu
    $('div.offcanvas-menu-wrapper').find('span.fa-shopping-cart').next().remove();
    $('div.offcanvas-menu-wrapper').find('span.fa-shopping-cart').after( $counter );

    // Normal menu
    $('div.header__right').find('span.fa-shopping-cart').next().remove();
    $('div.header__right').find('span.fa-shopping-cart').after( $counter );
}
