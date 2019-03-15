import Vue from 'vue';
import Router from 'vue-router';
import store from './store.js';
import Dashboard from './layouts/Dashboard.vue';
import Home from './views/Home.vue';
import Login from './views/Login.vue';

import User from './views/user/Index.vue';
import UserCreate from './views/user/Create.vue';
import UserEdit from './views/user/Edit.vue';

import Role from './views/role/Index.vue';

import PageNotFound from './views/PageNotFound.vue';

Vue.use(Router);

const  router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/admin',
            component: Dashboard,
            meta: { requiresAuth: true },
            children: [
                { path: '/', name: 'home', component: Home },
                { path: 'users', name: 'users', component: User },
                { path: 'users/create', name: 'users.create', component: UserCreate },
                { path: 'users/:id/edit', name: 'users.edit', component: UserEdit },
                { path: 'roles', name: 'roles', component: Role },
            ]
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        { path: '/', redirect: '/admin' },
        { path: '*', component: PageNotFound }
    ]
});

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next();
            return
        }
        next('/login')
    } else {
        next();
    }
});

export default router;
