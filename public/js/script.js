console.log('UserCandy JS loaded');
$(function(){
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
