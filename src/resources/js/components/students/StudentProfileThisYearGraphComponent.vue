<template>
    <canvas id="myChart"></canvas>
</template>
<script>
import Chart from 'chart.js/auto';

export default {
    props: ['thisYears'],
    data: function () {
        return {
            thisYears: this.thisYears
        }
    },
    methods: {
        renderChart:function() {
        let ctx = document.getElementById("myChart");
        new Chart(ctx, {
            type: 'line',
            data:{
                labels: [
                    "１月", "2月", "３月", "４月", "５月", "６月", "７月", "８月", "９月", "10月", "11月", "12月"
                ],
                datasets: [{
                    label: "エントリー数",
                    data: this.thisYears,
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
        if (this.thisYears[0] != undefined) {
            this.renderChart();
        }
    },
    updated() {
        this.renderChart();
    }
};
</script>

