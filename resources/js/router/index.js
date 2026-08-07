import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('../components/Login.vue')
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../components/Register.vue')
    },
    {
        path: '/profesor',
        name: 'PanelProfesor',
        component: () => import('../components/PanelProfesor.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/',
        name: 'Dashboard',
        component: () => import('../components/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/profesores',
        name: 'Profesores',
        component: () => import('../components/Profesores.vue'),
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');

    if (to.meta.requiresAuth && !token) {
        next('/login');
    }
    else if ((to.path === '/login' || to.path === '/register') && token) {
        next('/');
    }
    else {
        next();
    }
});

export default router;