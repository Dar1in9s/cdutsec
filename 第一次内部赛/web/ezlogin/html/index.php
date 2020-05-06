<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0" />
    <title>ezlogin</title>
    <script>
        function cipher(s) {
            var u = document.getElementById("username").value;
            var p = document.getElementById("password").value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', "cipher.php?u=" + u + "&p=" + p);
            xhr.responseType = 'arraybuffer';
            xhr.onreadystatechange = function getPdfOnreadystatechange(e) {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var data = (xhr.mozResponseArrayBuffer || xhr.mozResponse || xhr.responseArrayBuffer || xhr.response);
                        if (data) {
                            check(s, data);
                        }
                    }
                }
            };
            xhr.send(null);
        }

        function check(token, data) {

            var oReq = new XMLHttpRequest();
            oReq.open("POST", "check.php?token=" + token, true);
            oReq.onload = function(oEvent) {
                if (oReq.status === 200) {
                    var res = eval("(" + oReq.response + ")");
                    if (res.success == 1 && res.error != 1) {
                        alert(res.msg);
                        return;
                    }
                    if (res.error == 1) {
                        alert(res.errormsg);
                        return;
                    }
                }
                return;
            };
            oReq.send(data);
        }
    </script>
    <style>
        input {
            width: 260px;
            height: 30px;
            background-color: rgb(244, 244, 244);
        }
    </style>
</head>

<body>
    <center>
        <?php echo "<form action=javascript:cipher('" . md5(date('i')) . "');>" ?>
            <p>用户名：<input type="input" name="username" id="username"></p>
            <p>密&nbsp;&nbsp;&nbsp;码：<input type="password" name="password" id="password"></p>
            <input type="submit" value="登陆" style="margin-left:45px;">
        </form>
    </center>
</body>

</html>