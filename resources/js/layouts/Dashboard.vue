<template>
<div class="page-container">
    <md-app md-mode="reveal">
        <md-app-toolbar class="md-primary">
            <md-button class="md-icon-button" @click="menuVisible = !menuVisible">
                <md-icon>menu</md-icon>
            </md-button>
            <span class="md-title">Macasoft</span>
        </md-app-toolbar>

        <md-app-drawer :md-active.sync="menuVisible">
            <md-toolbar class="md-transparent" md-elevation="0">Navegación</md-toolbar>

            <md-list>
                <md-list-item>
                    <md-avatar>
                        <img :src="user.avatar" alt="People">
                    </md-avatar>

                    <span class="md-list-item-text text-capitalize">{{user.name}}</span>
                </md-list-item>
                <md-subheader>Tablero</md-subheader>

                <md-list-item to="/admin">
                    <md-icon>home</md-icon>
                    <span class="md-list-item-text">Inicio</span>
                </md-list-item>

                <md-list-item to="/admin/users">
                    <md-icon>group</md-icon>
                    <span class="md-list-item-text">Usuarios</span>
                </md-list-item>

                <md-list-item to="/admin/roles">
                    <md-icon>style</md-icon>
                    <span class="md-list-item-text">Roles</span>
                </md-list-item>

                <md-list-item @click="logout()">
                    <md-icon>directions_run</md-icon>
                    <span class="md-list-item-text">Cerrar Sesión</span>
                </md-list-item>
            </md-list>
        </md-app-drawer>

        <md-app-content>
            <router-view/>
        </md-app-content>
    </md-app>
</div>
</template>
<script>
import { mapState } from 'vuex';

export default {
    name: 'Dashboard',
    data: () => ({
        menuVisible: false
    }),
    created () {
        this.$http.interceptors.response.use( (response) => {
            return response;
        }, (error) => {
            if (401 === error.response.status) {
                this.$store.dispatch('logout').then(() => {
                    this.$router.push('/login')
                });
            }
            return Promise.reject(error.response);
        });
    },
    methods: {
        logout () {
            this.$store.dispatch('logout').then(() => {
                this.$router.push('/login')
            });
        }
    },
    computed: mapState({
        user: state => state.user,
    })
}
</script>
<style lang="sass">
.md-app-content
    height: 94vh
</style>
