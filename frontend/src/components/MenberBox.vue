<template>
  <!-- 
    卡片容器，包含用户信息和联系方式。
    :class="{ active: isActive }" 动态绑定类名，根据 isActive 的值添加或移除 'active' 类。
  -->
  <div class="card" :class="{ active: isActive }">
    
    <!-- 用户信息部分 -->
    <div class="user">
      
      <!-- 头像容器 -->
      <div class="imgBx">
        <!-- 
          用户头像图片。
          :src="image" 动态绑定图片源，来自后端 API。
          alt="user" 提供图片的替代文本，提升可访问性。
        -->
        <img :src="image" alt="user" />
      </div>
      
      <!-- 用户内容部分 -->
      <div class="content">
        <h2>
          {{ name }}<br /><br />
          <span>{{ info }}</span> <!-- 显示用户的附加信息，例如职位或简介 -->
        </h2>
      </div>
      
      <!-- 切换按钮，用于展开或收起联系信息 -->
      <span class="toggle" @click="toggle"></span>
    </div>
    
    <!-- 联系方式列表 -->
    <ul class="contact">
      <!-- 
        使用 v-for 指令遍历 contactList 数组，动态生成联系方式项。
        :style="{ '--clr': item.color, '--i': index }" 设置 CSS 变量，用于动态样式。
        :key="index" 为每个列表项提供唯一键，优化渲染性能。
      -->
      <li
        v-for="(item, index) in contactList"
        :style="{ '--clr': item.color, '--i': index }"
        :key="index"
      >
        <a :href="item.link" target="_blank">
          <!-- 图标容器 -->
          <div class="iconBx">
            <!-- 
              使用 Font Awesome 图标库显示相应的图标。
              :class="item.icon" 动态绑定图标类名，例如 'fa-envelope'。
            -->
            <i :class="item.icon"></i>
          </div>
          <!-- 联系方式内容 -->
          <p>{{ item.content }}</p>
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios' // 导入 axios 库用于发送 HTTP 请求

export default {
  props: {
    // 接收父组件传递的 fullname 属性，类型为字符串，必填
    fullname: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      isActive: false, // 控制卡片是否展开，初始为关闭状态
      name: '', // 用户姓名，从后端获取
      info: '', // 用户附加信息（如职位或简介），从后端获取
      image: '', // 用户头像图片链接，从后端获取
      contactList: [ // 联系方式列表，每个对象包含颜色、图标、内容和链接
        {
          color: '#c71610', // 联系方式项的颜色，用于图标背景
          icon: 'fa-solid fa-envelope', // Font Awesome 图标类名
          content: '', // 联系方式内容，例如邮箱地址
          link: '' // 联系方式链接，例如 mailto 链接
        },
        {
          color: '#171515',
          icon: 'fa-brands fa-github',
          content: '',
          link: ''
        },
        {
          color: '#1ed76d',
          icon: 'fa-brands fa-weixin',
          content: '',
          link: '' // 微信通常不提供链接，因此 link 可能为空
        }
      ]
    }
  },
  mounted() {
    // 组件挂载后，调用 API 获取用户个人信息
    axios
      .get(`http://localhost:8080/api/getpersonalinfo?name=${this.fullname}`)
      .then((response) => {
        const responseData = response.data
        this.name = responseData.Name // 设置用户姓名
        this.info = responseData.Info // 设置用户附加信息
        this.image = responseData.AvatarURL // 设置用户头像图片链接

        // 设置联系方式内容和链接
        this.contactList[0].content = responseData.Email
        this.contactList[0].link = 'mailto:' + responseData.Email // 邮箱链接

        this.contactList[1].content = responseData.GitHubAccount
        this.contactList[1].link = 'https://github.com/' + responseData.GitHubAccount // GitHub 个人主页链接

        this.contactList[2].content = responseData.WeChatID
        // 微信通常没有公开的链接，可以选择不设置 link 或设置为其他相关链接
      })
      .catch((error) => {
        console.error(error) // 请求失败时在控制台输出错误信息
      })
  },
  methods: {
    /**
     * 切换卡片的展开和收起状态
     */
    toggle() {
      this.isActive = !this.isActive // 切换 isActive 的布尔值
    }
  }
}
</script>

