<template>

    <div class="example-wrapper">
        <h1>Hello ! </h1>
        <p>Message page home : </p>
        <ul><li>Page d'accueil <code>{{ page }}</code></li></ul>     
    </div> 

</template>

<script>
    export default {
        name: "Home",
        data () {
            return {
                page: 'home',
            }
        },
        mounted() {
            console.log('Mounted Home')
        }
    }
</script>

<style scoped lang="scss">
    .example-wrapper { 
        margin: 1em auto; 
        max-width: 800px; 
        width: 95%; 
        font: 18px/1.5 sans-serif;
        code {
            background: #F5F5F5; 
            padding: 2px 6px;
        }
    }
</style>