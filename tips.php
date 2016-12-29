<?php
//取得指定位址的內容，并储存至 $text  
$text=file_get_contents('http://news.163.com/16/0120/09/BDOUQ8II000155IV.html#163interesting?xstt'); 

//取得第一个 img 标签，并储存至二维数组 $match 中   
preg_match('/<img[^>]*>/Ui', $text, $match);
$picurl = $match[0];
//打印出match
echo $picurl;
?>