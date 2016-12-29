<?php 
 public function getAccesstoken()
 {
 $access_token="";
 $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx03b529f87a6d25a7&secret=678d29e1b19f6e5a3beb110e9699b136"
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];
     
return $access_token;
}
 $access_token=getAccesstoken();
$option=array{
     "button":[
     {	
          "type":"view",
          "name":"主页",
          "url":"http://cgddgc.sinaapp.com/"
      },
      {
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"视频",
               "url":"http://v.qq.com/"
            },
            {
               "type":"view",
               "name":"测试",
               "url":"http://cgddgc.sinaapp.com/wechat/test1"
            }]
       },
	   {"type":"click",
	    "name":"哈哈",
		"key":"V1001_TODAY_MUSIC"
	   }
     ]}
   $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
　　$post_data = $option;
　　$ch = curl_init();
　　curl_setopt($ch, CURLOPT_URL, $url);
　　curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
　　// post数据
　　curl_setopt($ch, CURLOPT_POST, 1);
　　// post的变量
　　curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
　　$output = curl_exec($ch);
　　curl_close($ch);
}
?>