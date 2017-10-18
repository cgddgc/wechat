<?php
include '../cloud_music.php';
$data = $_GET;
$key=trim($data['data']);
   public function getmusic($key)
        {   	
			$art="";
			if(strstr($key,"*")<>"")
			{
			$a=strpos($key,"*");
			$word=substr($key,0,$a);
			$art=str_replace("*","",strstr($key,"*"));
			$key=$word;
		}
            $musicurl="";
            $musicurl=get_musicUrl($key,10,$art);
            $artist=get_artist($key,10,$art);
			$res=array($musicurl,$artist)
      		return $res;
        } 
echo getmusic($key);		
?>


