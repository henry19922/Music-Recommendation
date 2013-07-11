<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
$url='flash.html';//授权成功后的跳转页面
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );

if (isset($url)) 
{ 
	Header("HTTP/1.1 303 See Other"); 
	Header("Location: $url"); 
	exit; //from www.w3sky.com 
}
}else {
?>
授权失败。
<?php
}
?>
