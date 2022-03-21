<?php
include 'nav.php';
include 'connection.php';
$msg="";
?>

<!--------- style for btn and erorr -------------->
<style>
  #btn{
    margin-top: 5px;
    margin-left:78%;
  }
  .error{
		color: red;font-style: italic; font-weight: bold;
    text-align: center;
		}
</style>



<!------------------------------ Main Page Style Start ----------------------------->
<h3 id="h3">Document Management <hr></h3>
<!------------------------ Excel Sheet php code(bulk data) ----------------------->
<?php
 if(isset($_POST['xslxdata'])){
	 $file=$_FILES['doc']['tmp_name'];	
	 $ext=pathinfo($_FILES['doc']['name'],PATHINFO_EXTENSION);
	 if($ext=='xlsx'){
      require('PHPExcel/PHPExcel.php');
      require('PHPExcel/PHPExcel/IOFactory.php');
      $obj=PHPExcel_IOFactory::load($file);
      foreach($obj->getWorksheetIterator() as $sheet){
        $getHighestRow=$sheet->getHighestRow();
        for($i=0;$i<=$getHighestRow;$i++){
          $custid=$sheet->getCellByColumnAndRow(0,$i)->getValue();
          $custname=$sheet->getCellByColumnAndRow(1,$i)->getValue();
          $prodid=$sheet->getCellByColumnAndRow(2,$i)->getValue();
          //  $userid=$sheet->getCellByColumnAndRow(3,$i)->getValue();
          //  $username=$sheet->getCellByColumnAndRow(4,$i)->getValue();

          if($custid!=''){
            // mysqli_query($conn,"insert into prod_hdr(,user_id,user_name) values(,'$userid','$username')");
            mysqli_query($conn,"insert into prod_hdr(cust_id,cust_name,prod_id) values('$custid','$custname','$prodid')");
                      
          }
        }
		  }
        echo "<script>alert('data inserted');</script>";
        // echo '<h4 class="error" style="text-align="right">**data inserted</h4>';
	  }else{
      echo "<script>alert('Please upload .xlsx file');</script>";
      // echo '<h4 class="error" style="text-align="right">** Please upload .xlsx file</h4>';
    }
  }
?>
<!----------- bulk data enterning for .xlsx ------------>
  <button type="button" id="btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" >Bulk Data Upload</button>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" enctype="multipart/form-data" action="" >
          <div class="modal-body">
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">.xlsx File:</label>
              <input type="file" class="form-control" id="file"  name="doc">
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" id="">Clear</button>
            <button type="submit" name="xslxdata" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<!----------------------------------- PHP code for Prod_hr table(id) ---------------------------------------->
<?php
    $query   = "SELECT * FROM prod_hdr";
    $results = mysqli_query($conn, $query);
  ?>
 <?php
   $bul[10000007] = false;
 ?>
 <!------------ script for ajax to get product id -------------->
  <script>
        function getProd(val) {
        $.ajax({
            type: "POST",
            url: "get_prod.php",
            data:'prod_id='+val,


        success: function(data){
            $("#product-id").html(data);
        }
        });
        }

        function selectCustomer(val) {
        $("#search-box").val(val); 
        $("#suggesstion-box").hide();
        }
    </script>
<!----------------- Customer and Prodcut ID -------------------->
<div style="padding-left: 120px;">
     <div class="form-row" style="padding-top: 20px;">
        <form>
          <div class="col-lg-7">
                <div class="input-group">
                    <label class="input-group-text">Customer :</label>
                    <select class="form-select" id="customer" name="customer" onChange="getProd(this.value);">
                        <option value="">Choose Customer</option>
          <!----------------- Customer -------------------->
          <?php
                            $sql = "SELECT * FROM prod_hdr GROUP BY cust_name ASC";
                            $res = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($res)) {
                                if ($bul[$row['cust_name']] != true && $row['cust_name'] != 'cust_name') {
                        ?>
                        <option value="<?php  echo $row['cust_name']; ?>">
                                <?php echo $row['cust_name']; ?></option>
                            <?php $bul[$row['cust_name']] = true;
                                }
                            }
                        ?>
                        </select>
               </div>
           </div>
    
    <!----------------- Prodcut ID -------------------->
    <div class="col-lg-7" style="padding-top: 10px;">
                <div class="input-group">
                    <label class="input-group-text" >Product Id</label>
                    <select class="form-select" name="prodid" id="product-id">
                    <option selected>Product Id</option>
                    
                    </select>
                </div>
          </div>  

        </form>
     </div>  
  </div>
