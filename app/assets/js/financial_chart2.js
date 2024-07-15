$(document).ready(function() {
    weekly_chart();
    monthly_chart();
});



function weekly_chart() {
    {
        $.post("weekly_chart.php",
            function(data) {
                console.log(data);

                var year = [];
                var amount = [];

                for (var i in data) {
                    year.push(data[i].year);
                    // name.push(data[i].student_name);
                    // +
                    amount.push(data[i].amount);
                    // marks.push(data[i].marks);
                }

                var chartdata = {
                    labels: year,
                    datasets: [{
                        label: 'Total Sales',
                        backgroundColor: '#0000001a',
                        // trans: #0000001a
                        // backgroundColor: '#6774df36',
                        // backgroundColor: [
                        //     "#2ecc71",
                        //     "#3498db",
                        //     "#95a5a6",
                        //     "#34495e"
                        // ],
                        borderColor: '#7367f0',
                        // borderColor: '#4757d8',
                        // borderColor: '#46d5f1',
                        hoverBackgroundColor: '#6774dfb8',
                        hoverBorderColor: '#666666',
                        data: amount,
                    }],
                };

                var graphTarget = $("#weekly_chart");

                var barGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata,
                    options: {
                        // animation: false,
                        tooltips: {
                            callbacks: {
                                label: function(value, data) {
                                    return '₦' + value.yLabel.toLocaleString();
                                }
                            }
                        },
                        legend: { display: false },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                        if (parseFloat(value) >= 999) {
                                            return '₦' + value.toLocaleString();
                                            // toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
                                        } else {
                                            return '₦' + value;
                                        }
                                    }
                                }
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            // text: 'Weekly Sales',
                            fontFamily: 'Helvetica Neue',
                            fontSize: '25',
                            fontColor: '#7367f0'
                        }
                    }
                });

                // document.getElementById("printChart").addEventListener("click", function() {
                //     barGraph.print();
                // });
            });
    }
}



function monthly_chart() {
    {
        $.post("monthly_chart.php",
            function(data) {
                console.log(data);

                var year = [];
                var amount = [];

                for (var i in data) {
                    year.push(data[i].year);
                    // name.push(data[i].student_name);
                    // +
                    amount.push(data[i].amount);
                    // marks.push(data[i].marks);
                }

                var chartdata = {
                    labels: year,
                    datasets: [{
                        label: 'Total Sales',
                        backgroundColor: '#0000001a',
                        // trans: #0000001a
                        // backgroundColor: '#6774df36',
                        // backgroundColor: [
                        //     "#2ecc71",
                        //     "#3498db",
                        //     "#95a5a6",
                        //     "#34495e"
                        // ],
                        borderColor: '#7367f0',
                        // borderColor: '#4757d8',
                        // borderColor: '#46d5f1',
                        hoverBackgroundColor: '#6774dfb8',
                        hoverBorderColor: '#666666',
                        data: amount,
                    }],
                };

                var graphTarget = $("#monthly_chart");

                var barGraph = new Chart(graphTarget, {
                    type: 'line',
                    data: chartdata,
                    options: {
                        //animation: false,
                        tooltips: {
                            callbacks: {
                                label: function(value, data) {
                                    return '₦' + value.yLabel.toLocaleString();
                                }
                            }
                        },
                        legend: { display: false },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                        if (parseFloat(value) >= 999) {
                                            return '₦' + value.toLocaleString();
                                            // toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")
                                        } else {
                                            return '₦' + value;
                                        }
                                    }
                                }
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            // text: 'Weekly Sales',
                            fontFamily: 'Helvetica Neue',
                            fontSize: '25',
                            fontColor: '#7367f0'
                        }
                    }
                });

                // document.getElementById("printChart").addEventListener("click", function() {
                //     barGraph.print();
                // });
            });
    }
}