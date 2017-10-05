jQuery(document).on('click', '#allow-permissions > ul > li.permissions', function() {
  jQuery('#deny-permissions ul').append(jQuery(this));
});
jQuery(document).on('click', '#deny-permissions > ul > li.permissions', function() {
  jQuery('#allow-permissions ul').append(jQuery(this));
});
jQuery(document).on('click', '', function() {
  var dataList = jQuery("#allow-permissions > ul > li.permissions").map(function() {
    return $(this).get('li');
  }).get();
  var dataListDeny = jQuery("#deny-permissions > .permissions").map(function() {
    return $(this).data("name");
  }).get();
  jQuery('#roleform-allow_permissions').val(dataList);
  jQuery('#roleform-deny_permissions').val(dataListDeny);
});
jQuery(document).on('click', '#parent_roles_search', function() {
  var title = jQuery('#select2-permissionform-parent_roles_search-container').attr('title');
  console.log(title);
  jQuery('#parent_roles_list .table').append(divWrapper(title));
});
jQuery(document).on('click', '#parent_permissions_search', function() {
  var title = jQuery('#select2-permissionform-parent_permissions_search-container').attr('title');
  jQuery('#parent_permissions_list .table').append(divWrapper(title));
});
jQuery(document).on('click', '#children_permissions_search', function() {
  var title = jQuery('#select2-permissionform-children_permissions_search-container').attr('title');
  jQuery('#children_permissions_list .table').append(divWrapper(title));
});
jQuery(document).on('click', '', function() {
  var dataListRoles = jQuery("#parent_roles_list table .alert").map(function() {
    return $(this).data("name");
  }).get();
  var dataListPermissions = jQuery("#parent_permissions_list table .alert").map(function() {
    return $(this).data("name");
  }).get();
  var dataListChildrePermissions = jQuery("#children_permissions_list table .alert").map(function() {
    return $(this).data("name");
  }).get();
  jQuery('#permissionform-parent_roles').val(dataListRoles);
  jQuery('#permissionform-parent_permissions').val(dataListPermissions);
  jQuery('#permissionform-children_permissions').val(dataListChildrePermissions);
});
function divWrapper(title) {
  return '<tr><td class="alert" data-name="'+ title + '">'+
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
    title + '</td></tr>';
};
var inputAllow = document.getElementById('allowSearch');
inputAllow.onkeyup = function () {
  var filter = inputAllow.value.toUpperCase();

  var lis = document.querySelectorAll('#allow-permissions li');
  for (var i = 0; i < lis.length; i++) {
    var name = lis[i].innerHTML;
    if (name.toUpperCase().indexOf(filter) == 0)
      lis[i].style.display = 'list-item';
    else
      lis[i].style.display = 'none';
  }
};
var inputDenny = document.getElementById('denySearch');
inputDenny.onkeyup = function () {
  var filter = inputDenny.value.toUpperCase();

  var lis = document.querySelectorAll('#deny-permissions li');
  for (var i = 0; i < lis.length; i++) {
    var name = lis[i].innerHTML;
    if (name.toUpperCase().indexOf(filter) == 0)
      lis[i].style.display = 'list-item';
    else
      lis[i].style.display = 'none';
  }
};