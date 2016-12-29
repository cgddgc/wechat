<?php
include 'config.php';        //要调用的函数
define("TOKEN", "wechat");   //定义token
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

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
/**********************token验证函数*************************/



/**********************自动回复消息主函数*************************/
    public function responseMsg()                //
    {   
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//获取微信服务器post请求中的数据
        if (!empty($postStr))
        {
                
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);    //解释XML
                $RX_TYPE = trim($postObj->MsgType);   //判断消息类型
            $fromusername = $postObj->FromUserName;
            $tousername = $postObj->ToUserName;

            switch($RX_TYPE)         //针对不同请求内容调用不同函数生成回复内容
                {
                    case "text":
                        $resultStr = $this->receiveText($postObj);    //接收到文本消息时
                        break;
                    case "event":
                        $resultStr = $this->receiveEvent($postObj);   //接收到事件推送时
                        break;
               	    case "voice":
                        $resultStr = $this->receivevoice($postObj);   //接收到语音消息时
                    break;
                    default:
                        $resultStr = "unknow msg type: ".$RX_TYPE;    //未支持的消息类型
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
/**********************自动回复消息主函数*************************/



/**********************事件消息处理函数*************************/
    public function receiveEvent($object)  //
    {
      $contentStr= "";
      $event = $object->Event;
      $eventkey = $object->EventKey;
        if($event == "CLICK")
        {
            $event=$eventkey;
        }
      switch ($event)
       	 {
            
            case "subscribe":
				 $contentStr ="谢谢关注，发送文字或语音消息可以和我聊天哦，发送我想听+歌名可以点歌";                
                  break;
             case "aaa":
                 $contentStr = "更多功能正在开发哦，敬请期待！";
                  break;
        }
        $resultStr = $this->constructText($object, $contentStr);
        return $resultStr;
    }
/**********************事件消息处理函数*************************/  


/**********************文本消息处理函数*************************/
	public function receiveText($object)    //
    {       
            
        $funcFlag = 0;
        $keyword = $object->Content;
        $username = trim($object->FromUserName);
        $userid = substr($username,10);
		$key=strstr($keyword, "我想听");
        //$contentStr = robot1($keyword);   //机器人1号
        if($key<>"")
        {
            $resultStr=$this->textgetmusic($object);
        }
        else
        {
           
        $temp = robot($keyword,$userid);
        $contentStr = implode("",array($temp[1],$temp[2]));
        $resultStr=$this->constructText($object, $contentStr, $funcFlag);
        }
             return $resultStr;
    }
/**********************文本消息处理函数*************************/


/**********************回复内容构造函数*************************/

    public function constructText($object, $content, $flag=0)   //封装文字消息
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
/**********************回复内容构造函数*************************/



/**********************语音消息处理函数*************************/    
    public function receivevoice($object)    //
    {       
            
            $funcFlag = 0;
            $recognition=$object->Recognition; 
            $keyword= urlencode($recognition);
            $tousername = trim($object->ToUserName);
            $fromusername = trim($object->FromUserName);
            $userid = substr($fromusername,10);
           $key=strstr($recognition, "我想听");
        if($key<>"")
        {
            $resultStr=$this->voicegetmusic($object);
        }
        else
        {
			$temp = robot($keyword,$userid);
        	$contentStr = implode("",array($temp[1],$temp[2]));
             $resultStr=$this->constructText($object, $contentStr, $funcFlag);
        }
             return $resultStr;
    }
/**********************语音消息处理函数*************************/ 
    
	
	
 /**********************语音点歌系统主函数*************************/    
   public function voicegetmusic($postObj)    //
        {       
            $ret=   "<xml>
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
            $recognition=$postObj->Recognition; 
       		$key = str_replace("我想听","", $recognition);
            $key = str_replace("！","", $key);
            $keyword= urlencode($key);
            $musicapi =  "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$keyword}\$\$"; 
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
            }
              if($musicurl == "none")
       {
        $contentStr = "啊哦，没找到这首歌，听歌请输入\"我想听\"+歌名";
        $resultStr=$this->constructText($postObj, $contentStr, 0);
       }
       else
       {
 
           $resultStr = sprintf($ret, $postObj->FromUserName, $postObj->ToUserName,time(), $key,$musicurl,$musicurl);
      		 return $resultStr;
       }
 
        }  
/**********************语音点歌系统主函数*************************/    
    
    /**********************文字点歌系统主函数*************************/    
   public function textgetmusic($postObj)    //
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
			$key = trim($postObj->Content);
            $key = str_replace("我想听","", $key);
            $keyword= urlencode($key);
            $musicapi =  "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$keyword}\$\$"; 
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
            }
       if($musicurl == "none")   //没有找到音乐资源
       {
           $textTpl = "<xml>                                    
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%d</FuncFlag>
						</xml>";  
        $contentStr = "啊哦，没找到这首歌，听歌请输入\"我想听\"+歌名";
        $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), $contentStr, 0);
       }
       else{
       $resultStr = sprintf($ret, $postObj->FromUserName, $postObj->ToUserName,time(), $key,$musicurl,$musicurl);
       }
      		 return $resultStr;
 
        }  
    /**********************文字点歌系统主函数*************************/ 
}
?>