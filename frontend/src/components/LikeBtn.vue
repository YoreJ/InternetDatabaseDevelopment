<template>
  <button
    class="like-button"
    :class="{ 'is-liked': liked }"
    @mouseover="startTimer"
    @mouseleave="resetTimer"
    @click="addLikenum"
  >
    <span class="like-icon">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="heart-icon">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
      </svg>
    </span>
    <span class="like-text">{{ buttonText }}</span>
  </button>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      liked: this.like
    }
  },
  props: {
    like: {
      type: Boolean,
      required: true
    },
    userid: {
      type: String,
      required: true
    },
    id: {
      type: String,
      required: true
    },
    type: {
      type: String,
      required: true
    }
  },
  computed: {
    buttonText() {
      return this.liked ? '已点赞' : '点赞'
    }
  },
  methods: {
    addLikenum() {
      const userid = sessionStorage.getItem('UserID') || this.userid;
      const id = this.id || this.$route.params.id;

      if (this.type === 'v') {
        const url = `http://localhost:8080/api/likevideo?userId=${userid}&videoId=${id}`;
        axios.get(url)
          .then((response) => {
            console.log('操作成功', response.data);
            this.liked = !this.liked;
            this.$emit('click');
          })
          .catch((error) => {
            console.error('发送数据失败', error);
          });
      } 
      else if (this.type === 'a') {
        const url = `http://localhost:8080/api/likearticle?userId=${userid}&articleId=${id}`;
        axios.get(url)
          .then((response) => {
            console.log('操作成功', response.data);
            this.liked = !this.liked;
            this.$emit('click');
          })
          .catch((error) => {
            console.error('发送数据失败', error);
          });
      }
    },
    startTimer() {
      // 保留原有的定时器逻辑接口
    },
    resetTimer() {
      // 保留原有的定时器逻辑接口
    }
  }
}
</script>

<style scoped>
.like-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.like-button:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-1px);
}

.like-button:active {
  transform: translateY(0);
}

.like-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.2rem;
  height: 1.2rem;
}

.heart-icon {
  width: 100%;
  height: 100%;
  fill: currentColor;
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.like-button:hover .heart-icon {
  transform: scale(1.1);
}

.like-button.is-liked {
  background: rgba(255, 99, 132, 0.2);
  color: #ff6384;
}

.like-button.is-liked .heart-icon {
  animation: heartBeat 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes heartBeat {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.4);
  }
  100% {
    transform: scale(1);
  }
}

.like-text {
  font-size: 0.9rem;
  font-weight: 500;
}

/* Hover effect */
.like-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2) 0%, transparent 60%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.like-button:hover::before {
  opacity: 1;
}

/* Dark mode optimization */
@media (prefers-color-scheme: dark) {
  .like-button {
    background: rgba(255, 255, 255, 0.08);
  }
  
  .like-button:hover {
    background: rgba(255, 255, 255, 0.15);
  }
}

/* Mobile optimization */
@media (max-width: 768px) {
  .like-button {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
  }
  
  .like-icon {
    width: 1rem;
    height: 1rem;
  }
}
</style>