<?php /*a:1:{s:81:"E:\Code\Git-tp6\ThinkPHP6-Shopping_Project\tp\app\admin\view\category\dialog.html";i:1610346598;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>选择商品分类</title>
    <link rel="stylesheet" href="/static/admin/lib/layui-v2.5.4/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/admin/css/public.css" media="all">
    <style>
        body {
            background: #ffffff;
        }

        .layui-inline {
            border: 1px solid #eee;
            width: 140px;
            height: 330px;
            margin-right: 12px;
            padding: 10px;
            line-height: 1.9;
        }

        .layui-inline ul li {
            cursor: pointer;
        }

        .active {
            color: #009688;
        }
    </style>
</head>
<body>
<div class="layui-inline">
    <div class="layui-input-inline">
        <h5>一级分类</h5>
        <ul class="p-0" type="0">
        </ul>
    </div>
</div>

<div class="layui-inline">
    <div class="layui-input-inline">

        <h5>二级分类</h5>
        <ul class="p-1" type="1">
        </ul>
    </div>
</div>
<div class="layui-inline">
    <div class="layui-input-inline">
        <h5>三级分类</h5>
        <ul class="p-2" type="2">
        </ul>
    </div>
</div>
<div class="layui-input-block"></div>
<button class="layui-btn classify-btn" style="margin-left:300px;">确 定</button>

<script src="/static/admin/lib/layui-v2.5.4/layui.js" charset="utf-8"></script>
<script src="/static/admin/lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script src="/static/admin/js/common.js" charset="utf-8"></script>
<script>

    let moke = <?php echo $category; ?>;

    layui.use(['form'], function () {
        function queryClassif() { // 请求分类 后端接口
            let html = ''
            moke.forEach(function (item) {
                html += '<li class="child-ele"  data-id="' + item.id + '" pid="' + item.pid + '">' + item.name + '</li>'
            })

            $('.p-0').html(html)

            // });
        }

        queryClassif(); // 获取后端分类数据


        $('body').on('click', '.child-ele', function () {
            let pid = $(this).attr('data-id') ; // 这个地方需要 修改 pid => data-id  by singwa
            let type = $(this).parent().attr('type');
            let pcls = '.p-' + (1 + parseInt(type));

            $(this).parent().children('li').removeClass('active');
            $(this).addClass('active');
            if (pcls === '.p-3') {  //第三级分类  就避免在请求接口
                return false
            }
            $(".p-3").html("");
            $(".p-2").html("");

            let url = '/admin/category/getByPid?pid=' + pid

            // 封装的ajax
            layObj.get(url, function (res) {
                moke = res.result;
                console.log(moke);
                //alert($.parseJSON(moke));
                let html = ''
                moke.forEach(function (item) {
                    html += '<li class="child-ele" data-id="' + item.id + '"  pid="' + item.pid + '">' + item.name + '</li>'
                })
                console.log(pcls, 'pcls')
                $(pcls).html(html)
            });

        })
        $('html').on('click', '.classify-btn', function () {
            let p0 = $('.p-0 .active'),
                p1 = $('.p-1 .active'),
                p2 = $('.p-2 .active');

            let p0_name = p0.text(),
                p1_name = p1.text(),
                p2_name = p2.text();

            let p0_id = p0.attr('data-id'),
                p1_id = p1.attr('data-id'),
                p2_id = p2.attr('data-id');


            parent.$('#goods_cate').val(`${p0_name}->${p1_name}->${p2_name}`)
            parent.$('#goods_cate').attr('data-ids', `${p0_id},${p1_id},${p2_id}`)
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        })
    })
</script>
</body>
</html>
