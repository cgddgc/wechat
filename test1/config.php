<?php
/**
 * 连接数据库，并以utf-8读取数据
 * 数据库 变量$link
 * 使用的 表为  message_board ，需自行创建
 * 表目前有三个字段  name，content，date
 */
$link=mysql_connect('127.0.0.1:3306','root','cgddgc');
if( !$link )
	die('Could not connect database!');
//打开数据库的连接
//这里是打开 与sina 数据库的连接

mysql_query("set names utf8"); 
//以utf8读取数据

mysql_select_db(message_board,$link);
//选择数据库

?>
