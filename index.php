<!-- jquery mobile 教程：http://www.runoob.com/jquerymobile/jquerymobile-tutorial.html -->
<!DOCTYPE html>
<?php
session_start();

// 开始记录当前用户发送的表白数，在Seesion的生命周期内，最多限制3条。
if ( !isset($_SESSION['posts']) ) {
    $_SESSION['posts'] = 1;
}
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>江西农业大学表白墙</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.4.5/jquery.mobile.min.css" />
    <link rel="stylesheet" href="css/homepage.css" media="screen" title="no title">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" media="screen" />
</head>

<body>
<!-- 左侧拉栏 -->
<div data-role="panel" id="myPanel" data-display="overlay" data-position-fixed="true">
    <h3>选择排序方式</h3>
    <input id="new-posts" type="button" value="最新表白（默认）">
    <input id="old-posts" type="button" value="最早表白">
    <input id="most-liked" type="button" value="点赞数最多">
    <input id="less-liked" type="button" value="点赞数最少">
    <input id="random-posts" type="button" value="随机显示">
    <img src="images/icon/logo.png" alt="logo" height="60px"/>
</div>

<!-- 页头的信息 -->
<div id="Header" class="Header" data-role="header">
    <img src="images/logo.png" class="Header-logo" width="100%" alt=""/>
    <h1 align="center">粉色的农大表白墙</h1> 
</div>

<!-- 网站主体 -->
<div class="main-body" id="main" data-role="content">
    <!-- 这里是表白的心跳载入logo，当表白获取成功就会覆盖这里 -->
    <img src="images/icon/heart.gif" alt="" class="loading"/>
</div>
<p style="text-align:center;color:#9e9e9e;font-size:12px;">
    蓝色下划线：男生 / 红色下划线：女生 / 黑色下划线：保密
</p>
<p id="pageNum" style="text-align:center;color:#9e9e9e;font-size:12px;">

</p>
<div id="pages" data-role="footer" style="text-align:center;margin-bottom:56px;" page="1" mode="1" max="0">
    <div data-role="controlgroup" data-type="horizontal">
        <a href="#" id="previous" class="ui-btn ui-corner-all ui-shadow ui-icon-arrow-l ui-btn-icon-notext">上一页</a>
        <a href="#myPanel" id="" class="ui-btn ui-corner-all ui-shadow ui-icon-bars ui-btn-icon-notext">删除</a>
        <a href="#" id="returnTop" class="ui-btn  ui-corner-all ui-icon-carat-u ui-shadow ui-btn-icon-notext">返回顶部</a>
        <a href="#" id="next" class="ui-btn ui-corner-all ui-shadow ui-icon-arrow-r ui-btn-icon-notext">下一页</a>
    </div>
</div>
<div data-role="footer" id="footer" data-position="fixed" data-fullscreen="true" data-tap-toggle="false">
    <div class="" data-role="navbar">
        <ul>
            <li><a href="index.php" data-ajax='false' data-role="button" data-icon="articleNow" class="ui-icon-article" data-iconpos="notext">首页</a></li>
            <li><a href="saylove.html" data-ajax='false' data-transition="slide" data-role="button" data-icon="heart" class="ui-icon-heart" data-iconpos="notext">表白</a></li>
            <li><a href="search.html" data-ajax='false' data-role="button" data-icon="search" class="ui-icon-search" data-iconpos="notext">搜索</a></li>
            <li><a href="help.html" data-ajax='false' data-role="button" data-icon="help" class="ui-icon-help" data-iconpos="notext">帮助</a></li>
        </ul>
    </div>
</div>
<div data-role="popup" class="ui-content" data-overlay-theme="b" id="guess-Name-Popup">
    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">关闭</a>
    <h4>猜名字</h4>
    <p>
        已猜中 <span id="guess_right"></span> 次, 被猜 <span id="guess_all"></span> 次.
    </p>
    <p class="guess-hint">
        猜名字游戏介绍请点击查看：<a style="color:#333;" href="help.html">帮助</a>
    </p>
    <div class="ui-field-contain">
        <label for="guess-input">猜一猜发起者的名字：</label>
        <input type="search" name="search" id="guess-input" placeholder="名字">
    </div>
    <input id="guess-submit" style="text-align:center;display:block;margin:0 auto;" type="submit" data-inline="true" value="猜！">
    <span id="guess-hint"></span>
</div>

<div data-role="popup" class="ui-content" data-overlay-theme="b" id="comment-Popup">
    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">关闭</a>
    <h4>评论列表</h4>
    <div class="" id="comment-lists">
        <ul id="comment-lists-ul">
            <li style="visibility: hidden;">
                <span class="comment-floor">2楼</span>
                <span class="comment-ip">192.168.1.***</span>
                <span class="comment-time">2016/11/7 18:00:56</span>
                <p>占位占位占位占位占位占位占位占位占位占位占位</p>
            </li>
        </ul>
    </div>
    <div class="ui-field-contain">
        <label for="guess-input">评论：</label>
        <input type="search" name="search" id="guess-input" placeholder="我想说...">
    </div>
    <input id="comment-submit" style="text-align:center;display:block;margin:0 auto;" type="submit" data-inline="true" value="评论">
    <span id="comment-hint"></span>
</div>


<div data-role="popup" class="ui-content" data-overlay-theme="b" id="share-Popup">
    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">关闭</a>
    <h4>链接分享</h4>
    <h5>复制链接给朋友或者点击打开链接</h5>
    <div id="link">
        <a href=""></a>
    </div>
</div>

<div class="" style="display:none;">
    <script src="https://s11.cnzz.com/z_stat.php?id=1260775797&web_id=1260775797" language="JavaScript"></script>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mobile/1.4.5/jquery.mobile.min.js"></script>
<script src="js/display.js" charset="utf-8"></script>
<script src="js/layer_mobile/layer.js"></script>
<!-- 这里是下雪的插件，取消注释自动调用 -->
<script >
  //♥
(()=>{let h=10;let d=(n)=>Math.floor(Math.random()*n);let k=(c)=>{let s=document.createElement('style');if(!!(window.attachEvent && !window.opera)){s.styleSheet.cssText=c;}else{s.appendChild(document.createTextNode(c));}document.getElementsByTagName('head')[0].appendChild(s);};k('@keyframes u{0%{transform:rotate(0deg);}25%{transform:rotate(10deg);}50%{transform:rotate(0deg);}75%{transform:rotate(-10deg);}100%{transform:rotate(0deg);}};');k('@keyframes m{0%{margin-top:2vh;opacity:0;}20%{opacity:1.0;margin-top:0vh;margin-left:0vw;transform:rotate('+d(90)+'deg);}100%{opacity:0.4;margin-top:100vh;margin-left:'+d(4)+'vw;transform:rotate(1080deg);}};');let w=document.createElement('div');w.id='daWorld';w.style='animation:u 60s ease-in infinite;position:fixed;top:0;right:0;bottom:0;left:0;pointer-events:none;';document.body.appendChild(w);while(h--){let o=document.createElement('div');o.style=`pointer-events:none;opacity:0;animation:m ${d(14)+6}s ease-in ${d(4000)}ms infinite;z-index:1000;position:fixed;top:${d(40)}vh;left:${d(100)}vw;font-size:${d(20)+5}px;color:${['#d00','#e66','#fcc'][d(3)]};`;o.innerHTML=['♡','♡'][d(2)];w.appendChild(o);}})()
</script>
</body>

</html>
