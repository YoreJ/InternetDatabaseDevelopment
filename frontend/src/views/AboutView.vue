<!-- Coding by XuHaiying 2212180 -->
<!-- 完成前端VUE界面的设计、相关功能页面的构建，到美化 -->

<!-- Coding by JinagYu 2210705 -->
<!-- 前后段接口对接 -->

<template>
  <div class="about-container">
    <div class="hero-section">
      <div class="hero-content">
        <div class="logo-container">
          <svg class="team-logo" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <h1 class="site-title">Dream AI</h1>
        <div class="views-counter" @click="checkViews">
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

    <!-- 团队介绍部分 -->
    <div class="intro-section">
      <div class="section-header">
        <h2 class="section-title">团队介绍</h2>
        <div class="header-underline"></div>
      </div>
      <div class="intro-content">
        <div class="feature-grid">
          <div class="feature-item">
            <span class="feature-icon">🎯</span>
            <div class="feature-details">
              <h3>团队愿景</h3>
              <p>致力于推动AI技术创新，打造智能化未来，为用户提供卓越的AI解决方案。</p>
            </div>
          </div>
          <div class="feature-item">
            <span class="feature-icon">💡</span>
            <div class="feature-details">
              <h3>创新精神</h3>
              <p>持续探索AI前沿技术，结合实际应用场景，创造有价值的技术突破。</p>
            </div>
          </div>
          <div class="feature-item">
            <span class="feature-icon">🤝</span>
            <div class="feature-details">
              <h3>团队协作</h3>
              <p>优秀的跨领域人才组合，强大的技术研发能力，高效的团队协作机制。</p>
            </div>
          </div>
          <div class="feature-item">
            <span class="feature-icon">🚀</span>
            <div class="feature-details">
              <h3>发展目标</h3>
              <p>打造领先的AI技术平台，推动行业发展，成为AI领域的创新引领者。</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 团队成员展示 -->
    <div class="team-section">
      <div class="section-header">
        <h2 class="section-title">团队成员</h2>
        <div class="header-underline"></div>
      </div>
      <div class="team-grid">
        <div class="team-row" v-for="(row, rowIndex) in teamRows" :key="'row-' + rowIndex">
          <MemberBox 
            v-for="member in row" 
            :key="member.student_id"
            :memberInfo="member"
          />
        </div>
      </div>
    </div>

    <!-- 作业下载部分 -->
    <div class="download-section">
      <div class="section-header">
        <h2 class="section-title">作业下载</h2>
        <div class="header-underline"></div>
      </div>
      <div class="download-grid">
        <a 
          v-for="(item, index) in downloadSrc" 
          :key="index" 
          :href="item.link" 
          download
          class="download-card"
        >
          <svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="download-title">{{ item.title }}</span>
        </a>
      </div>
    </div>

    <el-backtop :right="100" :bottom="100" />
  </div>
</template>

<script>
import MemberBox from '../components/MenberBox.vue'; // 确保路径正确
import axios from 'axios';

export default {
  name: 'About',
  components: {
    MemberBox
  },

  data() {
    return {
      views: 0,
      downloadSrc: [], // 动态加载
      teamMembers: [] // 动态加载
    }
  },

  computed: {
    // 将 teamMembers 分割成多行，每行最多2个成员
    teamRows() {
      const rows = [];
      const perRow = 2;
      for (let i = 0; i < this.teamMembers.length; i += perRow) {
        rows.push(this.teamMembers.slice(i, i + perRow));
      }
      return rows;
    }
  },

  mounted() {
    this.checkViews();
    this.fetchTeamMembers();
  },

  methods: {
    async checkViews() {
      try {
        const response = await axios.get('http://localhost:8080/api/getwebviews');
        this.views = response.data.visitCount;
      } catch (error) {
        console.error('获取访问量失败:', error);
      }
    },

    async fetchTeamMembers() {
      try {
        const response = await axios.get('http://localhost:8080/api/getallstudents');
        if (response.data.status === 1) {
          const students = response.data.students;
          console.log('Fetched students:', students); // 调试信息
          const enrichedStudents = students.map(student => ({
            name: student.name,
            info: student.role,
            avatar: `/src/assets/avatars/${this.getAvatarNumber(student.student_id)}.jpg`,
            contactList: [
              {
                color: '#c71610',
                icon: 'fa-solid fa-envelope',
                content: student.email,
                link: 'mailto:' + student.email
              },
              {
                color: '#171515',
                icon: 'fa-brands fa-github',
                content: student.github,
                link: student.github
              },
              {
                color: '#1ed76d',
                icon: 'fa-brands fa-weixin',
                content: student.wechat
              }
            ],
            student_id: student.student_id,
            file_path: student.file_path
          }));
          console.log('Enriched students:', enrichedStudents); // 调试信息
          this.teamMembers = enrichedStudents;

          this.downloadSrc = enrichedStudents
            .filter(student => student.file_path)
            .map(student => ({
              title: `${student.student_id} ${student.name} 个人作业.zip`,
              link: student.file_path
            }));
          
          this.downloadSrc.unshift({
            title: '团队作业.zip',
            link: 'public/data/dream_ai_团队作业(2210705_2213040_2212180_2113927).zip'
          });

          console.log('Download Sources:', this.downloadSrc);
          
        } else {
          console.warn('获取所有学生信息失败:', response.data.message);
        }
      } catch (error) {
        console.error('获取所有学生信息时出错:', error);
      }
    },

    // 根据 student_id 获取头像编号
    getAvatarNumber(studentId) {
      const mapping = {
        '2210705': 1,
        '2213040': 2,
        '2212180': 3,
        '2113927': 4
      };
      return mapping[studentId] || 'example';
    }
  }
}
</script>

