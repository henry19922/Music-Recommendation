<?php
//设置字符集编码
header ( 'Content-type:text/html;charset=UTF-8' );
try {
	//    $c=new COM('ICTCLAS.Split');
//    $c->init('D:/AppServ/www/Competition_Computer/lib');  //初始化ictclas，参数为字典configure.xml和Data目录所在的文件夹全路径。它们可以从中科院下载的ictclas库里找到。
//	
//    $result=$c->split('我叫吴烈思，今天分词搞死我了');  //分词。返回带标记字符串
//    $result1=$c->split('这是测试文本，编码为GBK');
//	$nameArray=array();
//    $addressArray=array();
//    preg_match_all('/([\S]*)\/([\S]*)/',$result,$out);  //提取分词信息
//	echo mb_convert_encoding($result.'##'.$result1, "UTF-8", "GBK"); 
//	//print_r($out);
//    $c->exit();  //最后释放ictclas资源。
//	echo _seg(explode('##','心情糟透了'));
} catch ( Exception $e ) {
	echo "ERROR:COM not supported!";
}

function _seg($weibolist) {
	try {
//		$c = new COM ( 'ICTCLAS.Split' );
//		$c->init ( 'D:/AppServ/www/Competition_Computer/lib' );
		$so = scws_new();
		$so->set_charset('utf-8');
		$so->set_dict('C:\Program Files\scws\etc\dict.utf8.xdb');
		$so->set_rule('C:\Program Files\scws\etc\rules.ini');
		$resultlist = '';
		foreach ( $weibolist as $weibo ) {
			//替换链接
			$weibo=ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",'',$weibo);
			//替换@的人
			$weibo=ereg_replace("@[^[:space:]]+",'',$weibo);
			$exp='';
			if (ereg("(\\[[^\\[\\]+])", $weibo, $regs )) {	//提取表情
				for($i=1;$i<=count($regs);$i++){
					$exp=$exp.$regs[$i].' ';
				}
			} else {
				//echo "没有表情";
			}
			$weibo=ereg_replace('\\[[^\\[\\]+]','',$weibo);
			$temp = explode ( "_time_", $weibo );
			$so->send_text($temp [0]);
			$result='';
			while ($tmp = $so->get_result())
			{
				foreach($tmp as $word){
					$result=$result.$word["word"].' ';
				}
			}
//			echo $result;
//			$result = $c->split ( mb_convert_encoding ( $temp [0], "GBK", "UTF-8" ) );
			$resultlist = $resultlist . $result .$exp.'##';
		}
//		$c->exit (); //最后释放ictclas资源。
		$so->close();
		return $resultlist;
	} catch ( Exception $e ) {
		echo "ERROR:COM not supported!";
	}
}
?>