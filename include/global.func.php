<?php
/**
 * _runtime()是用来获取执行耗时
 * @access public 表示函数对外公开
 * @return float 表示返回出来的是一个浮点型数字
 */
function _runtime() {
	$_mtime = explode ( ' ', microtime () );
	return $_mtime [1] + $_mtime [0];
}
/**
 * _alert_back()是js弹窗
 * @param $_info
 */
function _alert_back($_info) {
	echo "<script type='text/javascript'>alert('" . $_info . "');history().back();</script>";
	exit ();
}

/**
 *  _code() 验证码产生函数
 * @param $_rnd_code   产生的位数
 * @param $_width
 * @param $_height 
 * @param $_flag 是否需要边框
 */
function _code($_rnd_code, $_width, $_height, $_flag) {
	for($i = 0; $i < $_rnd_code; $i ++) {
		$_nmsg .= dechex ( mt_rand ( 0, 15 ) );
	}
	$_SESSION ['code'] = $_nmsg;
	$_img = imagecreatetruecolor ( $_width, $_height );
	
	$_white = imagecolorallocate ( $_img, 255, 255, 255 );
	
	imagefill ( $_img, 0, 0, $_white );
	
	if ($_flag) {
		$_black = imagecolorallocate ( $_img, 0, 0, 0 );
		imagerectangle ( $_img, 0, 0, $_width - 1, $_height - 1, $_black );
	}
	
	for($i = 0; $i < 6; $i ++) {
		$_rnd_color = imagecolorallocate ( $_img, mt_rand ( 0, 255 ), mt_rand ( 0, 255 ), mt_rand ( 0, 255 ) );
		imageline ( $_img, mt_rand ( 0, $_width ), mt_rand ( 0, $_height ), mt_rand ( 0, $_width ), mt_rand ( 0, $_height ), $_rnd_color );
	}
	
	for($i = 0; $i < 100; $i ++) {
		$_rnd_color = imagecolorallocate ( $_img, mt_rand ( 200, 255 ), mt_rand ( 200, 255 ), mt_rand ( 200, 255 ) );
		imagestring ( $_img, 1, mt_rand ( 1, $_width ), mt_rand ( 1, $_height ), '*', $_rnd_color );
	}
	
	for($i = 0; $i < strlen ( $_SESSION ['code'] ); $i ++) {
		$_rnd_color = imagecolorallocate ( $_img, mt_rand ( 0, 100 ), mt_rand ( 0, 150 ), mt_rand ( 0, 200 ) );
		imagestring ( $_img, 5, $i * $_width / $_rnd_code + mt_rand ( 1, 10 ), mt_rand ( 1, $_height / 2 ), $_SESSION ['code'] [$i], $_rnd_color );
	}
	
	header ( 'Content-Type: image/png' );
	imagepng ( $_img );
	imagedestroy ( $_img );
}
/**
 * 获取服务器自身在局域网的IP
 * @param unknown_type $dest
 * @param unknown_type $port
 * @return unknown
 */
 function my_ip($dest='64.0.0.0', $port=80)
   {
    $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_connect($socket, $dest, $port);
    socket_getsockname($socket, $addr, $port);
    socket_close($socket);
    return $addr;
   }
?>