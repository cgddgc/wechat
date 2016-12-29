<!DOCTYPE html>
<?php
include("cc.js");
?>

<html>

<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script type="text/javascript" src="../../../javascript/firework.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/firework.css">
     <style type="text/css">
        
        p {margin-left: 20px;color:white}
         body{   color:white;
                background:url("bg.png");
				background-repeat: no-repeat;
           		background-size:200%;
            }
         a {text-decoration:none;color:white;}
         div#d{
             position:absolute;
             bottom:5%;
             left:41%;
             color:white;
             font-size:20px;
            }
         
	</style>
</head>
    
<title>无节操测试</title>

<body>
    
    <h3 align="center">双十一快要到了，想知道你和你心目中的那个他/她和你是否有缘吗，赶紧来测试下吧！<br/><br/><h3>
    
    <table align="center">
        <tr>
            <td><img src="3.jpg"></td>
            <td>：单身狗赶紧测<img src="3.jpg" width=20ppx height=20ppx align="top"></td>
        </tr>
        <tr>
            <td><img src="img/fn.jpg"></td>
            <td>：滑稽别闹<img src="img/mq.jpg" width=20ppx height=20ppx align="top"></td>
        </tr>
        <tr>
            <td><img src="img/leng.jpg"></td>
            <td>：说得好像自己不是一样<img src="img/bgx.jpg" width=20ppx height=20ppx align="top"></td>
        </tr>
    </table>
    <form width="700px" method="post" action="test2.php">
    <table width="100%" >
    	<tr>
            <td height=15px align="right">你的名字：</td>
            <td width="68%"><input type="text" name="yourname" style="height:28px"></td>
        </tr>
        <tr><td height=10px></td><td></td><tr>
        <tr>
            <td height=15px align="right">对方名字：</td>
            <td width="68%"><input type="text" name="oname" style="height:28px"></td>
        </tr>
        <!--<tr><td height=10ppx></td><td></td><tr>-->
    </table>
    <p align="center"><input type="image" src="bottom.png" onClick="ff(yourname.value,oname.value)" style="height:30px;width:100px;"></p>
	</form>
    <div id="d"><a href="../../../index.php">访问官网</a></div>
</body>
</html>