console.log('UserCandy JS loaded');

window.showPopup = function(message, type){
  var popup = $('<div>').addClass('px-4 py-2 rounded text-white shadow');
  popup.text(message);
  popup.addClass(type === 'success' ? 'bg-green-500' : 'bg-red-500');
  $('#popup-container').append(popup);
  setTimeout(function(){ popup.fadeOut(400, function(){ $(this).remove(); }); }, 5000);
};

function toggleTheme(){
  if($('html').hasClass('dark')){
    $('html').removeClass('dark');
    $('#theme-toggle').html('&#9728;');
  }else{
    $('html').addClass('dark');
    $('#theme-toggle').html('&#9790;');
  }
  localStorage.setItem('uc_theme', $('html').hasClass('dark') ? 'dark' : 'light');
}

$(function(){
  if(localStorage.getItem('uc_theme') === 'dark'){
    $('html').addClass('dark');
    $('#theme-toggle').html('&#9790;');
  }
  $('#theme-toggle').on('click', toggleTheme);

  $('#user-avatar').on('click', function(){ $('#user-menu').toggleClass('hidden'); });
  $('#notif-bell').on('click', function(){ $('#notif-menu').toggleClass('hidden'); });

  if($('#user-table').length){
    function loadUsers(){
      $('#loading').text('Loading...');
      $.getJSON(baseUrl + 'api/users.php', function(data){
        var tbody = $('#user-table tbody').empty();
        data.forEach(function(u){
          var row = $('<tr>');
          row.append($('<td class="border px-2 py-1">').text(u.id));
          row.append($('<td class="border px-2 py-1 editable" data-id="'+u.id+'" data-field="email">').text(u.email));
          tbody.append(row);
        });
        $('#loading').text('');
      });
    }
    loadUsers();
    setInterval(loadUsers, 10000);
    $('#user-table').on('dblclick', 'td.editable', function(){
      var cell = $(this);
      var val = cell.text();
      var input = $('<input type="text" class="border"/>').val(val);
      cell.empty().append(input);
      input.focus().blur(function(){
        var newVal = $(this).val();
        $.post(baseUrl + 'api/update_user.php', {id: cell.data('id'), field: cell.data('field'), value: newVal}, function(){
          cell.text(newVal);
        }).fail(function(){
          cell.text(val);
          alert('Update failed');
        });
      });
    });
  }
});
