<template>
  <!-- 
    用户页面的主容器，使用 Flexbox 布局，将左右两个部分并排显示。
    - 左侧为用户信息卡片
    - 右侧为评论内容区域
  -->
  <div class="user-container">
    
    <!-- 左侧用户信息卡片 -->
    <div class="user-card">
      
      <!-- 头像包裹容器，用于设置头像的大小和位置 -->
      <div class="avatar-wrapper">
        <!-- 
          用户头像图片。
          :src="userAvatar" 动态绑定图片源，来自 data 中的 userAvatar。
          alt="用户头像" 提供图片的替代文本，提升可访问性。
          class="avatar" 应用头像的样式。
        -->
        <img :src="userAvatar" alt="用户头像" class="avatar" />
      </div>
      
      <!-- 用户信息部分 -->
      <div class="user-info">
        <!-- 
          显示用户名，使用插值语法 {{ username }} 绑定 data 中的 username。
          class="username" 应用用户名的样式。
        -->
        <h2 class="username">{{ username }}</h2>
        
        <!-- 用户统计信息部分 -->
        <div class="stats">
          
          <!-- 文章评论统计项 -->
          <div class="stat-item">
            <!-- 
              显示文章评论数量，使用插值语法 {{ articleComments }} 绑定 data 中的 articleComments。
              class="stat-number" 应用统计数字的样式。
            -->
            <span class="stat-number">{{ articleComments }}</span>
            <!-- 
              显示统计标签 "文章评论"。
              class="stat-label" 应用统计标签的样式。
            -->
            <span class="stat-label">文章评论</span>
          </div>
          
          <!-- 视频评论统计项 -->
          <div class="stat-item">
            <!-- 
              显示视频评论数量，使用插值语法 {{ videoComments }} 绑定 data 中的 videoComments。
              class="stat-number" 应用统计数字的样式。
            -->
            <span class="stat-number">{{ videoComments }}</span>
            <!-- 
              显示统计标签 "视频评论"。
              class="stat-label" 应用统计标签的样式。
            -->
            <span class="stat-label">视频评论</span>
          </div>
        </div>
        
        <!-- 用户操作按钮部分 -->
        <div class="actions">
          <!-- 
            编辑资料按钮。
            使用 Element UI 的 el-button 组件，类型为 primary。
            @click="handleEdit" 绑定点击事件，调用 handleEdit 方法。
            class="action-btn" 应用按钮的通用样式。
          -->
          <el-button type="primary" @click="handleEdit" class="action-btn">
            编辑资料
          </el-button>
          
          <!-- 
            退出登录按钮。
            使用 Element UI 的 el-button 组件。
            @click="clearSession" 绑定点击事件，调用 clearSession 方法。
            class="action-btn logout-btn" 应用按钮的通用样式和特定的退出样式。
          -->
          <el-button @click="clearSession" class="action-btn logout-btn">
            退出登录
          </el-button>
        </div>
      </div>
    </div>

    <!-- 右侧评论内容区域 -->
    <div class="comment-container">
      <!-- 评论选项卡部分 -->
      <div class="comment-tabs">
        <!-- 
          使用 Element UI 的 el-tabs 组件，v-model 绑定 activeTab，控制当前激活的选项卡。
          @tab-click="handleTabChange" 监听选项卡点击事件，调用 handleTabChange 方法。
        -->
        <el-tabs v-model="activeTab" @tab-click="handleTabChange">
          
          <!-- 文章评论选项卡 -->
          <el-tab-pane label="文章评论" name="article">
            <!-- 
              使用 CommentList 组件显示文章评论列表。
              v-if="activeTab === 'article'" 确保仅在选中 "文章评论" 选项卡时渲染该组件。
              :comments="messages1" 传递文章评论数据。
              type="article" 指定评论类型为文章评论。
            -->
            <comment-list
              v-if="activeTab === 'article'"
              :comments="messages1"
              type="article"
            />
          </el-tab-pane>
          
          <!-- 视频评论选项卡 -->
          <el-tab-pane label="视频评论" name="video">
            <!-- 
              使用 CommentList 组件显示视频评论列表。
              v-if="activeTab === 'video'" 确保仅在选中 "视频评论" 选项卡时渲染该组件。
              :comments="messages2" 传递视频评论数据。
              type="video" 指定评论类型为视频评论。
            -->
            <comment-list
              v-if="activeTab === 'video'"
              :comments="messages2"
              type="video"
            />
          </el-tab-pane>
        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios' // 导入 axios 库用于发送 HTTP 请求
import CommentList from './CommentList.vue' // 导入 CommentList 组件，需要创建此子组件

