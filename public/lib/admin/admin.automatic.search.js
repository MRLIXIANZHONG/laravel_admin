// 及时搜索
$(".search-btn").on("keyup",function(){
    $(".forms-sample").submit();
});

$(".search-select").on("change",function(){
    $(".forms-sample").submit();
});
function searchSub(){
    $(".forms-sample").submit();
}
var textStr;
$("#search_iframe").on('load',function () {
    textStr = $(this)[0].contentDocument;
    if(textStr.body.textContent==""){return false; }
    if(textStr.getElementsByClassName("title").length!=0&&textStr.getElementById("data-num")==undefined){
        var text = $(textStr.getElementsByClassName("title")).text();
        layer.msg(text,{icon:0})
    }
    if(textStr.getElementById("data-list")){
        document.getElementById("data-list").innerHTML=textStr.getElementById("data-list").innerHTML;
    }
    document.getElementById("data-num").innerHTML=textStr.getElementById("data-num").innerHTML;
    // if(textStr.getElementById("data-money")!==undefined){
    //     document.getElementById("data-money").innerHTML=textStr.getElementById("data-money").innerHTML;
    // }
});
// 及时搜索end
