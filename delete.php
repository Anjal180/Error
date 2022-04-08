<!-- < ?php
include 'connection.php';
if(isset($_GET['ids'])){
    // echo $_GET['ids'];
    // echo $_GET['prodid'];
    
    echo $id= $_GET['ids'];
    echo $prodid= $_GET['prodid'];
    $query1=mysqli_query($conn,"SELECT `file_id`, `prod_id`, `doc_type`, `file_name`, `doc_name`, `ext` from `up_doc_dtls` where file_id='$id'");
    $row1=mysqli_fetch_array($query1);
    $file=$row1["doc_name"];
    $ext=$row1["ext"];
    unlink("F:/Root/$prodid/$file".".$ext");
    $prodid=$_GET['prodid'];
    echo $query = "DELETE FROM `up_doc_dtls` WHERE file_id = '$id'";
    $res=mysqli_query($conn,$query);

}
?> -->

<?php
include 'connection.php';

$id=$_POST['file_id'];
$query=mysqli_query($conn,"DELETE FROM `up_doc_dtls` WHERE file_id = '$id'");
?>