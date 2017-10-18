<?php
include 'config.php';//要调用的函数
include 'cloud_music.php';
define("TOKEN", "cgddgc");   //定义token
//$music = new cloud_music();
$wechatObj = new wechat_php();
if (isset($_GET['echostr']))   //token验证
    {
        $wechatObj->valid();
    }
else
	{
        $wechatObj->responseMsg();
    }

class wechat_php         //创建一个微信类
{
	
/**********************token验证函数*************************/
  public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature())
        {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()   
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature )
		{
            return true;
        }
		else
		{
            return false;
        }
    }
/**********************token验证函数*************************/



/**********************自动回复主函数*************************/
  public function responseMsg(){   
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//获取微信服务器post请求中的数据
        if (!empty($postStr))
        {
                
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);    //解释XML
            $msgtype = trim($postObj->MsgType);   //判断消息类型
            $fromusername = $postObj->FromUserName;
            $tousername = $postObj->ToUserName;				
            switch($msgtype)         //针对不同请求内容调用不同函数生成回复内容
                {
                    case "text":
                		$keyword = trim($postObj->Content);
                        record($keyword, $fromusername);
                        $resultStr = $this->respon($postObj,$keyword);    //接收到文本消息时
                        break;
                    case "event":
                        $resultStr = $this->receiveEvent($postObj);   //接收到事件推送时
                        break;
               	    case "voice":
						$keyword = trim($postObj->Recognition);
						$keyword = str_replace("！", "", $keyword);
               			 record($keyword, $fromusername);
                        $resultStr = $this->respon($postObj,$keyword);   //接收到语音消息时
						break;
                    default:
                        $resultStr = "unknow msg type: ".$msgtype;    //未支持的消息类型
                        break;
                }
                echo $resultStr;
        }
        else
        {
            echo "";
            exit;
        }     
    }
/**********************自动回复主函数*************************/



/**********************事件处理函数*************************/
  public function receiveEvent($object){
      $contentStr= "";
      $event = $object->Event;
      $eventkey = $object->EventKey;
        if($event == "CLICK")
        {
            $event=$eventkey;
        }
      switch ($event){
		case "subscribe":
			$contentStr ="谢谢关注，发送文字或语音消息可以和我聊天哦，发送'.'+Linux命令可查询该命令详细介绍,发送点歌+歌名可以点歌，后面加\"*\"+歌手名可以指定歌手";                
                  break;
		case "aaa":
                 $contentStr = "更多功能正在开发哦，敬请期待！";
                  break;
        }
        $resultStr = $this->constructText($object, $contentStr);
        return $resultStr;
    }
/**********************事件处理函数*************************/  


/**********************消息处理函数***********************/
  public function respon($object, $keyword){
		$funcFlag = 0;
        $temp=substr($keyword,0,1);
		if($temp=="."){
			$keyword=str_replace(".","",$keyword);
			$resultStr=linux_comman($keyword,$object);
			}
       else
	{
	$key=strstr($keyword, "点歌");
        $keyword = $keyword;
        if($key<>""){
			$resultStr=$this->getmusic($object, $keyword);
        }
        else
        {  
            /* $temp = robot($keyword,$object);
			$contentStr = implode("",array($temp[1],$temp[2]));
			$resultStr=$this->constructText($object, $contentStr, $funcFlag);
            /*$contentStr = robot1($keyword); //机器人1号
            $textTpl = "<xml>                                    
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%d</FuncFlag>
						</xml>"; 
            $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $contentStr, 0);*/
            $resultStr = robot($keyword, $object);
        }
	}
		return $resultStr;
    }
/**********************消息处理函数*************************/

 /**********************文字消息封装函数*************************/

  public function constructText($object, $content, $flag=0)  
    {
        $textTpl = "<xml>                                    
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";                                  
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;                                    
    }
/**********************文字消息封装函数*************************/
    
/**********************点歌系统函数*************************/    
   public function getmusic($postObj, $keyword)    //
        {   	
       		$ret = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[music]]></MsgType>
					<Music>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[]]></Description>
					<MusicUrl><![CDATA[%s]]></MusicUrl>
					<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
					<FuncFlag><![CDATA[1]]></FuncFlag>
					</Music>   
					</xml>";
			$key = str_replace("点歌","", $keyword);
			$art="";
			if(strstr($key,"*")<>""){
			$a=strpos($key,"*");
			$word=substr($key,0,$a);
			$art=str_replace("*","",strstr($key,"*"));
			$key=$word;
		}
            //$keywordc= urlencode($key);
            $musicurl="";
            $musicurl=get_musicUrl($key,10,$art);
            $artist=get_artist($key,10,$art);
           /* $musicapi = "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$keywordc}\$\$"; 
            $simstr=file_get_contents($musicapi);
            $musicobj=simplexml_load_string($simstr);
            $i=0;
            $musicurl="none";
            foreach($musicobj->url as $itemobj)
            {
                $encode = $itemobj->encode;
                $decode = $itemobj->decode;  
                $removedecode = end(explode('&', $decode));
                if($removedecode<>"")
                {
                    $removedecode="&".$removedecode;   
                }
                $decode = str_replace($removedecode,"", $decode);
                $musicurl= str_replace(end(explode('/', $encode)) ,$decode,$encode);
                break;
            }*/
       if($musicurl == "")       //没有找到音乐资源
	   { 
        $textTpl = "<xml>                                    
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%d</FuncFlag>
						</xml>";  
        $contentStr = "啊哦，没找到这首歌，听歌请输入\"点歌\"+歌名,想要指定歌手可以在后面加\"\"+歌手名字，如\"点歌告白气球*周二珂\"";
        $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), $contentStr, 0);
       }
       else
       {
       		$resultStr = sprintf($ret, $postObj->FromUserName, $postObj->ToUserName,time(), $artist."-".$key, $musicurl,$musicurl);
       }
      		 return $resultStr;
 
        }  
    /**********************点歌系统函数*************************/ 
}
?>

