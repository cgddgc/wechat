<?php
    //QQ:763283282
    //Email:zuopeng.gd@qq.com
    //个人主页:http://www.zuopeng.gd.cn
    //关注个人博客:http://weibo.com/zuopeng666
    //微信朋友关注：微信公众平台帐号--中国笑吧--随时随地看笑话
    require './weixin.php';
    $arr = array(
        'account' => '公众平台帐号',
        'password' => '密码'
    );

    $w = new Weixin($arr);
    //获取所有粉丝信息
    //$w->getAllUserInfo();
    //群发
    $w->manySend('群发内容'); 
