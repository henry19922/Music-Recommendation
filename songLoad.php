<?php
/**
 * 本文件完成加载歌曲列表及播放器的功能
 */
//定义常量，用来授权调用includes里面的文件
define ( 'IN_TG', true );
//定义常量，用来指定本页的内容
define ( 'SCRIPT', 'content' );
//$classifyresult = '2##1##2##2##1##2##1##2##4##2##6';
if ($_POST ['cont'] == 'wls'){
	$fd = fopen ('res/temp.txt', "r");
	if ($fd){
		$classifyresult = fread ($fd, filesize ('res/temp.txt'));
    	fclose ($fd);
	}
}else{
	$fp = fopen ('res/temp.txt', "w" );
	if ($fp){
		fwrite($fp,$classifyresult);
		fclose($fp);
	}
}
/**
 * 读取文件，返回数组列表
 * @param unknown_type $filename 文件名 
 * @return multitype:
 */
function read_list_file($filename) {
	$anjinglist = array ();
	$fp = fopen ( $filename, "r" ); //注意不要用中文名!!!
	if ($fp) {
		while ( ! feof ( $fp ) ) {
			$buffer = fgets ( $fp, 4096 );
			array_push ( $anjinglist, $buffer );
		}
		fclose ( $fp );
	}
	return $anjinglist;
}
/**
 * 读取歌曲类别文件的函数
 * @param unknown_type $filename 文件名
 * @param unknown_type $klass	类别名
 * @return multitype:
 */
function read_klist_file($filename, $klass) {
	$anjinglist = array ();
	$fp = fopen ( $filename, "r" ); //注意不要用中文名!!!
	if ($fp) {
		while ( ! feof ( $fp ) ) {
			$buffer = fgets ( $fp, 4096 );
			array_push ( $anjinglist, $buffer . '#' . $klass );
		}
		fclose ( $fp );
	}
	return $anjinglist;
}
/**
 * 处理音乐分类规则：
 * 1、获取rule.txt的内容
 * 2、读取rule.txt里中文单词里含有的情感拼音，并读取相应的文件
 * 3、把这些内容读到7个数组里，代表基本的7钟情感
 * 4、从这些数组里面挑选出现次数最多的情感还有次多、第三多的	//TODO 这个以后可以考虑用推荐算法
 * @var $rules string
 */
$classlist = explode ( '##', $classifyresult );
$rules = read_list_file ( dirname ( __FILE__ ) . '/res/rule.txt' );
$le1 = array ();
$hao2 = array ();
$nu3 = array ();
$e4 = array ();
$ju5 = array ();
$ai6 = array ();
$jing7 = array ();
$classess = array ($le1, $hao2, $nu3, $e4, $ju5, $ai6, $jing7 );
$k = 0;
foreach ( $rules as $temp ) {
	$templist = explode ( "、", $temp );
	for($i = 1; $i < count ( $templist ); $i ++) {
		$classess[$k] = array_merge ( read_klist_file ( dirname ( __FILE__ ) . '/res/' . trim ( $templist [$i] ) . '.txt', trim ( $templist [$i] ) ), $classess [$k] );
	}
	$k++;
}
//381861#坐在巷口的那对男女#自然卷
$resultsong = array ();

$analy = array_slice( $classlist, count( $classlist ) - 20, 20 ); //取分类结果中最新的20个做分析
$analy = array_count_values($analy );
arsort( $analy, SORT_STRING);
$analy = array_keys( $analy);

$classSongList = array ("anjing" => "安静", "gandong" => "感动", "jimo" => "寂寞", "kaixin" => "开心", "shanggan" => "伤感", "shuhuan" => "舒缓", "sinian" => "思念", "tiammi" => "甜蜜", "weimei" => "唯美", "wennuan" => "温暖" );
$classradioList=array("anjing" => "8", "gandong" => "4", "jimo" => "9", "kaixin" => "5", "shanggan" => "1", "shuhuan" => "7", "sinian" => "3", "tiammi" => "6", "weimei" => "10", "wennuan" => "11" );
$radiolink='http://www.xiami.com/radio/play/type/16/oid/';
$resulttable = '';
$songidlist='';
for($j = 0; $j < 3; $j++) {
	$rand_3unit = array_rand($classess[$analy[$j]], 3); //随机在类别中挑出三首歌曲
	foreach($rand_3unit as $unit){
		$templist = explode ( "#", $classess [$analy [$j]] [$unit] );
		$songName = $templist [1];
		$artist = $templist [2];
		$songidlist=$songidlist.$templist [0].',';
		$class = $classSongList [$templist [3]]; // 这里读取原来的歌曲类别，然后在每首曲后面做记号
		$resulttable = $resulttable . '<tr ><td width="34" class="current tabletitle" ></td>';
		$resulttable = $resulttable . '<td width="350" height="20" ><div class="control_td_height"><p><span style="left:0px;height:20px"><a onclick="freshplayer(this);" class="tablecontent2" name="'.$templist [0].'" >' . $songName . '</a></span></p></div></td>';
		$resulttable = $resulttable . '<td width="150" height="20" class="tablecontent"><div class="control_td_height">' . $artist . '</div></td>';
		$resulttable = $resulttable . '<td width="50" height="20" ><div class="control_td_height"><a href="'.$radiolink.$classradioList[$templist[3]].'" target="_blank" class="tablecontent1">' . $class . '</a></div></td>';
	}
}
echo $resulttable;
$songidlist=substr($songidlist, 0, - 1 );
?>