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

  }

  $(document).ready(init);

  }(jQuery));
