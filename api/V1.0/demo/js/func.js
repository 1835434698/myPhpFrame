//密码加密
function pwdencode(pwd){
	rcode = rndcode(3);
	pwd += rcode;
	pwd = chstr(pwd);
	pwd = $.base64.encode(pwd);
	pwd = chstr(pwd);
	pwd = $.base64.encode(pwd);
	pwd = chstr(pwd);
	pwd = $.base64.encode(pwd);
	return pwd;
}