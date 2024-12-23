<!-- Coding by XuHaiying 2212180 -->
<!-- 完成前端VUE界面的设计、相关功能页面的构建，到美化 -->

<!-- Coding by JinagYu 2210705 -->
<!-- 前后段接口对接 -->

<template>
  <div class="video-page">
    <div class="video-container">
      <!-- 视频播放区域 -->
      <div class="video-player-section glass-card">
        <div class="player-wrapper">
          <video id="player" playsinline controls>
            <source :src="videoSrc" type="video/mp4">
          </video>
        </div>
      </div>

      <!-- 视频信息区域 -->
      <div class="video-meta glass-card">
        <div class="meta-stats">
          <div class="meta-item">
            <span class="meta-icon">
              <i class="fas fa-calendar-alt"></i>
            </span>
            <span class="meta-text">{{ videoTime }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-icon">
              <i class="fas fa-heart"></i>
            </span>
            <span class="meta-text">{{ likeNum }} 个点赞</span>
          </div>
        </div>
        
        <div class="like-section">
          <LikeBtn 
            :like="like" 
            :userid="userID" 
            :id="id" 
            :type="v" 
            @click="getLikeNum()"
          />
        </div>
      </div>

      <!-- 评论区域 -->
      <div class="comment-section glass-card">
        <h2 class="section-title">
          <i class="fas fa-comments"></i>
          观众评论
        </h2>
        
        <!-- 评论输入框 -->
        <div class="comment-form">
          <textarea 
            v-model="message" 
            placeholder="分享您的想法..." 
            class="comment-textarea"
          ></textarea>
          <button 
            id="submitBtn" 
            @click="submitMessage"
            class="submit-button"
            :disabled="!message.trim()"
          >
            <i class="fas fa-paper-plane"></i>
            发表评论
          </button>
        </div>

        <!-- 评论列表 -->
        <transition-group name="comment-list" tag="div" class="message-board">
          <div 
            v-for="(msg, index) in messages" 
            :key="msg.id || index" 
            class="message-card"
          >
            <div class="message-header">
              <div class="user-info">
                <div class="user-avatar" :style="{ backgroundColor: getAvatarColor(msg.Username) }">
                  {{ msg.Username.charAt(0).toUpperCase() }}
                </div>
                <strong class="username">{{ msg.Username }}</strong>
              </div>
              <span class="comment-time">
                <i class="far fa-clock"></i>
                {{ msg.CommentedAt }}
              </span>
            </div>
            <div class="message-content">{{ msg.Content }}</div>
          </div>
        </transition-group>
      </div>
    </div>
  </div>
</template>

<script>
import Plyr from 'plyr'
import 'plyr/dist/plyr.css'
import axios from 'axios'
import LikeBtn from '../components/LikeBtn.vue'

export default {
  name: 'VideoPlay',
  
  components: {
    LikeBtn
  },

  data() {
    return {
      userID: '',
      videoSrc: '',
      videoTime: '',
      likeNum: '',
      messages: [],
      message: '',
      id: '',
      like: false,
      v: 'v'
    }
  },

  mounted() {
    this.userID = sessionStorage.getItem('UserID')
    this.initPlayer()
    this.getUrl()
    this.getComments()
    this.addClick()
    this.getLikeNum()
    this.likeOr()
    this.id = this.$route.params.id
    
    // 添加字体图标
    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
    document.head.appendChild(link)
  },

  methods: {
    initPlayer() {
      this.player = new Plyr('#player', {
        controls: [
          'play-large',
          'play',
          'progress',
          'current-time',
          'mute',
          'volume',
          'settings',
          'fullscreen'
        ],
        speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2] }
      })
    },

    getAvatarColor(username) {
      let hash = 0
      for (let i = 0; i < username.length; i++) {
        hash = username.charCodeAt(i) + ((hash << 5) - hash)
      }
      const hue = hash % 360
      return `hsl(${hue}, 70%, 60%)`
    },

    // 保持原有的API调用方法不变
    getUrl() {
      const id = this.$route.params.id
      axios
        .get('http://localhost:8080/api/getvideo?id=' + id)
        .then((response) => {
          this.videoSrc = response.data.URL
          this.videoTime = response.data.UploadDate
          this.player.source = {
            type: 'video',
            sources: [{ src: this.videoSrc, type: 'video/mp4' }]
          }
        })
        .catch((error) => {
          console.error('请求数据失败', error)
          this.$message.error('获取视频失败')
        })
    },

    getLikeNum() {
      const id = this.$route.params.id
      axios
        .get('http://localhost:8080/api/likenumvideo?videoId=' + id)
        .then((response) => {
          this.likeNum = response.data.likeCount
        })
        .catch((error) => {
          console.error('请求数据失败', error)
        })
    },

    likeOr() {
      const userID = sessionStorage.getItem('UserID')
      const id = this.$route.params.id
      axios
        .get('http://localhost:8080/api/getlikevideo?userId=' + userID + '&videoId=' + id)
        .then((response) => {
          this.like = response.data.liked
        })
        .catch((error) => {
          console.error('请求数据失败', error)
        })
    },

    getComments() {
      const id = this.$route.params.id
      axios
        .get('http://localhost:8080/api/showcommentvideo?videoId=' + id)
        .then((response) => {
          this.messages = response.data.comments
        })
        .catch((error) => {
          console.error('请求数据失败', error)
        })
    },

    addClick() {
      const id = this.$route.params.id
      axios
        .get('http://localhost:8080/api/viewvideo?id=' + id)
        .catch((error) => {
          console.error('请求失败', error)
        })
    },

    submitMessage() {
      if (!this.message.trim()) {
        this.$message.warning('请填写评论内容！')
        return
      }

      const userID = sessionStorage.getItem('UserID')
      const id = this.$route.params.id
      
      const url = `http://localhost:8080/api/commentvideo?userId=${userID}&videoId=${id}&content=${encodeURIComponent(
        this.message
      )}`
      
      axios
        .get(url)
        .then((response) => {
          const status = response.data.status
          if (status === -1) {
            this.$message.error('添加评论失败')
          } else {
            this.message = ''
            this.getComments()
            this.$message.success('评论成功')
          }
        })
        .catch((error) => {
          console.error('发送数据失败', error)
          this.$message.error('添加评论失败')
        })
    }
  }
}
</script>

