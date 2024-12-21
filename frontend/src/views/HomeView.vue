<template>
  <div class="home-container">
    <!-- 网站介绍区域 -->
    <div class="hero-section">
      <div class="hero-content">
        <h1 class="main-title">探索 AI 的未来</h1>
        <p class="subtitle">发现人工智能的无限可能</p>
        <div class="hero-stats">
          <div class="stat-item" @click="addWebView">
            <h3>{{ viewCount }}</h3>
            <p>今日访问</p>
          </div>
          <div class="stat-item">
            <h3>40+</h3>
            <p>AI 平台</p>
          </div>
          <div class="stat-item">
            <h3>24/7</h3>
            <p>实时更新</p>
          </div>
        </div>
      </div>
    </div>

    <!-- 网站介绍部分 -->
    <div class="intro-section">
      <h2 class="section-title">关于我们</h2>
      <div class="intro-grid">
        <div class="intro-content">
          <p class="intro-paragraph">
            欢迎来到AI News Hub，这是一个专注于AI领域前沿动态的资讯平台。我们致力于为用户提供最新、最权威的人工智能发展资讯，涵盖从OpenAI到各大科技公司的创新突破。
          </p>
          <div class="feature-list">
            <div class="feature-item">
              <span class="feature-icon">🔬</span>
              <div class="feature-text">最新AI研究成果解读</div>
            </div>
            <div class="feature-item">
              <span class="feature-icon">🚀</span>
              <div class="feature-text">AI产品发布动态</div>
            </div>
            <div class="feature-item">
              <span class="feature-icon">💡</span>
              <div class="feature-text">AI技术应用案例</div>
            </div>
            <div class="feature-item">
              <span class="feature-icon">🌐</span>
              <div class="feature-text">全球AI发展趋势</div>
            </div>
          </div>
        </div>
        <div class="intro-stats">
          <div class="stat-box">
            <div class="stat-number">100+</div>
            <div class="stat-label">每日更新文章</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">50+</div>
            <div class="stat-label">合作伙伴</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">10k+</div>
            <div class="stat-label">月活跃用户</div>
          </div>
          <div class="stat-box">
            <div class="stat-number">24/7</div>
            <div class="stat-label">实时资讯</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 新闻轮播部分 -->
    <div class="news-section">
      <div class="section-header">
        <h2 class="section-title">AI 前沿资讯</h2>
        <span class="update-time">最后更新: {{ currentDate }}</span>
      </div>
      <div class="carousel-wrapper">
        <el-carousel :interval="3000" type="card" height="400px" trigger="click">
          <el-carousel-item v-for="(image, index) in imagePaths" :key="index">
            <a :href="image.link" target="_blank" rel="noopener noreferrer" class="carousel-link">
              <div class="carousel-card">
                <img :src="image.src" alt="News Image" class="carousel-image" loading="lazy" />
                <div class="carousel-overlay">
                  <span class="carousel-tag">AI News</span>
                  <span class="news-time">{{ image.updateTime }}</span>
                </div>
              </div>
            </a>
          </el-carousel-item>
        </el-carousel>
      </div>
    </div>

    <!-- 视频展示区域 -->
    <div class="video-section">
    <div class="section-header">
      <h2 class="section-title">精选视频</h2>
      <span class="update-time">最后更新: {{ currentDate }}</span>
    </div>
    <div class="video-grid">
      <!-- 视频1 -->
      <div class="video-card">
        <div class="card-header">
          <h3 class="video-title">OpenAI 年度直播</h3>
          <span class="video-update-time">2024-03-21</span>
        </div>
        <div class="video-container">
          <video id="player1" playsinline controls>
            <source src="/src/assets/videos/OpenAI年度直播.mp4" type="video/mp4" />
          </video>
        </div>
      </div>

      <!-- 视频2 -->
      <div class="video-card">
        <div class="card-header">
          <h3 class="video-title">Sora 视频全集</h3>
          <span class="video-update-time">2024-03-20</span>
        </div>
        <div class="video-container">
          <video id="player2" playsinline controls>
            <source src="/src/assets/videos/sora视频.mp4" type="video/mp4" />
          </video>
        </div>
      </div>

      <!-- 视频3 -->
      <div class="video-card">
        <div class="card-header">
          <h3 class="video-title">Claude AI 应用展示</h3>
          <span class="video-update-time">2024-03-19</span>
        </div>
        <div class="video-container">
          <video id="player3" playsinline controls>
            <source src="/src/assets/videos/claude视频.mp4" type="video/mp4" />
          </video>
        </div>
      </div>

      <!-- 视频4 -->
      <div class="video-card">
        <div class="card-header">
          <h3 class="video-title">GPT-4V 技术解析</h3>
          <span class="video-update-time">2024-03-18</span>
        </div>
        <div class="video-container">
          <video id="player4" playsinline controls>
            <source src="/src/assets/videos/gpt4v视频.mp4" type="video/mp4" />
          </video>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import Plyr from 'plyr'
