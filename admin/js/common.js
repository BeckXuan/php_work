function _substr(str, n) {//字符串截取 包含对中文处理
    if (str.replace(/[\u4e00-\u9fa5]/g, "**").length <= n) {
        return str;
    } else {
        let len = 0;
        let tmpStr = "";
        for (let i = 0; i < str.length; i++) {//遍历字符串
            if (/[\u4e00-\u9fa5]/.test(str[i])) {//中文 长度为两字节
                len += 2;
            } else {
                len += 1;
            }
            if (len > n) {
                break;
            } else {
                tmpStr += str[i];
            }
        }
        return tmpStr + " ...";
    }
}

function _request(url, data, success, error) {
    let xhr = new XMLHttpRequest()
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert('浏览器不支持XMLHttpRequest！')
        return
    }
    xhr.onload = function () {
        let status = xhr.status
        if (status === 200) {
            //success
            success(xhr)
        } else if (status === 406) {
            //error
            error(xhr)
        } else if (status === 401) {
            //Unauthorized
            window.location.href = 'login.php'
        } else {
            //fail
            layer.msg(status + '未知错误！', {icon: 2, time: 3000})
        }
    }
    xhr.timeout = 2000;
    xhr.ontimeout = function () {
        layer.msg('请求服务器超时！', {icon: 2, time: 3000})
    }
    xhr.open("POST", url, true)
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
    xhr.send(data)
}