(function ($) {

  function init() {

    $('.user-registration a').on('click', function(e) {
      e.preventDefault();
    });

    $('.bid-btn').on('click', function(e) {
      e.preventDefault();
      var productID = $(this).attr('data-product-id');
      registerUser();

    });

    function registerUser() {
      Swal.fire({
        title: 'Registration',
        html: 
        `
        <input type="text" id="create-email" class="swal2-input" placeholder="Email">
        <input type="text" id="create-username" class="swal2-input" placeholder="Username">
        <input type="password" id="create-password" class="swal2-input" placeholder="Password">
        <a style="float:left" href="#">Register</a>
        `,
        confirmButtonText: 'Register',
        focusConfirm: false,
        preConfirm: () => {
          const email = Swal.getPopup().querySelector('#create-email').value
          const userame = Swal.getPopup().querySelector('#create-username').value
          const password = Swal.getPopup().querySelector('#create-password').value

          return new Promise(function (resolve) {
            $.ajax({
                url : sg_ajax_url,
                type: 'POST',
                data: {
                  'action'  : 'user_registration',
                  'email'   : email,
                  'username': userame,
                  'password': password
                }, 
                beforeSend: function () {},
                success: function (response) {
                  var resp = JSON.parse(response);
                  if (resp.status) {
                    location.reload();
                  }
                  else {
                    Swal.showValidationMessage(resp.error);
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
