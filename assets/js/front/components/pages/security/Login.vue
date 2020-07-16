<template>

    <div>
        <h1>Default Login page</h1>
    </div> 

</template>

<script>
    export default {
        name: 'Login',
        data () {
            return {
                page: 'Login',
            }
        },
        mounted() {
            console.log('Mounted Login')
        }
    }
</script>

<style scoped lang="scss">
    //
</style>
