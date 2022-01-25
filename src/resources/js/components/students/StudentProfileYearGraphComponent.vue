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
                labels: ["１月", "2月", "３月", "４月", "５月", "６月", "７月", "８月", "９月", "10月", "11月", "12月"],
                datasets: [{
                    label: "エントリー数",
                    data: [10, 19, 3, 5, 2, 3, 0, 40, 50, 9, 5, 16],
                    borderColor: 'rgba(255,99,132,1)',
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },{
                    label: "新規",
                    data: [6, 13, 26, 15,5, 3, 19, 6, 15, 17, 3, 1],
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

