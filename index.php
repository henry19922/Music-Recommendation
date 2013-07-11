<?php
//定义常量，用来授权调用includes里面的文件
define ( 'IN_TG', true );
//定义常量，用来指定本页的内容
define ( 'SCRIPT', 'index' );
//引入公共文件
require dirname ( __FILE__ ) . '/include/common.inc.php'; //转换成硬路径，速度更快

/**
 * 新浪认证调用
 */
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>计算机设计大赛</title>
<?php
require ROOT_PATH . '/include/title.inc.php';
?>
</head>
<body
	style="background: url(./pic/spring111.jpg) no-repeat center center; width: 100%; height: 100%; overflow: hidden;">
<div class="container">
<!--这里用于一开始就放背景音乐 <embed-->
<!--	src="res/Linus Blanket - Signal Waltz.mp3" width="357" height="33"-->
<!--	wmode="transparent" autostart="true" volume="50" hidden="true" loop="true"></embed>-->
<div class="header1"></div>
<div class="mainContent1 clearfix">
<div class="login">
<a href="<?php echo $code_url?>"><img width="250" height="50"
 src="pic/weibo_login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a>
</div>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="footer">Copyright @ 广东外语外贸大学有粮团队</div>
</div>
</body>
</html>