<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>查询系统</title>
    <link rel="stylesheet" href="/Public/frame/layui/css/layui.css">
    <link rel="stylesheet" href="/Public/frame/static/css/style.css">
    <!-- <link rel="stylesheet" href="/Public/frame/static/css/xtree.css"> -->
    <link rel="icon" href="/Public/frame/static/image/code.png">
    <script type="text/javascript" src="/Public/frame/layui/layui.js"></script>
    <script type="text/javascript" src="/Public/frame/static/js/vip_comm.js"></script>

	<script type="text/javascript" src="/Public/frame/static/js/layui-xtree.js"></script>
</head>
<body>
<div class="layui-layout layui-layout-admin">
    <!-- header -->
    <div class="layui-header my-header">
        <div class="my-header-logo">查询系统后台</div>
        <div class="my-header-btn">
            <button class="layui-btn layui-btn-small btn-nav"><i class="layui-icon">&#xe65f;</i></button>
        </div>
        <!-- 顶部右侧添加选项卡监听 -->
        <ul class="layui-nav my-header-user-nav" lay-filter="side-top-right">
            <li class="layui-nav-item">
                <a class="name" href="javascript:;">欢迎您,<b><font size="20"><?php echo ($_SESSION['nickname']); ?></font></b></a>
                <dl class="layui-nav-child">
                    <dd><a href="<?php echo U('Login/logout');?>"><i class="layui-icon">&#x1006;</i>退出</a></dd>
                </dl>
            </li>
        </ul>
    </div>
    <!-- side -->
    <div class="layui-side my-side">
        <div class="layui-side-scroll">
            <!-- 左侧主菜单添加选项卡监听 -->
            <ul class="layui-nav layui-nav-tree" lay-filter="side-main">
                <li class="layui-nav-item layui-nav-itemed"> <!-- 去除 layui-nav-itemed 即可关闭展开 -->
                    <a href="javascript:;"><i class="layui-icon">&#xe620;</i>功能项</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" href-url="<?php echo U('Tables/main');?>"><i class="layui-icon">&#xe621;</i>二维码查看</a></dd>
                        <dd><a href="javascript:;" href-url="<?php echo U('Tables/add_table');?>"><i class="layui-icon">&#xe621;</i>表格类型添加</a></dd>
                        <dd><a href="javascript:;" href-url="<?php echo U('Tables/add_pic');?>"><i class="layui-icon">&#xe621;</i>图片类型添加</a></dd>
                    </dl>
                </li>
                <!--<li class="layui-nav-item layui-nav-itemed">-->
                    <!--<a href="javascript:;"><i class="layui-icon">&#xe628;</i>设置中心</a>-->
                    <!--<dl class="layui-nav-child">-->
                        <!--<dd><a href="javascript:;" href-url="<?php echo U('Company/main');?>"><i class="layui-icon">&#xe621;</i>上报单位管理</a></dd>-->
                    <!--</dl>-->
                <!--</li>-->
                <!--<li class="layui-nav-item layui-nav-itemed">-->
                    <!--<a href="javascript:;"><i class="layui-icon">&#xe628;</i>用户中心</a>-->
                    <!--<dl class="layui-nav-child">-->
                        <!--<?php if($_SESSION['username'] == 'Administrator'): ?>-->
                            <!--<dd><a href="javascript:;" href-url="<?php echo U('User/Main');?>"><i class="layui-icon">&#xe621;</i>用户信息管理</a></dd>-->
                            <!--<dd><a href="javascript:;" href-url="<?php echo U('User/edit',array('username'=>$_SESSION['username']));?>"><i class="layui-icon">&#xe621;</i>修改信息</a></dd>-->
                        <!--<?php else: ?>-->
                            <!--<dd><a href="javascript:;" href-url="<?php echo U('User/edit',array('username'=>$_SESSION['username']));?>"><i class="layui-icon">&#xe621;</i>修改信息</a></dd>-->
                        <!--<?php endif; ?>-->
                    <!--</dl>-->
                <!--</li>-->
            </ul>
        </div>
    </div>
    <!-- body -->
    <div class="layui-body my-body">
        <div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="1"><span><i class="layui-icon">&#xe638;</i>欢迎页</span></li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe id="iframe" src="<?php echo U('Index/welcome');?>" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 右键菜单 -->
<div class="my-dblclick-box none">
    <table class="layui-tab dblclick-tab">
        <tr class="card-refresh">
            <td><i class="layui-icon">&#x1002;</i>刷新当前标签</td>
        </tr>
        <tr class="card-close">
            <td><i class="layui-icon">&#x1006;</i>关闭当前标签</td>
        </tr>
        <tr class="card-close-all">
            <td><i class="layui-icon">&#x1006;</i>关闭所有标签</td>
        </tr>
    </table>
</div>
</body>
</html>