/**
 * 
 */
// <!--***********************************以下内容***********************************-->
// 饼图
$(function () {
// var data = [
// { label: "Series1", data: 10},
// { label: "Series2", data: 30},
// { label: "Series3", data: 90},
// { label: "Series4", data: 70},
// { label: "Series5", data: 80},
// { label: "Series6", data: 110}
// ];
	var data=[<?php
	echo $data?>];
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
		if (!obj)
            return;
		percent = parseFloat(obj.series.percent).toFixed(2);
		$("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span> ');
	});
	$("#placeholder1").bind("plotclick",function pieClick(event, pos, obj) 
	{
		if (!obj)
	           return;
		percent = parseFloat(obj.series.percent).toFixed(2);
		alert(''+obj.series.label+': '+percent+' % ');
	});
});
// 打印折线图
$(function(){
var d5 = <?php
echo $dimision?>;
var d51 = new Array(<?php
echo $timedimisson?>);
d51 = d51.reverse();
$.plot($("#placeholder"), [{
		label: "心情类别",
		data: d5
	}
], {
	series: {
		lines: {
			show: true,
			fill: true,
			steps: false
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

			// var x = item.datapoint[0];
			var weibodate = new Date(d51[<?php echo ($showcount-1)?> - item.dataIndex]);
			var color = item.series.color;

			// 传数组给它就可以了
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
		// 点击滑动到相应位置的微博
		$.scrollTo("#timeline" + (item.dataIndex + 1), 300);
		// var sk=document.getElementById("#timeline"+(item.dataIndex));
		// console.debug("#timeline"+(item.dataIndex));
		// sk.blur();
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
// 折线图
// <!--****************************以上内容实现饼图******************************************-->
