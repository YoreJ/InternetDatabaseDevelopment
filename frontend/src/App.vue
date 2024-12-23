<template>
  <div class="app-wrapper">
    <div v-show="show" class="app-header">
      <NavBar></NavBar>
    </div>
    
    <transition name="fade" mode="out-in">
      <main class="app-container">
        <router-view v-slot="{ Component }">
          <component :is="Component" />
        </router-view>
      </main>
    </transition>
    
    <div v-show="show" class="app-footer">
      <WaveFoot></WaveFoot>
    </div>
  </div>
</template>

<script>
import NavBar from './components/NavBar.vue'
import WaveFoot from './components/WaveFoot.vue'
import axios from 'axios'

export default {
  name: 'App',
  components: {
    NavBar,
    WaveFoot
  },
  
  computed: {
    show() {
      const { meta } = this.$route
      return !meta || meta.showNavBar !== false
    }
  },
  
  created() {
    // 添加错误处理
    window.addEventListener('unhandledrejection', this.handleError)
  },
  
  mounted() {
    this.addWebView()
  },
  
  beforeUnmount() {
    window.removeEventListener('unhandledrejection', this.handleError)
  },
  
  methods: {
    async addWebView() {
      try {
        const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8080'
        await axios.post(`${baseURL}/api/addwebviews`)
      } catch (error) {
        console.error('Failed to increment view count:', error)
      }
    },
    
    handleError(event) {
      console.error('Unhandled promise rejection:', event.reason)
      // 可以添加错误上报逻辑
    }
  }
}
</script>

<style>
/* 重置和基础样式 */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
    Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

/* 应用程序容器 */
.app-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: transparent; /* 移除默认背景 */
  
}

/* 头部样式 */

/* 主容器样式 */
.app-container {
  flex: 1;
  width: 100%;
  max-width: 100%;
  margin: 0;
  padding: 0;
  position: relative;
}

/* 响应式布局 */


/* 页脚样式 */
.app-footer {
  margin-top: auto;
  background: transparent;
}

/* 过渡动画 */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* 响应式布局 */
@media (max-width: 768px) {
  .app-container {
    padding: 16px;
  }
  
  .app-header {
    height: 50px;
  }
}

/* 打印样式优化 */
@media print {
  .app-header,
  .app-footer {
    display: none;
  }
  
  .app-container {
    margin: 0;
    padding: 0;
  }
}



</style>