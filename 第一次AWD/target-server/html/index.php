<?php
//error_reporting(0);
$targets = array();
$ok = 0;
if (isset($_GET['token']) && strlen($_GET['token']) == 11) {
    $token = $_GET['token'];
    $teamNo = substr($token, -1);
    if ($teamNo == '1' || $teamNo == '2') {
        $teamfile = 'teams' . $teamNo . '.txt';
        $teams = explode("\n", file_get_contents($teamfile));
        foreach ($teams as $id => $team) {
            $info = json_decode($team, true);
            if ($info) {
                if (substr(md5($info['team']), 0, 10) === substr($token, 0, 10))
                    $ok = 1;
                else {
                    $target = $info['ip'] . ':' . $info['Server'] . '<br>';
                    array_push($targets, $target);
                }
            }
        }

        if ($ok) {
            foreach ($targets as $key => $value) {
                echo $value;
            }
        }
    }
}

