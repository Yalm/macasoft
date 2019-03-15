<template>
    <div id="app">
        <router-view/>
    </div>
</template>
<script>
export default {
    name: 'App'
}
</script>
<style lang="sass" scoped>
.md-drawer
    width: 230px
    max-width: calc(100vw - 125px)
</style>
