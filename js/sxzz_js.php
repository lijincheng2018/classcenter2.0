<script language="JavaScript">
		$(function () {
            $('#RegForm').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault();
                var title=$('#title').val();
                var content=$('#s_content').val();
                var time=$('#time').val();
                var leixing=$('#leixing').val();
                
                
                if(title==""){
                    $('#wrong').text('请输入活动名称');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(content==""){
                    $('#wrong').text('请输入活动内容');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(time==""){
                    $('#wrong').text('请选择活动时间');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                
                let str = '';
                var obj = document.getElementsByName('ck_box');
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].checked){
                        str += obj[i].value + ",";
                    }
                }
                
                console.log(str)
                
                $.ajax({
                    type:'post',
                    <?php if($sxzz_id=="1") echo "url:'?leixing=1&action=post',";  ?>
                    <?php if($sxzz_id=="2") echo "url:'?leixing=2&action=post',";  ?>
                    data:{
                        title:title,
                        content:content,
                        time:time,
                        people:str,
                        leixing:leixing
                    },
                    dataType:'json',
                    success: function(res){
                        console.log(res)
                        alert('操作成功');
                        location.reload();
                    }
                })
            })
        })
        
        
	function del(getid) {
        var isOK=this.window.confirm("确定要删除该活动以及该活动下的所有数据吗？");
        if(isOK){
            <?php if($sxzz_id=="1") echo "$.get('?action=del&leixing=1&id='+getid);";  ?>
            <?php if($sxzz_id=="2") echo "$.get('?action=del&leixing=2&id='+getid);";  ?>
            alert('删除成功');
            location.reload();
        }
    }
    
    $("#selectAll").click(function () { 
	    $("#RegForm input:checkbox").each(function () {   
		    $(this).prop('checked', true);//
	    }); 
	});
	$("#unSelect").click(function () {   
		$("#RegForm input:checkbox").removeAttr("checked");  
    });
    $("#reverse").click(function () {  
        $("#RegForm input:checkbox").each(function () {   
        	this.checked = !this.checked;  
        }); 
    });
    
    function post(){
        document.getElementById('post').style="display:block";
        document.getElementById('activity_list').style="display:none";
        document.getElementById('post_activity').style="display:none";
        document.getElementById('cancle_activity').style="display:block";
    }
    
    function cancle(){
        document.getElementById('post').style="display:none";
        document.getElementById('activity_list').style="display:block";
        document.getElementById('post_activity').style="display:block";
        document.getElementById('cancle_activity').style="display:none";
    }
    
</script>
