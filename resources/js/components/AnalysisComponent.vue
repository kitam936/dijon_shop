<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';
import { onMounted, reactive ,ref, computed} from 'vue';
import {getToday} from '@/common/'
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import Chart from '@/Components/Chart.vue'

// onMounted (()=>{
//     form.startDate = getToday()
//     form.endDate = getToday()
// })

// const form = useForm({
//     startDate:null,
//     endDate:null,
//     // type:'perDay'
// })

// const data = reactive({})

// const getData = async() =>{
//     try{
//         await axios.get('/api/analysis/',{
//             params:{
//                 startDate:form.startDate,
//                 endDate:form.endDate,
//                 type:form.type

//             }
//         })
//         .then(res => {
//             data.data = res.data.data
//             if (res.data.labels) { data.labels = res.data.labels }
//             if (res.data.eachCount) { data.eachCount = res.data.eachCount }
//             data.totals = res.data.totals;
//             data.previousTotals = res.data.previousTotals;
//             data.type = res.data.type;

//             console.log(res.data)
//     })
//     }catch(e){
//         console.log(e.message)
//     }
// }

</script>



<template>
    <!-- <div>
        <h1>Hello, Vue!  Morning analysis</h1>
    </div> -->
    <!-- <Head title="データ分析" /> -->

    <!-- <AuthenticatedLayout> -->
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template> -->
        <!-- <FlashMessage/> -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="">
                            分析方法<br>
                            <!-- <input type="radio" v-model="form.type" value="perDay" checked><span class="mr-2">日別</span>
                            <input type="radio" v-model="form.type" value="perMonth" ><span class="mr-2">月別</span>
                            <input type="radio" v-model="form.type" value="perYear" ><span class="mr-2">年別</span><br> -->
                            From:<input type="date" name="startDate" >
                            To:<input type="date" name="endDate" ><br>
                            <button class="mt-4 flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析</button>
                        </form>
                        <!-- <div v-show="data.data"> -->
                        <!-- <Chart :data="data"/> -->
                        <!-- </div> -->
                        <!-- <div v-show="data.data" class=" mt-4 mx-auto w-2/3 sm:px-4 lg:px-4 border "> -->
                        <!-- <table class="bg-white table-auto w-full text-center whitespace-no-wrap">
                            <thead>
                                <tr>
                                    <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年月日</th>
                                    <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額(千)</th>
                                    <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前年金額(千)</th>
                                    <th class="w-1/13 md:1/13 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">前年比</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data.data" :key="item.date">
                                    <td class="border-b-2 boder-gray-200"><span style="font-variant-numeric:tabular-nums">{{ item.date }} </span></td>
                                    <td class="border-b-2 boder-gray-200 text-right pr-10"><span style="font-variant-numeric:tabular-nums"> {{ item.total/1000}}</span> </td>
                                    <td class="border-b-2 boder-gray-200 text-right pr-10"><span style="font-variant-numeric:tabular-nums"> {{ item.previous_total/1000}}</span> </td>
                                    <td class="border-b-2 boder-gray-200 text-right pr-10"><span style="font-variant-numeric:tabular-nums"> {{ item.total/ item.previous_total*100}}</span> </td>
                                    <span style="font-variant-numeric:tabular-nums"> {{ number_format(round(item.total/1000))}}</span>
                                </tr>
                            </tbody>
                        </table> -->
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    <!-- </AuthenticatedLayout> -->
</template>

<style scoped>
    h1 {
        color: red;
    }
</style>
