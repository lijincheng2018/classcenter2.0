<?php
    @header("Content-type: text/html; charset=utf-8");
    session_start();
    if(!isset($_SESSION['userid']) ){
        header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
        exit();
    }
    
    
    include './config.php';
    $form_id=$_GET['fid'];
    
    $check_query = mysqli_query($conn,"select title from collect_list where id='$form_id' limit 1");
    if (!mysqli_fetch_array($check_query)) exit('error');
    
    $result_t=mysqli_query($conn,"SELECT * FROM collect_list WHERE id='$form_id'");
    $row_reg=mysqli_fetch_assoc($result_t);
    
    $reg_datalist=$row_reg['bond'];
        
    $classid=$_SESSION['classid'];
    $check_query_if = mysqli_query($conn,"select * from $reg_datalist where classid='$classid' limit 1");
    if(!mysqli_fetch_array($check_query_if)) exit('没有权限');
    
      
    $check_get_uid = mysqli_query($conn,"select * from $reg_datalist where classid='$classid' limit 1");
    $row_get_uid=mysqli_fetch_assoc($check_get_uid);
    
    $upload_file_name=$row_get_uid['upload_file'];
    require_once('./api/class.download.php');
    
    if($form_id=="20010" || $form_id == "20011" || $form_id == "20012")
    {
        $file_pure_name_data=array();
        $file_pure_name_data=explode('.',$upload_file_name);
        $file_pure_name = $file_pure_name_data[0];

        $file='./public/collect_files/'.$reg_datalist.'/'.$file_pure_name.'/'.$upload_file_name;
    }
    else $file='./public/collect_files/'.$reg_datalist.'/'.$upload_file_name;
    
    

    $newFile='./api/temp/'.$upload_file_name;
    copy($file,$newFile);
    $dw=new download($upload_file_name); 
	$dw->getfiles();


?>
  