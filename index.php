<?php
session_start();
//require "common.php";
//if (!isUserLegal() && !isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="container">
    <div class="main">
        <div class="title">
            <h4>主页</h4>
            <p>Articles</p>
        </div>
        <ul>
            <li class="one">
                <a href="#">
                    <img src="images/new-cont.jpg" />
                    <div class="news-title">
                        <h5>查看更多文章</h5>
                        <p>More articles</p>
                        <i class="icon-news"></i>
                    </div>
                </a>
            </li>
            <li class="two">
                <a href="#">
                    <div class="top">

                        <h5>微信支付HTTPS服务器更换证书对使用微信支付用户的影响解疑</h5>
                        <div class="p">
                            <p>2018年3月8日，微信支付商户平台发布公告：微信支付HTTPS服务器计划于2018年5月29日更换服务器SSL证书，为避免下单、退款等功能无法使用，微信支付要求商户平台开发人员尽快验证商户服务器是否</p>
                        </div>
                        <img src="images/new-jiantou.jpg">
                    </div>
                    <div class="bottom">
                        <h3>23</h3>
                        <span>2018.03</span>

                    </div>
                </a>
                <a href="#">
                    <div class="top">

                        <h5>商创网络荣获国家高新技术企业认证</h5>
                        <div class="p">
                            <p>近日，上海商创网络科技有限公司通过了由上海市科学技术委员会、上海市财政局和上海市国家税务局组织的国家高新技术企业认定，并荣获国家“高新技术企业”称号。据悉，此次国家级、市级高新技术企业认定是根据科技部..</p>
                        </div>
                        <img src="images/new-jiantou.jpg">
                    </div>
                    <div class="bottom">
                        <h3>21</h3>
                        <span>2018.03</span>

                    </div>
                </a>
            </li>
            <li class="three">
                <a href="#">
                    <div class="left">
                        <h3>27</h3>
                        <span>2017.05</span>
                    </div>
                    <div class="right">
                        <h5>相信你一定也知道，小程序又双叒叕升级了！</h5>
                        <img src="images/new-jiantou.jpg" />
                    </div>
                </a>
                <a href="#">
                    <div class="left">
                        <h3>31</h3>
                        <span>2017.01</span>
                    </div>
                    <div class="right">
                        <h5>微信小程序已陆续推出13项新能力，商家care吗？</h5>
                        <img src="images/new-jiantou.jpg" />
                    </div>
                </a>
                <a href="#">
                    <div class="left">
                        <h3>28</h3>
                        <span>2017.08</span>
                    </div>
                    <div class="right">
                        <h5>微信也着陆了，新零售时代真的要来了？</h5>
                        <img src="images/new-jiantou.jpg" />
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
</body>
</html>