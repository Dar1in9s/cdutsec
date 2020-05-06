<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0" />
    <title>ezF12</title>
<script type='text/javascript'>
    //禁用右键（防止右键查看源代码）
    window.oncontextmenu=function(){return false;}

    //禁止任何键盘敲击事件（防止F12和shift+ctrl+i调起开发者工具）
    window.onkeydown = window.onkeyup = window.onkeypress = function () {
        window.event.returnValue = false;
        return false;
    }

    //如果用户在工具栏调起开发者工具，那么判断浏览器的可视高度和可视宽度是否有改变，如有改变则关闭本页面
    var h = window.innerHeight,w=window.innerWidth;
    <?php
        error_reporting(0);
        $flag = trim(file_get_contents('/flag'));
        echo "var flag = '${flag}';\n";
    ?>
    window.onresize = function () {
        if (h!= window.innerHeight||w!=window.innerWidth){
            window.close();
            window.location = "about:blank";
        }
    }
</script>

</head>