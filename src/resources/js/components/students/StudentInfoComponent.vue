<template>
    <div >
        <ul>
            <li><h2>{{login_user.name}}</h2></li>
            <li><p class="profile">{{login_user.email}}</p></li>
        </ul>
    </div>
</template>

<script>
export default {
    data(){
        return {

        }
    },
    props:[
        'login_user'
    ],
    methods: {

    },
}
</script>

<style scoped lang="scss">
    ul {
        list-style: none;
        margin-top: 100px;
    }
    li {
        font-size: 20px;
        margin-bottom: 15px;
    }

    .profile {
        font-size: 15px;
    }
    #edit_btn {
        width: 125px;
    }
</style>
