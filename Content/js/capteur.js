
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

    // --- Affichage du niveau de gaz ---
    const latestGaz = gazData.length ? gazData[gazData.length - 1].value : 0;
    const seuilCritique = 700;
    const gazLevel = document.getElementById('gazLevel');
    const gazValue = document.getElementById('gazValue');

    const percentage = Math.min(100, (latestGaz / seuilCritique) * 100);
    gazLevel.style.width = percentage + '%';
    gazLevel.style.backgroundColor = latestGaz >= seuilCritique ? 'red' : latestGaz >= seuilCritique * 0.7 ? 'orange' : 'green';
    gazValue.textContent = latestGaz + ' ppm';

    // --- Affichage des actionneurs ---
    const actuatorsList = document.getElementById('actionneursList');
    actionneursState.forEach(actuator => {
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center';
    li.textContent = actuator.name;
    const badge = document.createElement('span');
    badge.className = 'badge rounded-pill ' + (actuator.state ? 'bg-success' : 'bg-secondary');
    badge.textContent = actuator.state ? 'ON' : 'OFF';
    li.appendChild(badge);
    actuatorsList.appendChild(li);
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