<style scoped>
/* 全局样式重置，去除默认内外边距和边框 */
* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

/* 卡片容器的基本样式 */
.card {
  position: relative; /* 相对定位，用于子元素定位 */
  transition: 0.5s; /* 添加过渡效果，平滑变化 */
  height: 100px; /* 默认高度为 100px */
  transition-delay: 0.5s; /* 过渡延迟 0.5s */
}

/* 当卡片处于活动状态时，增加高度以显示更多内容 */
.card.active {
  height: 450px; /* 活动状态下高度为 450px */
  transition-delay: 0s; /* 移除过渡延迟，立即开始过渡 */
}

/* 用户信息部分的样式 */
.card .user {
  position: relative; /* 相对定位，用于子元素绝对定位 */
  width: 400px; /* 固定宽度为 400px */
  min-height: 150px; /* 最小高度为 150px */
  background-color: rgba(255, 255, 255, 0.7); /* 半透明白色背景 */
  display: flex; /* 使用 Flexbox 布局 */
  justify-content: center; /* 水平居中对齐 */
  align-items: center; /* 垂直居中对齐 */
  flex-direction: column; /* 垂直方向排列子元素 */
  padding: 60px 40px 30px; /* 内边距：上 60px，左右 40px，下 30px */
  gap: 10px; /* 子元素之间的间距为 10px */
  border-radius: 10px; /* 圆角效果 */
}

/* 头像容器的样式 */
.card .user .imgBx {
  width: 100px; /* 宽度为 100px */
  height: 100px; /* 高度为 100px */
  position: absolute; /* 绝对定位，相对于父容器 .user */
  top: 0; /* 顶部对齐 */
  transform: translateY(-50%); /* 向上移动 50%，使头像居中于顶部 */
  border-radius: 100%; /* 完全圆形 */
  border: 2px solid #fff; /* 6px 白色边框 */
  overflow: hidden; /* 隐藏溢出内容，确保头像为圆形 */
  transition: 0.5s; /* 添加过渡效果 */
  z-index: 10; /* 设置堆叠顺序，确保头像在上方 */
}

/* 头像图片的样式 */
.card .user .imgBx img {
  width: 100%; /* 图片宽度为容器宽度的 100% */
  height: 100%; /* 图片高度为容器高度的 100% */
  object-fit: cover; /* 保持图片比例，覆盖整个容器 */
}

/* 用户内容部分的样式 */
.card .user .content {
  position: relative; /* 相对定位，用于子元素定位 */
  text-align: center; /* 文字居中对齐 */
}

/* 用户姓名和附加信息的样式 */
.card .user .content h2 {
  font-size: 1.2em; /* 字体大小为 1.2em */
  line-height: 1.05rem; /* 行高为 1.05rem */
  font-weight: 600; /* 字体粗细为 600 */
  color: #fff; /* 字体颜色为白色 */
}

/* 用户附加信息（例如职位或简介）的样式 */
.card .user .content h2 span {
  font-size: 0.75em; /* 字体大小为 0.75em */
  font-weight: 400; /* 字体粗细为 400 */
}

/* 切换按钮的样式 */
.card .user .toggle {
  position: absolute; /* 绝对定位，相对于父容器 .user */
  bottom: 0; /* 底部对齐 */
  width: 120px; /* 宽度为 120px */
  padding: 5px 15px; /* 内边距：上下 5px，左右 15px */
  background-color: #ff4383; /* 粉红色背景 */
  border-radius: 30px; /* 圆角效果 */
  transform: translateY(50%); /* 向下移动 50%，使按钮部分重叠在用户信息部分 */
  border: 6px solid var(--bg); /* 6px 边框，颜色由 CSS 变量 --bg 决定 */
  text-align: center; /* 文字居中对齐 */
  cursor: pointer; /* 鼠标悬停时显示为手型，表示可点击 */
  font-weight: 500; /* 字体粗细为 500 */
  transition: 0.5s; /* 添加过渡效果，平滑变化 */
}

/* 当卡片处于活动状态时，切换按钮的样式 */
.card.active .user .toggle {
  background-color: #d0d0d0; /* 灰色背景 */
  color: #fff; /* 文字颜色为白色 */
}

