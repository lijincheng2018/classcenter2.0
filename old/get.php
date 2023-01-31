<?php
    @header("Content-type: text/html; charset=utf-8");
    session_start();
    if(!isset($_SESSION['userid']) ){
        header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
        exit();
    }
    
    
    include './config.php';
    $uidd=$_GET['id'];
    $form_id=$_GET['fid'];
    
    $check_query = mysqli_query($conn,"select title from collect_list where id='$form_id' limit 1");
    if (!mysqli_fetch_array($check_query)) exit('error');
    
    $result_t=mysqli_query($conn,"SELECT * FROM collect_list WHERE id='$form_id'");
    $row_reg=mysqli_fetch_assoc($result_t);
        
        
    
    $reg_datalist=$row_reg['bond'];
    $reg_author=$row_reg['author'];
    $reg_title=$row_reg['title'];
    
    
    $uid=$_SESSION['userid'];
    
    $result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
    $row=mysqli_fetch_assoc($result);
    
    if($row['name']!=$reg_author && $_SESSION['usergroup']!=1) exit('没有权限');
  
    
    
    
    
    
    
  if($uidd=="all")
  {
    require_once('./api/class.download.php');
    require_once('./api/zip.php');
    $zipname=$reg_title.'.zip';

    $zip = new Zip();
   
    $sourceDir = './public/collect_files/'.$reg_datalist.'/';
    $outZipPath = './api/temp/'.$zipname;
    $zip->zipDir($sourceDir, $outZipPath);
    if(file_exists($outZipPath)){
        echo 'success';
    }else{
        echo 'fail';
    }


    $dw=new download($zipname); 
	$dw->getfiles();
  }
  else {
      
    $check_get_uid = mysqli_query($conn,"select * from $reg_datalist where id='$uidd' limit 1");
    $row_get_uid=mysqli_fetch_assoc($check_get_uid);
    $cid=$row_get_uid['id'];
    $cname=$row_get_uid['name'];
    $classid=$row_get_uid['classid'];
    $upload_file_name=$row_get_uid['upload_file'];


    require_once('./api/class.download.php');

    $file='./public/collect_files/'.$reg_datalist.'/'.$upload_file_name;


    $newFile='./api/temp/'.$upload_file_name;
    copy($file,$newFile);


    $dw=new download($upload_file_name); 
	$dw->getfiles();

  }


?>
  