<?php
/**
 * 本页面用于分类数据调用
 * 
 */
//设置字符集编码
header ( 'Content-type:text/html;charset=UTF-8' );
//定义常量，用来授权调用includes里面的文件
define ( 'IN_TG', true );
//定义常量，用来指定本页的内容
define ( 'SCRIPT', 'index1' );
require dirname ( __FILE__ ) . '/testseg.php';//引入分词文件
//echo $_POST['cont'].'Hello,This is Ajax';
require_once ("./lib/Java.inc"); //必须包含的配置文件
java_require ( "./lib/classify.jar" ); //引用包含的jar包

$input = _seg (explode ( '##', '[开心]我叫吴烈思 http://www.qq.com @wls ##这是分词文本[呵呵]' ) );
echo $input;
$classify = new Java ( "dicMatch.EmotionMatchDic" );
echo $classify->dicMatch ( $input );

?>