<!DOCTYPE html>
<html class="" lang="en">
<head  >

    <? require_once VIEW_PATH.'gitlab/common/header/include.php';?>
    <script src="<?=ROOT_URL?>dev/js/admin/group.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=ROOT_URL?>dev/lib/handlebars-v4.0.10.js" type="text/javascript" charset="utf-8"></script>

    <script src="<?=ROOT_URL?>dev/lib/bootstrap-select/js/bootstrap-select.js" type="text/javascript" charset="utf-8"></script>
    <link href="<?=ROOT_URL?>dev/lib/bootstrap-select/css/bootstrap-select.css" rel="stylesheet">

    <script src="<?=ROOT_URL?>dev/lib/bootstrap-paginator/src/bootstrap-paginator.js"  type="text/javascript"></script>

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
        <div class=" ">
            <div class="content" id="content-body">
                <?php include VIEW_PATH.'gitlab/admin/common_user_left_nav.php';?>
                <div class="container-fluid"  style="margin-left: 160px">
                    <div class="top-area">

                        <div class="nav-controls row-fixed-content" style="float: left;margin-left: 0px">
                            <form id="filter_form" action="/admin/user/filter" accept-charset="UTF-8" method="get">

                                <input name="params[page]" id="filter_page" type="hidden" value="1">
                                <input name="params[page_size]" id="filter_page_size" type="hidden" value="20">

                                <input type="text" name="params[name]" id="filter_name" placeholder="组名称"
                                       class="form-control search-text-input input-short" spellcheck="false" value="" />

                                <div class="dropdown inline prepend-left-10" >
                                    <button class="dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="false" >
                                        <span class="light" id="filter_page_size_view" data-title-origin="20"> 20</span>
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-align-right dropdown-menu-sort" style="min-width: 50px">
                                        <li class="filter_page_size"  data-value="20"><a href="#">20</a></li>
                                        <li class="filter_page_size"  data-value="50"><a href="#">50</a></li>
                                        <li class="filter_page_size"  data-value="100"><a href="#">100</a></li>

                                    </ul>
                                </div>

                                <a class="btn btn-gray btn-search " id="btn-group_filter" href="#">
                                     &nbsp; <i class="fa fa-filter"></i> &nbsp;
                                </a>

                                 <a class="btn"  href="#"  class="filter_group_reset" id="btn-group_reset" >
                                     &nbsp;<i class="fa fa-undo"></i> &nbsp;
                                </a>


                            </form>
                        </div>
                        <div class="nav-controls" style="right: ">

                            <div class="project-item-select-holder">

                                <a class="btn btn-new btn_group_add" data-target="#modal-group_add" data-toggle="modal" href="#modal-group_add">
                                    <i class="fa fa-plus"></i>
                                    New group
                                </a>
                            </div>

                        </div>

                    </div>

                    <div class="content-list pipelines">

                            <div class="table-holder">
                                <table class="table ci-table">
                                    <thead>
                                    <tr>
                                        <th class="js-pipeline-info pipeline-info">名称</th>
                                        <th class="js-pipeline-stages pipeline-info">描述</th>
                                        <th class="js-pipeline-stages pipeline-info">用户数</th>
                                        <th class="js-pipeline-date pipeline-date">权限方案</th>
                                        <th   style=" float: right" >操作</th>
                                    </tr>
                                    </thead>
                                    <tbody id="list_render_id">


                                    </tbody>
                                </table>
                            </div>
                            <div class="gl-pagination" id="pagination">

                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="modal-group_add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal" href="#">×</a>
                <h3 class="page-title">新增用户组</h3>
            </div>
            <div class="modal-body">
                <form class="js-quick-submit js-upload-blob-form form-horizontal"  id="form_add" action="/admin/group/add"   accept-charset="UTF-8" method="post">

                    <input type="hidden" name="format" id="format" value="json">
                    <div class="form-group">
                            <label class="control-label" for="id_name">名称:<span style="color: red"> *</span></label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="params[name]" id="id_name"  value="" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id_description">描述:</label>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="params[description]" id="id_description"  value="" />
                                </div>
                            </div>
                        </div>

                    <div class="form-actions">
                        <button name="submit" type="button" class="btn btn-create" id="btn-group_add">保存</button>
                        <a class="btn btn-cancel" data-dismiss="modal" href="#">取消</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-group_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal" href="#">×</a>
                <h3 class="page-title">编辑用户组</h3>
            </div>
            <div class="modal-body">
                <form class="js-quick-submit js-upload-blob-form form-horizontal" id="form_edit"  action="/admin/group/update"   accept-charset="UTF-8" method="post">

                    <input type="hidden" name="id" id="edit_id" value="">
                    <input type="hidden" name="format" id="format" value="json">

                    <div class="form-group">
                        <label class="control-label" for="id_name">显示名称:<span style="color: red"> *</span></label>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="params[name]" id="edit_name"  value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="id_description">描述:</label>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="params[description]" id="edit_description"  value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button name="submit" type="button" class="btn btn-save" id="btn-group_update">保存</button>
                        <a class="btn btn-cancel" data-dismiss="modal" href="#">取消</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/html"  id="list_tpl">
    {{#groups}}

        <tr class="commit">
            <td>
                <strong>{{name}}</strong>
            </td>
            <td>
                {{description}}
            </td>
            <td>
                {{cc}}
            </td>
            <td>
                权限方案
            </td>
            <td  >
                <div class="controls member-controls " style="float: right">

                    <a class="group_for_users btn btn-transparent " href="/admin/user/index/?group_id={{id}}" data-value="{{id}}" style="padding: 6px 2px;">所属成员 </a>
                    <a class="group_for_edit_users btn btn-transparent " href="/admin/group/edit_users/{{id}}" data-value="{{id}}" style="padding: 6px 2px;">编辑成员 </a>
                    <a class="group_for_edit btn btn-transparent " href="#" data-value="{{id}}" style="padding: 6px 2px;">编辑 </a>
                    <a class="group_for_delete btn btn-transparent  "  href="javascript:;" data-value="{{id}}" style="padding: 6px 2px;">
                        <i class="fa fa-trash"></i>
                        <span class="sr-only">Remove</span>
                    </a>
                </div>

            </td>
        </tr>
    {{/groups}}

</script>



<script type="text/javascript">

    var $group = null;
    $(function() {

        var options = {
            list_render_id:"list_render_id",
            list_tpl_id:"list_tpl",
            filter_form_id:"filter_form",
            filter_url:"/admin/group/filter",
            get_url:"/admin/group/get",
            update_url:"/admin/group/update",
            add_url:"/admin/group/add",
            delete_url:"/admin/group/delete",
            pagination_id:"pagination"

        }
        window.$group = new Group( options );
        window.$group.fetchGroups( );



    });

</script>
</body>
</html>