//引用百度地图微信小程序API模块
var bmap = require('../../libs/bmap-wx.min.js');
//初始化数据
Page({
  data: {
    weatherData: ''
  },
  onLoad: function () {
    var that = this;
    var BMap = new bmap.BMapWX({
      ak: 'Tkva2VLU27WnLuiNGGHrU69AUtAhE8lj'
    });
    var fail = function (data) {
      console.log('fail')
    };
    var success = function (data) {
      console.log('success');
      var weatherData = data.currentWeather[0];
      weatherData = '城市：'+ weatherData.currentCity + '\n'+ weatherData.date + '\n'+'\n'+'PM2.5：'+ weatherData.pm25 +'\n'+'\n'+'温度：' + weatherData.temperature + '\n' + '天气：' + weatherData.weatherDesc + '\n' + '风力：' + weatherData.wind + '\n';
      that.setData({
        weatherData: weatherData
      });
    }
    BMap.weather({
      fail: fail,
      success: success
    });
  }
})