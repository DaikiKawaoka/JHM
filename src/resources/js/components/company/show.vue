<template>
    <div class="entryComponent">
        <div class="entryComponent-left-part">
            <div class="show-pdf">
                <img :src="'http://localhost:8000/storage/pdf_image/'+company.image_path+'.jpg'" class="companies_pic" v-if="company.image_path">
                <img src="http://localhost:8000/img/no_image_square.jpg" class="companies_pic" v-else>
            </div>
        </div>
        <div class="entryComponent-right-part">
            <div class="company-detail radius margin-top font-size">
                <table>
                    <tr>
                        <th>会社名</th>
                        <td>{{company.name}}</td>
                    </tr>
                    <tr>
                        <th>勤務先</th>
                        <td>{{company.prefecture}}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td>{{company.url}}</td>
                    </tr>
                    <tr>
                        <th>応募締切日</th>
                        <td>{{company.deadline}}</td>
                    </tr>
                </table>
            </div>
            <div class="entry-circumstances radius margin-top font-size">
                <table>
                    <tr>
                        <th>エントリー人数</th>
                        <td>{{all_entry_count}}名</td>
                    </tr>
                    <tr>
                        <th>クラスのエントリー人数</th>
                        <td>{{class_entry.length}}名</td>
                    </tr>
                    <tr v-if="class_entry.length != 0">
                        <th>エントリーした生徒一覧</th>
                    </tr>
                    <tr v-for="student in class_entry" :key="student.id">
                        <th></th>
                        <td>{{student.name}}</td>
                    </tr>
                </table>
            </div>
            <div class="company-remarks radius margin-top my-3" v-show="company.remarks">
                <p class="font-size remarks-title">詳細説明</p>
                <div class="mx-3">
                    {{company.remarks}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ['company', 'all_entry_count', 'class_entry'],
    mounted(){
        console.log(this.class_entry);
    }
}
</script>

<style scoped lang='scss'>

    .entryComponent{
        margin-top: 1rem;
        display: flex;
        .entryComponent-left-part{
            // width: 80%;
            .show-pdf{
                margin-top: 1rem;
                display: flex;
                justify-content: center;
                align-items: center;
                img{
                    width: 35rem;
                    height: 35rem;
                }
            }
        }

        .entryComponent-right-part{
            width: 100%;
            margin-left: 20px;
                .company-detail, .entry-circumstances{
                    width: 90%;
                    background: #fff;
                    border: solid 1px #aaa;
                    box-shadow: 0 0 20px rgba(170, 170, 170, .1);
                    table{
                        tr{
                            th{
                                padding-left: 40px;
                            }
                            td{
                                padding-left: 100px;
                            }
                        }
                    }
                }
            .company-remarks{
                border: solid 1px #000000;
                .remarks-title{
                    font-weight: bold;
                }
            }
        }
    .radius{
        border-radius: 5px;
    }
    .margin-top{
        margin-top: 20px;
        padding: 20px;
    }
    .font-size{
        font-size: 1.3em;
    }
}

</style>