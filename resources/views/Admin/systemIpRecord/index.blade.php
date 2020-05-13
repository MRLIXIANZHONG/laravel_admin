<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$model = new App\Models\Iprecording;
?>
@extends('Admin.base.blank')
@section('title','IP访问记录')
@section('breadcrumb')
    <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active">IP访问记录</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i>条件检索栏</h3>
                </div>
                <div class="box-body">
                    <form method="get" class="forms-sample"  target="search_iframe">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control search-btn" placeholder="搜索IP地址" name="address" @if(isset($_GET['address']) && $_GET['address'] !== '') value="{{ $_GET['address'] }}" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="orderAccessnum" id="orderAccessnum">
                                <option value="">次数排序[不排]</option>
                                <option value="asc" @if(!empty($_GET['orderAccessnum']) and
                                $_GET['orderAccessnum']=='asc')selected="selected" @endif>正序</option>
                                <option value="desc" @if(!empty($_GET['orderAccessnum']) and
                                $_GET['orderAccessnum']=='desc')selected="selected" @endif>倒序</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control search-select" name="limit" id="limit">
                                <option value="10">显示条数(10)</option>
                                @foreach(getSizeArr() as $v)
                                    <option value="{{$v}}" @if(!empty($_GET['limit']) and $_GET['limit']==$v)selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/system-iprecord/index')}}" class="btn btn-default pull-right">
                            <i class="fa fa-trash-o"></i>清空检索条件
                        </a>
                        <div style="display: none;">
                            <input type="submit" class="btn btn-primary" value="搜索">
                        </div>
                    </form>
                    <iframe id="search_iframe" name="search_iframe" style="display:none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span id="data-num">{!!$list->total()!!}</span>条</h3>
                    <div class="pull-right mt10">
                    </div>
                </div>
                <div class="box-body" id="data-list">
                    @if($list->count())
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>IP</th>
                                <th>最后访问地址</th>
                                <th>访问次数</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $k=>$v)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$v->address}}</td>
                                    <td>{{$v->atlastroute}}</td>
                                    <td>{{$v->accessnum}}</td>
                                    <td>
                                        @if(getAuth()->can('admin/system-iprecord/show'))
                                            <button type="button" class="btn btn-info" title="查看更多详情" onClick="showInfo('{{url('admin/system-iprecord/show?id='.$v->id)}}')"><i class="fa fa-eye"></i></button>
                                        @endif
                                        @if(getAuth()->can('admin/system-iprecord/edit_type'))
                                            @php
                                                $title = $v->iptype == $model::IPTYPEACTIVITY ? "点击按钮限制该IP" : "解除IP限制";
                                                $class = $v->iptype == $model::IPTYPEACTIVITY ? "btn-primary" : "btn-danger";
                                            @endphp
                                            <button type="button" class="btn {{$class}}" title="{{$title}}" onClick="edit_type({{$v->id}})"><i class="fa fa-key"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    {!! $list->render('Admin.base.custom') !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>404!</strong> 当前列表暂无数据.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('lib/admin/admin.automatic.search.js')}}"></script>
    <script>
        //信息查看
        function showInfo(url) {
            layer.open({
                type:2,           //类型，解析url
                closeBtn: 1,      //关闭按钮是否显示 1显示0不显示
                title:"IP访问记录查看",
                shadeClose: false, //点击遮罩区域是否关闭页面
                shade: 0.6,       //遮罩透明度
                area:["50%","45%"],
                content:url
            })
        }
        //用户删除
        function edit_type(id) {
	        layer.confirm('确定修改该IP状态?',{btn:['确定','取消']},function () {
		        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
		        $.ajax({
			        type : 'get',
			        url: "{{url('admin/system-iprecord/edit_type')}}",
			        data:{id:id},
			        method:'put',
			        dataType:"json",
			        success:function (datas) {
				        if(datas['code']===200){
					        layer.msg(datas['infor'], {time:2000},function () {
						        parent.window.location.reload();
					        })
				        }else{
					        layer.msg(datas['infor'],{time:2000})
				        }
			        }
		        });
	        });
        }
    </script>
@endsection

