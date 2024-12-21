<template>
  <div class="card" :class="{ active: isActive }">
    <div class="user">
      <div class="imgBx">
        <img :src="memberInfo.avatar" :alt="memberInfo.name" />
        <div class="img-overlay"></div>
      </div>
      
      <div class="content">
        <h2>
          <span class="name">{{ memberInfo.name }}</span>
          <span class="info">{{ memberInfo.info }}</span>
        </h2>
      </div>
      
      <span class="toggle" @click="toggle">
        <span class="toggle-text">{{ isActive ? 'Close' : 'Contact Me' }}</span>
      </span>
    </div>
    
    <ul class="contact">
      <li
        v-for="(item, index) in memberInfo.contactList"
        :style="{ '--clr': item.color, '--i': index }"
        :key="index"
      >
        <a :href="item.link" target="_blank">
          <div class="iconBx">
            <i :class="item.icon"></i>
          </div>
          <p>{{ item.content }}</p>
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'MemberBox',
  
  props: {
    memberInfo: {
      type: Object,
      required: true,
      default: () => ({
        name: '',
        info: '',
        avatar: '',
        contactList: []
      })
    }
  },
  
  data() {
    return {
      isActive: false
    }
  },

  methods: {
    toggle() {
      this.isActive = !this.isActive
    }
  }
}
</script>

<style scoped>
.card {
  position: relative;
  transition: 0.5s;
  height: 150px;
  transition-delay: 0.5s;
}

.card.active {
  height: 450px;
  transition-delay: 0s;
}

.card .user {
  position: relative;
  width: 400px;
  min-height: 150px;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  padding: 60px 40px 30px;
  gap: 10px;
  border-radius: 16px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(130, 170, 255, 0.15);
  box-shadow: 0 8px 20px rgba(130, 170, 255, 0.1);
}

.card .user .imgBx {
  width: 120px;
  height: 120px;
  position: absolute;
  top: 0;
  transform: translateY(-50%);
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #4c6ef5;
  transition: 0.5s;
  z-index: 10;
}

.card .user .imgBx img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.card .user .imgBx:hover img {
  transform: scale(1.1);
}

.card .user .content {
  position: relative;
  text-align: center;
  margin-top: 15px;
}

.card .user .content h2 {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.card .user .content h2 .name {
  font-size: 1.5rem;
  font-weight: 600;
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.card .user .content h2 .info {
  font-size: 0.9rem;
  color: #495057;
  font-weight: 400;
  line-height: 1.4;
}

.card .user .toggle {
  position: absolute;
  bottom: 0;
  transform: translateY(50%);
  background: linear-gradient(90deg, #4c6ef5, #15aabf);
  padding: 0.6rem 1.5rem;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 12px rgba(76, 110, 245, 0.2);
}

.card .user .toggle:hover {
  transform: translateY(50%) scale(1.05);
  box-shadow: 0 6px 15px rgba(76, 110, 245, 0.3);
}

.toggle-text {
  color: white;
  font-weight: 500;
  font-size: 0.9rem;
}

.card .contact {
  position: relative;
  top: 30px;
  width: 100%;
  height: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
  transition: 0.5s;
}

.card.active .contact {
  height: 325px;
}

.card .contact li {
  list-style: none;
  width: 100%;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(237, 242, 247, 0.9));
  border-radius: 12px;
  display: flex;
  opacity: 0;
  transform: scale(0);
  padding: 1rem;
  transition: all 0.5s ease;
  border: 1px solid rgba(130, 170, 255, 0.15);
}

.card.active .contact li {
  opacity: 1;
  transform: scale(1);
  transition-delay: calc(0.2s * var(--i));
}

.card .contact li a {
  display: flex;
  align-items: center;
  text-decoration: none;
  width: 100%;
  gap: 15px;
}

.card .contact li a .iconBx {
  width: 50px;
  height: 50px;
  background-color: var(--clr);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: transform 0.3s ease;
}

.card .contact li:hover a .iconBx {
  transform: scale(1.1);
}

.card .contact li a .iconBx i {
  color: #ffffff;
  font-size: 1.2rem;
}

.card .contact li a p {
  color: #495057;
  font-size: 1rem;
  font-weight: 500;
  transition: color 0.3s ease;
}

.card .contact li:hover a p {
  color: #4c6ef5;
}

/* Hover effects */
.card.active .contact:hover li {
  opacity: 0.5;
  filter: blur(2px);
}

.card.active .contact li:hover {
  opacity: 1;
  filter: blur(0);
  transform: translateX(10px);
}
</style>