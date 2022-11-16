(function ($) {

  function init() {

    $('.user-login a').on('click', function(e) {
      e.preventDefault();

      Swal.fire({
        title: 'Login',
        showCloseButton: true,
        confirmButtonText: 'Sign in',
        confirmButtonColor: '#53CFCB',
        focusConfirm: false,
        html:
        `
        <input type="text" id="username" class="swal2-input" placeholder="Username">
        <input type="password" id="user-password" class="swal2-input" placeholder="Password">
        <a class="user-registration" style="float:left" href="#">Register</a>
        `,
        preConfirm: () => {
          const userame = Swal.getPopup().querySelector('#username').value
          const password = Swal.getPopup().querySelector('#user-password').value

          return new Promise(function (resolve) {
            $.ajax({
                url : ajaxurl,
                type: 'POST',
                data: {
                  'action'  : 'sg_user_login',
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

    });

  }

  $(document).ready(init);

  }(jQuery));
