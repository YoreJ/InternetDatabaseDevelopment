<template>
  <div
    class="likebtn"
    @mouseover="startTimer"
    @mouseleave="resetTimer"
    @click="addLikenum"
  >
    {{ buttonText }}
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      liked: this.like // 初始化 liked 状态
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
      console.log(this.liked)
      return this.liked ? '取消点赞' : '点一个赞'
    }
  },
  methods: {
    addLikenum() {
      const userid = sessionStorage.getItem('UserID') || this.userid
      const id = this.id || this.$route.params.id

      if (this.type === 'v') {
        const url = `http://localhost:8080/api/likevideo?userId=${userid}&videoId=${id}`
        axios.get(url)
          .then((response) => {
            console.log('操作成功', response.data)
            this.liked = !this.liked
          })
          .catch((error) => {
            console.error('发送数据失败', error)
          })
      } 
      else if (this.type === 'a') {
        const url = `http://localhost:8080/api/likearticle?userId=${userid}&articleId=${id}`
        axios.get(url)
          .then((response) => {
            console.log('操作成功', response.data)
            this.liked = !this.liked
          })
          .catch((error) => {
            console.error('发送数据失败', error)
          })
      }
    },
    startTimer() {
      // 如果有需要实现的定时器逻辑，请在这里添加
    },
    resetTimer() {
      // 如果有需要实现的定时器重置逻辑，请在这里添加
    }
  }
}
</script>

<style scoped>
.likebtn {
  display: inline-block;
  position: relative;
  z-index: 1;
  overflow: hidden;
  text-decoration: none;
  font-family: sans-serif;
  font-weight: 600;
  font-size: 2em;
  padding: 0.75em 1em;
  color: #fff;
  background-color: #007bff;
  border: 0.15em solid #007bff;
  border-radius: 2em;
  transition: all 0.3s ease;
  margin-bottom: 3vh;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  cursor: pointer;
}

.likebtn:before,
.likebtn:after {
  content: '';
  position: absolute;
  top: -1.5em;
  z-index: -1;
  width: 200%;
  aspect-ratio: 1;
  border: none;
  border-radius: 40%;
  background-color: rgba(0, 100, 253, 0.25);
  transition: transform 0.3s, background-color 0.3s;
}

.likebtn:before {
  left: -80%;
  transform: translate3d(0, 5em, 0) rotate(-340deg);
}

.likebtn:after {
  right: -80%;
  transform: translate3d(0, 5em, 0) rotate(390deg);
}

.likebtn:hover,
.likebtn:focus {
  color: #fff;
  background-color: #0056b3;
  border-color: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

.likebtn:hover:before,
.likebtn:hover:after,
.likebtn:focus:before,
.likebtn:focus:after {
  transform: none;
  background-color: rgba(0, 114, 253, 0.75);
}

</style>
