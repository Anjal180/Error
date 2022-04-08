<?php
include 'nav.php';
include 'connection.php';
?>

<!--------- style for btn and erorr -------------->
<style>
  #btn{
    margin-left:78%;
  }
 hr{
  margin: 0.5rem;
 }
 #h3 {
    padding: 10px;
}
h3{
  margin-bottom: 0rem;
}
  .error{
		color: red;font-style: italic; font-weight: bold;
    text-align: center;
		}
    .my-custom-scrollbar {
    position: relative;
    height: 335px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
    #TableS thead th{
      padding:5px;
    position: sticky;
    top: 0;
    background-color:#d0e0fc;
    }
    table tbody #td{
      padding: 3px;
    } 
  
</style>

<!----------------------------------- PHP code for Prod_hr table(id) ---------------------------------------->
<?php
    $query   = "SELECT `prod_id` FROM prod_hdr";
    $results = mysqli_query($conn, $query);
  ?>

  <script>    
//  <!-------------------- RETRIVE DATA FROM DATABASE  -------------------------------->
    function getAll(val){
      var x=document.getElementById("product-id").value;
      $.ajax({
        type: "POST",
        url: "getAll.php",
        data:{
          prod_id:x
        },
        success: function(data){
          $("#ans").html(data);
        }
      });
    }
  </script>

<!-------------------- MAIN PAGE CODE  -------------------------------->
<h3 id="h3">Document Management<hr></h3>
<form method="post" action="" enctype="multipart/form-data">

  <!----------------- Prodcut ID -------------------->
  <div style="padding-left: 110px;">
    <div class="form-row" >
      <div class="col-lg-3">
          <div class="input-group">
            <label class="input-group-text">Product Id :</label>
            <select  class="form-select" name="prodid" id="product-id" onChange="getAll(this.value);" required>
              <option value="">Product Id</option>
              <!----------------- Prodcut PHP CODE -------------------->
              <?php
                $sql = "SELECT * FROM prod_hdr GROUP BY prod_id ASC";
                $res = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($res)) {
                 
              ?>
              <option value="<?php  echo $row['prod_id']; ?>"><?php echo $row['prod_id']; ?></option>
              <?php 
                
                }
              ?>
            </select>
          </div>
        </div>
    </div> 
  </div><br>

  <!-------------------------------- PHP code for file upload ---------------------------------------->
  <?php 
    if(isset($_POST['upload'])){
      extract($_POST);
      $IDDir=$_POST["prodid"];
      $doctyp=$_POST['doctyp'];
      $doc=$_FILES['doc']['name'];
      
      // ------------Create folder in F Drive----------
      foreach($doc as $key => $value){
        $name = $_POST["filename"][$key];
        $doc=$_FILES['doc']['name'][$key];
        $extension=pathinfo($doc,PATHINFO_EXTENSION);
        $filePath='F:/Root/'.$IDDir;
        if(!is_dir($filePath)){
          mkdir($filePath, 0755);
      }
      
      $filePath .= "/". $name.".".$extension;
        $tmp_name=$_FILES['doc']['tmp_name'][$key];
        move_uploaded_file($tmp_name,$filePath);
        
        //<!-------------------- INSERT QUERY FOR MAIN PAGE  -------------------------------->
        $ins="INSERT INTO `up_doc_dtls`( `prod_id`, `doc_type`, `file_name`, `doc_name`, `ext`) 
        VALUES ('$prodid','".$doctyp[$key]."','".$value."','".$filename[$key]."','".$ext[$key]."')";
        $ab=mysqli_query($conn,$ins);
      }
    }
  ?>

  <!-------------------- TABLE  -------------------------------->
  <div class="container">
    <div class="table-wrapper-scroll-y my-custom-scrollbar"> 
      <table class="table  table-striped mb-0" id="TableS">
        <thead>
          <tr class="table-primary">
            <!-- <th scope="col"></th> -->
            <th scope="col">File Category</th>
            <th scope="col">Upload File</th>
            <th scope="col">Filename</th>
            <th scope="col"> </th>
          </tr>
        </thead>
        <tbody id="ans">
        </tbody>
      </table>
    </div>
    <hr>
    <button type="button" id="add" data-id="32" class="btn btn-primary" style="margin-left:75%;"><i class="bi bi-plus-circle-fill"></i></button>
    <input type="submit" name="upload" id="sumbit"class="btn btn-info" value="Upload">
  </div>
</form>
<!-------------------- FROM END  -------------------------------->
<div class="py-2"></div>

<!-------------------- ADDING ROWS FOR TABLE html += '<td id="td"></td>'; -------------------------------->
<script>
  function getHtml(index) {
  var html = '<tr id="td">';
      
      html += '<td id="td" class="col"><input type="text" name="doctyp[]" value="General" class="form-control-sm" readonly></td> ';
      html += '<td id="td" class="col"><input type="file" name="doc[]" id="inputfile[]" data-id='+index+' class="form-control-sm inputfile" required/></td>';
      html += ' <td id="td" class="col"><input type="text" class="form-control-sm outputfile" id="outputfile'+index+'" name="filename[]" autocomplete="off" required><input id="extension'
      +index+'"  name="ext[]" class="form-control-sm" readonly></td>';
      html += '<td id="td"class="col"><button type="button" id="delete" class="btn btn-primary"><i class="bi bi-trash3-fill"></i></button></td>';
      html += '</tr>';
      return html;
  }
  var x=1;

  // <!-------------------- ADD,REMOVE IN TABLE  -------------------------------->
  $(document).ready(function() {
    $("#add").click(function() {
      var html = getHtml(x++);
      $("#TableS").append(html);
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

<!--  Delete Ajax Code   -->

<script>
	  function deleteAjax(file_id){
      $(document).ready(function(){
        $.ajax({
          type:'post',
          url:'delete.php',
          data:{file_id:file_id},
          success:function(data){
            alert("deleted");
            // getAll(val);
          }
        })
      })
    }
</script>

<?php
include 'footer.php';
?> 
