<?php

//防止恶意调用
if(!defined('IN_TG')){
	exit('Access Defined!');
}
//设置字符集编码
header('Content-type:text/html;charset=utf-8');

//转换硬路径常量
define('ROOT_PATH',substr(dirname(__FILE__),0,-8));

//创建一个自动转义状态的常量
//define('GPC',get_magic_quotes_gpc());

//引入函数库
require ROOT_PATH.'/include/global.func.php';

//执行耗时
define('START_TIME' ,_runtime());

?>