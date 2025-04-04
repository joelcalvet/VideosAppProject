import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
  { path: '/', redirect: '/tabs/home' },
  { path: '/login', component: () => import('@/views/Login.vue') },
  { path: '/register', component: () => import('@/views/Register.vue') },
  { path: '/tabs/home', component: () => import('@/views/Home.vue'), beforeEnter: (to, from, next) => localStorage.getItem('token') ? next() : next('/login') },
  { path: '/tabs/user', component: () => import('@/views/User.vue'), beforeEnter: (to, from, next) => localStorage.getItem('token') ? next() : next('/login') },
  { path: '/tabs/create', component: () => import('@/views/Create.vue'), beforeEnter: (to, from, next) => localStorage.getItem('token') ? next() : next('/login') },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;