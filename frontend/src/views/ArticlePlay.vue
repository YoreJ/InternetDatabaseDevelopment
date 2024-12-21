<template>
  <div class="article-container">
    <div class="article-header">
      <h2 class="article-title">{{ title }}</h2>
      <div class="article-time">{{ articleTime }}</div>
    </div>
    <div class="article-content" v-html="content"></div>
    <div class="article-actions">
      <div class="like-button">
        <LikeBtn :like="like" :userid="userID" :id="id" :type="a" @click="getLikeNum()" />
      </div>
      <div class="like-count">{{ likeNum }} 个点赞👍</div>
    </div>
    <div class="comment-section">
      <div class="comment-form">
        <textarea v-model="message" placeholder="留言内容"></textarea>
        <button id="submitBtn" @click="submitMessage">留言</button>
      </div>
      <div class="message-board">
        <div v-for="(msg, index) in messages" :key="index" class="message">
          <div class="message-info">
            <div class="info">
              <strong>{{ msg.Username }}</strong>
            </div>
            <span>发布于: {{ msg.CommentedAt }}</span>
          </div>
          <div class="content">{{ msg.Content }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import LikeBtn from '../components/LikeBtn.vue';

export default {
  data() {
    return {
      userID: '',
      title: '',
      content: '',
      articleTime: '',
      likeNum: '',
      messages: [],
      id: '',
      like: false,
      a: 'a',
    };
  },
  components: {
    LikeBtn,
  },
  mounted() {
    this.userID = sessionStorage.getItem('UserID');
    this.getUrl();
    this.getComments();
    this.addClick();
    this.getLikeNum();
    this.id = this.$route.params.id;
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
          console.log(this.title);
        })
        .catch((error) => {
          console.error('请求数据失败', error);
        });
    },
    getLikeNum() {
      const id = this.$route.params.id;
      axios
        .get('http://localhost:8080/api/likenumarticle?articleId=' + id)
        .then((response) => {
          this.likeNum = response.data.likeCount;
          console.log(this.likeNum);
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
      const userid = sessionStorage.getItem('UserID');
      const id = this.$route.params.id;
      if (this.message) {
        const url = `http://localhost:8080/api/commentarticle?userId=${userid}&articleId=${id}&content=${encodeURIComponent(
          this.message
        )}`;
        axios
          .get(url)
          .then((response) => {
            const status = response.data.status;
            if (status === 0) {
              this.$message.error('添加评论失败');
              console.log(response.data);
            } else {
              this.message = '';
              this.getComments();
            }
          })
          .catch((error) => {
            console.error('发送数据失败', error);
            this.$message.error('添加评论失败2');
          });
      } else {
        this.$message.error('请填写留言内容！');
      }
    },
  },
};
</script>

<style>
.article-container {
  display: flex;
  flex-direction: column;
  margin-top: 6vh;
  width: 80%;
  align-items: center;
}

.article-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-bottom: 2vh;
}

.article-title {
  font-size: 2em;
  font-weight: bold;
  color: #333;
}

.article-time {
  font-size: 1em;
  color: #999;
}

.article-content {
  font-size: 1.2em;
  line-height: 1.5;
  color: #333;
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 5px;
}

.article-actions {
  display: flex;
  align-items: center;
  margin-top: 2vh;
}

.like-button {
  margin-right: 10px;
}

.like-count {
  font-size: 1.2em;
  color: #999;
}

.comment-section {
  width: 100%;
  margin-top: 4vh;
}

.comment-form {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-bottom: 2vh;
}

.comment-form textarea {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  font-size: 1em;
  resize: vertical;
}

.comment-form button {
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  font-size: 1em;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.comment-form button:hover {
  background-color: #0056b3;
}

.message-board {
  width: 100%;
}

.message {
  background-color: #f9f9f9;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
}

.message-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}

.message-info .info {
  font-weight: bold;
}

.message-info span {
  font-size: 0.9em;
  color: #999;
}

.message .content {
  font-size: 1em;
  line-height: 1.5;
  color: #333;
}
</style>
