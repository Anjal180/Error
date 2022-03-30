<?php
include 'nav.php';
include 'connection.php';
$msg="";
?>

<!------------------------------ Main Page Style Start ----------------------------->
<h3 id="h3">Document Management <hr></h3>



<?php
function fill_select_box($conn){
  $opt="";
  $q2="SELECT * FROM doc_mst ORDER BY doc_type ASC";
  $result2=mysqli_query($conn,$q2);
  // $result2=$conn->query($query);
  foreach($result2 as $row2)
  {
    $opt.='<option value="'.$row2['doc_type'].'">' .$row2['doc_type'].'</option>';
  }
  return $opt;
}


?>
<!-------------------------------- PHP code for file upload ---------------------------------------->
<?php 
if(isset($_POST['upload'])){
  extract($_POST);
  $doctyp=$_POST['doctyp'];
 $doc=$_FILES['doc']['name'];
  // $filename=$_POST['filename'];
  // $extension=$_POST['extension'];


  foreach($doc as $key => $value){
    echo $doc=$_FILES['doc']['name'][$key];
   echo $file_type=pathinfo($doc,PATHINFO_EXTENSION);
    echo $filePath='docfile/'.$doc;
   echo $tmp_name=$_FILES['doc']['tmp_name'][$key];
    move_uploaded_file($tmp_name,$filePath);
    
  // print_r($_POST);
  // $extension=$_POST['extension'][$key];
  // $filesnam=$filename.$extension;
    
   <!------------------ INSERT CODE DOCUMENTS UPLOAD---------------------------------->
  $ins="INSERT INTO `up_doc_dtls`(`cust_id`, `prod_id`, `doc_type`, `file_name`, `doc_name`) VALUES ('$customer','$prodid','".$doctyp[$key]."','".$value."','".$filename[$key]."')";
  $ab=mysqli_query($conn,$ins);
  }
}
?>



<div class="container">
  
  <!-- <form method="post" action="" enctype="multipart/form-data">   -->
  <div class="table-wrapper-scroll-y my-custom-scrollbar"> 
  <table class="table table-bordered table-striped mb-0" id="TableS">
    <thead>
      <tr class="table-primary">
        <th scope="col"></th>
        <th scope="col">File Category</th>
        <th scope="col">Upload File</th>
        <th scope="col">Filename</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>
    <button type="button" id="add" data-id="32" class="btn btn-primary" style="margin-left:85%;"><i class="bi bi-plus-circle-fill"></i></button>
    <input type="submit" name="upload" id="sumbit"class="btn btn-info" value="Upload">
    
  </form> 

  </div>
<div class="py-5"></div><div class="py-2"></div>
<script>
function getHtml(index) {
var html = '<tr id="td">';
    html += '<td id="td"></td>';
    html += '<td id="td"><select name="doctyp[]" class="form-select"><option >choose</option><?php echo fill_select_box($conn); ?></select></td > ';
    html += '<td id="td"><input type="file" name="doc[]" id="inputfile[]" data-id='+index+' class="form-control inputfile"/></td>';
    html += ' <td id="td"><input type="text" class="form-control-sm outputfile" id="outputfile'+index+'" name="filename[]"><input id="extension'+index+'" type="text" class="form-control-sm" name="extension[]" disabled></td>';
    html += '<td id="td"><button type="button" id="delete" class="btn btn-primary"><i class="bi bi-trash3-fill"></i></button></td>';
    html += '</tr>';
    return html;
}
var x=1;
$(document).ready(function() {
  
    $("#add").click(function() {
    		var html = getHtml(x++);
        $("#TableS").append(html);
        $("table tbody #td").css('padding','3px');
        $("table tbody .form-control-sm").css('border','1px solid #bdbdbd');
    });
    $('.table tbody').on('click', '#delete', function() {
        $(this).closest('tr').remove();
    });
});

// <!------------------------ filename setting ----------------------->
$(document).on('change', '.inputfile', function (e) {
    var imgid = $(this).attr("id");
    var filenames = [].slice.call(e.target.files).map(function (f) {
        // alert(f.name);
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
