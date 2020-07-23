<template>

    <div id="header" class="container">
        <h1-title :title="title"></h1-title>
    </div> 

</template>

<script>
    import Title from '@@js/front/components/helpers/header/Title';

    export default {
        name: 'Header',
        props: {
            title: {
                type: String,
                default: '',
            }
        },
        data () {
            return {
                info: 'Header',
            }
        },
        mounted() {
            // console.log(this.title)
        },
        components: {
            'h1-title': Title,
        },
    }
</script>

<style scoped lang="scss">
    //
</style>
