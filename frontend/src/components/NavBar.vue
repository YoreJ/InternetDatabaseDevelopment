<template>
  <div class="sidebar-wrapper">
    <!-- 伸缩按钮 -->
    <div class="sidebar-toggler" @click="toggleSidebar">
      <i :class="['fas', isCollapsed ? 'fa-bars' : 'fa-times']"></i>
    </div>

    <!-- 导航菜单 -->
    <el-menu
      :default-active="getCurrentMenuUrl()"
      class="el-menu-vertical"
      :collapse="isCollapsed"
      mode="vertical"
      router="true"
      :collapse-transition="true"
      unique-opened
      >
      <!-- Logo区域 -->
       <!-- Logo区域 -->
       <div class="sidebar-header">
        <svg v-if="!isCollapsed" class="logo-img" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#7795f8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M2 17L12 22L22 17" stroke="#7795f8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M2 12L12 17L22 12" stroke="#7795f8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <svg v-else class="logo-small" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#7795f8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <!-- 菜单项 -->
      <el-menu-item index="/">
        <i class="fas fa-home"></i>
        <span slot="title">首页</span>
      </el-menu-item>

      <el-menu-item index="/chat">
        <i class="fas fa-comments"></i>
        <span slot="title">对话</span>
      </el-menu-item>

      <el-menu-item index="/movie">
        <i class="fas fa-video"></i>
        <span slot="title">视频</span>
      </el-menu-item>

      <el-menu-item index="/article">
        <i class="fas fa-newspaper"></i>
        <span slot="title">文章</span>
      </el-menu-item>

      <el-menu-item index="/about">
        <i class="fas fa-info-circle"></i>
        <span slot="title">关于</span>
      </el-menu-item>

      <el-menu-item index="/user">
        <i class="fas fa-user"></i>
        <span slot="title">个人</span>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isCollapsed: false
    }
  },
  methods: {
    getCurrentMenuUrl() {
      return this.$route.path
    },
    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed
    }
  }
}
</script>

<style scoped>
.sidebar-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
  transition: width 0.3s;
  z-index: 1000;
}

.sidebar-toggler {
  position: absolute;
  top: 1rem;
  right: -12px;
  width: 24px;
  height: 24px;
  background: #7795f8;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: white;
  z-index: 1001;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
  padding: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-img {
  height: 40px;
  transition: all 0.3s;
}

.logo-small {
  height: 30px;
  transition: all 0.3s;
}

.el-menu-vertical {
  border-right: none;
  background: transparent;
}

.el-menu-vertical:not(.el-menu--collapse) {
  width: 240px;
}

:deep(.el-menu-item) {
  display: flex;
  align-items: center;
  color: #a8b2d1;
  height: 50px;
  margin: 4px 0;
}

:deep(.el-menu-item i) {
  margin-right: 10px;
  font-size: 18px;
  width: 24px;
  text-align: center;
}

:deep(.el-menu-item.is-active) {
  background: rgba(119, 149, 248, 0.1);
  color: #7795f8;
  border-radius: 0 20px 20px 0;
}

:deep(.el-menu-item:hover) {
  background: rgba(255, 255, 255, 0.05);
  color: #ffffff;
}

/* 响应式调整 */
@media (max-width: 768px) {
  .el-menu-vertical:not(.el-menu--collapse) {
    width: 200px;
  }

  .sidebar-toggler {
    width: 20px;
    height: 20px;
    right: -10px;
  }
}
</style>