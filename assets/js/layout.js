(function ($) {

    $(window).on('load', function () {
        $('.summary p.stock, .summary p.price, .single_add_to_cart_button, .bid-btn').show();
        $('.preloader').hide();
      })

    function init() {
      $('.bid-btn').remove().clone().insertAfter('form .single_add_to_cart_button');

      $('.wc-tabs-wrapper').addClass('row');
      $('#tab-description').addClass('col-6 pr-5');
      $('#tab-additional_information').addClass('col-6')
      $('#tab-reviews').appendTo('#tab-additional_information');

      $('.summary p.price').prependTo('.cart');

    }
  
    $(document).ready(init);
  
    }(jQuery));
  