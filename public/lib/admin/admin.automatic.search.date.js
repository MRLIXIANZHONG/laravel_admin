//时间凑转时间-------
function wGetDate(v) {
	v=v.length==10?v+"000":v;
	var now = new Date(parseInt(v));
	var y = now.getFullYear();
	var m = now.getMonth() + 1;
	var d = now.getDate();
	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d);
}
//得到初始化的时间---------------
var w_date = $("#date1").val()&&$("#date2").val()?wGetDate($("#date1").val())+" ~ "+wGetDate($("#date2").val()):"";
//执行一个laydate实例----------
laydate.render({
	elem: '#wlaydate',
	// type: 'datetime',//打开可选择时分秒嘻嘻！
	range: '~',
	value: w_date,
	done:function(value, date, endDate){
		if(value==""){
			$("#date1").val("");
			$("#date2").val("");
			searchSub();
		}else{
			var dateValue = value.split("~")
			$("#date1").val(new Date(dateValue[0].trim()+" 00:00:00").getTime()/1000);
			$("#date2").val(new Date(dateValue[1].trim()+" 23:59:59").getTime()/1000);
			searchSub();
		}
	}
});
//end: 执行一个laydate实例----------