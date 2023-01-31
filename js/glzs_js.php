<script language="javascript">
    function gl(getid) {
        var isOK=this.window.confirm("确定要对该用户进行归零操作吗？");
        if(isOK){
            $.get('?action=gl&id='+getid);
            alert('操作成功');
            location.reload();
        }
    }
    function cancel(getid) {
        var isOK=this.window.confirm("确定要恢复该用户的先锋指数吗？");
        if(isOK){
            $.get('?action=cancel&id='+getid);
            alert('操作成功');
            location.reload();
        }
    }
</script>