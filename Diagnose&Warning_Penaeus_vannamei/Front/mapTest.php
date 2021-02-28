<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=16zrDHPLkYrv8e1Tp98hPZ8UUpkKrsdv"></script>
<div class="pagelist"  style="width:400px;height:400px;margin:0 auto" id="allmap"></div>
<p id="demo">点击按钮获取您当前坐标（可能需要比较长的时间获取）：</p>
<button onclick="getLocation()">点我</button>
<script type="text/javascript">
    var x=document.getElementById("demo");
    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition((position) =>
            {
                let lat = position.coords.latitude;
                let lng = position.coords.longitude;
                const pointBak = new BMap.Point(lng, lat);
                const convertor = new BMap.Convertor();
                convertor.translate([pointBak], 1, 5,function(resPoint) {
                    if(resPoint && resPoint.points && resPoint.points.length>0){
                        lng = resPoint.points[0].lng;
                        lat = resPoint.points[0].lat;
                    }
                    const point = new BMap.Point(lng, lat);
                    const geo = new BMap.Geocoder();
                    geo.getLocation(point, (res) => {
                        var marker1 = new BMap.Marker(point);        // 创建标注
                        map.addOverlay(marker1);                     // 将标注添加到地图中
                        map.centerAndZoom(point,15);//根据坐标初始化地图
                    });
                });
            });
        }
        else{x.innerHTML="Geolocation is not supported by this browser.";}
    }

    //百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point(jd,wd),15);//根据坐标初始化地图
    map.enableScrollWheelZoom(true);
    map.addControl(new BMap.NavigationControl());   //平移缩放控件
    map.addControl(new BMap.ScaleControl());        //比例尺
    map.addControl(new BMap.OverviewMapControl());  //缩略地图
    map.addControl(new BMap.MapTypeControl());      //地图类型
    map.setCurrentCity("北京"); // 仅当设置城市信息时，MapTypeControl的切换功能才能可用

</script>
