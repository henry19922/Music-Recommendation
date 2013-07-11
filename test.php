<?php
header("Content-type: text/html; charset=utf-8");
//require_once("./Java.inc"); //必须包含的配置文件
//java_require("./weibo.jar"); //引用包含的jar包
//
//$crawler = new Java("GetUserTimeline"); //产生实例
//$crawler->setToken("2.009jPJvBKLPPsB165febcc8dto1uDB");
//$crawler->setName("Verus文浩");
//echo "以下是“Verus文浩”的微博文本<br>".$crawler->getWeiboText();
//$test->setName("哈哈,PHP调用JAVA的方法！"); //后面的调用就跟在php中调用类方法一样

//echo "调用类Test的getName方法，返回值为：".$test->getName()."<br>";
//echo "调用Test的add方法，返回值为：".$test->add(11.2, 15.7);
//echo strtotime('Sat May 11 11:04:47 +0800 2013')*1000; //1 368 241 487 000
$so = scws_new();
$so->set_charset('utf-8');
// 这里没有调用 set_dict 和 set_rule 系统会自动试调用 ini 中指定路径下的词典和规则文件
$so->set_dict('C:\Program Files\scws\etc\dict.utf8.xdb');
$so->set_rule('C:\Program Files\scws\etc\rules.ini');
//$so->set_multi(1);
$so->send_text("为什么手机每次切换静音状态，连震动都给我关掉了。MLGB");
$result='';
while ($tmp = $so->get_result())
{
	foreach($tmp as $word){
		$result=$result.$word["word"].'/'.' ';
	}
}
echo $result;
$so->close();

//$text='http://t.cn/zTY4gwq #高考阅卷内幕# 看来我跟广外挺有缘的，跟信息学院就更有缘了。。。 详情:http://t.cn/zT83RKz';
//echo preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $text);
//preg_match_all("/([[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/])/u",'http://t.cn/zTY4gwq #高考阅卷内幕# 看来我跟广外挺有缘的，跟信息学院就更有缘了。。。 详情:http://t.cn/zTY4gwq',$regs);
////[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]
//foreach($regs[0] as $temp){
//	echo $temp;
//	echo '<br>';
//}
?>