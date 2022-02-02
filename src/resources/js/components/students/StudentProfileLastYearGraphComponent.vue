<template>
    <canvas id="myChart"></canvas>
</template>
<script>
import Chart from 'chart.js/auto';

export default {
    props:['lastYears'],
    data: function () {
        return {
            lastYears: this.lastYears
        }
    },
    methods: {
        renderChart() {
        let ctx = document.getElementById("myChart");
        new Chart(ctx, {
            type: 'line',
            data:{
                labels: [
                    "１日", "2日", "３日", "４日", "５日", "６日", "７日", "８日", "９日", "10日", "11日", "12日"
                ],
                datasets: [{
                    label: "エントリー数",
                    data: this.lastYears,
                    borderColor: 'rgba(255,99,132,1)',
                    backgroundColor: 'rgba(255, 99, 132, 1)',
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
                        suggestedMax: 6,
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

