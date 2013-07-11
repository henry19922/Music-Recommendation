/**
 * 实现content页面下方的时间线
 */
$(function() {
	// masonry plugin call
	$('#tl_container').masonry({
		itemSelector: '.item',
	});
	//Injecting fresh arrows
	Arrow_Points();
});
// 时间线
$('.timeline_container').mousemove(function(e) {
	var topdiv = $("#containertop").height();
	var pag = e.pageY - topdiv - 26;
	$('.plus').css({
		"top": pag + "px",
		"background": "url('../pic/plus.png')",
		"margin-left": "1px"
	});
}).mouseout(function() {
	$('.plus').css({
		"background": "url('')"
	});
});
//小箭头

function Arrow_Points() {
	var s = $('#tl_container').find('.item');
	$.each(s, function(i, obj) {
		var posLeft = $(obj).css("left");
		$(obj).addClass('borderclass');
		if (posLeft == "0px") {
			html = "<span class='rightCorner'></span>";
			$(obj).prepend(html);
		} else {
			html = "<span class='leftCorner'></span>";
			$(obj).prepend(html);
		}
	});
}
//删除按钮
$(".deletebox").live('click', function() {
	if (confirm("Are your sure?")) {
		$(this).parent().fadeOut('slow');
		//Remove item block
		$('#tl_container').masonry('remove', $(this).parent());
		//Reload masonry plugin
		$('#tl_container').masonry('reload');
		//Hiding existing Arrows
		$('.rightCorner').hide();
		$('.leftCorner').hide();
		//Injecting fresh arrows
		Arrow_Points();
	}
	return false;
});
//定位与弹出
//Timeline navigator on click action 
$(".timeline_container").click(function(e) {
	alert("鼠标点了哦亲");
	var topdiv = $("#containertop").height();
	//Current Postion 
	$("#popup").css({
		'top': (e.pageY - topdiv - 33) + 'px'
	});
	$("#popup").fadeIn(); //Popup block show
	//Textbox focus
	$("#update").focus();
});

//Mouseover no action
$("#popup").mouseup(function() {
	return false
});
//Outside action of the popup block
$(document).mouseup(function() {
	$('#popup').hide();
});

//Update button action
$("#update_button").live('click', function() {
	//Textbox value
	var x = $("#update").val();
	//Ajax Part
	$("#container").prepend('<div class="item"><a href="#" class="deletebox">X</a>' + x + '</div>');
	//Reload masonry
	$('#container').masonry('reload');
	//Hiding existing arrows
	$('.rightCorner').hide();
	$('.leftCorner').hide();
	//Injecting fresh arrows
	Arrow_Points();
	//Clear popup textbox value
	$("#update").val('');
	//Popup hide
	$("#popup").hide();
	return false;
});