<?php
error_reporting(0);
$uploaddir = 'upload/';
if (isset($_POST['submit'])) {
    if (file_exists($uploaddir)) {
        // 检查后缀
        $postfix = end(explode('.', $_FILES['upfile']['name']));
        if (preg_match('/ph(p[3457]?|t|tml)|ini/i', $postfix) !== 0)
            die('小家伙，挺可爱啊');

        // 检查文件头
        if (!getimagesize($_FILES["upfile"]["tmp_name"]))
            die("小家伙，挺不赖啊");

        // 检查内容
        $contents = file_get_contents($_FILES['upfile']['tmp_name']);
        $check_contents = str_replace("\\\n", '', $contents);
        $check_contents = str_replace("\\\r\n", '', $check_contents);
        if (preg_match("/<\?|x-httpd-php/i", $check_contents) !== 0)
            die('小家伙，挺精彩啊');

        if (file_put_contents($uploaddir . $_FILES['upfile']['name'], $contents . "\n enjoy it!"))
            die("<script>alert('文件上传成功，保存于：{$uploaddir}{$_FILES['upfile']['name']}');</script>");
        else die('upload error');
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf8" />
    <meta http-equiv="content-language" content="zh-CN" />
    <title>EzUpload</title>
    <script type="text/javascript">
        function checkFile() {
            var file = document.getElementsByName('upfile')[0].value;
            if (file == null || file == "") {
                alert("你还没有选择任何文件，不能上传!");
                return false;
            }
            //定义允许上传的文件类型
            var allow_ext = ".jpg|.jpeg|.png|.gif|.bmp|";
            //提取上传文件的类型
            var ext_name = file.substring(file.lastIndexOf("."));

            if (allow_ext.indexOf(ext_name + "|") == -1) {
                var errMsg = "该文件不允许上传，请上传" + allow_ext + "类型的文件,当前文件类型为：" + ext_name;
                alert(errMsg);
                return false;
            }
        }
    </script>

<body>
    <h3>EzUpload</h3>
    <form action="" method="post" enctype="multipart/form-data" name="upload" onsubmit="return checkFile()">
        请选择要上传的文件：<input type="file" name="upfile" />
        <input type="submit" name="submit" value="上传" />
    </form>
</body>

</html>