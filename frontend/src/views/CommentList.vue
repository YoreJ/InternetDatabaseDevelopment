<!-- Coding by XuHaiying 2212180 -->
<!-- 完成前端VUE界面的设计、相关功能页面的构建，到美化 -->

<!-- Coding by JinagYu 2210705 -->
<!-- 前后段接口对接 -->

<template>
    <div class="comment-list">
      <div v-if="comments.length === 0" class="empty-state">
        暂无评论
      </div>
      <div
        v-for="(comment, index) in comments"
        :key="index"
        class="comment-item"
        :style="{ '--i': index }"
      >
        <div class="comment-header">
          <div class="comment-meta">
            <router-link
              :to="getCommentLink(comment)"
              class="article-link"
            >
              <span class="id-label">{{ type === 'article' ? '文章' : '视频' }}ID：</span>
              <span class="id-value">{{ type === 'article' ? comment.ArticleID : comment.VideoID }}</span>
            </router-link>
            <span class="comment-date">发布于: {{ formatDate(comment.CommentDate) }}</span>
          </div>
          <div class="comment-actions">
            <el-button
              type="text"
              size="small"
              @click="handleEdit(comment)"
            >
              编辑
            </el-button>
            <el-button
              type="text"
              size="small"
              class="delete-btn"
              @click="handleDelete(comment)"
            >
              删除
            </el-button>
          </div>
        </div>
        <div class="comment-content">
          {{ comment.Comment }}
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CommentList',
    props: {
      comments: {
        type: Array,
        required: true
      },
      type: {
        type: String,
        required: true,
        validator: value => ['article', 'video'].includes(value)
      }
    },
    methods: {
      formatDate(date) {
        if (!date) return ''
        return new Date(date).toLocaleString()
      },
      getCommentLink(comment) {
        const id = this.type === 'article' ? comment.ArticleID : comment.VideoID
        return `/${this.type}/${id}`
      },
      handleEdit(comment) {
        this.$emit('edit', comment)
      },
      handleDelete(comment) {
        this.$confirm('确认删除该评论吗？', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$emit('delete', comment)
        }).catch(() => {})
      }
    }
  }
  </script>
  
  <style scoped>
  .comment-list {
    padding: 10px 0;
  }
  
  .empty-state {
    text-align: center;
    padding: 40px 0;
    color: #909399;
    font-size: 14px;
  }
  
  .comment-item {
    margin-bottom: 20px;
    padding: 16px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    animation: fade-in 0.5s ease forwards;
    animation-delay: calc(var(--i) * 0.1s);
    opacity: 0;
  }
  
  @keyframes fade-in {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
  }
  
  .comment-meta {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  
  .article-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
  }
  
  .article-link:hover {
    color: #00b8fc;
  }
  
  .id-label {
    color: #666;
  }
  
  .id-value {
    color: #333;
  }
  
  .comment-date {
    color: #909399;
    font-size: 14px;
  }
  
  .comment-actions {
    display: flex;
    gap: 8px;
  }
  
  .comment-content {
    color: #333;
    line-height: 1.6;
    margin-top: 8px;
    word-break: break-word;
  }
  
  .delete-btn {
    color: #f56c6c;
  }
  
  .delete-btn:hover {
    color: #f78989;
  }
  
  /* Element UI button style override */
  :deep(.el-button--text) {
    padding: 0 4px;
  }
  
  :deep(.el-button--text:focus) {
    color: #00b8fc;
  }
  </style>