<?php
error_reporting(0);
if ($_GET['source'] == 1) {
  highlight_file(__FILE__);
  die();
}
$query = urldecode($_SERVER["QUERY_STRING"]);

if (strpos($query, '_') !== False)
  die("you are a bad gay!");

if (isset($_GET['file_name'])) {
  $filename = "upload/" . $_GET['file_name'];
  $content = $_POST['file_content'];

  if (strlen($content) > 18)
    die("only can upload 18 length max");
  if (preg_match('/<\?ph.|flag/i', $content) !== 0)
    die("don't play xiao ba xi");
  if (file_put_contents($filename, $content))   // I think it is safe enough, isn't it?
    die("upload ok");
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>ezphp</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
</head>

<body>
  <div style="text-align:center;clear:both;position:absolute;top:0;left:260px">
    <script src="/gg_bd_ad_720x90.js" type="text/javascript"></script>
    <script src="/follow.js" type="text/javascript"></script>
  </div>
  <canvas class="canvas"></canvas>

  <div class="help">?</div>

  <div class="ui">
    <input class="ui-input" type="text" />
  </div>
  <div class="overlay">
    <div class="tabs">
      <div class="tabs-labels">
        <span class="tabs-label">Commands</span>
      </div>

      <div class="tabs-panels">
        <ul class="tabs-panel commands">
          <li class="commands-item"><span class="commands-item-title">Text</span><span class="commands-item-info" data-demo="Hello :)">Type anything</span><span class="commands-item-action">Demo</span></li>
          <li class="commands-item"><span class="commands-item-title">Countdown</span><span class="commands-item-info" data-demo="#countdown 10">#countdown<span class="commands-item-mode">number</span></span><span class="commands-item-action">Demo</span></li>
          <li class="commands-item"><span class="commands-item-title">Time</span><span class="commands-item-info" data-demo="#time">#time</span><span class="commands-item-action">Demo</span></li>
          <li class="commands-item"><span class="commands-item-title">Rectangle</span><span class="commands-item-info" data-demo="#rectangle 30x15">#rectangle<span class="commands-item-mode">width x height</span></span><span class="commands-item-action">Demo</span></li>
          <li class="commands-item"><span class="commands-item-title">Circle</span><span class="commands-item-info" data-demo="#circle 25">#circle<span class="commands-item-mode">diameter</span></span><span class="commands-item-action">Demo</span></li>
          <li class="commands-item"><span class="commands-item-title">Show me code</span><span class="commands-item-info" data-demo="">have fun</span><span class="commands-item-action"><a href="index.php?source=1">Demo</a></span></li>
          <li class="commands-item commands-item--gap"><span class="commands-item-title">Animate</span><span class="commands-item-info" data-demo="The time is|#time|#countdown 3|#icon thumbs-up"><span class="commands-item-mode">command1</span>&nbsp;|<span class="commands-item-mode">command2</span></span><span class="commands-item-action">Demo</span></li>
        </ul>

      </div>
    </div>
  </div>

  <script src="js/index.js"></script>

</body>

</html>