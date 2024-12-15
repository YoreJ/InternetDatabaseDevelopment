<script>
import Plyr from 'plyr'
import 'plyr/dist/plyr.css'

export default {
  data() {
    return {
      // 修改后的图片路径数组，每个对象包含图片路径和对应的链接
      imagePaths: [
        { src: 'src/assets/imgs/change1.jpg', link: 'https://openai.com/' },
        { src: 'src/assets/imgs/change2.webp', link: 'https://openai.com/index/learning-to-reason-with-llms/' },
        { src: 'src/assets/imgs/change3.jpg', link: 'https://www.bing.com/?mkt=zh-CN' },
        { src: 'src/assets/imgs/change4.png', link: 'https://www.doubao.com/chat/' },
        { src: 'src/assets/imgs/change5.jpg', link: 'https://www.cursor.com/' },
        { src: 'src/assets/imgs/change6.png', link: 'https://x.ai/' },
        { src: 'src/assets/imgs/change7.jpg', link: 'https://sora.com/' },
        { src: 'src/assets/imgs/change8.jpeg', link: 'https://claude.ai/login' },
      ]
    }
  },
  mounted() {
    this.initPlayer() // 初始化视频播放器
  },
  methods: {
    /**
     * 初始化 Plyr 视频播放器
     * 为页面上的两个视频元素（#player1 和 #player2）创建 Plyr 实例
     */
    initPlayer() {
      // 初始化第一个视频播放器，绑定到元素 ID 为 player1
      this.player1 = new Plyr('#player1', {
        controls: ['play-large', 'progress', 'current-time'], // 控制条包含播放按钮、大型播放按钮、进度条和当前时间
        speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2] } // 播放速度选项
      })
      // 初始化第二个视频播放器，绑定到元素 ID 为 player2
      this.player2 = new Plyr('#player2', {
        controls: ['play-large', 'progress', 'current-time'], // 控制条包含播放按钮、大型播放按钮、进度条和当前时间
        speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2] } // 播放速度选项
      })
    }
  }
}
</script>

<template>
  <div class="homeContainer">
    <h1>AI NEWS</h1>
    <div class="timeLine">
      <el-carousel :interval="3000" type="card" height="50vh" trigger="click">
        <el-carousel-item v-for="(image, index) in imagePaths" :key="index">
          <a :href="image.link" target="_blank" rel="noopener noreferrer" class="carousel-link">
            <img :src="image.src" alt="News Image" class="carousel-image" loading="lazy" />
          </a>
        </el-carousel-item>
      </el-carousel>
    </div>

    <div class="videoPlayer">
      <h1>OpenAI年度直播</h1>
      <video id="player1" playsinline controls>
        <source src="../assets/videos/OpenAI年度直播.mp4" type="video/mp4" />
      </video>
    </div>

    <div class="videoPlayer">
      <h1>32个sora视频全集+提示词中文版</h1>
      <video id="player2" playsinline controls>
        <source src="../assets/videos/sora视频.mp4" type="video/mp4" />
      </video>
    </div>
  </div>
</template>

<style scoped>
/* 主容器样式 */
.homeContainer {
  display: flex; /* 使用 Flexbox 布局 */
  flex-direction: column; /* 垂直排列子元素 */
  justify-content: center; /* 在主轴（垂直方向）上居中 */
  align-items: center; /* 在交叉轴（水平方向）上居中 */
  width: 90%; /* 宽度为父容器的90% */
  min-height: 65vh; /* 最小高度为视口高度的65% */
  margin-top: 10vh; /* 上外边距为视口高度的10% */
  padding: 20px; /* 内边距为20像素 */
  background-color: rgba(0, 0, 0, 0.5); /* 添加半透明背景以提高对比度 */
  border-radius: 15px; /* 圆角半径为15像素 */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* 添加阴影，提升视觉效果 */
}

/* 首页标题样式 */
.homeContainer h1 {
  color: white; /* 文字颜色为白色 */
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* 添加文本阴影，增强可读性 */
  margin-bottom: 3vh; /* 下外边距为视口高度的3% */
  font-size: 2.5rem; /* 增加字体大小 */
  letter-spacing: 2px; /* 增加字间距 */
}

/* 时间线轮播图容器样式 */
.timeLine {
  display: block; /* 显示为块级元素 */
  width: 100%; /* 宽度为100% */
  height: 50vh; /* 高度为视口高度的50% */
  margin-bottom: 5vh; /* 下外边距为视口高度的5% */
  overflow: hidden; /* 隐藏溢出内容 */
  border-radius: 10px; /* 圆角半径为10像素 */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 添加阴影 */
}

/* 轮播项样式 */
.timeLine .el-carousel__item {
  display: flex;
  justify-content: center;
  align-items: center;
}

/* 轮播器中的链接样式 */
.carousel-link {
  display: block; /* 将链接设为块级元素 */
  width: 100%; /* 链接宽度填满父容器 */
  height: 100%; /* 链接高度填满父容器 */
}

/* 轮播图中的图片样式 */
.carousel-image {
  width: 100%;
  height: 100%;
  object-fit: cover; /* 保持比例，填满容器，裁剪溢出 */
  border-radius: 10px; /* 圆角半径为10像素 */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 添加阴影，提升视觉效果 */
  transition: transform 0.3s ease; /* 添加过渡效果 */
  display: block; /* 确保图片为块级元素，避免底部间距 */
}

.carousel-image:hover {
  transform: scale(1.05); /* 悬停时放大 */
}

/* 视频播放器容器样式 */
.videoPlayer {
  display: flex; /* 使用 Flexbox 布局 */
  justify-content: center; /* 在主轴（水平方向）上居中 */
  flex-direction: column; /* 垂直排列子元素 */
  align-items: center; /* 在交叉轴（水平方向）上居中 */
  margin-top: 10vh; /* 上外边距为视口高度的10% */
  max-width: 50%; /* 最大宽度为父容器的50% */

  border-radius: 15px; /* 圆角半径为15像素 */
  background-color: rgba(255, 255, 255, 0.7); /* 半透明白色背景 */
  padding: 20px; /* 内边距为20像素 */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* 添加阴影 */
}

/* 视频播放器标题样式 */
.videoPlayer h1 {
  color: #ffffff; /* 文字颜色为白色 */
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* 添加文本阴影，增强可读性 */
  font-size: 1.8rem; /* 增加字体大小 */
  margin-bottom: 1vh; /* 下外边距为视口高度的1% */
  text-align: center; /* 文本居中 */
}

/* 响应式设计 */
@media (max-width: 768px) {
  .homeContainer {
    width: 95%; /* 减少宽度，适应小屏幕 */
    margin-top: 5vh; /* 减少上外边距 */
    padding: 10px; /* 减少内边距 */
  }

  /* 调整首页标题 */
  .homeContainer h1 {
    font-size: 2rem; /* 减少字体大小 */
    margin-bottom: 2vh; /* 减少下外边距 */
  }

  /* 调整轮播器高度 */
  .timeLine {
    height: 30vh; /* 减少轮播器高度 */
    margin-bottom: 3vh; /* 减少下外边距 */
  }

  /* 调整视频播放器宽度 */
  .videoPlayer {
    max-width: 90%; /* 增加视频播放器的最大宽度 */
    padding: 15px; /* 减少内边距 */
  }

  /* 调整视频标题字体大小 */
  .videoPlayer h1 {
    font-size: 1.5rem; /* 减少字体大小 */
  }

  /* 调整图片悬停效果 */
  .carousel-image:hover {
    transform: scale(1.02); /* 减少放大比例 */
  }
}
</style>