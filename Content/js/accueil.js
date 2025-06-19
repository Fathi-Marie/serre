const options = {
    type: 'line',
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: 'white' } },
        },
        scales: {
            x: { ticks: { color: 'white' }, grid: { color: '#333' } },
            y: { ticks: { color: 'white' }, grid: { color: '#333' } },
        }
    }
};

new Chart(document.getElementById('tempChart'), {
    ...options,
    data: {
        labels: ['8h', '10h', '12h', '14h', '16h'],
        datasets: [{
            label: 'Température (°C)',
            data: [18, 20, 22, 24, 23],
            borderColor: '#768D3E',
            backgroundColor: 'rgba(118, 141, 62, 0.3)',
            fill: true,
        }]
    }
});

new Chart(document.getElementById('lightChart'), {
    ...options,
    data: {
        labels: ['8h', '10h', '12h', '14h', '16h'],
        datasets: [{
            label: 'Luminosité (%)',
            data: [30, 45, 70, 80, 60],
            borderColor: '#f1c40f',
            backgroundColor: 'rgba(118, 141, 62, 0.3)',
            fill: true,
        }]
    }
});

new Chart(document.getElementById('gasChart'), {
    ...options,
    data: {
        labels: ['8h', '10h', '12h', '14h', '16h'],
        datasets: [{
            label: 'Gaz détecté (ppm)',
            data: [200, 210, 250, 220, 190],
            borderColor: '#768D3E',
            backgroundColor: 'rgba(241, 196, 15, 0.3)',
            fill: true,
        }]
    }
});