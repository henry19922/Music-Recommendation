<?php
//定义常量，用来授权调用includes里面的文件
define ( 'IN_TG', true );
//定义常量，用来指定本页的内容
define ( 'SCRIPT', 'content' );
//引入公共文件
require dirname ( __FILE__ ) . '/include/common.inc.php'; //转换成硬路径，速度更快
require dirname ( __FILE__ ) . '/testseg.php'; //引入分词文件
session_start ();

include_once ('config.php');
include_once ('saetv2.ex.class.php');
/**
 * 利用新浪API完成数据抓取
 * @var $c SaeTClientV2
 */
$c = new SaeTClientV2 ( WB_AKEY, WB_SKEY, $_SESSION ['token'] ['access_token'] );
$uid_get = $c->get_uid ();
$uid = $uid_get ['uid']; //得到当前登录用户的uid
$ms = $c->user_timeline_by_id ( $uid, 1, 100, 0, 0, 0, 1, 0 ); // 获取最新100条微博
$rawWeiboText = '';
$zero1=strtotime(date('y-m-d h:i:s')); //当前时间
$day1count=0;	//计算过去24小时发的微博
foreach ( $ms ['statuses'] as $msunit ) { //组成文本+时间的字符串
	$timestamp = strtotime ( $msunit ['created_at'] ) * 1000; //Sat May 11 11:04:47 +0800 2013
	if($zero1-($timestamp/1000)<86400)	//60*60*24
		$day1count++;
	$rawWeiboText = $rawWeiboText . $msunit ['text'] . '_time_' . $timestamp . '##';
}
$user_message = $c->show_user_by_id ( $uid ); //根据ID获取用户等基本信息

require_once ("./lib/Java.inc"); //必须包含的配置文件
java_require ( "./lib/classify.jar" ); //引用包含的jar包


/**
 * 分类模块调用,给分词结果，返回classify那样的结果，直接通过js 把数据post上去
 */
$weibotext1 = $rawWeiboText;
$weibolist1 = explode ( "##", $weibotext1 );

$classify = new Java ( "dicMatch.EmotionMatchDic" );
$segresult = _seg ( $weibolist1 ); //调用分词接口
$classify->setRoute ( addcslashes ( realpath ( './lib/' ), '\\' ) . '\\\\' );
$classify->setInput ( $segresult );
$classifyresult = $classify->dicMatch ();
$classlist = explode ( '##', $classifyresult );

/**
 * 饼图的数据准备
 * @var $analy unknown_type
 */
$analy = array_count_values ( $classlist );
$index = array_keys ( $analy );
$classarray = array (0 => "无", 1 => "乐", 2 => "好", 3 => "怒", 4 => "恶", 5 => "惧", 6 => "哀", 7 => "惊" );
$data = '';
foreach ( $index as $temp ) {
	$data = $data . ' {label: "' . $classarray [$temp] . '",data:' . $analy [$temp] . '10}, ';
}
$data = substr ( $data, 0, - 1 );

/**
 * 折线图的数据准备
 */
$dimision = '['; //数值的轴
$timedimisson = ''; //时间的轴
$j = 100;
//$weibolist1=array_flip($weibolist1);
/*
 * 注意这里weibolist是逆着的
 */
$showcount = 50;
foreach ( $weibolist1 as $temp ) {
	$weibo1 = explode ( '_time_', $temp ); //weibo[0]为微博文本，weibo[1]
	//为发微博的时间
	$dimision = $dimision . '[' . (-- $j - (99 - $showcount)) . ',' . $classlist [$j] . '],';
	$timedimisson = $timedimisson . $weibo1 [1] . ',';
	if ($j == (100 - $showcount))
		break;
}
$dimision = substr ( $dimision, 0, - 1 ) . ']';
$timedimisson = substr ( $timedimisson, 0, - 1 );
//echo $dimision;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Access-Control-Allow-Origin" content="*" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>听心晴</title>
<?php
	require ROOT_PATH . '/include/title.inc.php ';
