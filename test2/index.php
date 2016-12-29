<?php
define("TOKEN", "cgddgc");
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


    public function responseMsg()
    {   
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr))
        {
                
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $RX_TYPE = trim($postObj->MsgType);

                switch($RX_TYPE)
                {
                    case "text":
                        $resultStr = $this->receiveText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->receiveEvent($postObj);
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
    public function receiveEvent($object)
    {
      $contentStr= "";
      
      switch ($object->Event)
       	 {
            
            case "subscribe":
				 $contentStr ="谢谢关注，敬请期待";                
                  break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }
        
    public function receiveText($object)
    {       
            $funcFlag = 0;
            $contentStr= "";
            $keyword = trim($object->Content);
            $resultStr = "";
        	$url="";
        	 switch($keyword)
                {
                    case "一路向西":
                        $contentStr = "一路向西：http://t.cn/RUwms15";
                		
                        break;
                    case "夏洛特烦恼":
                        
                        $contentStr = "夏洛特烦恼：http://t.cn/RUZyNqT";
                		
                        break;
                 case "小时代4":
                        
                 		$contentStr = "小时代4:http://t.cn/RUURwlf";
                		
                        break;
                 case "复仇者联盟2":
                        
                        $contentStr = "复仇者联盟2：http://t.cn/RU2n38c ";
                		
                        break;
                 case "速度与激情7":
                        
                        $contentStr = "速度与激情7：http://t.cn/RULCt4w";
                		
                        break;
                 case "港囧":
                        
                        $contentStr = "港囧：http://dwz.cn/20q8Nz";
                		
                        break;
                 

                 case "爱爱囧事":
                        
                        $contentStr = "爱爱囧事：http://dwz.cn/27OdP2";
                		
                        break;
                 default:
                        
                        $contentStr = "你想干啥？现在还没什么功能呢！可以回复电影名字，不过不一定有哦";
                		
                        break;
                }
                    $resultStr=$this->responseText($object, $contentStr, $funcFlag);
                    return $resultStr;

    }
    public function responseText($object, $content, $flag=0)
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

    
}
?>