<style scoped>
.about-container {
  height: 100vh;
  background: linear-gradient(135deg, #f6f9fc 0%, #7dbed6 50%, #e5effd 100%);
  color: #495057;
  padding: 5rem;
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.hero-section {
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

.hero-content {
  max-width: 800px;
}

.team-logo {
  width: 80px;
  height: 80px;
  color: #4c6ef5;
  margin-bottom: 1.5rem;
}

.site-title {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.views-counter {
  display: inline-flex;
  align-items: center;
  background: white;
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  transition: transform 0.3s ease;
}

.views-counter:hover {
  transform: translateY(-2px);
}

.view-icon {
  width: 24px;
  height: 24px;
  color: #4c6ef5;
  margin-right: 0.75rem;
}

.views-count {
  font-size: 1.5rem;
  font-weight: 600;
  color: #4c6ef5;
  margin-right: 0.5rem;
}

.views-label {
  color: #6b7280;
  font-size: 1rem;
}

.section-header {
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
}

.section-title {
  font-size: 2rem;
  color: #495057;
  margin-bottom: 1rem;
}

.header-underline {
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  border-radius: 2px;
  margin: 0 auto;
}

.intro-section {
  margin-bottom: 6rem;
  padding: 3rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
  padding: 1rem;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.5rem;
  background: rgba(255, 255, 255, 0.5);
  border-radius: 15px;
  transition: transform 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-5px);
}

.feature-icon {
  font-size: 2rem;
}

.feature-details h3 {
  font-size: 1.2rem;
  color: #4c6ef5;
  margin-bottom: 0.5rem;
}

.feature-details p {
  color: #495057;
  line-height: 1.6;
}

.team-section {
  margin-bottom: 6rem;
}

.team-grid {
  display: flex;
  flex-direction: column;
  gap: 10rem;
}

.team-row {
  display: flex;
  justify-content: center;
  gap: 5rem;
  margin: 0 2rem;
}

@media (max-width: 768px) {
  .about-container {
    padding: 1rem;
  }

  .site-title {
    font-size: 2rem;
  }

  .feature-grid {
    grid-template-columns: 1fr;
  }

  .team-row {
    flex-direction: column;
    align-items: center;
    gap: 8rem;
  }

  .download-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .hero-section {
    padding: 1.5rem;
  }

  .views-counter {
    padding: 0.5rem 1rem;
  }

  .views-count {
    font-size: 1.2rem;
  }

  .team-logo {
    width: 60px;
    height: 60px;
  }

  .feature-item {
    padding: 1rem;
  }
}

/* 下载部分样式 */
.download-section {
  margin-bottom: 4rem;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(237, 242, 247, 0.8));
  border-radius: 20px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
}

.download-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.download-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 2rem;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  border-radius: 15px;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.download-card:hover {
  transform: translateY(-5px);
  background: linear-gradient(135deg, rgba(76, 110, 245, 0.1), rgba(21, 170, 191, 0.1));
}

.download-icon {
  width: 40px;
  height: 40px;
  color: #4c6ef5;
  margin-bottom: 1rem;
}

.download-title {
  color: #495057;
  font-weight: 500;
  font-size: 1rem;
}
</style>