import 'plyr/dist/plyr.css'
import axios from 'axios'

export default {
  name: 'Home',
  data() {
    return {
      currentDate: new Date().toLocaleDateString('zh-CN'),
      imagePaths: [
        { src: 'src/assets/imgs/change1.jpg', link: 'https://openai.com/', updateTime: '2024-03-21' },
        { src: 'src/assets/imgs/change2.webp', link: 'https://openai.com/index/learning-to-reason-with-llms/', updateTime: '2024-03-20' },
        { src: 'src/assets/imgs/change3.jpg', link: 'https://www.bing.com/?mkt=zh-CN', updateTime: '2024-03-19' },
        { src: 'src/assets/imgs/change4.png', link: 'https://www.doubao.com/chat/', updateTime: '2024-03-18' },
        { src: 'src/assets/imgs/change5.jpg', link: 'https://www.cursor.com/', updateTime: '2024-03-17' },
        { src: 'src/assets/imgs/change6.png', link: 'https://x.ai/', updateTime: '2024-03-16' },
        { src: 'src/assets/imgs/change7.jpg', link: 'https://sora.com/', updateTime: '2024-03-15' },
        { src: 'src/assets/imgs/change8.jpeg', link: 'https://claude.ai/login', updateTime: '2024-03-14' },
      ],
      videoList: [
        {
          id: 1,
          title: 'OpenAI 年度直播',
          src: '../assets/videos/OpenAI年度直播.mp4',
          updateTime: '2024-03-21'
        },
        {
          id: 2,
          title: 'Sora 视频全集',
          src: '../assets/videos/sora视频.mp4',
          updateTime: '2024-03-20'
        },
        {
          id: 3,
          title: 'Claude AI 应用展示',
          src: '../assets/videos/claude视频.mp4',
          updateTime: '2024-03-19'
        },
        {
          id: 4,
          title: 'GPT-4V 技术解析',
          src: '../assets/videos/gpt4v视频.mp4',
          updateTime: '2024-03-18'
        }
      ],
      viewCount: 0,
      players: []
    }
  },
  mounted() {
    this.initPlayer()
    this.getWebViews()
  },
  beforeDestroy() {
    this.players.forEach(player => {
      if (player) {
        player.destroy()
      }
    })
  },
  methods: {
    initPlayer() {
      const playerOptions = {
        controls: [
          'play-large',
          'play',
          'progress',
          'current-time',
          'mute',
          'volume',
          'fullscreen'
        ],
        speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2] }
      }
      
      this.videoList.forEach(video => {
        const player = new Plyr(`#player${video.id}`, playerOptions)
        this.players.push(player)
      })
    },
    async getWebViews() {
      try {
        const response = await axios.get('http://localhost:8080/api/getwebviews')
        this.viewCount = response.data.visitCount
        console.log('访问量:', this.viewCount)
      } catch (error) {
        console.error('获取访问量失败:', error)
      }
    },
    async addWebView() {
      try {
        // 先增加访问量
        await axios.post('http://localhost:8080/api/addwebviews')
        // 然后重新获取最新访问量
        await this.getWebViews()
      } catch (error) {
        console.error('增加访问量失败:', error)
      }
    }
  }
}
</script>

<style scoped>
.home-container {
  min-height: 100vh;
  /* 更丰富的背景渐变 */
  background: linear-gradient(135deg, #f6f9fc 0%, #7dbed6 50%, #e5effd 100%);
  color: #495057;
  padding: 2rem;
  /* 添加背景渐变动画 */
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
}
/* 添加背景动画关键帧 */
@keyframes gradientBG {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.hero-section {
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  background: linear-gradient(rgba(255,255,255,0.8), rgba(237, 242, 247, 0.8)),
              url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSJyZ2JhKDAsIDAsIDAsIDAuMSkiLz48L3N2Zz4=');
  /* 添加微妙的hover效果 */
  transition: all 0.5s ease;
  border-radius: 20px;
  margin-bottom: 2rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
}

.hero-content {
  max-width: 800px;
  padding: 2rem;
}

.main-title {
  font-size: 4rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}


.subtitle {
  font-size: 1.5rem;
  margin-bottom: 3rem;
  color: #495057;
}

.hero-stats {
  display: flex;
  justify-content: center;
  gap: 4rem;
}

.stat-item {
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  border-radius: 15px;
  backdrop-filter: blur(5px);
  border: 1px solid rgba(130, 170, 255, 0.2);
}

.stat-item:hover {
  transform: translateY(-5px);
  background: linear-gradient(135deg, rgba(237, 242, 247, 0.95), rgba(255, 255, 255, 0.95));
  border: 1px solid rgba(130, 170, 255, 0.3);
}

.stat-item h3 {
  font-size: 2.5rem;
  color: #4c6ef5;
  margin-bottom: 0.5rem;
}

.intro-section {
  margin-bottom: 4rem;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
  transition: transform 0.3s ease;
}

.intro-section:hover {
  transform: translateY(-5px);
}

.intro-grid {
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 3rem;
  align-items: start;
}

.intro-content {
  color: #495057;
}

.intro-paragraph {
  font-size: 1.1rem;
  line-height: 1.8;
  margin-bottom: 1.5rem;
}

.feature-list {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 12px;
  border: 1px solid rgba(130, 170, 255, 0.15);
  transition: all 0.3s ease;
}

.feature-item:nth-child(1) {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(230, 255, 250, 0.8));
}

.feature-item:nth-child(2) {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 240, 245, 0.8));
}

