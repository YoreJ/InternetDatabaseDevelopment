<template>
  <!-- 
    主容器，包裹整个关于页面的内容。
    使用 Flexbox 布局，垂直方向排列子元素，居中对齐。
  -->
  <div class="aboutContainer">
    
    <!-- 
      显示网站的访问量。
      使用插值表达式 `{{ views }}` 动态绑定访问次数。
    -->
    <!-- 修改后的代码 -->
<div class="hero-section">
  <div class="logo-container">
    <!-- 这里使用一个简单的SVG作为示例logo -->
    <svg class="website-logo" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
    </svg>
  </div>
  <div class="site-info">
    <h1 class="site-title">团队介绍网站</h1>
    <div class="views-counter">
      <svg class="view-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
      <span class="views-count">{{ views }}</span>
      <span class="views-label">次浏览</span>
    </div>
  </div>
</div>
    
    <!-- 
      网站介绍部分。
      包含一个标题和一个动态渲染的介绍内容。
    -->
    <!-- 关于网站部分也需要改进 -->
<div class="webInfo0">
  <div class="section-header">
    <h2>关于网站</h2>
    <div class="header-underline"></div>
  </div>
  <div class="Info0" v-html="Info"></div>
</div>

    <!-- 
      作业下载部分。
      包含一个标题和一组可下载的作业链接。
    -->
    <!-- 修改后的代码 -->
<div class="homework">
  <a 
    v-for="(item, index) in downloadSrc" 
    :key="index" 
    :href="item.link" 
    download
    class="download-link"
  >
    <div class="download-item">
      <!-- 添加下载图标 -->
      <svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <p>{{ item.title }}</p>
    </div>
  </a>
</div>
    <!-- 
      成员展示部分（第一行）。
      显示两位团队成员的信息，使用 `MenberBox` 组件。
    -->
    <div class="webInfo1">
      <MenberBox :fullname="name1"></MenberBox>
      <MenberBox :fullname="name2"></MenberBox>
    </div>
    
    <!-- 
      成员展示部分（第二行）。
      显示另外两位团队成员的信息，继续使用 `MenberBox` 组件。
    -->
    <div class="webInfo2">
      <MenberBox :fullname="name3"></MenberBox>
      <MenberBox :fullname="name4"></MenberBox>
    </div>
    
    <!-- 
      返回顶部按钮。
      使用 Element UI 的 `el-backtop` 组件，设置按钮在页面右下角。
    -->
    <el-backtop :right="100" :bottom="100" />
  </div>
</template>

<script>
import MenberBox from '../components/MenberBox.vue' // 导入成员展示组件
import axios from 'axios' // 导入 axios 库用于发送 HTTP 请求

export default {
  data() {
    return {
      views: 0, // 网站访问量，初始值为 0
      Info: '', // 网站介绍内容，从后端获取
      name1: '徐海潆', // 第一位团队成员的姓名
      name2: '王禹衡', // 第二位团队成员的姓名
      name3: '唐明昊', // 第三位团队成员的姓名
      name4: '姜宇', // 第四位团队成员的姓名
      downloadSrc: [ // 作业下载链接数组，每个对象包含标题和下载链接
        {
          title: '徐海潆',
          link: 'public/data/2110951梁晓储个人作业.zip'
        },
        {
          title: '王禹衡',
          link: 'public/data/2112106方奕个人作业.zip'
        },
        {
          title: '团队作业',
          link: 'public/data/LFZW_团队作业(2110951_2112106_2112414_2113419).zip'
        },
        {
          title: '唐明昊',
          link: 'public/data/2113419张昊星个人作业.zip'
        },
        {
          title: '姜宇',
          link: 'public/data/2112414王思宇个人作业.zip'
        }
      ]
    }
  },
  components: {
    MenberBox // 注册成员展示组件，供模板中使用
  },
  mounted() {
    this.checkViews() // 页面加载完成后，调用方法获取访问量
    this.getInfo() // 页面加载完成后，调用方法获取网站介绍信息
  },
  methods: {
    /**
     * 获取网站的访问量
     * 发送 POST 请求到后端 API，获取当前网站的访问次数
     * 成功后，将返回的数据赋值给 `views`
     * 失败时，在控制台打印错误信息
     */
    checkViews() {
      axios
        .get('http://localhost:8080/api/getwebviews')
        .then((response) => {
          this.views = response.data.visitCount // 更新访问量
          console.log('访问量:', this.views)
        })
        .catch((error) => {
          console.error('请求失败', error) // 错误处理
        })
    },
    
    /**
     * 获取网站介绍信息
     * 发送 POST 请求到后端 API，获取网站的详细介绍
     * 成功后，将返回的 HTML 内容赋值给 `Info`
     * 失败时，在控制台打印错误信息
     */
    getInfo() {
      axios
        .post('http://localhost:8080/api/getpersonalinfo?name=网站介绍') // 发送 POST 请求
        .then((response) => {
          this.Info = response.data.Info // 更新网站介绍内容
        })
        .catch((error) => {
          console.error('请求失败', error) // 错误处理
        })
    }
  }
}
</script>

