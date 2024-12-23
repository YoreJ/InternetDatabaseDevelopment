<template>
  <div class="chat-page">
    <div class="chat-container">
      <!-- 聊天头部 -->
      <div class="chat-header">
        <div class="header-left">
          <div class="ai-avatar">
            <i class="fas fa-robot"></i>
          </div>
          <div class="header-info">
            <h2>AI 智能助手</h2>
            <div class="status-indicator">
              <span class="status-dot"></span>
              实时对话中
            </div>
          </div>
        </div>
        <div class="header-actions">
          <button class="action-btn">
            <i class="fas fa-refresh"></i>
          </button>
          <button class="action-btn">
            <i class="fas fa-cog"></i>
          </button>
        </div>
      </div>

      <!-- 聊天消息区域 -->
      <div ref="chatBox" class="chat-box">
        <!-- 欢迎消息卡片 -->
        <div class="welcome-card">
          <div class="card-icon">
            <i class="fas fa-magic"></i>
          </div>
          <h3>欢迎使用 AI 助手</h3>
          <p>我可以帮助您解答问题、编写代码、分析数据等</p>
          <div class="quick-actions">
            <button class="quick-action-btn">
              <i class="fas fa-code"></i>
              编写代码
            </button>
            <button class="quick-action-btn">
              <i class="fas fa-chart-bar"></i>
              数据分析
            </button>
            <button class="quick-action-btn">
              <i class="fas fa-book"></i>
              知识问答
            </button>
          </div>
        </div>

        <!-- 聊天消息 -->
        <div v-for="message in messages" 
             :key="message.id" 
             class="message" 
             :class="message.sender">
          <div class="message-avatar">
            <i :class="message.sender === 'AI' ? 'fas fa-robot' : 'fas fa-user'"></i>
          </div>
          <div class="message-content">
            <div class="message-header">
              <span class="sender-name">{{ message.sender }}</span>
              <span class="message-time">{{ formatTime(message.id) }}</span>
            </div>
            <div class="message-bubble">
              <p class="message-text">{{ message.text }}</p>
            </div>
            <div class="message-actions">
              <button class="message-action-btn">
                <i class="fas fa-copy"></i>
              </button>
              <button class="message-action-btn">
                <i class="fas fa-redo"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 输入区域 -->
      <div class="input-container">
        <div class="input-wrapper">
          <button class="input-action-btn">
            <i class="fas fa-plus"></i>
          </button>
          <input
            v-model="userInput"
            @keyup.enter="sendMessage"
            placeholder="输入您的问题，按回车发送..."
            class="input-field"
            :disabled="isSending"
          />
          <button class="input-action-btn">
            <i class="fas fa-microphone"></i>
          </button>
        </div>
        <button 
          @click="sendMessage" 
          class="send-button" 
          :disabled="isSending"
        >
          <i class="fas fa-paper-plane"></i>
          <span>{{ isSending ? '发送中...' : '发送' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      userID: '',
      userInput: '',
      messages: [],
      isSending: false
    }
  },
  async mounted() {
    this.userID = sessionStorage.getItem('UserID');
    await this.getConversation()
  },
  methods: {
    async getConversation() {
      // 在获取历史消息前先尝试创建对话
      try {
        const createConv = await axios.get(`http://localhost:8080/api/addonversation?UserID=${this.userID}`)
        if (createConv.data.status === 1) {
          console.log('Conversation created successfully:', createConv.data.conversation)
        } else {
          console.warn('Failed to create conversation:', createConv.data.message)
        }
      } catch (err) {
        console.error('Create conversation error:', err)
      }

      // 获取历史消息
      // try {
      //   const response = await axios.get('http://localhost:8080/api/chat')
      //   this.messages = response.data.messages
      //   this.$nextTick(() => {
      //     this.scrollToBottom()
      //   })
      // } catch (error) {
      //   console.error('Error:', error)
      //   this.messages = [
      //     {
      //       id: Date.now(),
      //       sender: 'System',
      //       text: '无法获取历史消息，请刷新页面重试'
      //     }
      //   ]
      // }
    },
    
    formatTime(timestamp) {
      const date = new Date(timestamp)
      return date.toLocaleTimeString('zh-CN', { 
        hour: '2-digit', 
        minute: '2-digit' 
      })
    },
    async sendMessage() {
      if (this.userInput.trim() === '') return

      this.messages.push({
        id: Date.now(),
        sender: 'You',
        text: this.userInput
      })

      const userMessage = this.userInput
      this.userInput = ''
      this.isSending = true

      this.$nextTick(() => {
        this.scrollToBottom()
      })

      try {
        const response = await axios.post(
          'http://localhost:8080/api/chat',
          { 
            UserID: this.userID,
            message: userMessage 
          },
          {
            headers: {
              'Content-Type': 'application/json'
            }
          }
        )

        this.messages.push({
          id: Date.now() + 1,
          sender: 'AI',
          text: response.data.reply
        })

        this.$nextTick(() => {
          this.scrollToBottom()
        })
      } catch (error) {
        console.error('Error:', error)
        this.messages.push({
          id: Date.now() + 1,
          sender: 'System',
          text: '消息发送失败，请重试'
        })
      } finally {
        this.isSending = false
      }
    },
    scrollToBottom() {
      const chatBox = this.$refs.chatBox
      chatBox.scrollTop = chatBox.scrollHeight
    }
  }
}
</script>

