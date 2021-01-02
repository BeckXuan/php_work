$(document).ready(function () {
    $("#main-container").height($(window).height() - 76);
    $("#iframe").height($(window).height() - 140);

    $(".sidebar").height($(window).height() - 99);
    let thisHeight = $("#nav_list").height($(window).outerHeight() - 173);
    $(".submenu").height();
    $("#nav_list").children(".submenu").css("height", thisHeight);

    //当文档窗口发生改变时 触发
    $(window).resize(function () {
        $("#main-container").height($(window).height() - 76);
        $("#iframe").height($(window).height() - 140);
        $(".sidebar").height($(window).height() - 99);

        let thisHeight = $("#nav_list").height($(window).outerHeight() - 173);
        $(".submenu").height();
        $("#nav_list").children(".submenu").css("height", thisHeight);
    });
    $(".iframeurl").click(function () {
        let cid = $(this).attr("name");
        let cname = $(this).attr("title");
        $("#iframe").attr("src", cid).ready();
        $("#Bcrumbs").attr("href", cid).ready();
        $(".Current_page a").attr('href', cid).ready();
        $(".Current_page").attr('name', cid);
        $(".Current_page").html(cname).css({"color": "#333333", "cursor": "default"}).ready();
        $("#parentIframe").html('<span class="parentIframe iframeurl"> </span>').css("display", "none").ready();
        $("#parentIfour").html('').css("display", "none").ready();
    });
});
$(document).ready(function () {
    $('#nav_list').find('li.home').click(function () {
        $('#nav_list').find('li.home').removeClass('active');
        $(this).addClass('active');
    });

    //时间设置
    function currentTime() {
        let d = new Date(), str = '';
        str += d.getFullYear() + '年';
        str += d.getMonth() + 1 + '月';
        str += d.getDate() + '日';
        str += d.getHours() + '时';
        str += d.getMinutes() + '分';
        str += d.getSeconds() + '秒';
        return str;
    }

    setInterval(function () {
        $('#time').html(currentTime)
    }, 1000);
    $('#Exit_system').on('click', function () {
        layer.confirm('是否确定退出系统？', {
                btn: ['是', '否'],//按钮
                icon: 0,
            },
            function () {
                location.href = "../logout.php";
            });
    });
})