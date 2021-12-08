<template>
    <div id="studentProfile-header">
        <ul id="header-ul">
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='overview'">Overview</button>
            </li>
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='enterd'">Entered</button>
            </li>
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='companies'">Companies</button>
            </li>
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='recommend'">★</button>
            </li>
        </ul>
        <hr>
    </div>
    <div id="studentProfile-item">
        <div id="studentInfo">
            <student-info-component :login_user="login_user"></student-info-component>
        </div>
        <div id="company">
            <company-component :recently_entered_companies="recently_entered_companies" v-if="currentComponent==='overview'"></company-component>
            <enterd-component :entered_companies="entered_companies" v-if="currentComponent==='enterd'"></enterd-component>
            <companies-component :companies="companies" v-if="currentComponent==='companies'"></companies-component>
            <recommend-component v-if="currentComponent==='recommend'"></recommend-component>
        </div>
    </div>
</template>

<script>
import HeaderComponent from './HeaderComponent.vue';
import StudentInfoComponent from './StudentInfoComponent.vue';
import CompanyComponent from './CompanyComponent.vue';
import EnterdComponent from './EnterdComponent.vue';
import CompaniesComponent from './CompaniesComponent.vue';
import RecommendComponent from './RecommendComponent.vue';

export default {
    data(){
        return {
            currentComponent: "overview",
            login_user: Object,
            recently_entered_companies: [],
            entered_companies: [],
            companies: [],
        }
    },
    components: {
        HeaderComponent,
        StudentInfoComponent,
        CompanyComponent,
        EnterdComponent,
        CompaniesComponent,
        RecommendComponent
    },
    created() {
        let self = this;
        let url = '/api/student/openview';
        let enterd_url = '/api/student/getEnteredCompanies';
        let companies_url = '/api/student/getMyCompanies';

        axios.get(url).then(function(response){
            self.login_user = response.data.login_user;
            self.recently_entered_companies = response.data.recently_entered_companies;
        })

        axios.get(enterd_url).then(function(response){
            self.login_user = response.data.login_user;
            self.entered_companies = response.data.entered_companies;
        })

        axios.get(companies_url).then(function(response){
            self.login_user = response.data.login_user;
            self.companies = response.data.companies;
        })
    },
    props:['csrf']
}

</script>

<style scoped lang="scss">
    #studentProfile-header {
        text-align: center;
        margin-left: 100px;
    }
    #header-ul {
        list-style: none;
        margin-top: 50px;
    }
    .header-li {
        font-size: 20px;
        display: inline;
        padding-left: 50px;
        padding-right: 50px;
    }
    #switch-button {
        border: none;
        background: transparent;
    }
    #switch-button:hover {
        color: #c0c0c0;
    }
    hr {
        border: solid 1px #ddd;
        width: 1100px;
        margin-left: 300px;
    }

    #studentProfile-item {
        display: flex;
    }

    #studentInfo {
        margin-left: 140px;
    }
    #studentProfileItem-ul {
        list-style: none;
        margin-top: 100px;
    }
    .studentProfileItem-li {
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
