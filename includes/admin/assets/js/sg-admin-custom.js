(function ($) {

  function init() {
    $('#chk-status').change(function() {
      var status = $(this).prop('checked');
      console.log(status);
    })
  }

  $(document).ready(init);

  }(jQuery));