?>
<!--引入js动态效果-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.masonry.min.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.flot.pie.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.flot.time.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.scrollTo.js"></script>
<script language="javascript" type="text/javascript"
	src="js/usrdefine.js"></script>
<!--实现时间线的效果-->
<script type="text/javascript" src="js/timeline.js"></script>
<!--实现页面的动态加载 引入ajax文件-->
<script type="text/javascript" src="js/ajax.js"></script>
<!--实现饼图-->
<script type="text/javascript">
//回到顶部
//<!--***********************************以下内容***********************************-->
//饼图
$(function () {
//	var data = [
//	    		{ label: "Series1",  data: 10},
//	    		{ label: "Series2",  data: 30},
//	    		{ label: "Series3",  data: 90},
//	    		{ label: "Series4",  data: 70},
//	    		{ label: "Series5",  data: 80},
//	    		{ label: "Series6",  data: 110}
//	    	];
	var data=[<?php echo $data?>];
	// GRAPH 3
	$.plot($("#placeholder1"), data, 
	{
		series: {
			pie: { 
				show: true,
				radius: 1,
				label: {
					show: true,
					radius: 3/4,
					formatter: function(label, series){
						return ' <div style = "font-size:8pt;text-align:center;padding:2px;color:white;" > '+label+' <br/> '+Math.round(series.percent)+' % </div>';
					},
					background: { opacity: 0.5 }
				}
			}
		},
		grid: {
		    hoverable: true,
		    clickable:true
		},
		legend: {
			show: true
		}
	});
	$("#placeholder1").bind("plothover",function pieHover(event, pos, obj)
	{
//		if (!obj)
//            return;
//		percent = parseFloat(obj.series.percent).toFixed(2);
		//$("#hover").html('<span style="font-weight: bold; color:'+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
	});
	$("#placeholder1").bind("plotclick",function pieClick(event, pos, obj) 
	{
		if (!obj)
	           return;
		percent = parseFloat(obj.series.percent).toFixed(2);
		alert(''+obj.series.label+'的微博有'+Math.ceil(100*0.01*percent)+'条，所占比重为'+percent+' % ');
	});
});
//<!--****************************以上内容实现饼图******************************************-->
</script>
<!--实现折线图 -->
<script type="text/javascript">
//	打印折线图
	$(function(){
	var d5 = <?php
	echo $dimision?>;
	var d51 = new Array(<?php
	echo $timedimisson?>);
	d51 = d51.reverse();
	$.plot($("#placeholder"), [{
			label: "心情趋势曲线",
			data: d5
		}
	], {
		series: {
			lines: {
				show: true,
				fill: true,
				steps: false
				//,fillColor: "rgba(255, 200, 255, 0.8)"
			},
			points: {
				show: true,
				fill: true,
				fillColor: "rgba(255, 255, 255, 0.8)"
			}
		},
		grid: {
			hoverable: true,
			clickable: true,
			autoHighlight: true,
			borderWidth: 1,
			backgroundColor: {
				colors: ["#ffffff", "#EDF5FF"]
			}
		},
		xaxis: {
			show: false,
			tickSize: 1,
			tickDecimals: 0
		},
		yaxis: {
			font: {
				size: 13,
				lineHeight: 9,
				style: "italic",
				weight: "bold",
				family: "sans-serif",
				color: "#545454"
			},
			ticks: [
				[0, "无"],
				[1, "乐"],
				[2, "好"],
				[3, "怒"],
				[4, "恶"],
				[5, "惧"],
				[6, "哀"],
				[7, "惊"]
			]
		}
	});
	var previousPoint = null,
		previousLabel = null;

	$("#placeholder").bind("plothover", function(event, pos, item) {
		if (item) {
			if ((previousLabel != item.series.label) ||
				(previousPoint != item.dataIndex)) {
				previousPoint = item.dataIndex;
				previousLabel = item.series.label;
				$("#tooltip").remove();

				//var x = item.datapoint[0];
				var weibodate = new Date(d51[<?php
				echo ($showcount - 1)?> - item.dataIndex]);
				var color = item.series.color;

				//传数组给它就可以了
				showTooltip(item.pageX,
					item.pageY,
					color,
					"<strong>" + "这是最近第" + (item.dataIndex + 1) + "微博，发表于:" + "</strong><br>" +weibodate.getFullYear() + "年 " + 
					(weibodate.getMonth() + 1) + "月 " + weibodate.getDate() + "日" +weibodate.getHours() + "时" + weibodate.getMinutes() + "分");
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
	});

	$("#placeholder").bind("plotclick", function(event, pos, item) {
		if (item) {
			//点击滑动到相应位置的微博
			$.scrollTo("#timeline" + (item.dataIndex + 1), 300);
			//           	 var sk=document.getElementById("#timeline"+(item.dataIndex));
			//           	 console.debug("#timeline"+(item.dataIndex));
			//           	 sk.blur();
		}
	});

	function showTooltip(x, y, color, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
        position: "absolute",
        display: "none",
        top: y - 10,
        left: x + 10,
        border: "2px solid "+ color,
        padding: "3px",
        "font-size": "9px",
        "border-radius": "5px",
        "background-color": "#fff",
        "font-family": "Verdana, Arial, Helvetica, Tahoma, sans-serif",
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}
});
//折线图	
</script>
</head>
<body style="background: url(./pic/bg.png) repeat;">
<!--<div id="sidebar"><p>hello,world</p></div>-->
<div class="container"><!--header-->
<div class="header2">
<div class="logo"><img src="pic/logo.png" title="欢迎来到听心晴音乐推荐系统"/></div>
<div class="user">
<?php
//显示用户头像
echo '<a href="http://weibo.com/'.$user_message ['id'].'"><img src = "' . $user_message ['profile_image_url'] . '"width = 80 height = 80 title="点击进入微博"/></a> ';
?>
</div>
<div class="greeting"><span><?php
echo $user_message ['screen_name']?></span> </div>
</div>
<div class="clearfix"></div>
<!--maincontainer-->
<div class="maincontainer2"><!--chart-->
<div class="chart"><!--start of musiclist-->
<div class="musiclist">
<div class="clearfix"></div>
<table align="center" class="tablelist">
	<tr>
		<td width="34" height="30" ></td>
		<td width="350" height="30"  class=" tabletitle">歌曲名</td>
		<td width="150" height="30"  class=" tabletitle">艺术家</td>
		<td width="50" height="30"   class=" tabletitle">类别</td>
	</tr>
</table>
<table  class="tablelist" id="tablehenry"> 
<?php
/**
 * 调用推荐主函数
 */
include 'songLoad.php';
?> </table>
<!--原来换一换的位置-->
	<a class="more"
	href="javascript:var ajax=new Ajax(); var cont='wls';
		ajax.post('http://<?php
		echo my_ip ()?>/Competition_Computer/songLoad.php','cont=wls',
		function(data){document.getElementById('tablehenry').innerHTML=data });
		//TODO: 这里想实现自动刷新播放器但是没反应">换一换 </a>
<div class="clearfix"></div>

</div>
<!--end of musiclist-->
<div class="rightchart"><!--虾米播放器--> 
<object
	style="margin: 0px; float: left;" width="235" height="120"
	type="application/x-shockwave-flash"
	data="http://www.xiami.com/widget/11120456_<?php
	echo $songidlist;
	?>_235_346_ff9900_ffffff_1/multiPlayer.swf"
	id="embedhenry"> 
	<embed
		src="http://www.xiami.com/widget/11120456_1439750,1439750,_235_346_ff9900_ffffff_1/multiPlayer.swf"
		type="application/x-shockwave-flash" width="235" height="100"
		wmode="opaque">
		</embed> 
		</object>
<div class="xmyy"><a href="http://www.xiami.com/widget/isingle">
Powered by 虾米音乐</a></div>
<div class="clearfix"></div>
<p>
	<?php
	//TODO 这里的样式需要调整
			include 'emtionDsp.php';
	?>

</p>


</div>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="beeline"></div>


<!--start of 两个图表-->
<!--<button id="btn1"> 隐藏图表 </button>-->
<div class="twograph">
<div class="chart_type"><a href="#"> <img src="pic/tzhexiantu.png"
	style="margin-top: 70px; float:left;" align="middle" class="pic1"
	onmouseover="displayforpic1()" /> </a> <a href="#"> <img
	src="pic/tbingtu.png" align="middle"
	style="float: left; margin-top: 30px;" id="pic2"
	onmouseover="displayforpic2()" /> </a></div>
	
<!-- 饼图与折线图的位置-->
<div class="con_chart">
<div id="placeholder" style="width: 700px; height: 220px; margin-top:10px;"></div>
<div id="placeholder1" class="graph" style="width: 698px; height: 220px;"></div>
<div class="clearfix"></div>
<!-- 一段推荐的话 -->
</div>

<!--end of 两个图表--> <!-- weibo-->
<div class="clearfix"></div>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
<!--888888888888888-->
<div class="weibo"><!--timeline-->
<!--<button id="btn2"> 隐藏微博 </button>-->
<div id="tl_container">
<div class="timeline_container">
<div class="timeline">
<div class="plus"></div>
</div>
</div>
<?php
/**
 * 用于显示timeline的微博文本内容
 */
date_default_timezone_set ( "Asia/Hong_Kong" ); //设置为本地时区
$cc = 0;
$oldesttime = '';
$emosfile = read_list_file ( 'pic\emotions.txt ' );
$emotions = array ();
foreach ( $emosfile as $emo ) {
	$splitone = explode ( " ", $emo );
	$emotions [$splitone [0]] = $splitone [1];
}
foreach ( $weibolist1 as $temp ) {
	$weibo = explode ( '_time_', $temp ); //weibo[0]为微博文本，weibo[1]为发微博的时间
	if (mb_strlen ( $weibo [0], "UTF-8" ) == 1) //如果微博文本为空，就跳过
		continue;
	$date = date ( 'Y年 m月 d日 H: i: s ', ($weibo [1] / 1000) ); //毫秒转换为秒
	$weibohtm = $weibo [0];
	$weibohtm = preg_replace ( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $weibohtm );
	if (preg_match_all ( "/\\[([^\\]\\[]+)\\]/u", $weibo [0], $regs )) { //提取表情
		for($i = 0; $i < count ( $regs [0] ); $i ++) {
			$weibohtm = str_replace ( $regs [0] [$i], '<img width = 18 height = 18 src ="' . $emotions [$regs [0] [$i]] . '" >
			', $weibohtm );
		}
	}
	echo '<div class = "item" ><span class = "timelinecss" id = "timeline' . ($cc + 1) . '">' . (++ $cc) . '</span>' . $weibohtm . '<br>
			<div class="date">' . $date . '</div> <div class = "emotion" >情感: <span>' . $classarray [( int ) $classlist [100 - $cc]] . '</span>
			</div><a class = "thumblink" title = "觉得正确的话就赞一个" > <div class = "thumb" ></div></a></div>';
	if ($cc == 50) {
		$oldesttime = $date;
		break;
	} // 显示30条微博
}
?> </div>
<div class="laststa">
<p>以上是您在 <span><?php
echo $oldesttime?></span> 之后发布的所有微博</p>
</div>
</div>
<!--timeline-->

<!--888888888888888888888-->
<div class="clearfix"></div>
<?php
require ROOT_PATH . '/include/footer.inc.php';
java_shutdown ();
?> 
</body>
</html>