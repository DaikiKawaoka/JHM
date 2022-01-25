<template>
    <canvas id="myChart"></canvas>
</template>
<script>
import Chart from 'chart.js/auto';

export default {
    methods: {
        renderChart() {
        let ctx = document.getElementById("myChart");
        new Chart(ctx, {
            type: 'line',
            data:{
                labels: [
                    "１日", "2日", "３日", "４日", "５日", "６日", "７日", "８日", "９日", "10日", "11日", "12日", "13日", "14日", "15日",
                    "１6日", "17日", "18日", "19日", "20日", "21日", "22日", "23日", "24日", "25日", "26日", "27日", "28日", "29日", "30日"
                ],
                datasets: [{
                    label: "エントリー数",
                    data: [
                        10, 19, 3, 5, 2, 3, 0, 40, 50, 9, 5, 16, 12, 3, 21,
                        10, 21, 12, 6, 4, 14, 12, 23, 0, 29, 15, 6, 1, 12, 11,
                    ],
                    borderColor: 'rgba(255,99,132,1)',
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },{
                    label: "新規",
                    data: [
                        6, 13, 26, 15, 5, 3, 19, 6, 15, 17, 3, 1, 23, 12, 18,
                        10, 19, 3, 5, 2, 3, 0, 40, 50, 9, 5, 16, 12, 3, 21,
                    ],
                    borderColor: 'rgba(0, 206, 209, 1)',
                    backgroundColor: 'rgba(0, 206, 209, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    position: "bottom"
                },
                scales: {
                    y: {
                        display: true,
                        suggestedMin: 0,
                        suggestedMax: 30,
                        beginAtZero: true,
                    }
                }
            }
        });
        }
    },
    mounted() {
        this.renderChart();
    }
};
</script>

