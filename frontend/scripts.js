// Gantikan dengan URL Railway anda nanti
const API_URL = 'https://your-railway-app.up.railway.app';

async function track() {
  const awb = document.getElementById('awb').value.trim();
  const resultDiv = document.getElementById('result');
  const loader = document.getElementById('loader');

  if (!awb) return alert('Sila masukkan nombor AWB');

  resultDiv.innerHTML = '';
  loader.style.display = 'block';

  try {
    const res = await fetch(`${API_URL}/track`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ awb })
    });

    const data = await res.json();

    if (data.success) {
      const r = data.data;
      resultDiv.innerHTML = `
        <h3 class="success">✅ ${r.latest_status}</h3>
        <p><strong>AWB:</strong> ${r.awb}</p>
        <p><strong>Tarikh:</strong> ${r.latest_update}</p>
        <h4>Sejarah:</h4>
        ${r.status_list ? Object.values(r.status_list).map(e => `
          <div class="event">
            <strong>${e.event_date} | ${e.event_time}</strong><br>
            ${e.status} di ${e.location}
          </div>
        `).join('') : 'Tiada sejarah'}
      `;
    } else {
      resultDiv.innerHTML = `<p class="error">❌ ${data.message}</p>`;
    }
  } catch (err) {
    resultDiv.innerHTML = `<p class="error">❌ Ralat sambungan</p>`;
  } finally {
    loader.style.display = 'none';
  }
}
