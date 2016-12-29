
<?php
$appid = "wx03b529f87a6d25a7";
$appsecret = "678d29e1b19f6e5a3beb110e9699b136";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

$output = https_request($url);
$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];


$jsonmenu = '{
      "button":[
      {
         "type":"view",
          "name":"主页",
          "url":"http://www.cccgd.me"
      },
      {
           "name":"呵呵",
           "sub_button":[
            {
               "type":"click",
               "name":"开发中",
               "key":"aaa"
            },
            {
                "type":"view",
                "name":"天气预报",
                "url":"http://m.hao123.com/a/tianqi"
            }]
       },
       {
           "name":"关于我",
           "sub_button":[
            {
               "type":"view",
               "name":"微博",
               "url":"http://weibo.com/cgddgc/"
            },
            {
               "type":"click",
               "name":"待定",
               "key":"aaa"
            },
            {
                "type":"click",
                "name":"待定++",
                "key":"aaa"
            }]
       

       }]
 }';


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);

function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

?>