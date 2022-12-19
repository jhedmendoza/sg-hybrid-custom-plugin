(function ($) {

  function init() {

    $('.add_to_watchlist ').on('click', function(e) {
      e.preventDefault();

        var product_id = $(this).attr('data-product-id');
        var user_id    = $(this).attr('data-user-id');

        $.ajax({
          url : ajaxurl,
          type: 'POST',
          data: {
            'action'    : 'override_yith_watchlist',
            'product_id': product_id,
            'user_id'   : user_id
          },
          beforeSend: function () {
            $('.add_to_watchlist').css('pointer-events', 'none');
          },
          success: function (resp) {

            if (resp.status) {
              Swal.fire({
                icon: 'success',
                text: resp.msg,
                showConfirmButton: true,
                preConfirm:()=> {
                  location.reload();
                }
              });
            }

          }
      });
    });

    $(document).on('click', '.gallery-star', function(e) {
      e.preventDefault();

        $(this).toggleClass('active');

        var product_id = $(this).attr('data-product-id');
        var user_id    = $(this).attr('data-user-id');
        var isActive   = $(this).hasClass('active') ? 'yes' : 'no';


        $.ajax({
          url : ajaxurl,
          type: 'POST',
          data: {
            'action'    : 'override_yith_watchlist',
            'product_id': product_id,
            'user_id'   : user_id,
            'is_active' : isActive
          },
          beforeSend: function () {
            $('.gallery-star').css('pointer-events', 'none');
          },
          success: function (resp) {

            if (resp.status) {
              Swal.fire({
                icon: 'success',
                text: resp.msg,
                showConfirmButton: true,
                preConfirm:()=> {
                  $('.gallery-star').css('pointer-events', 'auto');
                  // location.reload();
                }
              });
            }

          }
        });

    });

    $(document).on('click', '.del_watchlist', function(e) {
      e.preventDefault();

        var product_id = $(this).attr('data-product-id');
        var user_id    = $(this).attr('data-user-id');

        $.ajax({
          url : ajaxurl,
          type: 'POST',
          data: {
            'action'    : 'override_yith_watchlist',
            'product_id': product_id,
            'user_id'   : user_id,
            'is_active' : 'yes'
          },
          beforeSend: function () {},
          success: function (resp) {

            if (resp.status) {
              Swal.fire({
                icon: 'success',
                text: resp.msg,
                showConfirmButton: true,
                preConfirm:()=> {
                  location.reload();
                }
              });
            }

          }
        });

    });





  }

  $(document).ready(init);

  }(jQuery));