<style scoped>
/* 
  关于页面的主容器样式。
  使用 Flexbox 布局，垂直排列子元素，居中对齐。
  设置页面的宽度为 100%，最小高度为 65vh（视口高度的 65%）。
  隐藏水平和垂直方向的溢出内容，防止滚动条出现。
*/
.aboutContainer {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 5vh; /* 上外边距为视口高度的 10% */
  width: 100%;
  min-height: 65vh;
  overflow-x: hidden; /* 隐藏水平溢出 */
  overflow-y: hidden; /* 隐藏垂直溢出 */
}
.aboutContainer > h2 {
  /* 添加特殊样式使标题更突出 */
  font-size: 2.2rem;
  color: #040506;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  padding: 15px 30px;
  background: rgba(255, 255, 255, 0.9);
  opacity: 0.8; /* 透明度 */
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
/* 
  网站介绍部分的容器样式。
  使用 Flexbox 布局，垂直排列子元素，居中对齐。
  设置半透明白色背景，圆角，内边距和外边距。
  宽度为父容器的 80%，最小高度为 40vh。
*/

/* Hero section styles */
.hero-section {
  display: flex;
  align-items: center;
  padding: 2rem;
  background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  margin-bottom: 3rem;
  margin-top: 5rem;
  width: 90%;
  max-width: 1200px;
}

.logo-container {
  padding: 1rem;
  margin-right: 2rem;
}

.website-logo {
  width: 50px;
  height: 50px;
  color: #3b82f6;
}

.site-info {
  flex: 1;
}

.site-title {
  font-size: 1.8rem;
  color: #1f2937;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.views-counter {
  display: flex;
  align-items: center;
  background: white;
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  display: inline-flex;
}

.view-icon {
  width: 24px;
  height: 24px;
  color: #3b82f6;
  margin-right: 0.5rem;
}

.views-count {
  font-size: 1.25rem;
  font-weight: 600;
  color: #3b82f6;
  margin-right: 0.25rem;
}

.views-label {
  color: #6b7280;
  font-size: 0.95rem;
}

/* 改进关于网站部分的样式 */


.section-header {
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
}

.section-header h2 {
  font-size: 1.8rem;
  color: #1f2937;
  margin-bottom: 1rem;
  font-weight: 600;
}

.header-underline {
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
  border-radius: 2px;
  margin: 0 auto;
}



/* 响应式调整 */
@media (max-width: 768px) {
  .hero-section {
    flex-direction: column;
    text-align: center;
    padding: 1.5rem;
  }

  .logo-container {
    margin-right: 0;
    margin-bottom: 1rem;
  }

  .site-title {
    font-size: 1.5rem;
  }

  
}
.webInfo0 {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.7); /* 半透明白色背景 */
  border-radius: 15px; /* 圆角 */
  margin-top: 5vh; /* 上外边距为视口高度的 10% */
  margin-bottom: 10vh; /* 下外边距为视口高度的 10% */
  width: 80%;
  min-height: 60vh;
}

/* 
  网站介绍内容的容器样式。
  使用 Flexbox 布局，垂直排列子元素，左对齐，均匀分布空间。
  设置白色背景，圆角，内边距和外边距。
  宽度为父容器的 95%，最小高度为父容器的 70%。
*/
.Info0 {
  display: flex;
  flex-direction: column;
  align-items: flex-start; /* 左对齐 */
  justify-content: space-evenly; /* 子元素均匀分布 */
  background-color: rgba(255, 255, 255, 0.8); /* 白色背景 */
  border-radius: 15px; /* 圆角 */
  padding: 20px;
  margin-top: 3vh; /* 上外边距为视口高度的 3% */
  margin-bottom: 3vh; /* 下外边距为视口高度的 3% */
  width: 95%;
  min-height: 60%;
}

/* 
  作业下载部分的容器样式。
  使用 Flexbox 布局，垂直排列子元素，居中对齐。
  设置半透明白色背景，圆角，内边距和外边距。
  宽度为父容器的 80%，最小高度为 40vh。
*/
.download {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.8); /* 半透明白色背景 */
  border-radius: 15px; /* 圆角 */
  margin-top: 0vh; /* 无上外边距 */
  margin-bottom: 10vh; /* 下外边距为视口高度的 10% */
  width: 80%;
  min-height: 40vh;
}

/* 修改作业下载列表的容器样式 */
.homework {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  padding: 20px;
  width: 95%;
}

/* 修改下载链接样式 */
.homework a {
  text-decoration: none;
  width: 100%;
  height: auto;
  margin: 0;
}

/* 修改下载项样式 */
.download-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-color: rgba(255, 255, 255, 0.8); /* 半透明白色背景 */
  border-radius: 10px;
  transition: all 0.3s ease;
}

/* 添加下载图标样式 */
.download-icon {
  width: 40px;
  height: 40px;
  margin-bottom: 12px;
  color: #3b82f6;
}

/* 添加悬停效果 */
.download-item:hover {
  background-color: #e5e7eb;
  transform: translateY(-4px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.download-item p {
  margin: 0;
  font-size: 1rem;
  color: #374151;
  font-weight: 500;
}

/* 
  第一行成员展示部分的容器样式。
  使用 Flexbox 布局，水平排列子元素，均匀分布空间。
  设置外边距和宽度为 100%。
*/
.webInfo1 {
  display: flex;
  margin-top: 10vh; /* 上外边距为视口高度的 10% */
  margin-bottom: 10vh; /* 下外边距为视口高度的 10% */
  width: 100%;
  justify-content: space-evenly; /* 子元素均匀分布 */
}

/* 
  第二行成员展示部分的容器样式。
  与 `.webInfo1` 类似，继续使用 Flexbox 布局。
*/
.webInfo2 {
  display: flex;
  margin-top: 10vh; /* 上外边距为视口高度的 10% */
  margin-bottom: 10vh; /* 下外边距为视口高度的 10% */
  width: 100%;
  justify-content: space-evenly; /* 子元素均匀分布 */
}

/* 
  成员卡片的样式。
  设置左右外边距为 30px，用于调整成员卡片之间的间距。
*/
.card {
  margin-inline: 50px;
}




</style>
