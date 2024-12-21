<template>
  <div class="article-page">
    <div class="article-container">
      <!-- 文章头部区域 -->
      <div class="article-hero-section">
        <div class="article-hero-content">
          <h1 class="article-title">{{ title }}</h1>
          <div class="article-meta">
            <div class="meta-item">
              <span class="meta-icon">
                <i class="fas fa-calendar-alt"></i>
              </span>
              <span class="meta-text">{{ articleTime }}</span>
            </div>
            <div class="meta-item like-section">
              <div class="like-wrapper">
                <button 
                  class="like-button"
                  :class="{ 'liked': like }"
                >
                  <LikeBtn 
                    :like="like" 
                    :userid="userID" 
                    :id="id" 
                    :type="a" 
                    @click="getLikeNum()" 
                  />
                  <span class="like-count">{{ likeNum }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 文章内容区域 -->
      <div class="content-section">
        <div class="article-content glass-card" v-html="content"></div>
      </div>

      <!-- 评论区域 -->
      <div class="comment-section glass-card">
        <h2 class="section-title">
          <i class="fas fa-comments"></i>
          读者评论
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
import axios from 'axios';
import LikeBtn from '../components/LikeBtn.vue';

export default {
  name: 'ArticlePlay',
  
  components: {
    LikeBtn,
  },

  data() {
    return {
      userID: '',
      title: '',
      content: '',
      articleTime: '',
      likeNum: '',
      messages: [],
      message: '',
      id: '',
      like: false,
      a: 'a',
    };
  },

  mounted() {
    this.userID = sessionStorage.getItem('UserID');
    this.getUrl();
    this.getComments();
    this.addClick();
    this.getLikeNum();
    this.likeOr();
    this.id = this.$route.params.id;
    
    // 添加字体图标
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
    document.head.appendChild(link);
  },

  methods: {
    getUrl() {
      const id = this.$route.params.id;
      axios
        .get('http://localhost:8080/api/getarticle?id=' + id)
        .then((response) => {
          this.title = response.data.Title;
          this.content = response.data.Content;
          this.articleTime = response.data.PublicationDate;
        })
        .catch((error) => {
          console.error('请求数据失败', error);
          this.$message.error('获取文章失败');
        });
    },

    getLikeNum() {
      const id = this.$route.params.id;
      axios
        .get('http://localhost:8080/api/likenumarticle?articleId=' + id)
        .then((response) => {
          this.likeNum = response.data.likeCount;
        })
        .catch((error) => {
          console.error('请求数据失败', error);
        });
    },

    likeOr() {
      const id = this.$route.params.id;
      const userid = sessionStorage.getItem('UserID');
      axios
        .get('http://localhost:8080/api/getlikearticle?userId=' + userid + '&articleId=' + id)
        .then((response) => {
          this.like = response.data.liked;
        })
        .catch((error) => {
          console.error('请求数据失败', error);
        });
    },

    getComments() {
      const id = this.$route.params.id;
      axios
        .post('http://localhost:8080/api/showcommentarticle?articleId=' + id)
        .then((response) => {
          this.messages = response.data.comments;
        })
        .catch((error) => {
          console.error('请求数据失败', error);
          this.$message.error('获取评论失败');
        });
    },

    addClick() {
      const id = this.$route.params.id;
      axios
        .get('http://localhost:8080/api/viewarticle?id=' + id)
        .catch((error) => {
          console.error('请求失败', error);
        });
    },

    submitMessage() {
      if (!this.message.trim()) {
        this.$message.warning('请填写评论内容！');
        return;
      }

      const userid = sessionStorage.getItem('UserID');
      const id = this.$route.params.id;
      
      const url = `http://localhost:8080/api/commentarticle?userId=${userid}&articleId=${id}&content=${encodeURIComponent(
        this.message
      )}`;
      
      axios
        .get(url)
        .then((response) => {
          const status = response.data.status;
          if (status === 0) {
            this.$message.error('添加评论失败');
          } else {
            this.message = '';
            this.getComments();
            this.$message.success('评论成功');
          }
        })
        .catch((error) => {
          console.error('发送数据失败', error);
          this.$message.error('添加评论失败');
        });
    },

    getAvatarColor(username) {
      let hash = 0;
      for (let i = 0; i < username.length; i++) {
        hash = username.charCodeAt(i) + ((hash << 5) - hash);
      }
      const hue = hash % 360;
      return `hsl(${hue}, 70%, 60%)`;
    }
  }
};
</script>

<style>
.article-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  color: #ffffff;
  padding: 2rem;
}

.article-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.article-hero-section {
  min-height: 30vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 20px;
  margin-bottom: 2rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 2rem;
}

.article-hero-content {
  width: 100%;
  max-width: 800px;
}

.article-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  background: linear-gradient(90deg, #7795f8, #6772e5);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  line-height: 1.2;
}

.article-meta {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 2rem;
  color: #a8b2d1;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 15px;
}

.meta-icon {
  font-size: 1.2rem;
}

.like-section {
  background: none;
}

.like-wrapper {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.like-button {
  background: none;
  border: none;
  padding: 0.5rem;
  transition: transform 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.like-button:hover {
  transform: scale(1.1);
}

.like-count {
  color: #7795f8;
  font-size: 1.1rem;
}

.glass-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: transform 0.3s ease;
}

.content-section {
  margin: 2rem 0;
}

.article-content {
  padding: 2rem;
  font-size: 1.1rem;
  line-height: 1.8;
  color: #e6e9f0;
}

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
  .article-page {
    padding: 1rem;
  }

  .article-title {
    font-size: 2rem;
  }

  .article-meta {
    flex-direction: column;
    gap: 1rem;
  }

  .comment-section {
    padding: 1rem;
  }

  .message-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .user-avatar {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }

  .submit-button {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .article-hero-section {
    min-height: 20vh;
    padding: 1rem;
  }

  .article-title {
    font-size: 1.5rem;
  }

  .meta-item {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
  }

  .article-content {
    padding: 1rem;
    font-size: 1rem;
  }

  .comment-textarea {
    min-height: 100px;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .message-card {
    padding: 1rem;
  }
}

/* 打印样式 */
@media print {
  .article-page {
    background: none;
  }

  .article-hero-section {
    background: none;
    min-height: auto;
    padding: 1rem 0;
  }

  .article-title {
    color: #000;
    -webkit-text-fill-color: initial;
  }

  .meta-item,
  .like-section,
  .comment-section {
    display: none;
  }

  .article-content {
    color: #000;
  }

  .glass-card {
    background: none;
    border: none;
  }
}
</style>