<?php
include 'config.php';
define("TOKEN", "wechat");
$wechatObj = new wechat_php();
if (isset($_GET['echostr'])) 
    {
        $wechatObj->valid();
    }
else
	{
        $wechatObj->responseMsg();
    }

class wechat_php
{
    
     public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature())
        {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()   //校验
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


    public function responseMsg()                //生成回复消息函数
    {   
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//获取微信服务器post请求中的数据
        if (!empty($postStr))
        {
                
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);  //解释XML
                $RX_TYPE = trim($postObj->MsgType);   //判断消息类型

            switch($RX_TYPE)   //针对不同请求内容调用不同函数生成回复
                {
                    case "text":
                        $resultStr = $this->receiveText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->receiveEvent($postObj);
                        break;
               	    case "voice":
                        $resultStr = $this->responvoice($postObj);
                    break;
                    default:
                        $resultStr = "unknow msg type: ".$RX_TYPE;
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
    public function receiveEvent($object)  //对事件回复的函数
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
				 $contentStr ="谢谢关注，发送文字或语音消息可以和我聊天哦";                
                  break;
             case "aaa":
                 $contentStr = "功能正在开发哦，敬请期待！";
                  break;
        }
        $resultStr = $this->constructText($object, $contentStr);
        return $resultStr;
    }
        
    public function receiveText($object)    //对文本消息回复的函数
    {       
            
            $funcFlag = 0;
            $contentStr= "";
            $keyword = trim($object->Content);
            $username = trim($object->FromUserName);
            $userid = substr($username,10);
            $resultStr = "";
        	$url="";
			$temp = robot($keyword,$userid);
        	$contentStr = implode("",array($temp[1],$temp[2]));
             $resultStr=$this->constructText($object, $contentStr, $funcFlag);
             return $resultStr;

    }
    public function constructText($object, $content, $flag=0)   //此函数用来生成回复的字符串
    {
        $textTpl = "<xml>                                    
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";                                  //封装XML
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;                                    //返回生成的字符串
    }
    
    public function responvoice($object)    //对文本消息回复的函数
    {       
            
            $funcFlag = 0;
            $contentStr= "";
            $recognition=$object->Recognition; 
            $keyword= urlencode($recognition);
            $username = trim($object->FromUserName);
            $userid = substr($username,10);
            $resultStr = "";
        	$url="";
			$temp = robot($keyword,$userid);
        	$contentStr = implode("",array($temp[1],$temp[2]));
             $resultStr=$this->constructText($object, $contentStr, $funcFlag);
             return $resultStr;

    }
    
    
   public function receivevoice($postObj)    //对语音消息回复的函数
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
            $keyword= urlencode($recognition);
            $musicapi =  "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$keyword}\$\$"; 
            $simstr=file_get_contents($musicapi);
            $musicobj=simplexml_load_string($simstr);
            $i=0;
            $musicurl="www.baidu.com";
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
 
       $resultStr = sprintf($ret, $postObj->FromUserName, $postObj->ToUserName,time(), $recognition,$musicurl,$musicurl);
      		 return $resultStr;
 
        }  

}
?>