<!-- Coding by XuHaiying 2212180 -->
<!-- 完成前端VUE界面的设计、相关功能页面的构建，到美化 -->

<!-- Coding by JinagYu 2210705 -->
<!-- 前后段接口对接 -->

<template>
  <div class="profile-container">
    <!-- 头部信息区域 -->
    <div class="profile-hero hero-section">
      <div class="hero-content">
        <div class="profile-info">
          <div class="avatar-section">
            <div class="avatar-wrapper">
              <img :src="userInfo.avatar || defaultAvatar" alt="用户头像" class="avatar">
              <label class="avatar-upload">
                <input type="file" accept="image/*" @change="handleAvatarUpload" hidden>
                <i class="fas fa-camera"></i>
                <span>更换头像</span>
              </label>
            </div>
          </div>
          <div class="user-stats">
            <!-- <div class="stat-item">
              <h3>{{ articleCount || 0 }}</h3>
              <p>已发布文章</p>
            </div>
            <div class="stat-item">
              <h3>{{ videoCount || 0 }}</h3>
              <p>已发布视频</p>
            </div> -->
            <div class="stat-item">
              <h3>{{ userInfo.username }}</h3>
              <p>用户名</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 内容区域 -->
    <div class="content-section">
      <!-- 发布内容选项卡 -->
      <div class="tabs intro-section">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          :class="['tab-btn', { active: currentTab === tab.id }]"
          @click="currentTab = tab.id"
        >
          <i :class="tab.icon"></i>
          {{ tab.name }}
        </button>
      </div>

      <!-- 文章发布表单 -->
      <div v-if="currentTab === 'article'" class="publish-form intro-section">
        <h2 class="section-title">发布文章</h2>
        <div class="form-group">
          <input 
            v-model="articleForm.title" 
            type="text" 
            class="form-input"
            placeholder="请输入文章标题"
          >
        </div>
        <div class="form-group">
          <textarea 
            v-model="articleForm.content" 
            class="form-textarea"
            placeholder="请输入文章内容"
            rows="15"
          ></textarea>
        </div>
        <button 
          class="submit-btn"
          :disabled="!articleForm.title || !articleForm.content || isSubmitting"
          @click="publishArticle"
        >
          <i class="fas fa-paper-plane"></i>
          {{ isSubmitting ? '发布中...' : '发布文章' }}
        </button>
      </div>

      <!-- 视频发布表单 -->
      <div v-if="currentTab === 'video'" class="publish-form intro-section">
        <h2 class="section-title">发布视频</h2>
        <div class="form-group">
          <input 
            v-model="videoForm.title" 
            type="text" 
            class="form-input"
            placeholder="请输入视频标题"
          >
        </div>

        <!-- 视频文件上传 -->
        <div class="form-group">
          <div class="upload-section feature-item" @click="triggerVideoUpload">
            <input 
              type="file" 
              ref="videoInput"
              accept="video/*" 
              @change="handleVideoUpload" 
              hidden
            >
            <div v-if="!videoForm.videoFile" class="upload-placeholder">
              <i class="fas fa-cloud-upload-alt"></i>
              <span>点击上传视频</span>
              <span class="upload-tip">支持 MP4、AVI 格式</span>
            </div>
            <div v-else class="file-info">
              <i class="fas fa-file-video"></i>
              <span>{{ videoForm.videoFile.name }}</span>
            </div>
          </div>
        </div>

        <!-- 封面图片上传 -->
        <div class="form-group">
          <div class="upload-section feature-item" @click="triggerCoverUpload">
            <input 
              type="file" 
              ref="coverInput"
              accept="image/*" 
              @change="handleCoverUpload" 
              hidden
            >
            <div v-if="!videoForm.coverUrl" class="upload-placeholder">
              <i class="fas fa-image"></i>
              <span>点击上传封面</span>
              <span class="upload-tip">推荐尺寸 16:9</span>
            </div>
            <img 
              v-else 
              :src="videoForm.coverUrl" 
              class="cover-preview" 
              alt="视频封面"
            >
          </div>
        </div>

        <button 
          class="submit-btn"
          :disabled="!isVideoFormValid || isSubmitting"
          @click="publishVideo"
        >
          <i class="fas fa-paper-plane"></i>
          {{ isSubmitting ? '发布中...' : '发布视频' }}
        </button>
      </div>
    </div>

    <!-- 加载遮罩 -->
    <div v-if="isSubmitting" class="loading-overlay">
      <div class="loading-content">
        <div class="loading-spinner"></div>
        <span>{{ loadingMessage }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'UserProfile',
  
  data() {
    return {
      defaultAvatar: './src/avatar.png',
      isSubmitting: false,
      loadingMessage: '',
      uploadProgress: 0,
      isVideoUploading: false,
      userInfo: {
        userID: -1,
        username: '',
        role: '',
        avatar: ''
      },
      currentTab: 'article',
      tabs: [
        { id: 'article', name: '发布文章', icon: 'fas fa-pen-fancy' },
        { id: 'video', name: '发布视频', icon: 'fas fa-video' }
      ],
      articleForm: {
        title: '',
        content: ''
      },
      videoForm: {
        title: '',
        coverUrl: '',
        videoFile: null,
        videoUrl: ''
      }
    }
  },

  computed: {
    isVideoFormValid() {
      return this.videoForm.title && 
             this.videoForm.videoFile && 
             this.videoForm.coverUrl
    }
  },

  mounted() {
    this.initializePage()
  },

  methods: {
    async initializePage() {
      await this.getUserInfo()
      this.setupPageTitle()
    },

    setupPageTitle() {
      document.title = `个人中心 - ${this.userInfo.username || '用户'}`
    },

    async getUserInfo() {
      try {
        const userID = sessionStorage.getItem('UserID')
        if (!userID) {
          this.$router.push('/login')
          return
        }
        console.log(userID)
        const response = await axios.get('http://localhost:8080/api/getuser?userId=' + userID)
        if (response.data.status === 1) {
          this.userInfo = response.data.user
        } 
        else {
          this.errorMessage = response.data.message || '失败.'
        }
        // console.log(this.userInfo)
      } 
      catch (error) {
        this.handleError(error, '获取用户信息失败')
      }
    },

    // 头像上传处理
    async handleAvatarUpload(event) {
      const file = event.target.files[0]
      if (!file) return

      if (!this.validateImageFile(file)) {
        this.$message.error('请上传 JPG 或 PNG 格式的图片')
        return
      }

      const formData = new FormData()
      formData.append('avatar', file)
      formData.append('userId', sessionStorage.getItem('UserID'))

      this.isSubmitting = true
      this.loadingMessage = '正在上传头像...'

      try {
        const response = await axios.post('http://localhost:8080/api/updateavatar?userId=' + sessionStorage.getItem('UserID'), formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
        console.log(response)

        this.userInfo.avatar = response.data.url
        this.$message.success('头像更新成功')
      } catch (error) {
        this.handleError(error, '头像上传失败')
      } finally {
        this.isSubmitting = false
      }
    },

    // 视频上传处理
    async handleVideoUpload(event) {
      const file = event.target.files[0]
      if (!file) return

      if (!this.validateVideoFile(file)) {
        this.$message.error('请上传 MP4 或 AVI 格式的视频')
        return
      }

      this.videoForm.videoFile = file
      this.videoForm.videoUrl = URL.createObjectURL(file)
      this.$message.success('视频已选择，请点击发布上传')
    },

    // 封面上传处理
    async handleCoverUpload(event) {
      const file = event.target.files[0]
      if (!file) return

      if (!this.validateImageFile(file)) {
        this.$message.error('请上传 JPG 或 PNG 格式的图片')
        return
      }

      this.videoForm.coverFile = file
      this.videoForm.coverUrl = URL.createObjectURL(file)
      this.$message.success('封面已选择，请点击发布上传')
    },

    // 发布文章
    async publishArticle() {
      if (!this.validateArticleForm()) return

      this.isSubmitting = true
      this.loadingMessage = '正在发布文章...'

      try {
        const titleEncoded = encodeURIComponent(this.articleForm.title)
        const contentEncoded = encodeURIComponent(this.articleForm.content)
        const userId = sessionStorage.getItem('UserID')
        const response = await axios.get('http://localhost:8080/api/addarticle?title=' + titleEncoded + '&content=' + contentEncoded + '&userId=' + userId)
        console.log(response)

        if (response.data.status === 1) {
          this.$message.success('文章发布成功')
          this.resetArticleForm()
        } 
        else {
          throw new Error(response.data.message || '发布失败')
        }
      } catch (error) {
        this.handleError(error, '文章发布失败')
      } finally {
        this.isSubmitting = false
      }
    },

    // 发布视频
    async publishVideo() {
      if (!this.validateVideoForm()) return

      this.isSubmitting = true
      this.loadingMessage = '正在上传并发布视频...'

      try {
        const userId = sessionStorage.getItem('UserID')
        const formData = new FormData()
        formData.append('userId', userId)
        formData.append('title', this.videoForm.title)
        formData.append('video', this.videoForm.videoFile)
        formData.append('cover', this.videoForm.coverFile)

        for (let pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1])
        }

        const response = await axios.post('http://localhost:8080/api/addvideo', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        if (response.data.status === 1) {
          this.$message.success('视频发布成功')
          this.resetVideoForm()
        } else {
          throw new Error(response.data.message || '发布失败')
        }
      } catch (error) {
        this.handleError(error, '视频发布失败')
      } finally {
        this.isSubmitting = false
      }
    },

    // 表单验证
    validateArticleForm() {
      if (!this.articleForm.title.trim()) {
        this.$message.warning('请输入文章标题')
        return false
      }
      if (!this.articleForm.content.trim()) {
        this.$message.warning('请输入文章内容')
        return false
      }
      return true
    },

    validateVideoForm() {
      if (!this.videoForm.title.trim()) {
        this.$message.warning('请输入视频标题')
        return false
      }
      if (!this.videoForm.videoFile) {
        this.$message.warning('请上传视频文件')
        return false
      }
      if (!this.videoForm.coverUrl) {
        this.$message.warning('请上传视频封面')
        return false
      }
      return true
    },

    // 文件验证
    validateImageFile(file) {
      const validTypes = ['image/jpeg', 'image/png']
      return validTypes.includes(file.type)
    },

    validateVideoFile(file) {
      const validTypes = ['video/mp4', 'video/avi']
      return validTypes.includes(file.type)
    },

    // 表单重置
    resetArticleForm() {
      this.articleForm.title = ''
      this.articleForm.content = ''
    },

    resetVideoForm() {
      this.videoForm.title = ''
      this.videoForm.coverUrl = ''
      this.videoForm.videoFile = null
      this.videoForm.videoUrl = ''
    },

    // 错误处理
    handleError(error, message) {
      console.error(error)
      this.$message.error(message)
    },

    // 触发文件选择
    triggerCoverUpload() {
      if (!this.isSubmitting) {
        this.$refs.coverInput.click()
      }
    },

    triggerVideoUpload() {
      if (!this.isSubmitting && !this.isVideoUploading) {
        this.$refs.videoInput.click()
      }
    }
  }
}
</script>
<style scoped>
.profile-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #f6f9fc 0%, #7dbed6 50%, #e5effd 100%);
  color: #495057;
  padding: 2rem;
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* 头部区域 */
.profile-hero {
  min-height: 40vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  background: linear-gradient(rgba(255,255,255,0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  margin-bottom: 3rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
  padding: 2rem;
}

.profile-info {
  max-width: 800px;
}

/* 头像部分 */
.avatar-section {
  margin-bottom: 2rem;
}

.avatar-wrapper {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  margin: 0 auto;
  position: relative;
  border: 3px solid white;
  box-shadow: 0 4px 12px rgba(76, 110, 245, 0.1);
}

.avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-upload {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(76, 110, 245, 0.9);
  color: white;
  padding: 0.5rem;
  text-align: center;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.avatar-wrapper:hover .avatar-upload {
  opacity: 1;
}

/* 用户统计 */
.user-stats {
  display: flex;
  justify-content: center;
  gap: 3rem;
}

.stat-item {
  text-align: center;
  cursor: pointer;
  padding: 1rem 2rem;
  background: white;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(76, 110, 245, 0.1);
  transition: transform 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-5px);
}

.stat-item h3 {
  font-size: 2rem;
  color: #4c6ef5;
  margin-bottom: 0.5rem;
}

.stat-item p {
  color: #495057;
}

/* 内容区域 */
.content-section {
  max-width: 1200px;
  margin: 0 auto;
}

/* 标签页 */
.tabs {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(rgba(255,255,255,0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
  margin-bottom: 2rem;
}

.tab-btn {
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 10px;
  background: white;
  color: #495057;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1rem;
}

.tab-btn:hover {
  background: rgba(76, 110, 245, 0.1);
  color: #4c6ef5;
}

.tab-btn.active {
  background: #4c6ef5;
  color: white;
}

/* 表单区域 */
.publish-form {
  background: linear-gradient(rgba(255,255,255,0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
  padding: 2rem;
}

.section-title {
  font-size: 1.8rem;
  color: #495057;
  margin-bottom: 2rem;
  position: relative;
  padding-bottom: 0.5rem;
}

.section-title:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  border-radius: 2px;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 1rem;
  background: white;
  border: 1px solid rgba(130, 170, 255, 0.2);
  border-radius: 10px;
  color: #495057;
  transition: all 0.3s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #4c6ef5;
  box-shadow: 0 0 0 3px rgba(76, 110, 245, 0.1);
}

/* 上传区域 */
.upload-section {
  border: 2px dashed rgba(76, 110, 245, 0.3);
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: white;
}

.upload-section:hover {
  border-color: #4c6ef5;
  background: rgba(76, 110, 245, 0.05);
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  color: #495057;
}

.upload-placeholder i {
  font-size: 2rem;
  color: #4c6ef5;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid rgba(130, 170, 255, 0.2);
}

.file-info i {
  color: #4c6ef5;
}

/* 提交按钮 */
.submit-btn {
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 1rem 2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 2rem;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(76, 110, 245, 0.2);
}

.submit-btn:disabled {
  background: linear-gradient(90deg, rgba(76, 110, 245, 0.5), rgba(21, 170, 191, 0.5));
  cursor: not-allowed;
}

/* 加载状态 */
.loading-overlay {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
}

.loading-spinner {
  border: 3px solid rgba(76, 110, 245, 0.1);
  border-top-color: #4c6ef5;
}

/* 响应式设计 */
@media (max-width: 768px) {
  .profile-container {
    padding: 1rem;
  }

  .user-stats {
    flex-direction: column;
    gap: 1rem;
  }

  .tabs {
    flex-direction: column;
  }

  .tab-btn {
    width: 100%;
    justify-content: center;
  }

  .section-title {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .profile-hero {
    padding: 1.5rem;
  }

  .avatar-wrapper {
    width: 100px;
    height: 100px;
  }

  .stat-item h3 {
    font-size: 1.5rem;
  }
}
</style>

/**
 * 1. 用户信息接口
 * 接口：GET /api/user/info
 * 参数：
 *   - userId: string (从sessionStorage获取)
 * 返回：
 *   {
 *     username: string,
 *     avatar: string
 *   }
 * 用途：获取用户基本信息
 */

/**
 * 2. 头像上传接口
 * 接口：POST /api/user/avatar
 * 参数：FormData格式
 *   - avatar: File (头像文件)
 *   - userId: string (用户ID)
 * 返回：
 *   {
 *     url: string (头像URL地址)
 *   }
 * 用途：上传用户头像
 */

/**
 * 3. 视频文件上传接口
 * 接口：POST /api/upload/video
 * 参数：FormData格式
 *   - video: File (视频文件)
 * 返回：
 *   {
 *     url: string (视频URL地址)
 *   }
 * 特点：支持上传进度监控
 * 用途：上传视频文件
 */

/**
 * 4. 视频封面上传接口
 * 接口：POST /api/upload/cover
 * 参数：FormData格式
 *   - cover: File (封面图片)
 * 返回：
 *   {
 *     url: string (封面URL地址)
 *   }
 * 用途：上传视频封面图片
 */

/**
 * 5. 发布文章接口
 * 接口：POST /api/article/publish
 * 参数：
 *   {
 *     userId: string,
 *     title: string,
 *     content: string
 *   }
 * 返回：
 *   {
 *     status: number (1: 成功, 0: 失败)
 *   }
 * 用途：发布新文章
 */

/**
 * 6. 发布视频接口
 * 接口：POST /api/video/publish
 * 参数：
 *   {
 *     userId: string,
 *     title: string,
 *     coverUrl: string,
 *     videoUrl: string
 *   }
 * 返回：
 *   {
 *     status: number (1: 成功, 0: 失败)
 *   }
 * 用途：发布新视频
 */

/**
 * 文件格式限制：
 * 1. 图片格式：
 *    - JPEG/JPG
 *    - PNG
 * 
 * 2. 视频格式：
 *    - MP4
 *    - AVI
 */

/**
 * 错误处理：
 * 所有接口在失败时返回标准的错误信息：
 * {
 *   error: string (错误信息)
 * }
 * 
 * HTTP状态码：
 * - 200: 成功
 * - 400: 请求参数错误
 * - 401: 未授权（未登录）
 * - 403: 权限不足
 * - 500: 服务器错误
 */