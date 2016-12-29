<?php
include("test1.php");
include("cc.js");
?>

<!--<?php
session_start(); 
if ($_SESSION['yourname'])
  echo "Welcome " . $_SESSION['yourname'] . "!<br />";
else
  echo "Welcome guest!<br />";?>-->


   

<html>
    <title>测试结果</title>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <style>
        body{ color:white;
                background:url("bg.png");
				background-repeat: no-repeat;
            	background-size:cover;
            }
        a	{
                display:block;
                font-weight:bold;
                font-size:14px;
                font-family:Verdana, Arial, Helvetica, sans-serif;
                color:#FFFFFF;
                /* background-color:#98bf21;
                background-image:url("3.jpg");*/
                width:120px;
                text-align:center;
                padding:4px;
                text-decoration:none;
                }
          #td1{
              	color:white;
                font-size:15px;
           	  }
            #td2{
                margin-left:5%;
            }
        </style>
    </head>
    <body>
        <h2 align ="center">
            <script>
            ff("<?php echo $_POST["yourname"];?>","<?php echo $_POST["oname"];?>");
            document.write(w);
            </script>
        </h2>
        <h2 align ="center"><script>document.write(r);</script></h2>
        
        <p align="center"><a href="index.php"><img src="3.jpg"></a></p>
        <h3 align="center"><img src="img/up.gif"></h3>
        <p align ="center">&lt;&lt;--点我头像重新测试<img src="3.jpg" width=20ppx height=20ppx align="top">--&gt;&gt;</p>
        
        <table width=100% align="center"><tr>
            <td id="td2"><a href="http://lem3101.sinaapp.com">友情链接</a></td>
            <td id="td1"><a href="../../../index.php" id="td1">更多有趣测试</a></td>
            </tr>
        </table>
    </body>
</html>