<?php
include 'nav.php';
include 'connection.php';
$msg="";
?>

<!------------------------------ Main Page Style Start ----------------------------->
<h3 id="h3">Document Management <hr></h3>



<!------------------------ TABLE----------------------->

<div class="container">   
  <form method="post" action="" enctype="multipart/form-data">    
  <table class="table table-bordered" id="TableS">
    <thead>
      <tr class="table-primary">
        <th scope="col"></th>
        <th scope="col">File Category</th>
        <th scope="col">Upload File</th>
        <th scope="col">Filename</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"></td>
        <td><button type="button" id="add" data-id="32" class="btn btn-primary" ><i class="bi bi-plus-circle-fill"></i>Add</button>
        <input type="submit" name="upload" id="sumbit"class="btn btn-info" value="Upload"></td>
      </tr>
    </tfoot>
  </table>
  </form> 
</div>

<!------------------------ JQUERY SCRIPT FOR ADD ROW ----------------------->
<!------------------------ VAR X=1 (ID FOR EACH FIELD BUT ITS NOT INCREASING) ----------------------->
<script>
var x=1;
$(document).ready(function() {
  
    var html = '<tr>';
    html += '<td></td>';
    html += '<td><select name="doctyp[]" class="form-select "><option >choose</option></select></td > ';
    html += '<td><input type="file" name="doc[]" id="inputfile[]" data-id='+x+' class="form-control inputfile"/></td>';
    html += ' <td><input type="text" class="outputfile" id="outputfile'+x+'" name="filename[]"><input id="extension'+x+'" type="text" name="extension" disabled></td>';
    html += '<td><button type="button" id="delete" class="btn btn-primary"><i class="bi bi-trash3-fill"></i>delete</button></td>';
    html += '</tr>';
    // x++;
    // var x = 2;
   
    $("#add").click(function() {
        $("#TableS").append(html);  
    });
    $('.table tbody').on('click', '#delete', function() {
        $(this).closest('tr').remove();
    });
});
// x++;

<!------------------------ filename setting ----------------------->
$(document).on('change', '.inputfile', function (e) {
    var imgid = $(this).attr("id");
    var filenames = [].slice.call(e.target.files).map(function (f) {
        alert(f.name);
        return f.name.split('.')[0];
    });
    var Extnames = [].slice.call(e.target.files).map(function (g) {
        return g.name.split('.')[1];
    });
    $('#extension' + $(this).attr("data-id")).val(Extnames);
    $('#outputfile' + $(this).attr("data-id")).val(filenames);
});

</script>
<?php
include 'footer.php';
?> 
