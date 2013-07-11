<?php
/**
 * 本文件完成心情分析的功能
 */
//定义常量，用来授权调用includes里面的文件
	define ( 'IN_TG', true );
//定义常量，用来指定本页的内容
	define ( 'SCRIPT', 'emtionDsp' );
	$classlist = explode ( '##', $classifyresult);
	if($day1count!=0){
	$analy = array_slice ( $classlist, count ( $classlist ) - $day1count, $day1count ); //取分类结果中最新的10个做分析
	//print_r($analy);
	$sentence=array(
	"1"=>"听些舒缓的歌，保持你的好心晴",
	"2"=>"要保持稳定的情绪哦~",
	"3"=>"忍一时风平浪静，退一步海阔天空",
	"4"=>"生活是一面镜子，你笑它就笑",
	"5"=>"不要<span>恐惧</span>，正面困难",
	"6"=>"忧郁的日子总会过去，<span>振作</span>点",
	"7"=>"不以物喜，不以己悲，<span>放松</span>一下",
	);
	$stat=round(array_sum($analy)/$day1count);
	echo '<br>心情状态：<span>'.$classarray[$stat].'</span><br><br>过去24小时发表微博数：<span>'.$day1count.'</span><br><br>';
	echo $sentence[$stat];	//直接取平均值
	echo '<br><br>';
	}else{
		echo '你过去24小时没有发布微博哦';
	}
?>