import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    // User Registration Trend Chart
    const userRegistrationCtx = document.getElementById('userRegistrationChart');
    if (userRegistrationCtx && window.chartData) {
        const monthlyUsers = window.chartData.monthlyUsers || [];
        new Chart(userRegistrationCtx, {
            type: 'line',
            data: {
                labels: monthlyUsers.map(item => item.month),
                datasets: [{
                    label: 'New Registrations',
                    data: monthlyUsers.map(item => item.count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }



    // Listing Status Distribution Chart
    const listingStatusCtx = document.getElementById('listingStatusChart');
    if (listingStatusCtx && window.chartData) {
        const listingStatusData = window.chartData.listingStatusData || {};
        new Chart(listingStatusCtx, {
            type: 'bar',
            data: {
                labels: ['Active', 'Rented', 'Inactive', 'Banned'],
                datasets: [{
                    label: 'Properties',
                    data: [
                        listingStatusData.active || 0,
                        listingStatusData.rented || 0,
                        listingStatusData.inactive || 0,
                        listingStatusData.banned || 0
                    ],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(156, 163, 175)',
                        'rgb(107, 114, 128)'
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Monthly Payments Chart
    const monthlyPaymentsCtx = document.getElementById('monthlyPaymentsChart');
    if (monthlyPaymentsCtx && window.chartData) {
        const monthlyPayments = window.chartData.monthlyPayments || [];
        new Chart(monthlyPaymentsCtx, {
            type: 'line',
            data: {
                labels: monthlyPayments.map(item => item.month),
                datasets: [{
                    label: 'Revenue (₱)',
                    data: monthlyPayments.map(item => item.amount),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
