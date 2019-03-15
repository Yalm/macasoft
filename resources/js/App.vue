<template>
    <div id="app">
        <router-view/>
    </div>
</template>
<script>
export default {
    name: 'App',
    created () {
        this.$http.interceptors.response.use(undefined, (err) => {
            return new Promise( (resolve, reject) => {
                if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
                    this.$store.dispatch(logout)
                }
                throw err;
            });
        });
    }
}
</script>
<style lang="sass" scoped>
.md-drawer
    width: 230px
    max-width: calc(100vw - 125px)
</style>
