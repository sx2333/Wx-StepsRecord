var wxCharts = require('../../utils/wxcharts.js');
var app = getApp();
var areaChart = null;
Page({
  data: {
  },

  
  touchHandler: function (e) {
    console.log(areaChart.getCurrentDataIndex(e));
    areaChart.showToolTip(e);
  },
  get3rdSession: function () {
    let that = this
    wx.login({
      success: function (res) {
        wx.request({
          url: 'https://374495727.duapp.com/login.php',
          data: {
            code: res.code
          },
          method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          success: function (res) {
            var sessionId = res.data;
            that.setData({ sessionId: sessionId })
            wx.setStorageSync('sessionId', sessionId)
            that.decodeUserInfo()
          }
        })
      }
    })
  },


  decodeUserInfo: function () {
    let that = this
    wx.request({
      url: 'https://374495727.duapp.com/decrypt.php',
      data: {
        encryptedData: that.data.encryptedData,
        iv: that.data.iv,
        session: wx.getStorageSync('sessionId')
      },
      method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        var step = []
        var data = res.data.stepInfoList.reverse()
        console.log(res.data.stepInfoList)
        for (var i = 0; i < 7; i++) {
          step.push(res.data.stepInfoList[i].step)
        }
        step = step.reverse()
        that.setData({ step: step })
        wx.setStorageSync('step', step);
        console.log(step)//获取七天步数数据并存入step中

        var step2 = []
        var data2 = res.data.stepInfoList.reverse()
        console.log(res.data.stepInfoList)
        for (var i = 7; i < 14; i++) {
          step2.push(res.data.stepInfoList[i].step)
        }
        step2 = step2.reverse()
        that.setData({ step2: step2 })
        wx.setStorageSync('step2', step2);
        // console.log(step2)//获取七天前的七天的数据并存入step2中
        // that.charts() //调用绘制图表函数
        var step1 = res.data.stepInfoList[30].step
        var km = step1*0.0007
        var showkm = km.toFixed(2)
        var energy = showkm*60*1.036
        var showenergy = energy.toFixed()
        that.setData({ 
          step1: step1,
          showkm:showkm,
          showenergy:showenergy      
        })
        wx.setStorageSync('step1', step1);
        console.log(step1)//今日步数，能量消耗，里程
        var steps  = 0
        for (var i = 0; i < 7; i++){
          steps = steps + step[i]
        }
        var sumkm = steps * 0.0007
        var showsumkm = sumkm.toFixed(2)
        var sumenergy = showsumkm*60*1.036
        var showsumenergy = sumenergy.toFixed(2)
        that.setData({
          steps:steps,
          showsumkm:showsumkm,
          showsumenergy:showsumenergy,
        })
        console.log(steps)
        that.area()      
      }
    })
  },


  createSimulationData: function () {
    var that = this;
    var categories = [];
    var data = wx.getStorageSync('step');
    console.log(wx.getStorageSync('step'))
    // 日期
    var now = new Date();
    // var year = now.getFullYear();
    // var month = now.getMonth() + 1;
    var day = now.getDate();
    var week = now.getDay();
    var weeks = ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"]
    if (week < 7) {
      week = week + 7
    }
    categories = weeks.slice(week - 7, week)
    console.log(categories)
    return {
      categories: categories,
      data: data
    }
  },
  
  area:function(){
    var windowWidth = 320;
    try {
      var res = wx.getSystemInfoSync();
      windowWidth = res.windowWidth;
    } catch (e) {
      console.error('getSystemInfoSync failed!');
    }

    var simulationData = this.createSimulationData();
    areaChart = new wxCharts({
      canvasId: 'areaCanvas',
      type: 'area',
      categories: simulationData.categories,
      animation: true,
      series: [{
        name: '步数',
        data: simulationData.data,
        format: function (val) {
          return val.toFixed(0) + '步';
        }
      }],
      yAxis: {
        title: '步数',
        format: function (val) {
          return val.toFixed(0);
        },
        min: 0,
        fontColor: '#8085e9',
        gridColor: '#8085e9',
        titleFontColor: '#f7a35c'
      },
      xAxis: {
        fontColor: '#7cb5ec',
        gridColor: '#7cb5ec'
      },
      extra: {
        legendTextColor: '#cb2431'
      },
      width: windowWidth,
      height: 200
    });
  },


  onLoad: function (e) {
    let that = this
    wx.login({
      success: function (res) {
        let code = res.code
        that.setData({ code: code })
        wx.getWeRunData({//解密微信运动
          success(res) {
            const encryptedData = res.encryptedData
            that.setData({ encryptedData: encryptedData })
            that.setData({ iv: res.iv })
            that.get3rdSession()//解密请求函数
          }
        })
      }
    })
  }


});