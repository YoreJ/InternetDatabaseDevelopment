<template>
  <div class="articles-container">
    <!-- 头部统计区域 -->
    <div class="header-section">
      <div class="header-content">
        <h1 class="main-title">文章资讯</h1>
        <p class="subtitle">探索AI的前沿动态</p>
        <div class="article-stats">
          <div class="stat-item">
            <i class="ni ni-books text-primary"></i>
            <!-- 修改显示方式，避免除法运算错误 -->
            <h3>{{ Math.floor(pagecount/10) || 0 }}</h3>
            <p>文章总数</p>
          </div>
          <div class="stat-item">
            <i class="ni ni-chart-bar-32 text-info"></i>
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

    <!-- 文章列表区域 - 调整边距 -->
    <div class="main-content">
      <!-- 加载状态 -->
      <div v-if="loading" class="loading-state">
        <i class="ni ni-settings-gear-65 spin"></i>
        <p>加载中...</p>
      </div>

      <!-- 文章列表 -->
      <div v-else class="articles-grid">
        <div
          v-for="article in articleList"
          :key="article.ArticleID"
          class="article-card"
        >
          <router-link :to="'/article/' + article.ArticleID">
            <div class="card-content">
              <div class="article-info">
                <h3 class="article-title">{{ article.Title }}</h3>
                <div class="article-meta">
                  <span class="meta-item">
                    <i class="ni ni-calendar-grid-58"></i>
                    {{ formatDate(article.CreateTime || new Date()) }}
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
          hide-on-single-page
          @current-change="handlePageChange"
        />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ArticlesList',
  data() {
    return {
      articleList: [],
      pagecount: 0, // 初始值改为0
      loading: false,
      currentDate: new Date().toLocaleDateString('zh-CN')
    }
  },
  mounted() {
    this.getUrl()
    this.getpage()
  },
  methods: {
    async getUrl() {
      this.loading = true
      try {
        const response = await axios.post('http://localhost:8080/api/getarticle')
        console.log('文章数据:', response.data) // 调试日志
        if (Array.isArray(response.data)) {
          this.articleList = response.data
        }
      } catch (error) {
        console.error('请求失败', error)
      } finally {
        this.loading = false
      }
    },
    async handlePageChange(page) {
      this.loading = true
      try {
        const response = await axios.post('http://localhost:8080/api/getarticle?page=' + page)
        if (Array.isArray(response.data)) {
          this.articleList = response.data
          window.scrollTo({ top: 0, behavior: 'smooth' })
        }
      } catch (error) {
        console.error('请求失败', error)
      } finally {
        this.loading = false
      }
    },
    async getpage() {
      try {
        const response = await axios.post('http://localhost:8080/api/getarticlepagecount')
        console.log('页数数据:', response.data) // 调试日志
        this.pagecount = response.data * 10
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
.articles-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  color: #ffffff;
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

.article-stats {
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

/* 调整主内容区域位置 */
.main-content {
  position: relative;
  margin-top: -3rem; /* 减小负边距 */
  padding: 3rem 2rem 2rem;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
  z-index: 2;
}

.articles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.article-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 15px;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid rgba(255, 255, 255, 0.1);
  height: 100%;
}

.article-card:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(119, 149, 248, 0.3);
}

.card-content {
  padding: 2rem;
  height: 100%;
}

.article-title {
  font-size: 1.25rem;
  color: #ffffff;
  margin: 0 0 1rem;
  line-height: 1.4;
}

.article-meta {
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

/* 加载状态样式 */
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

  .article-stats {
    flex-direction: column;
    gap: 1rem;
  }

  .stat-item {
    min-width: auto;
  }

  .main-content {
    margin-top: -30px;
    padding: 0 1rem 1rem;
  }

  .articles-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .card-content {
    padding: 1.5rem;
  }
}
</style>