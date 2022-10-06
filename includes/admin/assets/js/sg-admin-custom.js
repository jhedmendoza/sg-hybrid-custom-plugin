(function ($) {

  function init() {

    $('#chk-status').change(function() {
      var status = $(this).prop('checked');
      if (status) {
        $.ajax({
            url : ajaxurl,
            type: 'POST',
            data: {
              'action'    : 'approve_reject_user_auction',
              'user_id'   : $(this).attr('data-user-id'),
              'product_id': $(this).attr('data-product-id'),
              'bid_price' : $(this).attr('data-bid-price'),
              'status'    : status
            },
            beforeSend: function () {},
            success: function (response) {
              var resp = JSON.parse(response);
              if (resp.status) {
              }
              else {
              }
            }
        });
      }
    })

  }

  $(document).ready(init);

  }(jQuery));
