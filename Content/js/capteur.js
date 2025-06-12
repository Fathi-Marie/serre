
    // Formatage des dates pour affichage
    function formatDate(dateStr) {
    const options = { hour: '2-digit', minute: '2-digit' };
    return new Date(dateStr).toLocaleTimeString('fr-FR', options);
}

    function formatDateLarge(dateStr, period) {
    const d = new Date(dateStr);
    if (period === 'day')
    return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    if (period === 'week')
    return d.toLocaleDateString('fr-FR', { weekday: 'short', day: '2-digit', month: '2-digit' });
    if (period === 'month')
    return d.toLocaleDateString('fr-FR', { month: 'long' });
    return dateStr;
}

    // Filtrage des données selon la période (optionnel, ici on renvoie tout)
    function filterDataByPeriod(data, period) {
    return data; // tu peux adapter si besoin
}

    // Génère toutes les dates d’une semaine (du lundi au dimanche) contenant la date donnée
    function getWeekDays(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1); // ajuster pour lundi
    const monday = new Date(d.setDate(diff));

    const days = [];
    for (let i = 0; i < 7; i++) {
    const dayDate = new Date(monday);
    dayDate.setDate(monday.getDate() + i);
    days.push(dayDate);
}
    return days;
}

    // Génère labels pour une semaine entière (lun->dim)
    function getWeekLabels(refDate) {
    const days = getWeekDays(refDate);
    return days.map(d => d.toLocaleDateString('fr-FR', { weekday: 'short', day: '2-digit', month: '2-digit' }));
}

    // Prépare les données pour la période semaine : on veut un point par jour même si aucune donnée
    function prepareWeeklyChartData(data) {
    if (!data.length) return { labels: [], values: [] };
    const refDate = new Date(data[0].date); // référence pour semaine

    const weekDays = getWeekDays(refDate);
    const labels = getWeekLabels(refDate);

    // Grouper par jour (format yyyy-mm-dd)
    const grouped = {};
    data.forEach(d => {
    const date = new Date(d.date);
    const key = date.toISOString().slice(0, 10);
    if (!grouped[key]) grouped[key] = [];
    grouped[key].push(d.value);
});

    const values = weekDays.map(d => {
    const key = d.toISOString().slice(0, 10);
    if (!grouped[key]) return null;
    const sum = grouped[key].reduce((a, b) => a + b, 0);
    return sum / grouped[key].length;
});

    return { labels, values };
}

    // Fonction déjà fournie pour préparer les mois (adaptée légèrement)
    function prepareMonthlyChartData(data) {
    if (data.length === 0) return { labels: [], values: [] };
    const currentYear = new Date().getFullYear();

    const monthlyLabels = [];
    for (let month = 0; month < 12; month++) {
    const date = new Date(currentYear, month, 1);
    const label = date.toLocaleDateString('fr-FR', { month: 'long' });
    monthlyLabels.push(label);
}

    const grouped = {};
    data.forEach(d => {
    const date = new Date(d.date);
    if (date.getFullYear() !== currentYear) return;
    const key = date.toLocaleDateString('fr-FR', { month: 'long' });
    if (!grouped[key]) grouped[key] = [];
    grouped[key].push(d.value);
});

    const values = monthlyLabels.map(label => {
    if (!grouped[label]) return null;
    const sum = grouped[label].reduce((a, b) => a + b, 0);
    return sum / grouped[label].length;
});

    return { labels: monthlyLabels, values };
}

    // Création du graphique "large" selon période
    function createLargeChart(chart, ctx, data, label, borderColor, backgroundColor, period) {
    const filtered = filterDataByPeriod(data, period);

    let labels = [];
    let values = [];

    if (period === 'month') {
    const result = prepareMonthlyChartData(filtered);
    labels = result.labels;
    values = result.values;
} else if (period === 'week') {
    const result = prepareWeeklyChartData(filtered);
    labels = result.labels;
    values = result.values;
} else if (period === 'day') {
    labels = filtered.map(d => formatDateLarge(d.date, period));
    values = filtered.map(d => d.value);
} else {
    labels = filtered.map(d => formatDateLarge(d.date, period));
    values = filtered.map(d => d.value);
}

    return new Chart(ctx, {
    type: 'line',
    data: {
    labels,
    datasets: [{
    label,
    data: values,
    borderColor,
    backgroundColor,
    fill: true,
    tension: 0.3,
    spanGaps: true,
}]
},
    options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
    y: { beginAtZero: true },
    x: {
    ticks: {
    autoSkip: false,
    maxRotation: 0,
    minRotation: 0
}
}
}
}
});
}


    const tempHumChart = new Chart(document.getElementById('tempHumChart').getContext('2d'), {
    type: 'line',
    data: {
    labels: tempHumData.map(d => formatDate(d.date)),
    datasets: [{
    label: 'Température/Humidité',
    data: tempHumData.map(d => d.value),
    borderColor: 'rgba(75, 192, 192, 1)',
    backgroundColor: 'rgba(75, 192, 192, 0.2)',
    fill: false,
    tension: 0.2,
}]
},
    options: {
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true } }
}
});

    const luminositeChart = new Chart(document.getElementById('luminositeChart').getContext('2d'), {
    type: 'line',
    data: {
    labels: luminositeData.map(d => formatDate(d.date)),
    datasets: [{
    label: 'Luminosité (lux)',
    data: luminositeData.map(d => d.value),
    borderColor: 'rgba(255, 206, 86, 1)',
    backgroundColor: 'rgba(255, 206, 86, 0.4)',
    fill: true,
    tension: 0.2,
}]
},
    options: {
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true } }
}
});

    const gazChart = new Chart(document.getElementById('gazChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: gazData.map(d => formatDate(d.date)),
            datasets: [{
                label: 'Concentration de gaz (ppm)',
                data: gazData.map(d => d.value),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true,
                tension: 0.2,
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    //actioneurs
    document.addEventListener("DOMContentLoaded", () => {
        const actionneurs = [
            { nom: "Ventilateur", etat: "Allumé" },
            { nom: "Lumière", etat: "Éteinte" },
            { nom: "Pompe", etat: "Allumée" }
        ];

        const actionneursList = document.getElementById("actionneursList");
        actionneursList.innerHTML = "";

        actionneurs.forEach(act => {
            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between align-items-center";
            li.textContent = act.nom;

            const badge = document.createElement("span");
            badge.className = `badge ${act.etat.startsWith("Allum") ? 'bg-success' : 'bg-secondary'} rounded-pill`;
            badge.textContent = act.etat;

            li.appendChild(badge);
            actionneursList.appendChild(li);
        });
    });

    // --- Gestion du grand graphique dans modal ---
    let tempHumChartLarge = null;
    document.getElementById('tempHumPeriod').addEventListener('change', function () {
    const period = this.value;
    if (tempHumChartLarge) tempHumChartLarge.destroy();
    tempHumChartLarge = createLargeChart(
    tempHumChartLarge,
    document.getElementById('tempHumChartLarge').getContext('2d'),
    tempHumData,
    'Température/Humidité',
    'rgba(75, 192, 192, 1)',
    'rgba(75, 192, 192, 0.2)',
    period
    );
});
    document.getElementById('modalTempHum').addEventListener('shown.bs.modal', function () {
    document.getElementById('tempHumPeriod').dispatchEvent(new Event('change'));
});

    let luminositeChartLarge = null;
    document.getElementById('lumPeriod').addEventListener('change', function () {
    const period = this.value;
    if (luminositeChartLarge) luminositeChartLarge.destroy();
    luminositeChartLarge = createLargeChart(
    luminositeChartLarge,
    document.getElementById('luminositeChartLarge').getContext('2d'),
    luminositeData,
    'Luminosité (lux)',
    'rgba(255, 206, 86, 1)',
    'rgba(255, 206, 86, 0.4)',
    period
    );
});
    document.getElementById('modalLuminosite').addEventListener('shown.bs.modal', function () {
    document.getElementById('lumPeriod').dispatchEvent(new Event('change'));
});

    let gazChartLarge = null;
    document.getElementById('gazPeriod').addEventListener('change', function () {
        const period = this.value;
        if (gazChartLarge) gazChartLarge.destroy();
        gazChartLarge = createLargeChart(
            gazChartLarge,
            document.getElementById('gazChartLarge').getContext('2d'),
            gazData,
            'Concentration de gaz (ppm)',
            'rgba(255, 99, 132, 1)',
            'rgba(255, 99, 132, 0.2)',
            period
        );
    });
    document.getElementById('modalGaz').addEventListener('shown.bs.modal', function () {
        document.getElementById('gazPeriod').dispatchEvent(new Event('change'));
    });

    function colorizeValue(value, type) {
        if (type === 'temp') {
            if (value < 15) return { color: 'blue', label: `${value} °C` };
            if (value <= 28) return { color: 'green', label: `${value} °C` };
            return { color: 'orange', label: `${value} °C` };
        }
        if (type === 'gaz') {
            if (value < 500) return { color: 'green', label: `${value} ppm` };
            if (value < 700) return { color: 'orange', label: `${value} ppm` };
            return { color: 'red', label: `${value} ppm` };
        }
        if (type === 'lum') {
            if (value < 300) return { color: 'blue', label: `${value} lux` };
            if (value < 700) return { color: 'green', label: `${value} lux` };
            return { color: 'red', label: `${value} lux` };
        }
        return { color: 'gray', label: value };
    }

    function updateSummary() {
        const tempVal = tempHumData.length ? tempHumData[tempHumData.length - 1].value : null;
        const gazVal = gazData.length ? gazData[gazData.length - 1].value : null;
        const lumVal = luminositeData.length ? luminositeData[luminositeData.length - 1].value : null;

        const temp = colorizeValue(tempVal, 'temp');
        const gaz = colorizeValue(gazVal, 'gaz');
        const lum = colorizeValue(lumVal, 'lum');

        const tempElem = document.getElementById('resumeTemp');
        const gazElem = document.getElementById('resumeGaz');
        const lumElem = document.getElementById('resumeLum');

        tempElem.textContent = temp.label;
        tempElem.style.backgroundColor = temp.color;
        tempElem.style.color = 'white';

        gazElem.textContent = gaz.label;
        gazElem.style.backgroundColor = gaz.color;
        gazElem.style.color = 'white';

        lumElem.textContent = lum.label;
        lumElem.style.backgroundColor = lum.color;
        lumElem.style.color = 'white';
    }

    updateSummary();
