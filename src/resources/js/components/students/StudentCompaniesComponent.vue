<template>
    <div id="companies_container">
        <ul id="studentProfile_companies_list">
            <li class="studentProfile_companies_item"  v-for="company in companies" :key="company.id">
                <a v-bind:href="`/student/companies/${company.id}`" class="studentProfile_link">
                    <h3>{{company.name}}</h3>
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
        'companies'
    ],
    methods: {

    },
}
</script>

<style scoped lang="scss">
    #companies_container {
        margin-left: 180px;
    }

    #studentProfile_companies_list {
        list-style-type: none;
        width: 1200px;
    }

    .studentProfile_companies_item {
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
    .studentProfile_companies_item:hover {
        background-color: #e0e0e0;
    }
    .studentProfile_link {
        text-decoration: none;
        color: #000;
    }
    h3 {
        margin-top: 40px;
    }
</style>