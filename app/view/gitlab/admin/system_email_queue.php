<!DOCTYPE html>
<html class="" lang="en">
<head  >

    <? require_once VIEW_PATH.'gitlab/common/header/include.php';?>
    <script src="<?=ROOT_URL?>dev/js/admin/setting.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=ROOT_URL?>dev/lib/handlebars-v4.0.10.js" type="text/javascript" charset="utf-8"></script>

</head>
<body class="" data-group="" data-page="projects:issues:index" data-project="xphp">
<? require_once VIEW_PATH.'gitlab/common/body/script.php';?>
<header class="navbar navbar-gitlab with-horizontal-nav">
    <a class="sr-only gl-accessibility" href="#content-body" tabindex="1">Skip to content</a>
    <div class="container-fluid">
        <? require_once VIEW_PATH.'gitlab/common/body/header-content.php';?>
    </div>
</header>
<script>
    var findFileURL = "/ismond/xphp/find_file/master";
</script>
<div class="page-with-sidebar">
    <? require_once VIEW_PATH.'gitlab/admin/common-page-nav-admin.php';?>


    <div class="content-wrapper page-with-layout-nav page-with-sub-nav">
        <div class="alert-wrapper">

            <div class="flash-container flash-container-page">
            </div>

        </div>
        <div class="container-fluid ">

            <div class="content" id="content-body">

                <?php include VIEW_PATH.'gitlab/admin/common_system_left_nav.php';?>

                <div class="row prepend-top-default"  style="margin-left:160px;">

                    <div class="panel  ">
                        <div class="panel-heading">
                        <strong> 邮件队列</strong><span>显示正在发送的或已经错误的邮件队列</span>


                    </div>

                        <div class="top-area">
                            <ul class="nav-links issues-state-filters">
                                <li class="active">
                                    <a id="state-opened" title="Filter by issues that are currently opened." href="javascript:search('')"><span> 全部 </span>

                                    </a>
                                </li>
                                <li class="">
                                    <a id="state-all" title="Filter by issues that are currently closed." href="javascript:search('pending')"><span>执行中 </span>

                                    </a></li>
                                <li class="">
                                    <a id="state-all" title="Show all issues." href="javascript:search('done')"><span>完成</span>
                                        </a>
                                </li>
                                <li class="">
                                    <a id="state-all" title="Show all issues." href="javascript:search('error')"><span>错误</span>
                                          </a>
                                </li>
                            </ul>
                            <div class="nav-controls row-fixed-content">

                                <a id="btn-clear"  class="btn btn" title="清空错误队列" href="#"><i class="fa fa-remove"></i>&nbsp;清空错误队列</a>

                            </div>

                        </div>

                        <div class="content-list pipelines">

                            <div class="table-holder">
                                <table class="table ci-table">
                                    <thead>
                                    <tr>
                                        <th class="js-pipeline-info pipeline-info">主题</th>
                                        <th class="js-pipeline-commit pipeline-commit">收件人</th>
                                        <th class="js-pipeline-date pipeline-date">创建时间</th>
                                        <th class="js-pipeline-info pipeline-info">错误信息</th>
                                        <th class="js-pipeline-date pipeline-date">状态</th>
                                        <th >动作</th>
                                    </tr>
                                    </thead>
                                    <tbody id="data_id">

                                    </tbody>
                                </table>
                            </div>
                            <div class="gl-pagination" pagenum="1" count="0" >
                                <form class="form-inline">
                                    <div class="form-group">
                                        <span class="page_size_view"></span>条/页 当前第<span class="current_page_view"></span>页/共<span class="page_total_view"></span>页
                                    </div>
                                    <div class="form-group">
                                        <ul class="pagination clearfix" id="ajax_page_id">
                                        </ul>
                                    </div>
                                    <div class="form-group">
                                        <span>跳转至</span><input type="text" value="" style="width: 40px" class="page-num form-control" name="page_go_num" id="page_go_num">页
                                    </div>
                                    <button type="submit" class="btn btn-gray">GO</button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>




            </div>
            
        </div>
    </div>
</div>

<input type="hidden" id="search_status" value="" name="status">
<script type="text/html"  id="log_tpl">
    {{#queues}}
    <tr class="commit"
        {{#if_eq status 'error'}}
            style="background-color:#ffcccc"
        {{/if_eq}} >
        <td>{{title}}</td>
        <td>{{address}}</td>
        <td class="pipelines-time-ago">{{time_text}}</td>
        <td >{{error}}</td>
        <td >{{status}}</td>
        <td >
            <div class="pull-right btn-group">
                {{#if_eq status 'error'}}
                <!--<a class="btn " ><i class="fa fa-recycle"></i>重新执行</a>-->
                {{/if_eq}}

            </div>
        </td>
    </tr>
    {{/queues}}

</script>


<script type="text/javascript">

    var fetch_url = "/admin/system/mail_queue_fetch";
    $(document).ready(function () {

        getAjaxPage( fetch_url, 1,  "data_id", "ajax_page_id");
    });

    function search( $status ){
        $('#search_status').val($status);
        getAjaxPage( fetch_url, 1, "data_id", "ajax_page_id");
    }

    function getAjaxPage( new_url, page, div_id, page_id ) {
        var fnName = arguments.callee;
        var params = {  page:page, format:'json' , status:$('#search_status').val()};
        $.ajax({
            type: "GET",
            dataType: "json",
            async: true,
            url: new_url,
            data: params ,
            success: function (res) {

                var source = $('#log_tpl').html();
                var template = Handlebars.compile(source);
                var result = template(res.data);

                $('#' + div_id).html(result);
                $('#' + page_id).html(res.data.page_html);
                $('.page_size_view' ).html(res.data.page_size);
                $('.current_page_view' ).html(res.data.current_page);
                $('.page_total_view' ).html(res.data.pages);
                $('#' + page_id + " a").each(function () {
                    $(this).click(function () {
                        fnName(new_url, $(this).attr('page'), div_id, page_id);
                    });
                });
                $('#' + page_id + " input[type='button']").click(function () {
                    fnName(new_url, $('#' + page_id +" input[type='text']").val(), div_id, page_id);
                });
            },
            error: function (res) {
                alert("请求数据错误" + res);
            }
        });
    }

    $("#btn-clear").click(function(){

        var method = 'post';
        var url = '/admin/system/email_queue_error_clear';
        var params = {}
        $.ajax({
            type: method,
            dataType: "json",
            async: true,
            url: url,
            data: params ,
            success: function (resp) {
                alert(resp.msg );
                window.location.reload();
            },
            error: function (resp) {
                alert("请求数据错误" + resp);
            }
        });

    });


</script>



</body>
</html>


</div>