<?php
    $connect_login=mysqli_connect("localhost","","","classcenter_info");
    if (mysqli_connect_errno($connect_login)){
        echo "连接数据库失败: " . mysqli_connect_error();
    }
    mysqli_set_charset($connect_login,"utf8");
    
    //$nowv="v11";
?>
