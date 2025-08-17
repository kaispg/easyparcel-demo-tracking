<?php
if ($_POST['awb']) {
    $awb = $_POST['awb'];

    // Demo API EasyParcel
    $url = "http://demo.connect.easyparcel.my/?ac=EPTrackingBulk";
    
    $postparam = array(
        'api' => 'test123', // demo tak perlu API key sebenar
        'bulk' => array(
            array('awb_no' => $awb)
        )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>EasyParcel Tracking MY</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
    .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input, button { padding: 10px; width: 80%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
    button { background: #007cba; color: white; border: none; cursor: pointer; }
    .event { padding: 10px; margin: 5px 0; background: #f9f9f9; border-left: 3px solid #007cba; }
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>
  <div class="container">
    <h2>📦 Track Parcel (EasyParcel Demo)</h2>
    <form method="post">
      <input type="text" name="awb" placeholder="Masukkan nombor AWB (contoh: 238725129086)" value="<?= htmlspecialchars($_POST['awb'] ?? '') ?>" required>
      <button type="submit">Track Parcel</button>
    </form>

    <?php if ($_POST['awb'] && $data): ?>
      <?php if ($data['api_status'] == 'Success' && !empty($data['result'])): ?>
        <?php foreach ($data['result'] as $r): ?>
          <h3>✅ Status: <strong><?= $r['latest_status'] ?></strong></h3>
          <p><strong>AWB:</strong> <?= $r['awb'] ?></p>
          <p><strong>Tarikh Terkini:</strong> <?= $r['latest_update'] ?></p>
          <h4>Sejarah:</h4>
          <?php if (isset($r['status_list'])): ?>
            <?php foreach ($r['status_list'] as $event): ?>
              <div class="event">
                <strong><?= $event['event_date'] ?> | <?= $event['event_time'] ?></strong><br>
                <?= $event['status'] ?> di <?= $event['location'] ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="error">❌ Parcel tidak ditemui atau AWB salah.</p>
      <?php endif; ?>
    <?php endif; ?>

    <p><small>🔧 Projek peribadi menggunakan <a href="https://developers.easyparcel.com" target="_blank">EasyParcel API Demo</a>.</small></p>
  </div>
</body>
</html>