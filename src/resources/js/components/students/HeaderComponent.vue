<template>
    <div>
        <ul>
            <li><a href="">Overview</a></li>
            <li><a href="">Entered</a></li>
            <li><a href="">Companies</a></li>
            <li><a href="">★</a></li>
        </ul>
        <hr>
    </div>
</template>

<script>

export default {
    name: 'Header',

    data(){
        return {

        }
    },
    methods: {

    },
}
</script>

<style scoped lang="scss">
    ul {
        list-style: none;
        margin-top: 50px;
    }
    li {
        font-size: 20px;
        display: inline;
        padding-left: 50px;
        padding-right: 50px;
    }
    hr {
        border: solid 1px #ddd;
        width: 1100px;
        margin-left: 300px;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    a:hover {
        color: #c0c0c0;
    }
</style>
