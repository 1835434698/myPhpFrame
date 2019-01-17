//反转字符串
function chstr(str){
	var result = "";
	for(var i = str.length; i > 0; i--){
		result += str.charAt(i-1);
	}
	return result;
}
//随机数字串
function rndcode(len){
	var str = "";
	for(var i=0; i<len; i++)
	{
		rnd = Math.floor(Math.random()*10);
		rnd.toString();
		str += rnd;
	}
	return str;
}
//JS获取地址栏参数
function getParam(paramName) {
    paramValue = "";
    isFound = false;
    if (this.location.search.indexOf("?") == 0 && this.location.search.indexOf("=") > 1) {
        arrSource = unescape(this.location.search).substring(1, this.location.search.length).split("&");
        i = 0;
        while (i < arrSource.length && !isFound) {
            if (arrSource[i].indexOf("=") > 0) {
                if (arrSource[i].split("=")[0].toLowerCase() == paramName.toLowerCase()) {
                    paramValue = arrSource[i].split("=")[1];
                    isFound = true;
                }
            }
            i++;
        }
    }
    return paramValue;
}