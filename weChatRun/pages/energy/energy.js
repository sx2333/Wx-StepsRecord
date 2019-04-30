var energyData = {
  name: '能量消耗：   '
}

Page({

  /**
   * 初始化数据
   */
  data: {
    Km: '',
    Kg: '',
  },

  /**
   * 监听运动距离输入
   */
  listenerKmInput: function (e) {
    this.data.Km = e.detail.value;
  },
  /**
   * 监听体重输入
   */
  listenerKgInput: function (e) {
    this.data.Kg = e.detail.value;
  },
  /**
   * 监听计算按钮
   */
  data: energyData,
  listenerCount: function (e) {
    this.setData({
      name: '能量消耗为：'+this.data.Km * this.data.Kg * 1.03
    })


  },

  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})