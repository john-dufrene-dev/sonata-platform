<template>

    <div>
        <h1> {{ title }} </h1>
    </div> 
    
</template>

<script>
    export default {
        name: "DefaultTitle",
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
