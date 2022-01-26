<template>
    <div id="form-content">
        <div class="form-group row form-row">
            <label for="name" class="col-md-3 col-form-label text-md-right">会社名</label>

            <div class="col-md-8">
                <input id="name" type="text" class="form-control" name="name" :value=company.name required autofocus>
                <span class="error-msg" role="alert" v-show="errors.name">
                    <strong>{{errors.name[0]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row form-row">
            <label for="prefecture" class="col-md-3 col-form-label text-md-right">勤務先</label>
            <div class="col-md-8">
                <select class="form-control" id="prefecture" name="prefecture" :value=company.prefecture>
                    <option value="北海道">北海道</option>
                    <option value="青森">青森</option>
                    <option value="東京">東京</option>
                    <option value="大阪">大阪</option>
                    <option value="愛媛" selected>愛媛</option>
                    <option value="福岡">福岡</option>
                    <option value="沖縄">沖縄</option>
                </select>
                <span class="error-msg" role="alert" v-show="errors.prefecture">
                    <strong>{{errors.prefecture[0]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row form-row">
            <label for="url" class="col-md-3 col-form-label text-md-right">ホームページURL</label>

            <div class="col-md-8">
                <input id="url" type="text" class="form-control" name="url" :value=company.url autofocus>
                <span class="error-msg" role="alert" v-show="errors.url">
                    <strong>{{errors.url[0]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row form-row">
            <label for="deadline" class="col-md-3 col-form-label text-md-right">応募締切</label>
            <div class="col-md-8">
                <input type="date" class="form-control" id="deadline" name="deadline" :value=company.deadline>
                <span class="error-msg" role="alert" v-show="errors.deadline">
                    <strong>{{errors.deadline[0]}}</strong>
                </span>
            </div>
        </div>

        <div class="form-group row form-row" v-for="num in pdf_count" :key="num">
            <label for="pdf" class="col-md-3 col-form-label text-md-right">PDF({{num}})</label>
            <div class="col-md-6">
                <input type="file" class="form-control" id="pdf" :name="'pdf'+num" value="">
            </div>
            <div class="col-md-2" id="pdf-btn-field" v-show="pdf_count == num">
                <button :class="'ope-pdf-field'+getDisableAddClass" @click="addPdfField" type="button">
                    <i class="fas fa-plus"/>
                </button>
                <button :class="'ope-pdf-field'+getDisableRemoveClass" @click="removePdfField" type="button">
                    <i class="fas fa-backspace"></i>
                </button>
            </div>
        </div>

        <div class="form-group row form-row">
            <label for="name" class="col-md-3 col-form-label text-md-right">備考</label>
            <div class="col-md-8">
                <textarea id="remarks" class="form-control" name='remarks' placeholder="備考" rows="8" v-model="company.remarks"></textarea>
                <span class="error-msg" role="alert" v-show="errors.remarks">
                    <strong>{{errors.remarks[0]}}</strong>
                </span>
            </div>
        </div>


        <div class="form-group row form-row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    登録
                </button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            pdf_count: 1,
        }
    },
    methods:{
        addPdfField(){
            if(this.pdf_count < 3)
                this.pdf_count++;
        },
        removePdfField(){
            if(this.pdf_count > 1)
                this.pdf_count--;
        }
    },
    computed:{
        getDisableAddClass(){
            if(this.pdf_count >= 3)
                return ' disable-btn';
            else
                return '';
        },
        getDisableRemoveClass(){
            if(this.pdf_count <= 1)
                return ' disable-btn';
            else
                return '';
        },
    },
    created(){
        console.log(this.errors);
        console.log(this.company);
    },
    props: ['errors', 'company'],
}
</script>

<style scoped lang='scss'>
    #form-content{
        .error-msg{
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #e3342f;
        }
        .form-row{
            #pdf-btn-field{
                display: flex;
                justify-content: flex-end;
                .ope-pdf-field{
                    width: 2.0rem;
                    height: 2.0rem;
                    border-radius: .4rem;
                    padding: .2rem;
                    background: #b2bec3;
                    color: #fff;
                    border: none;
                    margin: 0 .5rem;
                    &:hover{
                        transition: .5s;
                        opacity: .7;
                        background: #636e72;
                    }
                }
                .disable-btn{
                    background: #dfe6e9;
                        &:hover{
                        opacity: 1.0;
                        background: #dfe6e9;
                    }
                }
            }
        }
    }
</style>