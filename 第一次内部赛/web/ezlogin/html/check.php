<?php
error_reporting(0);
$flag = str_replace(PHP_EOL, '', file_get_contents("/flag"));
$token = $_GET['token'];
$ret['error'] = "1";
$ret['success'] = "0";
$ret['errormsg'] = "login fail";

if (md5(date("i")) === $token) {
    $receiveFile = 'flag.dat';
    $streamData = file_get_contents('php://input');
    file_put_contents($receiveFile, $streamData);

    if (md5_file($receiveFile) === md5_file("key.dat")) {
        if (hash_file("sha512", $receiveFile) != hash_file("sha512", "key.dat")) {
            $ret['success'] = "1";
            $ret['error'] = "0";
            $ret['msg'] = "success! $flag";
            unset($ret['errormsg']);
        }
    }
} else $ret['errormsg'] = "token error";

echo json_encode($ret);
