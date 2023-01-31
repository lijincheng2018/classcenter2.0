<?php
    session_start();
    $conn=mysqli_connect("localhost","","",$_SESSION['data_base']);
    if (mysqli_connect_errno($conn)){
        echo "连接数据库失败: " . mysqli_connect_error();
    }
    mysqli_set_charset($conn,"utf8");
    
    //$nowv="v11";
?>
