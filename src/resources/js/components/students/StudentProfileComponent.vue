<template>
    <div id="studentProfile-header">
        <ul id="header-ul">
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='overview'" v-if="currentComponent!='overview'">Overview</button>
                <button id="switch-large-button" @click="currentComponent='overview'" v-if="currentComponent==='overview'">Overview</button>
            </li>
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='enterd'" v-if="currentComponent!='enterd'">Entered</button>
                <button id="switch-large-button" @click="currentComponent='enterd'" v-if="currentComponent==='enterd'">Entered</button>
            </li>
            <li class="header-li">
                <button id="switch-button" @click="currentComponent='companies'" v-if="currentComponent!='companies'">Companies</button>
                <button id="switch-large-button" @click="currentComponent='companies'" v-if="currentComponent==='companies'">Companies</button>
            </li>
        </ul>
        <hr>
    </div>
    <div id="studentProfile-item">
        <div id="studentInfo">
            <student-info-component :login_user="login_user"></student-info-component>
        </div>
        <div id="company">
            <over-view-component :recently_entered_companies="recently_entered_companies" v-if="currentComponent==='overview'"></over-view-component>
            <enterd-companies-component :entered_companies="entered_companies" v-if="currentComponent==='enterd'"></enterd-companies-component>
            <student-companies-component :companies="companies" v-if="currentComponent==='companies'"></student-companies-component>
        </div>
    </div>
    <div id="graphSwitch">
        <ul id="graphSwitch-ul">
            <li class="graphSwitch-li">
                <button id="switch-button" @click="graphSwitch='thisYear'" v-if="currentComponent==='overview'&&graphSwitch!='thisYear'">今年</button>
                <button id="switch-large-button" @click="graphSwitch='thisYear'" v-if="currentComponent==='overview'&&graphSwitch==='thisYear'">今年</button>
            </li>
            <li class="graphSwitch-li">
                <button id="switch-button" @click="graphSwitch='lastYear'" v-if="currentComponent==='overview'&&graphSwitch!='lastYear'">昨年</button>
                <button id="switch-large-button" @click="graphSwitch='lastYear'" v-if="currentComponent==='overview'&&graphSwitch==='lastYear'">昨年</button>
            </li>
        </ul>
    </div>
    <div id="studentProfileGraph" style="position: relative; height:40vh; width:55vw">
            <student-profile-last-year-graph-component :lastYears="lastYears" v-if="currentComponent==='overview'&&graphSwitch==='lastYear'"></student-profile-last-year-graph-component>
            <student-profile-this-year-graph-component :thisYears="thisYears" v-if="currentComponent==='overview'&&graphSwitch==='thisYear'"></student-profile-this-year-graph-component>
    </div>
</template>

<script>
import HeaderComponent from './HeaderComponent.vue';
import StudentInfoComponent from './StudentInfoComponent.vue';
import OverViewComponent from './OverViewComponent.vue';
import EnterdCompaniesComponent from './EnterdCompaniesComponent.vue';
import StudentCompaniesComponent from './StudentCompaniesComponent.vue';
import StudentProfileLastYearGraphComponent from './StudentProfileLastYearGraphComponent.vue';
import StudentProfileThisYearGraphComponent from './StudentProfileThisYearGraphComponent.vue';

export default {
    data(){
        return {
            currentComponent: "overview",
            login_user: NaN,
            recently_entered_companies: [],
            entered_companies: [],
            companies: [],
            graphSwitch: "thisYear",
            thisYears: [],
            lastYears: [],
        }
    },
    components: {
        HeaderComponent,
        StudentInfoComponent,
        OverViewComponent,
        EnterdCompaniesComponent,
        StudentCompaniesComponent,
        StudentProfileLastYearGraphComponent,
        StudentProfileThisYearGraphComponent,
    },
    created() {
        let self = this;
        let url = '/api/student/overview';
        let enterd_url = '/api/student/getEnteredCompanies';
        let companies_url = '/api/student/getMyCompanies';

        axios.get(url).then(function(response){
            self.login_user = response.data.login_user;
            self.recently_entered_companies = response.data.recently_entered_companies;
            self.thisYears = response.data.this_year_entry_count_array;
            self.lastYears = response.data.last_year_entry_count_array;
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
    props:['csrf'],
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
    #switch-large-button {
        border: none;
        background: transparent;
        text-decoration: underline;
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

    .small {
        max-width: 600px;
        margin:  150px auto;
    }

    #graphSwitch {
        margin-left: 75%;
    }
    #graphSwitch-ul {
        list-style: none;
        margin-top: 50px;
    }
    .graphSwitch-li {
        display: inline;
        padding-right: 40px;
    }

    #studentProfileGraph {
        margin-left: 450px;
        width: 700px;
    }
</style>