<style scoped>
.chat-page {
  min-height: 100vh;
  padding: 1rem;
  background: linear-gradient(135deg, #1a1f35 0%, #2d3250 100%);
  display: flex;
  justify-content: center;
  align-items: center;
}

.chat-container {
  width: 95%;
  height: 95vh;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border-radius: 24px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.chat-header {
  padding: 1.5rem 2rem;
  background: rgba(119, 149, 248, 0.1);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.ai-avatar {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #7795f8 0%, #6772e5 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.ai-avatar i {
  color: white;
  font-size: 24px;
}

.header-info h2 {
  color: #fff;
  font-size: 1.5rem;
  margin: 0;
  margin-bottom: 0.25rem;
}

.status-indicator {
  display: flex;
  align-items: center;
  color: #a8b2d1;
  font-size: 0.9rem;
}

.status-dot {
  width: 8px;
  height: 8px;
  background: #7795f8;
  border-radius: 50%;
  margin-right: 8px;
  animation: pulse 2s infinite;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.action-btn {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-btn:hover {
  background: rgba(119, 149, 248, 0.2);
  transform: translateY(-2px);
}

.chat-box {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.welcome-card {
  background: rgba(119, 149, 248, 0.1);
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  margin-bottom: 2rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.card-icon {
  width: 64px;
  height: 64px;
  background: linear-gradient(135deg, #7795f8 0%, #6772e5 100%);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
}

.card-icon i {
  color: white;
  font-size: 32px;
}

.welcome-card h3 {
  color: #fff;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.welcome-card p {
  color: #a8b2d1;
  margin-bottom: 1.5rem;
}

.quick-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.quick-action-btn {
  padding: 0.75rem 1.5rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.quick-action-btn:hover {
  background: rgba(119, 149, 248, 0.2);
  transform: translateY(-2px);
}

.message {
  display: flex;
  gap: 1rem;
  max-width: 80%;
}

.message.You {
  flex-direction: row-reverse;
  align-self: flex-end;
}

.message-avatar {
  width: 40px;
  height: 40px;
  background: rgba(119, 149, 248, 0.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.message-avatar i {
  color: #7795f8;
  font-size: 20px;
}

.message-content {
  flex: 1;
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.sender-name {
  color: #7795f8;
  font-weight: 500;
}

.message-time {
  color: #a8b2d1;
  font-size: 0.8rem;
}

.message-bubble {
  background: rgba(119, 149, 248, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 1rem;
}

.You .message-bubble {
  background: rgba(119, 149, 248, 0.2);
}

.message-text {
  color: #fff;
  line-height: 1.6;
  margin: 0;
}

.message-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.5rem;
  justify-content: flex-end;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.message:hover .message-actions {
  opacity: 1;
}

.message-action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: #a8b2d1;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.message-action-btn:hover {
  background: rgba(119, 149, 248, 0.2);
  color: #fff;
}

.input-container {
  padding: 1.5rem 2rem;
  background: rgba(119, 149, 248, 0.05);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.input-wrapper {
  display: flex;
  gap: 1rem;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  padding: 0.5rem;
  margin-bottom: 1rem;
}

.input-action-btn {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: transparent;
  border: none;
  color: #a8b2d1;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.input-action-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.input-field {
  flex: 1;
  padding: 0.75rem;
  background: transparent;
  border: none;
  color: #fff;
  font-size: 1rem;
}

.input-field:focus {
  outline: none;
}

.input-field::placeholder {
  color: #a8b2d1;
}

.send-button {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(135deg, #7795f8 0%, #6772e5 100%);
  border: none;
  border-radius: 16px;
  color: #fff;
  font-size: 1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(119, 149, 248, 0.2);
}

.send-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(119, 149, 248, 0.3);
}

.send-button:disabled {
  background: rgba(255, 255, 255, 0.1);
  cursor: not-allowed;
  box-shadow: none;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.2);
    opacity: 0.7;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

/* 滚动条样式 */
.chat-box::-webkit-scrollbar {
  width: 6px;
}

.chat-box::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.chat-box::-webkit-scrollbar-thumb {
  background: rgba(119, 149, 248, 0.3);
  border-radius: 3px;
}

/* 响应式设计 */
@media (max-width: 768px) {
  .chat-page {
    padding: 0;
  }

  .chat-container {
    width: 100%;
    height: 100vh;
    border-radius: 0;
  }

  .message {
    max-width: 90%;
  }

  .quick-actions {
    flex-direction: column;
  }

  .quick-action-btn {
    width: 100%;
  }

  .header-info h2 {
    font-size: 1.2rem;
  }

  .welcome-card {
    padding: 1.5rem;
  }
}

/* 打印样式 */
@media print {
  .chat-page {
    background: none;
    padding: 0;
  }

  .chat-container {
    box-shadow: none;
    border: none;
  }

  .input-container,
  .chat-header {
    display: none;
  }
}
</style>