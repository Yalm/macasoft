<template>
    <div class="pagination-table d-flex justify-content-end align-items-center pt-2" v-if="data && data.total > data.per_page">
        <span class="text-pagination">{{`${data.from}-${data.to} de ${data.total}`}}</span>
        <md-button class="md-icon-button ml-3" :disabled="data.from == 1 || progress" @click="nextPage(data.prev_page_url)">
            <md-icon>keyboard_arrow_left</md-icon>
        </md-button>
        <md-button class="md-icon-button" @click="nextPage(data.next_page_url)" :disabled="data.current_page == data.last_page || progress">
            <md-icon>keyboard_arrow_right</md-icon>
        </md-button>
    </div>
</template>
<script>
export default {
    name: 'pagination-material',
    props:['data','search'],
    data: () => ({
        progress: false
    }),
    methods: {
        nextPage(page){
            this.progress = true;
            this.$http.get(`${page}&json=true&search=${this.search}`).then(response => {
                this.progress = false;
                this.$emit('dataChange',response.data);
            });
        }
    }
}
</script>

<style lang="sass" scoped>
.pagination-table
    border-top: 1px solid rgba(0, 0, 0, 0.12)
    .text-pagination
        font-size: 12px
</style>