<br>
<!----------------------------------- Adding Row and File Uploading ------------------------------------->
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
  $doctyp=$_POST['doctyp'];
  $doc=$_FILES['doc']['name'];
  $filename=$_POST['filename'];

  foreach($doc as $key => $value){
    $doc=$_FILES['doc']['name'][$key];
    $file_type=pathinfo($doc,PATHINFO_EXTENSION);
    $filePath='docfile/'.$doc;
    $tmp_name=$_FILES['doc']['tmp_name'][$key];
    move_uploaded_file($tmp_name,$filePath);
  }
}
?>

<!---------------- ERROR ON FOLDER EXTENSION -------------------------------->

     <!-- $targetfolder = "filepath/";
     $targetfolder = $targetfolder . basename( $_FILES['doc']['name']) ;
     $ok=1;
     $file_type=$_FILES['doc']['type'];
    if ($file_type=="application/pdf" || $file_type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document"
     || $file_type=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $file_type=="application/vnd.ms-excel"
     ||$file_type=="application/msword" || $file_type=="text/plain") {
     if(move_uploaded_file($_FILES['doc']['tmp_name'], $targetfolder))
     {
    echo "The file ". basename( $_FILES['doc']['name']). " is uploaded";
    }
    else {
    echo "Problem uploading file";
   } 
   }
   else {echo "<h5 class='error'>**You may only upload pdf, xlsx , xls , txt , docx or doc.</h5><br>";} -->



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

      <tr>
        <td></td>
        <td><select name="doctyp[]" class="form-select ">
        <option selected>choose</option>
            <?php echo fill_select_box($conn); ?>
          </select>
        </td>
        <td><input type="file" name="doc[]" id='inputfile' class="form-control"onChange='getoutput()'/></td>
        <td><input type="text" class="outputfile" id="outputfile" name="filename[]"><input id="extension" type="text" name="extension" disabled></td>
        <td><button type="button" id="delete" class="btn btn-primary"><i class="bi bi-trash3-fill"></i></button></td>
      </tr>
      
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4"></td>
        <td><button type="button" id="add" class="btn btn-primary" ><i class="bi bi-plus-circle-fill"></i>Add</button>
        <input type="submit" name="upload" id="sumbit"class="btn btn-info" value="Upload"></td>
      </tr>
    </tfoot>
  </table>
  </form> 
</div>
<div class="py-5"></div><div class="py-2"></div>
<!-- <script > function getFile(filePath) {
    return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
}

function getoutput() {
    outputfile.value = getFile(inputfile.value);
    extension.value = inputfile.value.split('.')[1];
}</script> -->


<script>$(document).ready(function() {
    var html = '<tr>';
    html += '<td></td>';
    html += '<td><select name="doctyp[]" class="form-select "><option >choose</option><?php echo fill_select_box($conn);?> </select></td > ';
    html += '<td><input type="file" name="doc[]" id="inputfile" class="form-control" /></td>';
    html += ' <td><input type="text"  id="outputfile" name="filename[]"><input id="extension" type="text" name="extension" disabled></td>';
    html += '<td><button type="button" id="delete" class="btn btn-primary"><i class="bi bi-trash3-fill"></i></button></td>';
    html += '</tr>';
    var x = 1;

    $("#add").click(function() {
        $("#TableS").append(html);
    });
    $('.table tbody').on('click', '#delete', function() {
        $(this).closest('tr').remove();
    });

      $(window).load(function() {
        // alert("HELLO");
        $("#inputfile").on('change', function (e) {
        //   getoutput(this.value);
          getoutput();
        });
    });

});


function getFile(filePath) {
    return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
}

function getoutput() {
    // alert("hello");
    outputfile.value = getFile(inputfile.value);
      extension.value = inputfile.value.split('.')[1];
}
</script>
<?php
include 'footer.php';
?> 