// File: backend/index.js
const express = require('express');
const cors = require('cors');
const axios = require('axios');
const app = express();

app.use(cors());
app.use(express.json());

app.post('/track', async (req, res) => {
  const { awb } = req.body;

  try {
    const response = await axios.post('http://demo.connect.easyparcel.my/?ac=EPTrackingBulk', {
      api: 'test123',
      bulk: [{ awb_no: awb }]
    });

    const data = response.data;

    if (data.api_status === 'Success' && data.result) {
      return res.json({ success: true, data: data.result[0] });
    } else {
      return res.json({ success: false, message: 'Parcel not found' });
    }
  } catch (err) {
    return res.status(500).json({ success: false, message: 'API Error' });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));