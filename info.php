#!/bin/bash
<?php

/* 
 * This script, will dump all readable information to the console about a windows pe.
 */

include('io/FileStream.php');
include('pe/Binary.php');

if(!isset($argv[1]) || $argv[1] === 'help') { 
    echo "PE Info - Reads the structural informationof the PE.\n" . 
         "Usage: php -f info.php [peInputPath]\n" . 
         "Example: php -f info.php /home/user/executable.exe\n";
    die();
}

// Check File Exists.
if($argc < 2 || !file_exists($argv[1])) {
    die("Unable to find a executable to meddle with :(\n");
}

$pe = new PEImage($argv[1]);

var_dump($pe);
unset($pe);
?>
