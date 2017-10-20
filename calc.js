var x=null;
var y=null;

function updateSum() {
    if (x != null && y != null) {
    $.post( "calc-ajax.php", { 'x': x, 'y': y })
     .done(function( data ) {
         $("#z").val(data.z + " (" + data.status + ")");
      });
    }
}

$(document).ready(function() {
  $("#x").change(function() { x=$(this).val(); updateSum(); });
  $("#y").change(function() { y=$(this).val(); updateSum(); });
});
