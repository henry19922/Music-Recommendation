/**
 * 检查表单元素
 * @return
 */
function checkform(username,passwd) {
	document.MM_returnValue=true;
	if(username.value.length==0||passwd.value.length==0){
		alert('请输入微博昵称及密码');
		username.focus();
		document.MM_returnValue=false;
	}
	else if(code.value.length==0){
		alert('请输入验证码');
		code.focus();
		document.MM_returnValue=false;
	}
}
