<template>
    <div id="enterd_container">
        <ul id="studentProfile_enterd_list">
            <li class="studentProfile_enterd_item"  v-for="company in entered_companies" :key="company.id">
                <a href="" class="studentProfile_link" v-if="entered_companies.name!==null">
                    <h3>{{company.name}}</h3>
                </a>
                <a href="" id="studentProfile_link" v-else>
                    <h3>データがありません</h3>
                </a>
            </li>
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
        'entered_companies'
    ],
    methods: {

    },
}
</script>

<style scoped lang="scss">
    #enterd_container {
        margin-left: 180px;
    }

    #studentProfile_enterd_list {
        list-style-type: none;
        width: 1200px;
    }

    .studentProfile_enterd_item {
        float: left;
        width: 600px;
        border: solid 1px #ddd;
        width: 420px;
        height: 120px;
        text-align: center;
        border-radius: 10px;
        margin-top: 20px;
        margin-right: 30px;
    }
    .studentProfile_link {
        text-decoration: none;
        color: #000;
    }
    h3 {
        margin-top: 40px;
    }

    .studentProfile_enterd_item:hover {
        background-color: #e0e0e0;
    }
</style>