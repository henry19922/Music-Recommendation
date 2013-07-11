/**
 * 定义用于点击更改播放器的函数 content.php
 * 
 * @param elem
 * @return
 */

function freshplayer(elem) {
	if (elem != null) {
		var id = elem.name;
		document.getElementById('embedhenry').data = 'http://www.xiami.com/widget/11120456_'
				+ id + '_235_346_ff9900_ffffff_1/multiPlayer.swf';
		document.getElementById('embedhenry').innerHTML = '<embed src="http://www.xiami.com/widget/11120456_'
				+ id
				+ '_235_346_ff9900_ffffff_1/multiPlayer.swf" type="application/x-shockwave-flash" width="270" height="33" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
	}
}
/**
 * 定义用于加载自动刷新播放器的函数 content.php
 * 
 * @return
 */

function freshplayer2() {
	// var table11=document.getElementById('tablehenry');
	// var linkList=table11.getElementsByTagName('a');
	// var stsongid=linkList[0].name;
	// $('#embedhenry').css('data','http://www.xiami.com/widget/11120456_<?php
	// echo $songidlist;?>_235_346_ff9900_ffffff_1/multiPlayer.swf');
	var id = "tablehenry";
	// document.getElementById(id).get;
	// document.getElementById('embedhenry').data='http://www.xiami.com/widget/11120456_'+stsongid+'_235_346_ff9900_ffffff_1/multiPlayer.swf';
}
/**
 * 回到顶部还有手势变换,juery函数集
 */
$(document).ready(
		function() {
			// Show or hide the sticky footer button
			$(window).scroll(function() {
				if ($(this).scrollTop() > 100) {
					$('.go-top').fadeIn(200);
				} else {
					$('.go-top').fadeOut(200);
				}
			});
			// Animate the scroll to top
			$('.go-top').click(function(event) {
				event.preventDefault();
				$('html, body').animate({
					scrollTop : 0
				}, 300);
			})
			// 点击时更换图片
			$(".thumblink").click(
					function() {
						$(this).children("div").css("background",
								"url(./pic/orange-thumbs-up.png)");
					});
		});
/**
 * 左右两个图的切换
 * 
 * @return
 */

function displayforpic2() {
	document.getElementById("placeholder1").style.display = "block";
	document.getElementById("placeholder").style.display = "none";
}
/**
 * 左右两个图的切换
 * 
 * @return
 */

function displayforpic1() {
	document.getElementById("placeholder").style.display = "block";
	document.getElementById("placeholder1").style.display = "none";
}
/**
 * 侧边栏实现
 */
$(function() {
	// var $sidebar = $("#sidebar"),
	// $window = $(window),
	// offset = $sidebar.offset(),
	// topPadding = 15;
	// $window.scroll(function() {
	// if ($window.scrollTop() > offset.top) {
	// $sidebar.stop().animate({
	// marginTop: $window.scrollTop() - offset.top + topPadding
	// });
	// } else {
	// $sidebar.stop().animate({
	// marginTop: 0
	// });
	// }
	// });
});
/**
 * 隐藏跟显示
 */
$(document).ready(function() {
	$("#btn1").mousedown(function() {
		$(".con_chart").slideToggle();
		if($("#btn1").text()==' 隐藏图表 ')
			$("#btn1").text(" 显示图表 ");
		else
			$("#btn1").text(' 隐藏图表 ');
	});
	$("#btn2").mousedown(function() {
		$("#tl_container").slideToggle();
		if($("#btn2").text()==' 隐藏微博 ')
			$("#btn2").text(" 显示微博 ");
		else
			$("#btn2").text(' 隐藏微博 ');
	});
});