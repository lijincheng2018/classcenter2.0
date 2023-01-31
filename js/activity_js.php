<script language="JavaScript">
		$(function () {
            $('#RegForm').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault();
                var title=$('#title').val();
                var content=$('#s_content').val();
                var r_time=$('#r_time').val();
                var time=$('#time').val();
                var place=$('#place').val();
                var leixing=$('#leixing').val();
                var ljcid=$('#ljcid').val();
                
                
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
                else if(r_time==""){
                    $('#wrong').text('请输入活动时长');
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
                    url:'?action=post',
                    data:{
                        title:title,
                        content:content,
                        r_time:r_time,
                        time:time,
                        people:str,
                        leixing:leixing,
                        place:place,
                        ljcid:ljcid
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
            $.get('?action=del&id='+getid);
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

<script type="text/javascript">
        window.onload = function(){
            var input = document.getElementById("file_input");
            var result;
            var dataArr = []; // 储存所选图片的结果(文件名和base64数据)
            var fd;  //FormData方式发送请求
            var oSelect = document.getElementById("select");
            var oAdd = document.getElementById("add");
            var oSubmit = document.getElementById("uplode_img");
            var oInput = document.getElementById("file_input");
 
            if(typeof FileReader==='undefined'){
                alert("抱歉，你的浏览器不支持 FileReader");
                input.setAttribute('disabled','disabled');
            }else{
                input.addEventListener('change',readFile,false);
            }　　　　　//handler
            
            function readFile(){
                fd = new FormData();
                var iLen = this.files.length;
                var index = 0;
                for(var i=0;i<iLen;i++){
                    if (!input['value'].match(/.jpg|.gif|.png|.jpeg|.bmp/i)){　　//判断上传文件格式
                        return alert("上传的图片格式不正确，请重新选择");
                    }
                    var reader = new FileReader();
                    reader.index = i;
                    fd.append(i,this.files[i]);
                    reader.readAsDataURL(this.files[i]);  //转成base64
                    reader.fileName = this.files[i].name;
                    
                    reader.onload = function(e){
                        var imgMsg = {
                            name : this.fileName,//获取文件名
                            base64 : this.result
                        }
                        dataArr.push(imgMsg);
                        result = '<div class="delete">删除</div><div class="result"><img src="'+this.result+'" alt=""/></div>';
                        var div = document.createElement('div');
                        div.innerHTML = result;
                        div['className'] = 'float';
                        div['index'] = index;
                        document.getElementsByTagName('ljc')[0].appendChild(div);  　　//插入dom树
                        var img = div.getElementsByTagName('img')[0];
                        img.onload = function(){
                            var nowHeight = ReSizePic(this); //设置图片大小
                            this.parentNode.style.display = 'block';
                            var oParent = this.parentNode;
                            if(nowHeight){
                                oParent.style.paddingTop = (oParent.offsetHeight - nowHeight)/2 + 'px';
                            }
                        }
                        
                        div.onclick = function(){
                            this.remove();                  // 在页面中删除该图片元素
                            delete dataArr[this.index];  // 删除dataArr对应的数据
 
                        }
                        index++;
                    }
                }
            }
 
 
            function send(){
                var submitArr = [];
                for (var i = 0; i < dataArr.length; i++) {
                    if (dataArr[i]) {
                        submitArr.push(dataArr[i]);
                    }
                }
                // console.log('提交的数据：'+JSON.stringify(submitArr));
                $.ajax({
                    url : '?action=upload',
                    type : 'post',
                    data : {
                        'img':JSON.stringify(submitArr),
                    },
                    dataType: 'json',
                    success : function(data){
                        var respond_data=JSON.stringify(data);
                        var ok= JSON.parse(respond_data);
                        document.getElementById('ljcid').value=ok.ljcid;
                        if(ok.code=="200") alert('上传成功！');
                        else alert('上传失败！');
                        console.log(ok.ljcid)
                        
                    }
 
                })
            }
            
            oSelect.onclick=function(){
                oInput.value = "";
                //清空已选图片
                $('.float').remove();
                dataArr = [];
                index = 0;
                oInput.click();
            }
            
            oAdd.onclick=function(){
                oInput.value = "";
                oInput.click();
            }
 
            oSubmit.onclick=function(){
                if(!dataArr.length){
                    return alert('请先选择文件');
                }
                send();
            }
        }
        function ReSizePic(ThisPic) {
            var RePicWidth = 200; //显示的宽度值
            var TrueWidth = ThisPic.width; //图片实际宽度
            var TrueHeight = ThisPic.height; //图片实际高度
            if(TrueWidth>TrueHeight){
                //宽大于高
                var reWidth = RePicWidth;
                ThisPic.width = reWidth;
                //垂直居中
                var nowHeight = TrueHeight * (reWidth/TrueWidth);
                return nowHeight;  //将图片修改后的高度返回，供垂直居中用
            }else{
                //宽小于高
                var reHeight = RePicWidth;
                ThisPic.height = reHeight;
            }
        }
    </script>
