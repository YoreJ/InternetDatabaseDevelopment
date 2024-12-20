import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: {
        showNavBar: false
      }
    },
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/chat',
      name: 'chat',
      component: () => import('../views/ChatView.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/movie',
      name: 'movie',
      component: () => import('../views/MovieView.vue'),
      meta: {
        showNavBar: true,
        transition: 'fade'
      }
    },
    {
      path: '/movie/:id',
      name: 'movieplay',
      props: true,
      component: () => import('../views/MoviePlay.vue'),
      meta: {
        showNavBar: true,
        transition: 'fade'
      }
    },
    {
      path: '/article',
      name: 'article',
      component: () => import('../views/ArticleView.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/article/:id',
      name: 'articleplay',
      component: () => import('../views/ArticlePlay.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/user',
      name: 'user',
      component: () => import('../views/UserView.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
      meta: {
        showNavBar: true
      }
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      meta: {
        showNavBar: false
      }
    },
    {
      path: '/:path(.*)',
      name: 'NotFound',
      meta: {
        showNavBar: false
      },
      component: () => import('../views/NotFoundView.vue')
    }
  ]
})

router.beforeEach((to, from, next) => {
  const Username = sessionStorage.getItem('Username')
  const Password = sessionStorage.getItem('Password')

  // 检查 Session Storage 中的值
  if (!Username && !Password && to.path !== '/login') {
    next('/login')
  } else {
    next()
  }
})

export default router
