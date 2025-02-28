(function ($) {

  function init() {

    var auctionTimeLeft = $('.countdown-timer').attr('data-time-left');
    var currentTime = $('.countdown-timer').attr('data-current-time');
    var diffTime = auctionTimeLeft - currentTime;
    var duration = moment.duration(diffTime*1000, 'milliseconds');
    var interval = 1000;

    setInterval(function() {
      if (diffTime <= 0) {
        $('.table_running-bids tr').eq(1).find('.countdown-timer').html('<span class="badge bg-secondary badge_bid-status">Won</span>');
      }
      else {
        duration = moment.duration(duration - interval, 'milliseconds');
        $('.table_running-bids tr').eq(1).find('.countdown-timer').text( duration.minutes() + ' minutes ' + duration.seconds() + ' seconds' );
      }

      diffTime--;

    }, interval);

    $('.finished-bids table tr').eq(1).find('.badge_bid-status').show();

    $('.btn-approve').on('click', function(e) {
      e.preventDefault();

      var bidderName = $(this).parent().siblings('.bidder-name').text();
      console.log(bidderName);

      Swal.fire({
        title: 'Are you sure you want to approve '+bidderName+'\'s bid?',
        confirmButtonText: 'Yes',
        showCancelButton: 'Cancel',
        icon: 'warning',
        focusConfirm: false,
        preConfirm: () => {
          $.ajax({
              url : ajaxurl,
              type: 'POST',
              data: {
                'action'    : 'approve_user_auction',
                'user_id'   : $(this).attr('data-user-id'),
                'product_id': $(this).attr('data-product-id'),
                'bid_price' : $(this).attr('data-bid-price'),
                'status'    : 1
              },
              beforeSend: function () {},
              success: function (response) {
                var resp = JSON.parse(response);
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
                else {
                  Swal.fire({
                    icon: 'error',
                    text: resp.msg,
                    showConfirmButton: true,
                  });
                }
              }
          });
        },
      });

    });

    $('.btn-reject').on('click', function(e) {
      e.preventDefault();

      var bidderName = $(this).parent().siblings('.bidder-name').text();
      console.log(bidderName);

      Swal.fire({
        title: 'Are you sure you want to reject '+bidderName+'\'s bid?',
        confirmButtonText: 'Yes',
        showCancelButton: 'Cancel',
        icon: 'warning',
        focusConfirm: false,
        preConfirm: () => {
          $.ajax({
              url : ajaxurl,
              type: 'POST',
              data: {
                'action'    : 'approve_user_auction',
                'user_id'   : $(this).attr('data-user-id'),
                'product_id': $(this).attr('data-product-id'),
                'bid_price' : $(this).attr('data-bid-price'),
                'status'    : 0
              },
              beforeSend: function () {},
              success: function (response) {
                var resp = JSON.parse(response);
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
                else {
                  Swal.fire({
                    icon: 'error',
                    text: resp.msg,
                    showConfirmButton: true,
                  });
                }
              }
          });
        },
      });

    });




  }

  $(document).ready(init);

  }(jQuery));
