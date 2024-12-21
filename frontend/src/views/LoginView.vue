<template>
  <div class="container">
    <div class="register-box" :class="{ 'slide-up': isLoginBoxUp }">
      <h2 class="register-title" @click="toggleForm('register')">
        <span>没有账号，去</span>注册
      </h2>
      <div class="input-box">
        <div class="input-group">
          <i-user-circle class="input-icon" />
          <input 
            type="text" 
            placeholder="用户名" 
            v-model="registerData.username"
          />
        </div>
        <div class="input-group">
          <i-lock class="input-icon" />
          <input 
            type="password" 
            placeholder="密码" 
            v-model="registerData.password"
          />
        </div>
        <div class="input-group">
          <i-lock-keyhole class="input-icon" />
          <input 
            type="password" 
            placeholder="确认密码" 
            v-model="registerData.confirmPassword"
          />
        </div>
      </div>
      <button @click="register">
        <i-user-plus class="button-icon" />
        注册
      </button>
    </div>

    <div class="login-box" :class="{ 'slide-up': !isLoginBoxUp }">
      <div class="center">
        <h2 class="login-title" @click="toggleForm('login')">
          <span>已有账号，去</span>登录
        </h2>
        <div class="input-box">
          <div class="input-group">
            <i-user class="input-icon" />
            <input 
              type="text" 
              placeholder="用户名" 
              v-model="loginData.username"
            />
          </div>
          <div class="input-group">
            <i-lock class="input-icon" />
            <input 
              type="password" 
              placeholder="密码" 
              v-model="loginData.password"
            />
          </div>
        </div>
        <button @click="login">
          <i-log-in class="button-icon" />
          登录
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { 
  UserCircle, 
  Lock, 
  LockKeyhole, 
  LogIn, 
  UserPlus,
  User
} from 'lucide-vue-next'

export default {
  components: {
    'i-user-circle': UserCircle,
    'i-lock': Lock,
    'i-lock-keyhole': LockKeyhole,
    'i-log-in': LogIn,
    'i-user-plus': UserPlus,
    'i-user': User
  },
  data() {
    return {
      registerData: {
        username: '',
        password: '',
        confirmPassword: ''
      },
      loginData: {
        userID: '',
        username: '',
        password: ''
      },
      isLoginBoxUp: false
    }
  },
  created() {
    const Username = sessionStorage.getItem('Username')
    const Password = sessionStorage.getItem('Password')
    if (Username && Password) {
      window.location.href = '/'
    }
  },
  methods: {
    toggleForm(form) {
      if (form === 'login') {
        this.isLoginBoxUp = true
      } else if (form === 'register') {
        this.isLoginBoxUp = false
      }
    },
    register() {
      const username = this.registerData.username
      const password = this.registerData.password

      if (this.registerData.password !== this.registerData.confirmPassword) {
        this.$message.error('密码和确认密码不一致')
        return
      }

      const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/
      if (!passwordPattern.test(password)) {
        this.$message.error('密码至少为8位数，并且要包含大小写字母数字')
        return
      }

      axios
        .post('http://localhost:8080/api/signup', {
          username: username,
          password: password
        })
        .then((response) => {
          const status = response.data.status
          if (status === 1) {
            this.$message.success('注册成功')
            this.loginData.username = this.registerData.username
            this.loginData.password = this.registerData.password
            this.toggleForm('login')
          } else if (status === 0) {
            this.$message.error('用户已存在')
          }
        })
        .catch((error) => {
          console.error('注册失败:', error)
          this.$message.error('注册失败')
        })
    },
    login() {
      const username = this.loginData.username
      const password = this.loginData.password

      axios
        .post('http://localhost:8080/api/login', {
          username: username,
          password: password
        })
        .then((response) => {
          const status = response.data.status
          if (status === 1) {
            const userID = response.data.user.UserID
            sessionStorage.setItem('UserID', userID)
            sessionStorage.setItem('Username', username)
            sessionStorage.setItem('Password', password)
            this.$message.success('登录成功')
            setTimeout(() => {
              window.location.href = '/'
            }, 2000)
          } else {
            this.$message.error('用户名或密码错误')
          }
        })
        .catch((error) => {
          console.error('登录失败:', error)
          this.$message.error('登录失败')
        })
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.container {
  background-color: rgba(255, 255, 255, 0.95);
  width: 850px;
  height: 500px;
  border-radius: 20px;
  overflow: hidden;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  backdrop-filter: blur(10px);
}

.register-box {
  width: 70%;
  position: absolute;
  z-index: 1;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transition: 0.7s ease-in-out;
  padding: 20px 40px;
}

.register-title,
.login-title {
  color: #2d3748;
  font-size: 28px;
  text-align: center;
  margin-bottom: 20px;
  cursor: pointer;
  transition: 0.3s ease;
}

.register-title:hover,
.login-title:hover {
  color: #667eea;
}

.register-title span,
.login-title span {
  color: #718096;
  font-size: 16px;
  display: none;
}

.input-box {
  background-color: white;
  border-radius: 15px;
  overflow: hidden;
  margin-top: 30px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.input-group {
  position: relative;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.input-group:last-child {
  border-bottom: none;
}

.input-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #718096;
  width: 20px;
  height: 20px;
}

input {
  width: 100%;
  height: 50px;
  border: none;
  font-size: 14px;
  padding: 15px 15px 15px 45px;
  outline: none;
  transition: 0.3s ease;
}

input:focus {
  background-color: #f7fafc;
}

input::placeholder {
  color: #a0aec0;
}

button {
  width: 100%;
  padding: 15px;
  margin: 25px 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

button:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.button-icon {
  width: 18px;
  height: 18px;
}

/* 登录区域 */
.login-box {
  position: absolute;
  inset: 0;
  top: 20%;
  z-index: 2;
  background-color: white;
  transition: 0.7s ease-in-out;
  border-radius: 20px 20px 0 0;
}

.login-box::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  background-color: white;
  width: 200%;
  height: 250px;
  border-radius: 50%;
}

.login-box .center {
  width: 70%;
  position: absolute;
  z-index: 3;
  left: 50%;
  top: 40%;
  transform: translate(-50%, -50%);
}

/* 滑动效果 */
.login-box.slide-up {
  top: 90%;
}

.login-box.slide-up .center {
  top: 10%;
  transform: translate(-50%, 0%);
}

.login-box.slide-up .login-title,
.register-box.slide-up .register-title {
  font-size: 16px;
}

.login-box.slide-up .login-title span,
.register-box.slide-up .register-title span {
  margin-right: 5px;
  display: inline-block;
}

.login-box.slide-up .input-box,
.login-box.slide-up button,
.register-box.slide-up .input-box,
.register-box.slide-up button {
  opacity: 0;
  visibility: hidden;
  transition: 0.3s ease;
}

.register-box.slide-up {
  top: 6%;
  transform: translate(-50%, 0%);
}

/* 响应式设计 */
@media (max-width: 768px) {
  .container {
    width: 90%;
    height: auto;
    min-height: 500px;
  }

  .register-box,
  .login-box .center {
    width: 85%;
    padding: 20px;
  }

  .register-title,
  .login-title {
    font-size: 24px;
  }

  input {
    font-size: 14px;
  }

  button {
    padding: 12px;
    font-size: 14px;
  }
}
</style>