<script type="text/javascript">
  $.ajax({
    type: 'GET',
    dataType: 'json',
    url: "{{ $url }}",
    data: {
      id : "{{ $old_id }}"
    }
  }).then(function (data) {
      var option = new Option(data.text, data.id, true, true);
      $("{{ $id }}").append(option).trigger('change');
      $("{{ $id }}").trigger({
          type: 'select2:select',
          params: {
              data: data
          }
      });
  });
</script>