export default {
  components: {
    CommentList // 注册 CommentList 组件，供模板中使用
  },
  data() {
    return {
      username: '', // 存储用户名，从 sessionStorage 获取
      userAvatar: '../assets/imgs/touxiang.jpg', // 用户头像的默认路径，可以从后端获取
      activeTab: 'article', // 当前激活的选项卡，初始为 'article'
      messages1: [], // 存储文章评论数据
      messages2: [], // 存储视频评论数据
      articleComments: 0, // 文章评论的数量
      videoComments: 0 // 视频评论的数量
    }
  },
  mounted() {
    this.initUserData() // 页面加载完成后，初始化用户数据
    this.getAComments() // 获取文章评论数据
    this.getVComments() // 获取视频评论数据
  },
  methods: {
    /**
     * 初始化用户数据
     * 从 sessionStorage 获取用户名，如果存在则赋值给 this.username
     * 如果不存在，重定向到登录页面
     */
    initUserData() {
      const username = sessionStorage.getItem('Username') // 从 sessionStorage 获取用户名
      if (username) {
        this.username = username // 如果存在，赋值给 data 中的 username
      } else {
        this.$router.push('/login') // 如果不存在，重定向到登录页面
      }
    },
    
    /**
     * 处理编辑资料按钮点击事件
     * 目前仅显示提示信息，实际逻辑需根据需求实现
     */
    handleEdit() {
      // 实现编辑资料的逻辑
      this.$message.info('编辑资料功能开发中') // 显示提示信息
    },
    
    /**
     * 处理退出登录按钮点击事件
     * 弹出确认对话框，确认后清除 sessionStorage 并重定向到登录页面
     */
    clearSession() {
      this.$confirm('确认退出登录吗？', '提示', { // 弹出确认对话框
        confirmButtonText: '确定', // 确认按钮文本
        cancelButtonText: '取消', // 取消按钮文本
        type: 'warning' // 对话框类型为警告
      })
        .then(() => {
          sessionStorage.clear() // 清除 sessionStorage 中的所有数据
          this.$router.push('/login') // 重定向到登录页面
        })
        .catch(() => {
          // 用户取消操作，不做任何处理
        })
    },
    
    /**
     * 处理选项卡切换事件
     * 目前为空方法，可根据需要实现具体逻辑
     */
    handleTabChange() {
      // 处理标签页切换逻辑
      // 可以在这里添加额外的逻辑，例如统计切换次数等
    },
    
    /**
     * 获取文章评论数据
     * 发送 POST 请求到后端 API，获取当前用户的文章评论
     * 成功后，更新 messages1 和 articleComments
     * 失败时，显示错误消息
     */
    async getAComments() {
      try {
        // 发送 POST 请求到获取文章评论的 API
        const response = await axios.post(
          `http://localhost:8080/api/getarticlecomment?username=${this.username}`
        )
        this.messages1 = response.data // 将返回的数据赋值给 messages1
        this.articleComments = response.data.length // 更新文章评论数量
      } catch (error) {
        console.error('获取文章评论失败:', error) // 在控制台输出错误信息
        this.$message.error('获取评论失败') // 显示错误消息给用户
      }
    },
    
    /**
     * 获取视频评论数据
     * 发送 POST 请求到后端 API，获取当前用户的视频评论
     * 成功后，更新 messages2 和 videoComments
     * 失败时，显示错误消息
     */
    async getVComments() {
      try {
        // 发送 POST 请求到获取视频评论的 API
        const response = await axios.post(
          `http://localhost:8080/api/getvideocomment?username=${this.username}`
        )
        this.messages2 = response.data // 将返回的数据赋值给 messages2
        this.videoComments = response.data.length // 更新视频评论数量
      } catch (error) {
        console.error('获取视频评论失败:', error) // 在控制台输出错误信息
        this.$message.error('获取评论失败') // 显示错误消息给用户
      }
    }
  }
}
</script>

<style scoped>
/* 
  用户页面主容器的样式。
  使用 Flexbox 布局，将左右两个部分并排显示。
  - display: flex; 启用 Flexbox 布局
  - padding: 20px; 设置内边距
  - gap: 20px; 设置子元素之间的间距
  - margin-top: 80px; 添加上边距，避免覆盖导航栏
  - min-height: calc(100vh - 140px); 设置最小高度，确保内容区域充满视口高度减去导航栏高度
  - background-color: #f5f7fa; 设置背景颜色
*/
.user-container {
  display: flex;
  padding: 20px;
  gap: 20px;
  margin-top: 80px;  /* 添加上边距，避免覆盖导航栏 */
  min-height: calc(100vh - 140px); /* 减去导航栏高度和上边距 */
  background-color: rgba(255, 255, 255, 0.8); /* 半透明白色背景 */
  border-radius: 10px;
}

/* 
  用户信息卡片的样式。
  - position: sticky; 使卡片在滚动时保持在视口指定位置
  - top: 20px; 卡片距离视口顶部 20px
  - width: 600px; 设置固定宽度
  - background: white; 白色背景
  - border-radius: 12px; 圆角效果
  - padding: 24px; 内边距
  - box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1); 添加阴影，提升立体感
  - height: fit-content; 高度自适应内容
*/
.user-card {
  position: sticky;
  top: 20px;
  width: 600px;
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  height: fit-content;
}

