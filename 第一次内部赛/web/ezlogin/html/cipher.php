<?php

if(isset($_GET['u']) && isset($_GET['p']))
{
    echo md5($_GET['u'].$_GET['p']);
}