.feature-item:nth-child(3) {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(240, 255, 240, 0.8));
}

.feature-item:nth-child(4) {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 250, 240, 0.8));
}

.feature-item:hover {
  transform: translateX(10px);
  background: linear-gradient(135deg, rgba(76, 110, 245, 0.1), rgba(21, 170, 191, 0.1));
}

.feature-icon {
  font-size: 1.5rem;
}

.feature-text {
  font-size: 0.95rem;
  color: #495057;
}

.intro-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.stat-box {
  background: linear-gradient(135deg, rgba(76, 110, 245, 0.1), rgba(21, 170, 191, 0.1));
  padding: 1.5rem;
  border-radius: 15px;
  text-align: center;
  transition: transform 0.3s ease;
  border: 1px solid rgba(130, 170, 255, 0.2);
  box-shadow: 0 4px 12px rgba(130, 170, 255, 0.1);
}

.stat-box:hover {
  transform: translateY(-5px);
}

.stat-number {
  font-size: 2rem;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #495057;
  font-size: 0.9rem;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding: 0 1rem;
}

.section-title {
  font-size: 2rem;
  color: #495057;
  margin: 0;
}

.update-time {
  font-size: 0.9rem;
  color: #495057;
  background: linear-gradient(135deg, rgba(76, 110, 245, 0.1), rgba(21, 170, 191, 0.1));
  padding: 0.5rem 1rem;
  border-radius: 20px;
  border: 1px solid rgba(130, 170, 255, 0.2);
}

.news-section, .video-section {
  margin-bottom: 4rem;
}

.carousel-wrapper {
  margin: 0 -2rem;
  padding: 2rem;
}

.carousel-card {
  position: relative;
  height: 100%;
  border-radius: 15px;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
}

.carousel-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.carousel-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 2rem;
  background: linear-gradient(transparent, rgba(255, 255, 255, 0.9));
}

.carousel-tag {
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  color: white;
}

.video-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
}

.video-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  border-radius: 15px;
  overflow: hidden;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
  transition: transform 0.3s ease;
}

.video-card:hover {
  transform: translateY(-5px);
}

.card-header {
  padding: 1.5rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(237, 242, 247, 0.95));
  border-bottom: 1px solid rgba(130, 170, 255, 0.15);
}

.video-title {
  color: #495057;
  font-size: 1.25rem;
  margin: 0;
}

.video-update-time {
  font-size: 0.8rem;
  color: #868e96;
  margin-top: 0.5rem;
  display: block;
}

.video-container {
  padding: 1rem;
  aspect-ratio: 16/9;
}

.video-container video {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  object-fit: cover;
}

/* Plyr播放器自定义样式 */
:deep(.plyr) {
  --plyr-color-main: #4c6ef5;
  --plyr-video-background: transparent;
  border-radius: 10px;
}

/* Element UI Carousel自定义样式 */
:deep(.el-carousel__item) {
  border-radius: 15px;
}

/* 响应式布局 */
@media (max-width: 768px) {
  .home-container {
    padding: 1rem;
  }

  .main-title {
    font-size: 2.5rem;
  }

  .subtitle {
    font-size: 1.2rem;
  }

  .hero-stats {
    flex-direction: column;
    gap: 1rem;
  }

  .stat-item {
    padding: 1rem;
  }

  .intro-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .feature-list {
    grid-template-columns: 1fr;
  }

  .intro-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .carousel-wrapper {
    margin: 0;
    padding: 1rem;
  }

  .video-grid {
    grid-template-columns: 1fr;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
}

@media (max-width: 480px) {
  .intro-stats {
    grid-template-columns: 1fr;
  }

  .stat-item h3 {
    font-size: 2rem;
  }

  .video-container {
    padding: 0.5rem;
  }

  .update-time {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
  }
}
</style>