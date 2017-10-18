<?php
$locale='en_US.UTF-8';  
setlocale(LC_ALL,$locale);  
putenv('LC_ALL='.$locale); 
$output=shell_exec("python 1.py");
var_dump($output);
?>
