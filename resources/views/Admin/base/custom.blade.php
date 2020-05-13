<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
?>
@if ($paginator->hasPages())
    <style>
        /* .wch-page-num+#go{margin: 0!important; padding: 5px 10px; font-size: 12px; border-radius: 0 3px 3px 0!important; } */
        /* .page-num{ font-size: 14px!important; } */
        /* .wch-page-num{margin: 0 0 0 3px!important; border-radius: 3px 0 0 3px!important; border-right: none!important; } */
        .wch-checkbox.pull-left{margin: 3px 10px 0 0; }
        /* .page-num input{ border: none; background: #a8d6f5; font-size: 12px; } */
        .pagination{margin:0 0 10px; }

    </style>

    <ul class="pagination pagination-sm no-margin pull-right">
        {{-- 首页 --}}
        @if ($paginator->onFirstPage())
            <li class="disabled">
                <span>首页</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->url(1) . '&limit=' . $paginator->perPage() }}">首页</a>
            </li>
        @endif

        {{-- 上一页 --}}
        @if ($paginator->onFirstPage())
            <li class="disabled">
                <span>上一页</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() . '&limit=' . $paginator->perPage() }}">上一页</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        <!-- "Three Dots" Separator -->
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

        <!-- Array Of Links -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url . '&limit=' . $paginator->perPage() }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- 下一页 --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() . '&limit=' . $paginator->perPage() }}">下一页</a>
            </li>
        @else
            <li class="disabled">
                <span>下一页</span>
            </li>
        @endif

        <li class="btn-group">
            {{-- 尾页 --}}
            @if ($paginator->hasMorePages())
                <a class="btn" href="{{ $paginator->url($paginator->lastPage()) . '&limit=' . $paginator->perPage() }}">尾页</a>
            @else
                <li class="disabled"><span>尾页</span></li>
            @endif
            <span data-toggle="tooltip" class="page-num btn" data-placement="bottom" title="输入页码,点击跳转按钮">
            第<input type="text" class="text-center"
                       value="{{ $paginator->currentPage() }}" id="customPage" style="height: 15px;width: 30px;"
                       data-total-page="{{ $paginator->lastPage() }}"> 页 / 共 {{$paginator->lastPage() }} 页
            </span>
            <a class="btn" href="javascript:void(0)" id="go">跳转</a>
        </li>
    </ul>
    <script>
        $(document).on("click", "#go", function () {
            var page = $('#customPage').val();
            var total= {{$paginator->lastPage()}};
            if (page*1 > total*1 || page*1 < 1) {
                layer.msg('您输入的页数大于总共页数了',function () {});
                return false;
            }
            var url = "{{ $paginator->url(666) . '&limit=' . $paginator->perPage()}}";
            window.location.href = url.replace(666,page).replace(/&amp;/g,'\&');
        })
    </script>
@endif
