<?php
include 'nav.php';
$msg="";
?>
<?php
include 'connection.php';

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
        $msg= "**data insert";
	}else{
		echo '<span style="color: red;font-style: italic; font-weight: bold;font-size:24px">*** Please upload .xlsx file</span>';
    // echo  "<script>alert('print')</script>";
}
}
?>
<style>
  #btn{
    margin-top: 5px;
    margin-left:78%;
  }
  .error{
		color: red;font-style: italic; font-weight: bold;
    text-align-last: auto;
		}
</style>


<h3 id="h3">Document Management <hr></h3>
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
<div style="padding-left: 110px;">
  <div class="form-row" style="padding-top: 20px;">
    <div class="col-lg-7">
      <div class="input-group">
        <label class="input-group-text" for="inputGroupSelect01">Customer :</label>
        <select class="form-select" id="inputGroupSelect01">
          <option selected>Choose...</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
    </div>
    <div class="col-lg-7" style="padding-top: 10px;">
      <div class="input-group">
        <label class="input-group-text" for="inputGroupSelect01">Product Id</label>
        <select class="form-select" id="inputGroupSelect01">
          <option selected>Choose...</option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>
    </div>
   </div>
   
</div>
<br>
<div class="container">       
  <table class="table table-striped" id="tableS">
    <thead>
      <tr class="table-primary">
        <th scope="col"></th>
        <th scope="col">File Category</th>
        <th scope="col">Upload File</th>
        <th scope="col">Filename</th>
      </tr>
    </thead>
    <tbody>
      <tr>
      <td></td>
        <td>
          
          <select name="cars" id="cars" class="form-select ">
            <option value="">choose</option>
            <option value="saab">Saab</option>
            <option value="opel">Opel</option>
            <option value="audi">Audi</option>
          </select>
        </td>
        <td><input type="file" name="doc" class="form-control"/></td>
        <td><input type="text" class="form-control"></td>
        <td><button type="button" onclick="deleteRow(this);" class="btn btn-primary"><i class="bi bi-trash3-fill"></i></button></td>
        <td><button type="button" onclick="addItem();" class="btn btn-primary" ><i class="bi bi-plus-circle-fill"></i></button></td>
      </tr>
    </tbody>
  </table>
  <button type="button" class="btn btn-primary" onclick="addItem();" id="hide"><i class="bi bi-plus-circle-fill"></i></button>
    <input type="submit" name="submit" id="sumbit"class="btn btn-info" value="Upload">
</div>
<div class="py-5"></div><div class="py-2"></div>
<script type="text/javascript">
    //  var items = 0;
    function addItem() {
        // items++;
 
        var html = "<tr>";
            html += "<td></td>";
           
            html += "<td><button type='button' onclick='deleteRow(this);'>Delete</button> <button type='button' onclick='addItem();'><i class='bi bi-plus-circle-fill'></button</td>"
            html += "</tr>";
 
        var r = document.getElementById("tbody");
        var row= r.insertRow();
        row.innerHTML = html;
    }
 
function deleteRow(button) {
    button.parentElement.parentElement.remove();
    // first parentElement will be td and second will be tr.
}
// $(document).ready(function(){
//     $('#hide').on('click',function(){
//         $('#hide').hide();
//     });
// });
</script>
<?php
include 'footer.php';
?> 
