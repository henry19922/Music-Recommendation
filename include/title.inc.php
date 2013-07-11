<?php

//防止恶意调用
if (! defined ( 'IN_TG' )) {
	exit ( 'Access Defined!' );
}

//防止恶意调用
if (! defined ( 'SCRIPT' )) {
	exit ( 'Script Error!' );
}
?>
<!--引入css样式表-->
<link href="css/layout.css" rel="stylesheet" type="text/css">
<link href="css/index.css" rel="stylesheet" type="text/css">
<link href="css/content.css" rel="stylesheet" type="text/css">
<link href="css/basic.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/checkLogin.js"></script>