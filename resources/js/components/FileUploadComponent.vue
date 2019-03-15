<template>
    <div class="picture-container">
        <div class="picture" v-bind:style="[url ? {'background-image': 'url(' + url + ')'} : {'background-color': '#999'}]" v-bind:class="{ error: err }" >
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 350 350" style="enable-background:new 0 0 350 350;" xml:space="preserve" v-bind:style="[ !url ? {'opacity': '1' } : {'opacity': '0'}]" >
                <path d="M175,171.173c38.914,0,70.463-38.318,70.463-85.586C245.463,38.318,235.105,0,175,0s-70.465,38.318-70.465,85.587
                    C104.535,132.855,136.084,171.173,175,171.173z"/>
                <path d="M41.909,301.853C41.897,298.971,41.885,301.041,41.909,301.853L41.909,301.853z"/>
                <path d="M308.085,304.104C308.123,303.315,308.098,298.63,308.085,304.104L308.085,304.104z"/>
                <path d="M307.935,298.397c-1.305-82.342-12.059-105.805-94.352-120.657c0,0-11.584,14.761-38.584,14.761
                    s-38.586-14.761-38.586-14.761c-81.395,14.69-92.803,37.805-94.303,117.982c-0.123,6.547-0.18,6.891-0.202,6.131
                    c0.005,1.424,0.011,4.058,0.011,8.651c0,0,19.592,39.496,133.08,39.496c113.486,0,133.08-39.496,133.08-39.496
                    c0-2.951,0.002-5.003,0.005-6.399C308.062,304.575,308.018,303.664,307.935,298.397z"/>
            </svg>
            <input class="input-picture" type="file" @change="uploadFile($event)" name="avatar" v-if="!disabled">
        </div>
        <h6 class="text-center" v-bind:class="{ 'text-danger': err }">{{ err ? 'Solo imagenes': 'Elegir Foto' }}</h6>
    </div>
</template>

<script>
export default {
    props: ['disabled','data'],
    name: 'fileUpload',
    data: () => ({
        url: '',
        file: null,
        err: false
    }),mounted() {
        this.url = this.data;
    },
    methods: {
        async uploadFile(event) {
            this.err = false;
            if (event.target.files && event.target.files[0]) {
                const file = event.target.files[0];
                let type = file.type.split("/");
                type = type[0];
                if(type == 'image') {
                    this.url = await this.readUploadedFile(file);
                    this.file = file;
                    this.$emit('upload', this.file);
                }else {
                    this.err = true;
                    this.$emit('upload', null);
                    this.url = null;
                }
            }
        },
        readUploadedFile(file)
        {
            const reader = new FileReader();

            return new Promise((resolve) =>
            {
                reader.onload = () =>
                {
                    resolve(reader.result);
                };
                reader.readAsDataURL(file);
            });
        }
    },
    watch: {
        data() {
            if(this.data == null) {
                this.url = null;
                this.file = null;
            }
        }
    }
}
</script>

<style lang="sass">
.picture-container
    position: relative
    cursor: pointer
    .picture
        width: 100%
        background-color: #999
        border: 4px solid #ccc
        color: #fff
        border-radius: 100%
        margin: 5px auto
        overflow: hidden
        transition: .2s
        padding-top: 1rem
        background-position: center
        background-repeat: no-repeat
        background-size: cover
        &.error
            border-color: #dc3545
    .input-picture
        cursor: pointer
        display: block
        height: 100%
        left: 0
        opacity: 0
        position: absolute
        top: 0
        width: 100%
    &:hover .picture
        border-color: var(--md-theme-default-primary, #448aff)
</style>
