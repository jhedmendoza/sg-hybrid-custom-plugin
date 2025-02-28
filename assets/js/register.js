(function ($) {

  function init() {

    if ( $('#winner_maximun_bid').length ) {
      $('#yith-wcact-form-bid').hide();
    }

    $('body').on('click', '.user-registration', function(e) {
      e.preventDefault();
      registerUser();
    });

    $('body').on('click', '.bid-btn', function(e) {
      e.preventDefault();
      var productID = $(this).attr('data-product-id');

      if (isUserLogin == 1)
        bidOnProduct(productID);

      else
        registerUser();

    });

    function bidOnProduct(productID) {

      Swal.fire({
        title: 'Bid on this product',
        showCloseButton: true,
        confirmButtonText: 'Submit',
        confirmButtonColor: '#53CFCB',
        focusConfirm: false,
        html:
        `
        <label for="bid_amount" style="margin-top: 40px;padding-right:15px;font-size:25px">£</label>
        <input type="number" id="bid-amount" class="swal2-input" min="1" placeholder="Amount" name="bid_amount">
        `,
        preConfirm: () => {
          const amount = Swal.getPopup().querySelector('#bid-amount').value

          if (amount == '' || amount == 0) {
            Swal.showValidationMessage('Please add an amount');
          } else if (!isNumeric(amount)) {
            Swal.showValidationMessage('Please add a valid amount');
          }

          return new Promise(function (resolve) {
            $.ajax({
                url : ajaxurl,
                type: 'POST',
                data: {
                  'action'    : 'sg_user_bid',
                  'amount'    : amount,
                  'product_id': productID
                },
                beforeSend: function () {},
                success: function (response) {
                  var resp = JSON.parse(response);
                  if (resp.status) {

                    Swal.fire({
                      icon: 'success',
                      text: resp.msg,
                      showConfirmButton: true,
                    })

                    $('.bid-btn')
                      .addClass('disabled')
                      .attr({
                          'disabled':true,
                          'title':'You already bid for this product. Wait for the seller to approve your bid.',
                      });
                  }
                  else {
                    Swal.showValidationMessage(resp.msg);
                    return false;
                  }
                }
            });
          })


        },
      })

      $('.swal2-input').on('keyup mouseup',function () {
        $('.swal2-confirm').removeAttr('disabled');
      });
    }

    function registerUser() {
      Swal.fire({
        title: 'Registration',
        showCloseButton: true,
        confirmButtonText: 'Register',
        confirmButtonColor: '#53CFCB',
        focusConfirm: false,
        html:
        `
        <input type="text" id="create-email" class="swal2-input" placeholder="Email" required>
        <input type="text" id="create-username" class="swal2-input" placeholder="Username">
        <input type="password" id="create-password" class="swal2-input" placeholder="Password">
        <input type="password" id="repeat-password" class="swal2-input" placeholder="Repeat Password">
        `,
        preConfirm: () => {
          const email = Swal.getPopup().querySelector('#create-email').value
          const username = Swal.getPopup().querySelector('#create-username').value
          const password = Swal.getPopup().querySelector('#create-password').value
          const repeatPassword = Swal.getPopup().querySelector('#repeat-password').value

          return new Promise(function (resolve) {
            $.ajax({
                url : ajaxurl,
                type: 'POST',
                data: {
                  'action'  : 'sg_user_registration',
                  'email'   : email,
                  'username': username,
                  'password': password,
                  'repeat_password': repeatPassword
                },
                beforeSend: function () {},
                success: function (response) {
                  var resp = JSON.parse(response);
                  if (resp.status) {
                    window.location.replace(siteurl+'/my-account/payment-methods');
                  }
                  else {
                    Swal.showValidationMessage(resp.msg);
                    return false;
                  }
                }
            });
          })
        },
      })

      $('.swal2-input').on('keyup',function () {
        $('.swal2-confirm').removeAttr('disabled');
      });
    }

    function isNumeric(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }

  }

  $(document).ready(init);

  }(jQuery));