<style scoped>
.video-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  color: #ffffff;
  padding: 2rem;
}

.video-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.glass-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease;
}

/* 视频播放器样式 */
.video-player-section {
  width: 100%;
  padding: 1.5rem;
}

.player-wrapper {
  width: 100%;
  border-radius: 12px;
  overflow: hidden;
  background: #000;
}

/* Plyr播放器自定义样式 */
:deep(.plyr) {
  --plyr-color-main: #7795f8;
  --plyr-video-background: #000;
  border-radius: 12px;
}

/* 视频信息样式 */
.video-meta {
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.meta-stats {
  display: flex;
  gap: 2rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #a8b2d1;
}

.meta-icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(119, 149, 248, 0.1);
  border-radius: 50%;
}

/* 评论区样式 */
.comment-section {
  padding: 2rem;
}

.section-title {
  font-size: 1.8rem;
  color: #ffffff;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.comment-form {
  margin-bottom: 2rem;
}

.comment-textarea {
  width: 100%;
  min-height: 120px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  padding: 1rem;
  color: #ffffff;
  font-size: 1rem;
  resize: vertical;
  margin-bottom: 1rem;
  transition: border-color 0.3s ease;
}

.comment-textarea:focus {
  outline: none;
  border-color: #7795f8;
}

.submit-button {
  background: linear-gradient(90deg, #7795f8, #6772e5);
  color: #ffffff;
  border: none;
  border-radius: 12px;
  padding: 0.8rem 2rem;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  float: right;
}

.submit-button:hover {
  transform: translateY(-2px);
  opacity: 0.9;
}

.submit-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

/* 评论列表样式 */
.message-board {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.message-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 15px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.message-card:hover {
  transform: translateY(-2px);
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.2rem;
}

.username {
  color: #ffffff;
  font-size: 1.1rem;
}

.comment-time {
  color: #a8b2d1;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.message-content {
  color: #e6e9f0;
  line-height: 1.6;
}

/* 动画效果 */
.comment-list-enter-active,
.comment-list-leave-active {
  transition: all 0.5s ease;
}

.comment-list-enter-from,
.comment-list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

/* 响应式设计 */
@media (max-width: 768px) {
  .video-page {
    padding: 1rem;
  }

  .video-meta {
    flex-direction: column;
    gap: 1rem;
  }

  .meta-stats {
    flex-direction: column;
    gap: 0.5rem;
  }

  .submit-button {
    width: 100%;
    justify-content: center;
  }
}

/* 暗色模式优化 */
@media (prefers-color-scheme: dark) {
  .video-page {
    background: linear-gradient(135deg, #0f1219 0%, #1a1f35 100%);
  }
}
</style>