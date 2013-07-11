// JavaScript Document

function Ajax(recvType) {
	var aj = new Object();
	aj.targetUrl = '';
	aj.sendString = '';
	aj.resultHandle = null;

	aj.GetXmlHttpRequest = function() {
		var xmlHttp = null;
		try {
			xmlHttp = new XMLHttpRequest();
		} catch (e) {
			try {
				xmlHttp = new ActiveXObject("Msxm12.XMLHTTP");
			} catch (e) {
				try {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {
					xmlHttp = false;
				}
			}
		}
		return xmlHttp;
	}

	aj.XMLHttpRequest = aj.GetXmlHttpRequest();

	aj.updatePage = function() {
		if (aj.XMLHttpRequest.readyState == 4 && aj.XMLHttpRequest.status == 200) {
			aj.resultHandle(aj.XMLHttpRequest.responseText); //使用了调用页面创建的 function(data) 函数
		}
	}

	aj.get = function(targetUrl, resultHandle) {
		aj.targetUrl = targetUrl;

		if (resultHandle != null) { //如果请求页面需要返回值，则调用返回函数
			aj.XMLHttpRequest.onreadystatechange = aj.updatePage;
			aj.resultHandle = resultHandle;
		}

		aj.XMLHttpRequest.open("GET", aj.targetUrl, true);
		aj.XMLHttpRequest.send(null);
	}

	aj.post = function(targetUrl, sendString, resultHandle) {
		aj.targetUrl = targetUrl;

		if (typeof(sendString) == "object") {
			var str = "";
			for (var pro in sendString) { // 发送的是 数组
				str += pro + "=" + sendString[pro] + "&";
			}
			aj.sendString = str.substr(0, str.length - 1); //将最后一个  & 符 删除
		} else {
			aj.sendString = sendString; //发送的是字符串
		}

		if (resultHandle != null) { //如果请求页面需要返回值，则调用返回函数
			aj.XMLHttpRequest.onreadystatechange = aj.updatePage;
			aj.resultHandle = resultHandle;
		}

		aj.XMLHttpRequest.open("post", targetUrl);
		aj.XMLHttpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); //告诉浏览器编码信息
		aj.XMLHttpRequest.send(aj.sendString);
	}
	return aj;
}