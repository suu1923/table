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
<style>
    .thumb {margin-left:5px; margin-top:30px; height:600px}
    #prevModal {width:100%; height:100%; text-align:center; display:none;}
    #img_prev {max-width:98%; max-height:98%; margin: 10px auto}
    .file {
        position: relative;
        display: inline-block;
        background: #159587;
        /*border: 1px solid #99D3F5;*/
        border-radius: 2px;
        padding: 4px 12px;
        overflow: hidden;
        color: #fff;
        text-decoration: none;
        text-indent: 0;
        line-height: 20px;
    }
    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }
    .file:hover {
        background: #44aa9f;
        border-color: #78C3F3;
        color: #fff;
        text-decoration: none;
    }
</style>

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>图片类型添加</legend>
</fieldset>

<div class="layui-tab">
    <div class="layui-tab-content">
        <!-- 添加 -->
        <div class="layui-tab-item layui-show">
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">文件名：</label>
                    <div class="layui-input-block">
                        <input class="layui-input" required  lay-verify='required'  placeholder="输入文件名，方便以后查找" type="text" name="fileName"></td>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">内容：</label>
                    <div class='layui-container' style='margin-top:15px;margin-left: 0px'>
                        <a href="javascript:;" class="file">选择图片
                            <input id="uploadInput" type="file"  name="files" />
                        </a>
                        <div class='layui-input-block' id='div_prev' title=''></div>
                    </div>
                    <div id="imgs">
                    </div>
                    <div id='prevModal'>
                        <img id='img_prev'/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="add">
                            立即提交
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    //获取文件地址，显示预览用
    var getObjectURL = function(file) {
        var url = null;
        if (window.createObjectURL != undefined) { // basic
            url = window.createObjectURL(file);
        } else if (window.URL != undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file);
        } else if (window.webkitURL != undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file);
        }
        return url;
    };

    //图片过滤
    var imgFilter = function(files) {
        var a = true;
        for (var i = 0,file; file = files[i]; i++) {
            if (file.type.indexOf("image") == 0) {
                if (file.size >= 512000*4) {
                    layer.msg('您这张"' + file.name + '"图片大小过大，应小于2M，请重新上传',{icon:2,time:4000});
                    a = false;
                }
            } else {
                layer.msg('文件"' + file.name + '"不是图片。请重新上传',{icon:2,time:4000});
                a = false;
            }
        }
        return a;
    }

    layui.use(['table','vip_table', 'layer','element','form'],function() {
        var table = layui.table,
            layer = layui.layer,
            $ = layui.jquery,
            form = layui.form;
        //  ---------------------------------------------------------------------
        //普通图片上传
        $("#uploadInput").on("change",
            function(obj) {
                var files = obj.target.files || obj.dataTransfer.files; //js获取所有文件
                if (imgFilter(files) == false) {
                    return flase;
                }
                //判断上传的图片跟页面上的图片，如果已经上传了，不需要重新上传
                var imgList = $("img[data-flag='flag']"); //获取所有的img
                $.each(files,
                    function(i, item) {
                        // console.log(files);
                        var objUrl = getObjectURL(item);
                        var a = true;
                        if (imgList.length > 0) {
                            $.each(imgList,
                                function(j, val) {
                                    var fileName = $(val).data("img").name;
                                    var fileSize = $(val).data("img").size;
                                    if (fileName == item.name && fileSize == item.size) {
                                        a = false;
                                    }
                                });
                        }
                        if (a) {
                            var html = "";
                            html += "<div style='display:inline'>";
                            html += "<img id='col-img' class='thumb' data-flag='flag' src='" + objUrl + "' ondblclick='$(this).remove()'>";
                            html += "</div>";
                            $("#div_prev").append(html);
                            var img = $("#div_prev>div").last().children("img"); //获取新生成的img标签
                            img.data("img", item); //将file放到img当中
                        }

                    })
                $("#uploadInput").val(""); //清空刚才选中的图片，bug：选中一张，如果重新选择，不执行change方法， 所以清空file，就会执行change方法
            });
        // 提交
        form.on('submit(add)',function(data) {
            /**
             * 1. 获取到thead内的全部input元素
             * 3. 组合 1:1,2:1-2-3,3:3-1-3-3;
             */
            var formdata = new FormData();
            var imgList = $("img[data-flag='flag']")
            $.each(imgList,
                function(j, value) { //添加图片
                    formdata.append("file", $(value).data("img"));
                });
            formdata.append("file_name",$("input[name='fileName']").val())
            $.ajax({
                type : "POST",
                url : "<?php echo U('Tables/add_pic');?>",
                dataType : 'json',
                data : formdata,
                processData: false,
                contentType: false,
                success : function(res){
                    console.log(res);
                    if(res.errno == 0){
                        layer.msg("添加成功",{icon:1,time:2000},function(){
                            // 刷新
                            window.location.reload();

                        });
                    }else{
                        layer.msg(res.errmsg,{icon:2,time:4000});
                    }
                },
                error : function(res){
                    layer.msg("提交异常,请重试");
                }
            });

            return false;
        });

    });
</script>
</body>
</html>