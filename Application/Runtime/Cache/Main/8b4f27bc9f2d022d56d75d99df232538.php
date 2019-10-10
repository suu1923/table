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
    .thumb {margin-left:5px; margin-top:30px; height:128px}
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
    <legend>表格类型添加</legend>
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
                    <label class="layui-form-label">表头：</label>
                    <!-- ToDo... 上传图片-->
                    <div class='layui-container' style='margin-top:15px;margin-left: 0px'>
                        <a href="javascript:;" class="file">选择文件
                            <input id="uploadInput" type="file"  name="files" />
                        </a>
                        <div class='layui-input-block' id='div_prev' title=''></div>
                    </div>
                    <div id="imgs">
                    </div>
                    <div id='prevModal'>
                        <img id='img_prev'/>
                    </div>
                    <!-- ToDo... 上传图片-->
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">内容：</label>
                    <div class="layui-input-block">
                        <table class="layui-table">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                                <col width="50">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody class="form-content">
                            <tr class="form-unit f-input-1" smallCount=1 >
                                <td><input class="layui-input l-input-1" type="text"  required  lay-verify='required' placeholder='在此输入...' value=""></td>
                                <td class="small-td">
                                    <!-- TODO... Add Small -->

                                    <!--<div class='small-text'>-->
                                    <!--<div class='layui-input-inline' style='width: 80%;'>-->
                                    <!--<input type='text' name='r-input"' required lay-verify='required' placeholder='在此输入...' value='' class='layui-input'>-->
                                    <!--</div>-->
                                    <!--<div class='layui-form-mid small-del layui-word-aux' style='cursor: pointer'>删除</div>-->
                                    <!--</div>-->
                                    <!-- TODO... Add Small -->

                                    <div class="layui-input-inline">
                                        <i class="layui-icon add-small"  style="font-size: 15px; color: GREEN; cursor: pointer;line-height: 33px;">点击添加行&#xe654</i>
                                    </div>
                                </td>
                                <td style='cursor: pointer' class='big-del'>
                                    删除
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="add-big">
                                        <i class="layui-icon" style="font-size: 20px; color: GREEN; cursor: pointer;">点击添加行&#xe654</i>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">底部信息</label>
                    <div class="layui-input-block">
                        <input type="text" name="table_footer"  placeholder="底部信息，例如：此版权所属XX，可忽略不写"
                               autocomplete="off" class="layui-input userName">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="add">
                            立即提交
                        </button>
                        <!--<button type="reset" class="layui-btn layui-btn-primary">-->
                        <!--重置-->
                        <!--</button>-->
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
                    layer.msg('您这张"' + file.name + '"图片大小过大，应小于2M，请重新上传',{icon:config().layerMsg.icon.error});
                    a = false;
                }
            } else {
                layer.msg('文件"' + file.name + '"不是图片。请重新上传',{icon:config().layerMsg.icon.error});
                a = false;
            }
        }
        return a;
    }

    layui.use(['table','vip_table', 'layer','element','form'],function() {
        var table = layui.table,
            vipTable = layui.vip_table,
            layer = layui.layer,
            $ = layui.jquery,
            form = layui.form;
        //  ---------------------------------------------------------------------
        bigCount = 1; // 外部输入框数量
        var smallTextDiv = "<div class='small-text'>\n" +
            "<div class='layui-input-inline' style='width: 80%;'>\n" +
            "<input type='text' name='r-input' required lay-verify='required'  placeholder='在此输入...' value=''\n" +
            "class='layui-input'>\n" +
            "</div>\n" +
            "<div class='layui-form-mid small-del layui-word-aux' style='cursor: pointer'>删除</div>\n" +
            "</div>";

        $(".small-td").prepend(smallTextDiv)

        // 行内添加动作
        $(document).on("click",".add-small",function () {
            // 找到该单位的已有的单位数量
            var smallDiv = $(this).parent().parent().parent();
            var smallCount = smallDiv.attr("smallCount");
            // console.log(smallCount)
            if (smallCount >=10 ){
                layer.msg("最多创建10个",{icon:2,time:4000});
                return false;
            }
            var textDiv = "<div class='small-text'>\n" +
                "                                        <div class='layui-input-inline' style='width: 80%;'>\n" +
                "                                            <input type='text' name='r-input' required lay-verify='required' placeholder='在此输入...' value=''\n" +
                "                                                   class='layui-input'>\n" +
                "                                        </div>\n" +
                "                                        <div class='layui-form-mid small-del layui-word-aux' style='cursor: pointer'>删除</div>\n" +
                "                                    </div>";
            smallDiv.attr("smallCount",parseInt(smallCount)+1)
            $(this).parent().before(textDiv)
        })
        // 行外添加动作
        $(".add-big").click(function () {
            var bigTextDiv = "<tr class='form-unit f-input-"+(parseInt(bigCount)+1)+"' smallCount=1>\n" +
                "                                <td><input class='layui-input l-input-"+(parseInt(bigCount)+1)+"'  type='text' required value='' lay-verify='required' placeholder='在此输入...'></td>\n" +
                "                                <td class='small-td'>\n" +smallTextDiv+
                "                                    <div class='layui-input-inline'>\n" +
                "                                        <i class='layui-icon add-small'  style='font-size: 15px; color: GREEN; cursor: pointer;line-height: 33px;'>点击添加行&#xe654</i>\n" +
                "                                    </div>\n" +
                "                                </td>\n" +
                "                                <td style='cursor: pointer' class='big-del'>\n" +
                "                                    删除\n" +
                "                                </td>\n" +
                "                            </tr>";
            $(".form-content").children(":last").before(bigTextDiv)
            bigCount+=1;
            console.log("总行数："+bigCount)
        })
        // 外层删除
        $(document).on("click",".big-del",function () {
            if (bigCount ==1 ){
                layer.msg("最少需要留一个单元",{icon:2,time:4000});
                return false;
            }
            $(this).parent(".form-unit").remove();
            bigCount -= 1;
            console.log(bigCount);
        })
        // 内层删除
        $(document).on("click",".small-del",function (e) {
            // console.log(e);
            var smallDiv = $(this).parents("tr");
            var smallCount = smallDiv.attr("smallCount");
            // 判断不能低于一个
            if (smallCount == 1 ){
                layer.msg("最少需要留一个单元",{icon:2,time:4000});
                return false;
            }
            // 删除当前
            $(this).parent(".small-text").remove();
            smallDiv.attr("smallCount",parseInt(smallCount)-1)
            // console.log(smallCount)
        });
        /*table.render({
            elem: '#inforData',
            height: vipTable.getFullHeight(),
            cellMinWidth: 50,
            // size: 'sm', //小尺寸的表格
            cols: [[
                {
                    field: 'id',
                    title: 'ID',
                    width:50
                },
                {
                    field: 'username',
                    title: '用户名',
                    align: 'left',
                    unresize:true,

                },
                {
                    field: 'nickname',
                    title: '姓名',
                    align: 'left',
                    unresize:true,

                },
                {
                    field: 'lasttime',
                    title: '最后登录时间',
                    align: 'left',
                    unresize:true,

                },
                {
                    field: 'lastip',
                    title: '最后登录IP',
                    align: 'center',
                    unresize:true,

                },
                {
                    fixed: 'right',
                    title: '操作',
                    align: 'center',
                    toolbar: '#opFunc',
                    unresize:true,
                },
            ]],
            url: '<?php echo U("User/getUser");?>',
            response:{
                statusName: 'errno',
                statusCode: 0,
                msgName: 'errmsg',
                dataName: 'data'
            },
        });*/
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
        //监听工具条
        table.on('tool(test)',function(obj) {
            var data = obj.data,
                id   = data.id,
                username = data.username;

            if (obj.event === 'del') {
                layer.confirm('真的删除该用户吗?',function(index) {
                    $.ajax({
                        url: '<?php echo U("User/delete");?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {id: id},
                        success: function(res){
                            if(res.errno == 0){
                                // 删除成功后调用
                                obj.del();
                                layer.close(index);
                                layer.msg("删除成功",{icon:1,time:2000});
                            }else{
                                layer.msg(res.errmsg,{icon:2});
                            }
                        },
                        error : function(res){
                            layer.msg("提交异常,请重试");
                        }
                    })
                });
            } else if (obj.event === 'edit') {
                var url = "<?php echo U('User/edit');?>?username="+username;
                window.location.href = url;
            }
        });
        // 验证
        form.verify({
            userName:function(value,userName){
                if(new RegExp("[^\x00-\xff]").test(value)){
                    return "用户名不能输入中文";
                }
                if(value.length>16){
                    return "用户名长度控制在16个字之内";
                }
            },
            nickName:function(value,nickName){
                if(!new RegExp("[^\x00-\xff]").test(value)){
                    return "姓名只能输入中文";
                }
                if(value.length>6){
                    return "姓名长度控制在6个字之内";
                }
            },
            passWord:function(value,passWord){
                if(value.length<6){
                    return "密码不能低于6位数";
                }
            },
        });
        // 提交
        form.on('submit(add)',function(data) {
            /**
             * 1. 获取到thead内的全部input元素
             * 3. 组合 1:1,2:1-2-3,3:3-1-3-3;
             */
            var formdata = new FormData();
            var inputVal = new Array();
            var colNumber = $(".form-unit").length;
            var imgList = $("img[data-flag='flag']")
            $.each(imgList,
                function(j, value) { //添加图片
                    formdata.append("file", $(value).data("img"));
                });

            // 获取动态表格值
            for (var i=1;i<=colNumber;i++){
                // 左边输入框的值
                var leftValue = $(".f-input-"+i).find(".l-input-"+i).val();
                // 右边输入框的值
                var rightValueDom = $(".f-input-"+i).find("input[name='r-input']");
                var rValue = new Array();
                rightValueDom.each(function (i,item) {
                    rightValue = item.value
                    rValue.push(item.value);
                })
                // 添加到大数组中
                inputVal.push(leftValue+":"+rValue.join("-"))
                // 清空该数组
                rValue.length = 0;
            }
            // Add...
            formdata.append("file_name",$("input[name='fileName']").val())
            formdata.append("table_content",inputVal.join(","))
            formdata.append("table_footer",$("input[name='table_footer']").val())
            $.ajax({
                type : "POST",
                url : "<?php echo U('Tables/add_table');?>",
                dataType : 'json',
                data : formdata,
                processData: false,
                contentType: false,
                success : function(res){
                    // console.log(res);
                    if(res.errno == 0){
                        layer.msg("添加成功",{icon:1,time:2000},function(){
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