<script>
// 导入电影信息展示组件 InfoBox
import InfoBox from '../components/InfoBox.vue'
// 导入 axios，用于发起 HTTP 请求
import axios from 'axios'

export default {
  data() {
    return {
      movieList: [], // 存储电影列表数据
      pagecount: 1 // 存储分页总条数，初始为 1
    }
  },
  // Vue 生命周期钩子，组件挂载完成后调用
  mounted() {
    this.getUrl() // 获取电影列表数据
    this.getpage() // 获取分页总数
  },
  components: {
    InfoBox // 注册 InfoBox 组件，用于显示电影信息
  },
  methods: {
    // 获取电影列表数据
    getUrl() {
      axios
        .post('http://localhost:8080/api/getvideo') // 向后端 API 发起 POST 请求
        .then((response) => {
          this.movieList = response.data // 将响应数据赋值给 movieList
        })
        .catch((error) => {
          console.error('请求失败', error) // 请求失败时在控制台输出错误信息
        })
    },
    // 处理分页变化的回调函数
    handlePageChange(page) {
      axios
        .post('http://localhost:8080/api/getvideo?page=' + page) // 根据页码发起请求
        .then((response) => {
          const elBacktop = document.querySelector('.el-backtop') // 获取返回顶部按钮
          this.movieList = response.data // 更新电影列表数据
          elBacktop.click() // 模拟点击返回顶部按钮，回到页面顶部
        })
        .catch((error) => {
          console.error('请求失败', error) // 请求失败时在控制台输出错误信息
        })
    },
    // 获取分页总数
    getpage() {
      axios
        .post('http://localhost:8080/api/getvideopagecount') // 请求后端获取分页总数
        .then((response) => {
          this.pagecount = response.data * 8 // 假设后端返回的是页数，将其转换为总条数
        })
        .catch((error) => {
          console.error('请求失败', error) // 请求失败时在控制台输出错误信息
        })
    }
  }
}
</script>

<template>
  <!-- 主容器 -->
  <div class="movieContainer">
    <!-- 包裹所有电影项目 -->
    <div class="movieBox">
      <!-- 电影列表容器 -->
      <div class="movieItemBox">
        <!-- 遍历 movieList 动态渲染电影项目 -->
        <div v-for="item in movieList" :key="item.VideoID" class="movieItem">
          <!-- 路由链接，点击跳转到对应电影的详情页面 -->
          <router-link :to="'/movie/' + item.VideoID">
            <!-- 使用 InfoBox 组件展示电影信息 -->
            <InfoBox
              :src="item.PictureURL"        
              :title="item.Title"          
              :time="item.UploadDate"
            />
          </router-link>
        </div>
      </div>
      <!-- 分页组件 -->
      <el-pagination
        background
        layout="prev, pager, next"       
        :total="pagecount"               
        hide-on-single-page              
        @current-change="handlePageChange" 
      />
    </div>
  </div>
  <!-- 返回顶部按钮 -->
  <el-backtop :right="100" :bottom="100" />
</template>

<style>
/*
html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  background: url('../assets/imgs/back2.png') no-repeat center center;
  background-size: cover;  
}
*/


/* 主容器样式 */
.movieContainer {
  display: flex; /* 使用 Flexbox 布局 */
  justify-content: center; /* 水平方向居中 */
  flex-direction: column; /* 子元素垂直排列 */
  align-items: center; /* 垂直方向居中 */
  margin-top: 10vh; /* 上外边距为视口高度的 10% */
  width: 100vw; /* 宽度为视口宽度的 100% */
  height: auto; /* 高度自动适应内容 */
}

/* 包裹所有电影项目的容器 */
.movieBox {
  display: flex; /* 使用 Flexbox 布局 */
  flex-wrap: wrap; /* 自动换行 */
  flex-direction: row; /* 水平方向排列子元素 */
  justify-content: center; /* 子元素水平居中 */

  background-color: rgba(255, 255, 255, 0.7); /* 半透明白色背景 */
  border-radius: 15px; /* 圆角 */
  width: 90%; /* 宽度为父容器的 90% */
  height: auto; /* 高度根据内容自动调整 */
  padding: 30px; /* 内边距为 30px */


  background: url('../assets/imgs/back2.png') no-repeat center center;
  background-size: cover; /* 背景图片覆盖整个容器 */
}

/* 电影项目容器 */
.movieItemBox {
  display: flex; /* 使用 Flexbox 布局 */
  flex-wrap: wrap; /* 自动换行 */
  flex-direction: row; /* 水平方向排列子元素 */
  justify-content: center; /* 子元素水平居中 */
  width: 100%; /* 宽度为父容器的 100% */
}

/* 单个电影项目样式 */
.movieItem {
  display: flex; /* 使用 Flexbox 布局 */
  flex-direction: row; /* 子元素水平排列 */
  align-items: center; /* 子元素垂直居中 */
  padding: 20px; /* 内边距为 20px */
  width: auto; /* 宽度自动适应内容 */
  height: auto; /* 高度自动适应内容 */
  margin: 20px 30px; /* 上下外边距为 20px，左右外边距为 30px */
}

/* 图片容器样式 */
.picBox {
  display: flex; /* 使用 Flexbox 布局 */
  height: 100%; /* 高度为父容器的 100% */
  width: 20%; /* 宽度为父容器的 20% */
}

/* 内容容器样式 */
.contentBox {
  display: flex; /* 使用 Flexbox 布局 */
  flex-direction: column; /* 子元素垂直排列 */
  justify-content: flex-start; /* 子元素顶部对齐 */
  margin-left: 40px; /* 左外边距为 40px */
  height: 100%; /* 高度为父容器的 100% */
}
</style>
