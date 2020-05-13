<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@extends('Admin.base.popup')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list-ul"></i> 数据列表/检索数据:<span
                            id="data-num">{{$permissionPGNO->count()}}</span>条</h3>
                </div>
                @if($permissionPGNO->count())
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>
                                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                    </button>
                                </th>
                                <th></th>
                                <th>路由地址</th>
                                <th>名称/描述</th>
                                <th></th>
                                <th>上次更新</th>
                            </tr>
                            @foreach($permissionPGNO as $k=>$v)
                                <tr>
                                    <td><input type="checkbox" name="ckbox" value="{{$v->id}}"></td>
                                    <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                    <td class="mailbox-name"><a href="javascript:void(0)">{{$v->name}}</a></td>
                                    <td class="mailbox-display_name"><b>{{$v->display_name}}</b>
                                        -{{$v->description ?: '暂无描述'}}
                                    </td>
                                    <td class="mailbox-attachment"></td>
                                    <td class="mailbox-updated_at">{{$v->updated_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                    <div class="mailbox-controls">
                    <!-- /.btn-group -->
                        <div class="pull-right">
                            <a href="javascript:void(0)" onClick="groupMovein('{{$groupId}}')" class="btn btn-sm
                            btn-primary">确认拉取</a>
                        </div>
                        <!-- /.pull-right -->
                    </div>
                </div>
                @else
                    <div class="alert alert-info fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>404!</strong> 当前列表暂无数据.
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
@section('script')
    <!-- js引入/多选/特效 -->
    <script src="{{asset('lib/admin/admin.group.index.js')}}"></script>
    <script>
        function groupMovein(groupId) {
	        var idArr= new Array();
	        $("input[name='ckbox']:checked").each(function() {
		        idArr.push($(this).val());
	        });
	        if(!idArr[0]){
		        alert("请选择要移入组内的权限！");
	        }else{
		        idArr = idArr.join(",");
		        if(confirm("是否确定批量移入选中的权限？")){
			        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
			        $.ajax({
				        type : "POST",
				        url : "{{url('admin/permission/group/movein?groupId=')}}"+groupId,
				        data: {idArr:idArr},
				        method:'put',
				        dataType : "json",
				        success:function(datas){
					        if(datas['code']===200) {
						        layer.msg(datas['infor'], {time: 1000},function(){
							        parent.window.location.reload();
						        })
					        }else{
						        layer.msg(datas['infor'],{time:3000},function () {
							        parent.window.location.reload();
						        })
					        }
				        }//ALAX调用成功
				        ,error:function () {
					        alert("网络异常！");
				        }
			        });
		        }
	        }
        }
    </script>
@endsection
