<!-- Coding by XuHaiying 2212180 -->
<!-- 完成前端VUE界面的设计、相关功能页面的构建，到美化 -->

<!-- Coding by JinagYu 2210705 -->
<!-- 前后段接口对接 -->

<template>
  <div class="videos-container">
    <!-- 头部统计区域 -->
    <div class="header-section">
      <div class="header-content">
        <h1 class="main-title">视频专区</h1>
        <p class="subtitle">探索AI视觉世界</p>
        <div class="video-stats">
          <div class="stat-item">
            <i class="ni ni-camera-compact text-primary"></i>
            <h3>{{ total || 0 }}</h3>
            <p>视频总数</p>
          </div>
          <div class="stat-item">
            <i class="ni ni-watch-time text-info"></i>
            <h3>24/7</h3>
            <p>实时更新</p>
          </div>
          <div class="stat-item">
            <i class="ni ni-time-alarm text-success"></i>
            <h3>{{ currentDate }}</h3>
            <p>最近更新</p>
          </div>
        </div>
      </div>
    </div>

    <!-- 视频列表区域 -->
    <div class="main-content">
      <!-- 加载状态 -->
      <div v-if="loading" class="loading-state">
        <i class="ni ni-settings-gear-65 spin"></i>
        <p>加载中...</p>
      </div>

      <!-- 视频网格 -->
      <div v-else class="videos-grid">
        <div v-for="video in movieList" :key="video.VideoID" class="video-card">
          <router-link :to="'/movie/' + video.VideoID">
            <div class="card-content">
              <div class="thumbnail-wrapper">
                <img :src="video.PictureURL" :alt="video.Title" class="video-thumbnail">
                <div class="play-overlay">
                  <i class="ni ni-button-play"></i>
                </div>
              </div>
              <div class="video-info">
                <h3 class="video-title">{{ video.Title }}</h3>
                <div class="video-meta">
                  <span class="meta-item">
                    <i class="ni ni-calendar-grid-58"></i>
                    {{ formatDate(video.UploadDate) }}
                  </span>
                </div>
              </div>
            </div>
          </router-link>
        </div>
      </div>

      <!-- 分页器 -->
      <div class="pagination-wrapper">
        <el-pagination
          background
          layout="prev, pager, next"
          :total="pagecount"
          :page-size="12"
          hide-on-single-page
          @current-change="handlePageChange"
        />
      </div>
    </div>

    <!-- 返回顶部按钮 -->
    <el-backtop :right="100" :bottom="100" />
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'VideosList',
  
  data() {
    return {
      movieList: [],
      pagecount: 0,
      total: 0,
      loading: false,
      currentDate: new Date().toLocaleDateString('zh-CN')
    }
  },

  mounted() {
    this.getUrl()
    this.getpage()
    this.getTotal()
  },

  methods: {
    async getUrl() {
      this.loading = true
      try {
        const response = await axios.post('http://localhost:8080/api/getvideo')
        this.movieList = response.data
      } catch (error) {
        console.error('请求失败', error)
      } finally {
        this.loading = false
      }
    },

    async getTotal() {
      this.loading = true
      try {
        const response = await axios.post('http://localhost:8080/api/getvideototal')
        if (response.data.status === 1) {
          this.total = response.data.count
        }
      } 
      catch (error) {
        console.error('请求失败', error)
      } 
      finally {
        this.loading = false
      }
    },

    async handlePageChange(page) {
      this.loading = true
      try {
        const response = await axios.post('http://localhost:8080/api/getvideo?page=' + page)
        this.movieList = response.data
        window.scrollTo({ top: 0, behavior: 'smooth' })
      } catch (error) {
        console.error('请求失败', error)
      } finally {
        this.loading = false
      }
    },

    async getpage() {
      try {
        const response = await axios.post('http://localhost:8080/api/getvideopagecount')
        console.log('页数数据:', response.data)
        this.pagecount = response.data.pagecount * 12
      } catch (error) {
        console.error('请求失败', error)
      }
    },

    formatDate(dateString) {
      try {
        const date = new Date(dateString)
        return date.toLocaleDateString('zh-CN')
      } catch (error) {
        return '暂无日期'
      }
    }
  }
}
</script>

<style scoped>
.videos-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  color: #ffffff;
  padding: 5rem;
}

.header-section {
  min-height: 40vh;
  background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
              url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMSkiLz48L3N2Zz4=');
  padding: 4rem 2rem;
  text-align: center;
  backdrop-filter: blur(10px);
}

.main-title {
  font-size: 3.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  background: linear-gradient(90deg, #7795f8, #6772e5);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.subtitle {
  font-size: 1.5rem;
  color: #a8b2d1;
  margin-bottom: 3rem;
}

.video-stats {
  display: flex;
  justify-content: center;
  gap: 4rem;
  margin-top: 2rem;
}

.stat-item {
  text-align: center;
  padding: 1.5rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  backdrop-filter: blur(5px);
  transition: transform 0.3s ease;
  min-width: 200px;
}

.stat-item:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.15);
}

.stat-item i {
  font-size: 2rem;
  margin-bottom: 1rem;
  color: #7795f8;
}

.stat-item h3 {
  font-size: 2rem;
  color: #7795f8;
  margin: 0.5rem 0;
}

.main-content {
  position: relative;
  margin-top: -3rem;
  padding: 3rem 2rem 2rem;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
  z-index: 2;
}

.videos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.video-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 15px;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.video-card:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(119, 149, 248, 0.3);
}

.thumbnail-wrapper {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 宽高比 */
  overflow: hidden;
}

.video-thumbnail {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.play-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.play-overlay i {
  font-size: 3rem;
  color: #ffffff;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.video-card:hover .play-overlay {
  opacity: 1;
}

.video-card:hover .video-thumbnail {
  transform: scale(1.05);
}

.video-info {
  padding: 1.5rem;
}

.video-title {
  font-size: 1.25rem;
  color: #ffffff;
  margin: 0 0 1rem;
  line-height: 1.4;
}

.video-meta {
  display: flex;
  gap: 1rem;
  color: #a8b2d1;
  font-size: 0.9rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.meta-item i {
  color: #7795f8;
}

.pagination-wrapper {
  display: flex;
  justify-content: center;
  padding: 2rem 0;
}

.loading-state {
  text-align: center;
  padding: 3rem;
}

.loading-state i {
  font-size: 2rem;
  color: #7795f8;
}

.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* Element UI 分页器样式 */
:deep(.el-pagination.is-background .el-pager li:not(.disabled).active) {
  background-color: #7795f8;
}

:deep(.el-pagination.is-background .el-pager li:not(.disabled):hover) {
  color: #7795f8;
}

/* 响应式设计 */
@media (max-width: 768px) {
  .header-section {
    padding: 3rem 1rem;
    min-height: auto;
  }

  .main-title {
    font-size: 2.5rem;
  }

  .subtitle {
    font-size: 1.2rem;
  }

  .video-stats {
    flex-direction: column;
    gap: 1rem;
  }

  .stat-item {
    min-width: auto;
  }

  .main-content {
    margin-top: -30px;
    padding: 1rem;
  }

  .videos-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .video-info {
    padding: 1rem;
  }
}
</style>