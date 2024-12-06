    <template>
      <div class="overlay">
        <div class="chat-container">
          <div ref="chatBox" class="chat-box">
            <div v-for="message in messages" :key="message.id" class="message" :class="message.sender">
              <p><strong>{{ message.sender }}:</strong> {{ message.text }}</p>
            </div>
          </div>
          <div class="input-container">
            <input 
              v-model="userInput" 
              @keyup.enter="sendMessage" 
              placeholder="Type your message..." 
              class="input-field"
              :disabled="isSending"/>
            <button @click="sendMessage" class="send-button" :disabled="isSending">Send</button>
          </div>
        </div>
      </div>
    </template>
    
    <script>
    import axios from 'axios';
    
    export default {
      data() {
        return {
          userInput: '',
          messages: [],
          isSending: false // 用于跟踪是否正在等待服务器响应
        };
      },
      methods: {
        async sendMessage() {
          if (this.userInput.trim() === '') return;
    
          this.messages.push({ 
            id: Date.now(), 
            sender: 'You', 
            text: this.userInput 
          });
    
          const userMessage = this.userInput;
          this.userInput = '';
          this.isSending = true; // 禁用发送按钮
    
          this.$nextTick(() => {
            const chatBox = this.$refs.chatBox;
            chatBox.scrollTop = chatBox.scrollHeight;
          });
    
          try {
            console.log("Sending message:", userMessage);
    
            const response = await axios.post('http://localhost:8080/api/chat', {
              message: userMessage
            }, {
              headers: {
                'Content-Type': 'application/json'
              }
            });
            console.log("Response from server:", response.data);
    
            this.messages.push({
              id: Date.now() + 1,
              sender: 'AI',
              text: response.data.reply
            });
    
            this.$nextTick(() => {
              const chatBox = this.$refs.chatBox;
              chatBox.scrollTop = chatBox.scrollHeight;
            });
          } catch (error) {
            console.error("Error while sending message:", error);
          } finally {
            this.isSending = false; // 启用发送按钮
          }
        }
      }
    };
    </script>
    
    <style scoped>
    /* 样式部分 */
    .overlay {
      position: fixed;
      top: 50%; 
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: rgba(255, 255, 255, 0.7);
      border-radius: 15px;
      width: 90%;
      height: 80%;
      padding: 20px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
    }
    
    .chat-container {
      background-color: #fff;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }
    
    .chat-box {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      margin-bottom: 10px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
    }
    
    .message {
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 10px;
      max-width: 70%;
      word-wrap: break-word;
      margin-left: 10px;
      margin-right: 10px;
    }
    
    .message.You {
      background-color: #007bff;
      color: white;
      align-self: flex-end;
    }
    
    .message.AI {
      background-color: #f3f3f3;
      color: black;
      align-self: flex-start;
    }
    
    .input-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border-top: 1px solid #eee;
      background-color: #fff;
      width: 100%;
    }
    
    .input-field {
      width: 80%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    
    .send-button {
      width: 15%;
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    
    .send-button:hover {
      background-color: #0056b3;
    }
    
    .send-button:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }
    </style>