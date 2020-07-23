<template>

    <div>
        <h1> {{ title }} </h1>
    </div> 
    
</template>

<script>
    export default {
        name: "Title",
        props: {
            title: {
                type: String,
                default: '',
            }
        },
        created() {
            //
        },
        mounted() {
            //
        }
    }
</script>

<style scoped lang="scss">
    //
</style>
