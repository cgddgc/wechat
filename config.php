<?php

/******************获取access_token******************/
function get_access_token($appid,$appsecret)    //获取access_token的函数
{
    $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  //获取access-token的借口
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    if(curl_errno($ch))
    {
        echo 'Errno'.curl_error($ch);
    }
    curl_close($ch);
    $jsoninfo = json_decode($output, true);
    
    return $jsoninfo["access_token"];
}


/*************聊天机器人接口**************/

function robot($keyword,$object)
{
    $fromusername = $object->FromUserName;
    $userid = substr($fromusername ,15);
    $userid = urlencode($userid);
    $content = urlencode($keyword);
    $url="http://www.tuling123.com/openapi/api?key=b8bb8bf591af8b522652fc2aa1e4a03a&info=$content&userid=$userid"; 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    if(curl_errno($ch))
    {
        echo 'Errno'.curl_error($ch);
    }
    curl_close($ch);
    $jsoninfo = json_decode($output, true);
    $code = $jsoninfo["code"];
    $time = time();
    switch($code)
    {
        case "100000":
       			 $contentStr = $jsoninfo["text"];
                $textTpl = "<xml>                                    
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[text]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>%d</FuncFlag>
                                    </xml>"; 
                $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, $time, $contentStr, 0);
        break;
        case "200000":
        		$contentStr = $jsoninfo["text"];
                $contentStr = $contentStr.$jsoninfo["url"];
                $textTpl = "<xml>                                    
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[text]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>%d</FuncFlag>
                                    </xml>"; 
                $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, $time, $contentStr, 0);
            break;
        case "302000":
       		   $title1 = $jsoninfo["list"][0]["article"];
     		   $url1 = $jsoninfo["list"][0]["detailurl"];
     		   $description1 = $jsoninfo["list"][0]["source"];
       		   $title2 = $jsoninfo["list"][2]["article"];
     		   $url2 = $jsoninfo["list"][2]["detailurl"];
     		   $description2 = $jsoninfo["list"][2]["source"];
                   $title3 = $jsoninfo["list"][1]["article"];
     		   $url3 = $jsoninfo["list"][1]["detailurl"];
     		   $description3 = $jsoninfo["list"][1]["source"];
           	   $picurl = $jsoninfo["list"][0]["icon"];
       		   $news = "<xml>
        		<ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>3</ArticleCount>
                        <Articles>
                        <item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        <item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        <item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        </Articles>
                        </xml>";
        $resultStr = sprintf($news, $object->FromUserName, $object->ToUserName, $time, $title1, $description1,$picurl ,$url1, $title2, $description2,$picurl ,$url2, $title3, $description3,$picurl ,$url3);
        break;
    }
    return $resultStr;

}
/******************************/

/******************记录用户输入和openid********************/

function record($keyword, $fromusername)
{
    $time = date('Y-m-d H:m:s');
    $link = mysql_connect('localhost', 'root', 'cgd1011');
    mysql_select_db("wechat");
    $search = "INSERT INTO record(openid, text, time)
			VALUES('$fromusername','$keyword','$time')";
    mysql_query("set names 'utf8'");
    mysql_query($search, $link);
    mysql_close();
}
/******************记录用户输入和openid********************/

/*******************Linux命令查询函数*********************/

function linux_comman($keyword,$object){

        $url="http://linux.51yip.com/search/$keyword";
	$result=file_get_contents($url);
	$time=time();
        $news="<xml>
	       <ToUserName><![CDATA[%s]]></ToUserName>
               <FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime><![CDATA[%s]]></CreateTime>
               <MsgType><![CDATA[news]]></MsgType>
               <ArticleCount>1</ArticleCount>
               <Articles>
               <item>
               <Title><![CDATA[%s]]></Title>
               <Description><![CDATA[%s]]></Description>
               <PicUrl><![CDATA[%s]]></PicUrl>
               <Url><![CDATA[%s]]></Url>
               </item>
	       </Articles>
               </xml>";
        $textTpl = "<xml>                                    
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>"; 
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, $time, $result, 0);
        $title="Linux命令之：$keyword";
	$description="好好看,好好学";
	$picurl="http://upload.chinaz.com/2015/1028/1445998581465.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240";
	$resultStr1 = sprintf($news, $object->FromUserName, $object->ToUserName, $time, $title, $description,$picurl ,$url);
	 return $resultStr1;
}

/******************Linux命令查询函数***********************/
function robot1($content)
{
    $key = urlencode($content);
    $url="http://api.qingyunke.com/api.php?key=free&appid=0&msg=$key"; 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    if(curl_errno($ch))
    {
        echo 'Errno'.curl_error($ch);
    }
    curl_close($ch);
    $jsoninfo = json_decode($output, true);
    $result=$jsoninfo["content"];
    return $result;
}
?>
