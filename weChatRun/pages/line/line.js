var wxCharts = require('../../utils/wxcharts.js');
var app = getApp();
var lineChart = null;
Page({
  data: {
  },
  touchHandler: function (e) {
    console.log(lineChart.getCurrentDataIndex(e));
    lineChart.showToolTip(e, {
      // background: '#7cb5ec',
      format: function (item, category) {
        return category + ' ' + item.name + ':' + item.data
      }
    });
  },
  createSimulationData: function () {
    //     var categories = [];
    //     var data = [];
    //     for (var i = 0; i < 10; i++) {
    //         categories.push('5.' + (i + 1));
    //         data.push(Math.random()*(20-10)+10);
    //     }
    //     // data[4] = null;
    //     return {
    //         categories: categories,
    //         data: data
    //     }
    // },
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


  createSimulationData2: function () {
    // var categories2 = [];
    var data2 = wx.getStorageSync('step2');
    // data[4] = null;
    return {
      // categories2: categories2,
      data2: data2
    }
  },

  updateData: function () {
    var simulationData = this.createSimulationData();
    var series = [{
      name: '本周步数',
      data: simulationData.data,
      format: function (val, name) {
        return val.toFixed(0) + '步';
      }
    }];
    lineChart.updateData({
      categories: simulationData.categories,
      series: series
    });
  },
  // back:function(){
  //   wx.redirectTo({
  //     url: '../step/step',
  //   })
  // },

  onLoad: function (e) {
    var windowWidth = 320;
    try {
      var res = wx.getSystemInfoSync();
      windowWidth = res.windowWidth;
    } catch (e) {
      console.error('getSystemInfoSync failed!');
    }

    var simulationData = this.createSimulationData();
    var simulationData2 = this.createSimulationData2();
    lineChart = new wxCharts({
      canvasId: 'lineCanvas',
      type: 'line',
      categories: simulationData.categories,
      animation: true,
      // background: '#f5f5f5',
      series: [{
        name: '本周步数',
        data: simulationData.data,
        format: function (val, name) {
          return val.toFixed(0) + '步';
        }
      }, {
        name: '上周步数',
        data: simulationData2.data2,
        format: function (val, name) {
          return val.toFixed(0) + '步';
        }
      }],
      xAxis: {
        disableGrid: true
      },
      yAxis: {
        title: '步数',
        format: function (val) {
          return val.toFixed(0);
        },
        min: 0
      },
      width: windowWidth,
      height: 200,
      dataLabel: false,
      dataPointShape: true,
      extra: {
        lineStyle: 'curve'
      }
    });
  }
});