/* 切换按钮的文本内容，当卡片处于非活动状态时显示 'Contact me' */
.card .user .toggle::before {
  content: 'Contact me';
}

/* 切换按钮的文本内容，当卡片处于活动状态时显示 'Close' */
.card.active .user .toggle::before {
  content: 'Close';
}

/* 联系方式列表的容器样式 */
.card .contact {
  position: relative; /* 相对定位，用于子元素定位 */
  top: 30px; /* 向下移动 30px，调整位置 */
  width: 100%; /* 宽度为 100% */
  height: 0; /* 默认高度为 0，隐藏联系方式 */
  /* overflow: hidden; */ /* 可选：隐藏溢出内容 */
  display: flex; /* 使用 Flexbox 布局 */
  flex-direction: column; /* 垂直方向排列子元素 */
  gap: 10px; /* 子元素之间的间距为 10px */
  transition: 0.5s; /* 添加过渡效果，平滑变化 */
}

/* 当卡片处于活动状态时，显示联系方式列表 */
.card.active .contact {
  height: 325px; /* 设置高度为 325px，展开联系方式 */
}

/* 联系方式列表项的样式 */
.card .contact li {
  list-style: none; /* 去除默认列表样式 */
  width: 100%; /* 宽度为 100% */
  min-height: 100px; /* 最小高度为 100px */
  background-color: #fff; /* 白色背景 */
  border-radius: 10px; /* 圆角效果 */
  display: flex; /* 使用 Flexbox 布局 */
  opacity: 0; /* 初始透明度为 0，隐藏元素 */
  transform: scale(0); /* 初始缩放为 0，隐藏元素 */
  padding: 10px 20px; /* 内边距：上下 10px，左右 20px */
  transition: 0.5s; /* 添加过渡效果，平滑变化 */
  transition-delay: 0.2s; /* 过渡延迟 0.2s */
}

/* 当卡片处于活动状态时，显示联系方式列表项 */
.card.active .contact li {
  opacity: 1; /* 透明度变为 1，显示元素 */
  transform: scale(1); /* 缩放恢复为 1，显示元素 */
  transition-delay: calc(0.25s * var(--i)); /* 根据索引设置过渡延迟，创建顺序动画效果 */
}

/* 当鼠标悬停在联系方式列表上时，所有联系方式项变暗并模糊 */
.card.active .contact:hover li {
  opacity: 0.15; /* 透明度变为 0.15，变暗 */
  filter: blur(2px); /* 添加模糊效果 */
  transition-delay: 0s; /* 过渡延迟为 0，立即开始过渡 */
}

/* 当鼠标悬停在单个联系方式项上时，恢复其不变暗和不模糊 */
.card.active .contact li:hover {
  opacity: 1; /* 透明度恢复为 1，显示元素 */
  filter: blur(0px); /* 移除模糊效果 */
}

/* 联系方式链接的样式 */
.card .contact li a {
  display: flex; /* 使用 Flexbox 布局 */
  align-items: center; /* 垂直方向居中对齐 */
  text-decoration: none; /* 去除下划线 */
  gap: 10px; /* 子元素之间的间距为 10px */
}

/* 图标容器的样式 */
.card .contact li a .iconBx {
  position: relative; /* 相对定位，用于子元素定位 */
  width: 60px; /* 宽度为 60px */
  height: 60px; /* 高度为 60px */
  background-color: var(--clr); /* 背景颜色使用 CSS 变量 --clr */
  border-radius: 100%; /* 完全圆形 */
  display: flex; /* 使用 Flexbox 布局 */
  justify-content: center; /* 水平居中对齐 */
  align-items: center; /* 垂直居中对齐 */
}

/* 图标的样式 */
.card .contact li a .iconBx i {
  color: #ffffff; /* 图标颜色为白色 */
  font-size: 1.5em; /* 图标字体大小为 1.5em */
}

/* 联系方式内容的样式 */
.card .contact li a p {
  color: #666; /* 文字颜色为灰色 */
  font-size: 1.1em; /* 字体大小为 1.1em */
}

/* 当鼠标悬停在联系方式内容上时，改变文字颜色 */
.card .contact li a:hover p {
  color: #111; /* 文字颜色变为深色 */
}
</style>
