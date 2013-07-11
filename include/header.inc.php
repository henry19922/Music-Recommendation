<?php
/**
 * 页面头部
 * @author Hery
 * @version 
 */
//防止恶意调用
if (! defined ( 'IN_TG' )) {
	exit ( 'Access Defined!' );
}
include 'content.php';
?>
<div class="header2">
<div class="logo"><img src="pic/logo.gif" /></div>
<div class="user">
<?php 
	echo '<img src="'.$test->getImage().'" width=80 height=80/>';
?>
</div>
</div>
<div class="clearfix"></div>