<head>
    <meta charset="UTF-8">
    <title>ezwrappers</title>
</head>

<?php
error_reporting(0);
highlight_file(__FILE__);
$sandbox = 'sandbox/' . md5($_SERVER['REMOTE_ADDR']) . '/';
@mkdir($sandbox);
chdir($sandbox);
echo "<br>your sandbox: $sandbox";

$a = $_GET['a'];
$a = preg_replace("/rot13|iconv|base64/", 'what', $a);      # 应该都过滤了吧啊哈哈哈
if ($a)
    @file_put_contents($a, "<?php die('hahaha');" . $a);
