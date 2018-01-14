<?php
function Title(){
    $fName = isset($_GET['url']) ? $_GET['url']:'index';
    if ($fName == "index"){
        echo "sitename";
    }else{
        $fName = preg_replace('/[^A-Za-z]/',' ',$fName);
        echo "sitename - ".$fName;
    }
}
