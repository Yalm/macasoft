import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem('token') || '',
        user : JSON.parse(localStorage.getItem('user')) || {}
    },
    mutations: {
        auth_request(state) {
            state.status = 'loading';
        },
        auth_success(state, {token,user}) {
            state.status = 'success';
            state.token = token;
            state.user = user;
        },
        auth_error(state) {
            state.status = 'error';
        },
        logout(state) {
            state.status = '';
            state.token = '';
        }
    },
    actions: {
        login({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('auth_request')
                axios.post('/api/auth/login',user)
                .then(resp => {
                    const token = resp.data.access_token;
                    const user = resp.data.user;
                    localStorage.setItem('token', token);
                    localStorage.setItem('user', JSON.stringify(user));
                    localStorage.setItem('expires_in', resp.data.expires_in);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    commit('auth_success', {token,user});
                    resolve(resp);
                    reject();
                })
                .catch(err => {
                    commit('auth_error');
                    localStorage.removeItem('token');
                    reject(err);
                })
            })
        },
        logout({commit}){
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/logout').then( () => {
                    commit('logout');
                    localStorage.removeItem('token');
                    delete axios.defaults.headers.common['Authorization'];
                    resolve();
                })
                .catch(err => {
                    reject(err);
                });
            })
        }
    },
    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status
    }
})
