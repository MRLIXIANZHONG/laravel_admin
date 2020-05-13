<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
$BaseModel = new \App\Models\BaseModel;
$navigations = $BaseModel->redisOperation($BaseModel::NAVIGATIONREDIS,'get');
?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{getAuth()->avatar ? asset(getAuth()->avatar) : asset('AdminLTE-2.4.18/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{getAuth()->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>

        <!-- search form (Optional) -->
{{--        <form action="#" method="get" class="sidebar-form">--}}
{{--            <div class="input-group">--}}
{{--                <input type="text" name="q" class="form-control" placeholder="Search...">--}}
{{--                <span class="input-group-btn">--}}
{{--              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
{{--              </button>--}}
{{--            </span>--}}
{{--            </div>--}}
{{--        </form>--}}
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">系统导航栏</li>
            <!-- 设置导航后显示导航,未设置显示权限设置4个默认模块 -->
            @if(!empty($navigations))
                @foreach($navigations as $v)
                    @if(!isset($v['submenu']) && getAuth()->can($v['permissionName']))

                                {{--一级导航,且用户拥有该权限--}}
                                <li><a href="{{$v['permissionName'] ? url($v['permissionName']) : "#"}}">
                                        <i class="fa fa-link"></i> <span>{{$v['name']}}</span>
                                    </a>
                                </li>
                    @elseif($BaseModel->hasMultistageShow(@$v['submenu']))
                        {{--多级导航,且用户拥有导航组中任一权限方可显示--}}
                        {{--TODO 导航数据库可以无限极,但是目前的获取结构及html样式，只支持到二级导航--}}
                        <li class="treeview">
                            <a href="{{$v['permissionName'] ? url($v['permissionName']) : "#"}}">
                                <i class="fa fa-link"></i> <span>{{$v['name']}}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @foreach(@$v['submenu'] as $submenu)
                                    @if(getAuth()->can($submenu['permissionName']))
                                    <li>
                                        <a href="{{$submenu['permissionName'] ? url($submenu['permissionName']) : "#"}}">{{$submenu['name']}}</a>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            @else
                <li class="treeview">
                    <a href="#"><i class="fa fa-link"></i> <span>权限设置</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('admin/permission/route/index')}}">权限管理</a></li>
                        <li><a href="{{url('admin/permission/group/index')}}">权限组管理</a></li>
                        <li><a href="{{url('admin/permission/role/index')}}">角色管理</a></li>
                        <li><a href="{{url('admin/permission/navigation/index')}}">导航管理</a></li>
                    </ul>
                </li>
            @endif
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