/* 
  头像包裹容器的样式。
  - width: 120px; 设置固定宽度
  - height: 120px; 设置固定高度
  - margin: 0 auto 20px; 上下外边距为 0 和 20px，水平居中
  - position: relative; 相对定位，便于子元素定位
*/
.avatar-wrapper {
  width: 120px;
  height: 120px;
  margin: 0 auto 20px;
  position: relative;
  border-radius: 50%;      /* 确保容器也是圆形 */
  overflow: hidden;        /* 确保图片不会溢出圆形边界 */
  display: flex;          /* 使用flex布局居中图片 */
  justify-content: center;
  align-items: center;
  background: white;      /* 添加背景色 */
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);  /* 添加阴影效果 */
}
/* 
  头像图片的样式。
  - width: 100%; 高度为容器的 100%
  - height: 100%;
  - border-radius: 50%; 圆形头像
  - object-fit: cover; 保持图片比例，覆盖整个容器
  - border: 4px solid #fff; 白色边框
  - box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); 添加阴影，提升立体感
*/
.avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;      /* 确保图片填充满容器 */
  border-radius: 50%;     /* 圆形图片 */
  border: 4px solid #fff; /* 白色边框 */
}

/* 
  用户信息部分的样式。
  - text-align: center; 文字居中对齐
*/
.user-info {
  text-align: center;
}

/* 
  用户名的样式。
  - font-size: 24px; 设置字体大小
  - font-weight: 600; 设置字体粗细
  - color: #333; 设置字体颜色
  - margin: 0 0 20px; 设置下外边距
*/
.username {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  margin: 0 0 20px;
}

/* 
  统计信息部分的样式。
  - display: flex; 启用 Flexbox 布局
  - justify-content: space-around; 子元素在主轴上均匀分布
  - margin-bottom: 24px; 下外边距
  - padding: 12px 0; 上下内边距
  - border-top: 1px solid #eee; 上边框
  - border-bottom: 1px solid #eee; 下边框
*/
.stats {
  display: flex;
  justify-content: space-around;
  margin-bottom: 24px;
  padding: 12px 0;
  border-top: 1px solid #eee;
  border-bottom: 1px solid #eee;
}

/* 
  统计项的样式。
  - display: flex; 启用 Flexbox 布局
  - flex-direction: column; 垂直排列子元素
  - align-items: center; 子元素在交叉轴上居中对齐
*/
.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* 
  统计数字的样式。
  - font-size: 20px; 设置字体大小
  - font-weight: 600; 设置字体粗细
  - color: #333; 设置字体颜色
*/
.stat-number {
  font-size: 20px;
  font-weight: 600;
  color: #333;
}

/* 
  统计标签的样式。
  - font-size: 14px; 设置字体大小
  - color: #666; 设置字体颜色
  - margin-top: 4px; 设置上外边距
*/
.stat-label {
  font-size: 14px;
  color: #666;
  margin-top: 4px;
}

/* 
  操作按钮部分的样式。
  - display: flex; 启用 Flexbox 布局
  - flex-direction: column; 垂直排列子元素
  - gap: 12px; 子元素之间的间距
*/
.actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: stretch; /* 确保按钮在水平方向上拉伸 */
}

.action-btn {
  width: 100%;
  height: 40px;
  border-radius: 20px;
  border: 1px solid transparent; /* 统一边框宽度 */
  box-sizing: border-box; /* 包含边框在内 */
  margin: 0; /* 移除默认外边距 */
  padding: 0; /* 移除默认内边距 */
}

/* 
  主按钮（编辑资料）的特定样式。
  - background-image: linear-gradient(to right, #00e5dd, #00b8fc); 渐变背景
  - border: none; 移除边框
*/
.action-btn.el-button--primary {
  background-image: linear-gradient(to right, #00e5dd, #00b8fc);
  border: 1px solid transparent; /* 保持边框宽度一致 */
}


/* 
  退出登录按钮的特定样式。
  - background: transparent; 透明背景
  - border: 1px solid #dcdfe6; 灰色边框
  - color: #606266; 字体颜色
*/
.logout-btn {
  background: transparent;
  border: 1px solid #dcdfe6;
  color: #606266;
}

/* 
  评论内容区域的样式。
  - flex: 1; 使评论区域占据剩余空间
  - background: white; 白色背景
  - border-radius: 12px; 圆角效果
  - padding: 24px; 内边距
  - box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1); 添加阴影，提升立体感
*/
.comment-container {
  width: 800px; /* 设置固定宽度 */
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

/* 
  评论选项卡部分的样式。
  - margin-top: 20px; 上外边距
*/
.comment-tabs {
  margin-top: 20px;
}

/* 
  Element UI 样式覆盖。
  使用 :deep 选择器，确保样式应用于子组件的深层元素。
*/

/* 覆盖 el-tabs 的下划线 */
:deep(.el-tabs__nav-wrap::after) {
  height: 1px;
}

/* 覆盖 el-tabs__item 的字体大小 */
:deep(.el-tabs__item) {
  font-size: 16px;
}

/* 覆盖活动状态的 el-tabs__item 的颜色 */
:deep(.el-tabs__item.is-active) {
  color: #00b8fc;
}

/* 覆盖 el-tabs 的活动条的颜色 */
:deep(.el-tabs__active-bar) {
  background-color: #00b8fc;
}
</style>
