(function ($) {
  $(document).ready(function(){
    init_role_permissions_multiselect();
  })

  function init_role_permissions_multiselect() {
    if (! $('#allow_permissions').length) {
      return;
    }

    $('#allow_permissions').multiselect({
      right: '#deny_permissions',
      sort: function(a, b) {
        console.log([$(a).data('weight'), $(b).data('weight')])
        return $(a).data('weight') > $(b).data('weight') ? 1 : -1;
      },
      search: {
        left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
      }
    });
  }
})(jQuery);
