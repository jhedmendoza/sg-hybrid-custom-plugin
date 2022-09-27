(function ($) {

  function init() {

    $('body').on('click', '.user-registration', function(e) {
      e.preventDefault();
      registerUser();
    });

    $('body').on('click', '.bid-btn', function(e) {
      e.preventDefault();
      var productID = $(this).attr('data-product-id');
      registerUser();

    });

    function registerUser() {
      Swal.fire({
        title: 'Registration',
        html: 
        `
        <input type="text" id="create-email" class="swal2-input" placeholder="Email" required>
        <input type="text" id="create-username" class="swal2-input" placeholder="Username">
        <input type="password" id="create-password" class="swal2-input" placeholder="Password">
        <input type="password" id="repeat-password" class="swal2-input" placeholder="Repeat Password">
        `,
        confirmButtonText: 'Register',
        focusConfirm: false,
        preConfirm: () => {
          const email = Swal.getPopup().querySelector('#create-email').value
          const username = Swal.getPopup().querySelector('#create-username').value
          const password = Swal.getPopup().querySelector('#create-password').value
          const repeatPassword = Swal.getPopup().querySelector('#repeat-password').value

          return new Promise(function (resolve) {
            $.ajax({
                url : sg_ajax_url,
                type: 'POST',
                data: {
                  'action'  : 'user_registration',
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

  }

  $(document).ready(init);

  }(jQuery));
