<template>
    <div>
        <header-component></header-component>
        <student-info-component></student-info-component>
        <company-component></company-component>
    </div>
</template>

<script>
import HeaderComponent from './HeaderComponent.vue';
import StudentInfoComponent from './StudentInfoComponent.vue';
import CompanyComponent from './CompanyComponent.vue';
export default {
    data(){
        return {

        }
    },
    components: {
        HeaderComponent, StudentInfoComponent, CompanyComponent
    },
    methods: {

    },
    props:['csrf']
}
</script>

<style scoped lang="scss">

</style>
