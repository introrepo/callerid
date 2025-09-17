<?php
$response_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country_code = $_POST['country_code'];
    $phone_number = $_POST['phone_number'];

    $url = "https://recovername.aexan.in/aura/datainfo/insert.php";
    $params = [
        "code" => $country_code,
        "number" => $phone_number
    ];
    $query = http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . "?" . $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: */*",
        "User-Agent: Call%20Tracker/3 CFNetwork/1410.1 Darwin/22.6.0",
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Authorization: Bearer pf9MuEUjYeyk046XQdTNGAhlzLnvCm"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    if ($decoded !== null) {
        $response_data = $decoded;
    } else {
        $response_data = $response;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعلام عن رقم الهاتف</title>
    <link href="https://fonts.googleapis.com/css2?family=Barada+Reqa&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#0E0F13;
            --panel:#131417;
            --panel-2:#181A1F;
            --border:#2A2D33;
            --muted:#8B8D93;
            --text:#E6E7EB;
            --accent:#10A37F;
            --accent-2:#0E8C6E;
            --shadow: 0 8px 30px rgba(0,0,0,.35);
            --radius: 16px;
        }
        *{box-sizing:border-box}
        body {
            font-family: 'Barada Reqa', system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: radial-gradient(1200px 800px at 70% -10%, rgba(16,163,127,.08), transparent 60%), var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .container {
            width: 100%;
            max-width: 460px;
            margin: auto;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(180deg, var(--panel) 0%, var(--panel-2) 100%);
            box-shadow: var(--shadow);
        }
        .header-image {
            width: 100%;
            margin-bottom: 14px;
            border-radius: calc(var(--radius) - 6px);
            border: 1px solid var(--border);
        }
        h2{margin: 8px 0 14px;font-size: 20px;font-weight: 700;}
        label{font-size: 13px;color: var(--muted);}
        input[type="text"] {
            width: 100%;padding: 12px;margin: 8px 0 14px;
            border: 1px solid var(--border);border-radius: 12px;
            background-color: #0F1115;color: var(--text);outline: none;
        }
        button {
            width: 100%;padding: 12px;background: linear-gradient(180deg, var(--accent) 0%, var(--accent-2) 100%);
            color: white;border: 1px solid #0c745d;border-radius: 12px;font-size: 14px;font-weight: 700;
            cursor: pointer;box-shadow: 0 6px 18px rgba(16,163,127,.25);
        }
        .response-box {
            margin-top: 18px;padding: 16px;background: #0F1115;
            border: 1px solid var(--border);border-radius: 14px;
            word-wrap: break-word;box-shadow: var(--shadow);
        }
        table {width: 100%;margin: 16px auto;border-collapse: collapse;font-size: 13px;border: 1px solid var(--border);border-radius: 12px;}
        th, td {padding: 10px 12px;text-align: start;border-bottom: 1px solid var(--border);}
        th{color: #C8CBD2;font-weight: 700;}
        td{color: var(--text);}
        tr:last-child td{border-bottom:none;}
        .google-translate {position: fixed;top: 16px;left: 16px;z-index: 9999;background: var(--panel);padding: 10px;border-radius: 12px;border: 1px solid var(--border);}
    </style>
</head>
<body>

    <div class="container">
        <img src="https://i.ibb.co/gLH23YNT/20250907-0655-White-Logo-Black-Background-remix-01k4h4xvcyffht413taqv3w6wj.png" alt="Logo" class="header-image">
        <h2>استعلام عن رقم الهاتف</h2>
        <form method="POST">
            <label for="country_code">مقدمة الدولة:</label>
            <input type="text" id="country_code" name="country_code" placeholder="مثال 972 او 970" required>
            <label for="phone_number">رقم الهاتف:</label>
            <input type="text" id="phone_number" name="phone_number" placeholder="رقم الهاتف" required>
            <button type="submit">استعلام</button>
        </form>

        <?php if ($response_data): ?>
        <div class="response-box">
            <h3>نتيجة الاستعلام:</h3>

            <?php if (is_array($response_data)): ?>
                <h4>تفاصيل الاستجابة:</h4>
                <table>
                    <tr><th>البيان</th><th>القيمة</th></tr>
                    <tr><td>الاسم</td><td><?= htmlspecialchars($response_data['Eyecup'] ?? '') ?></td></tr>
                    <tr><td>اسم آخر</td><td><?= htmlspecialchars($response_data['Callapp'] ?? '') ?></td></tr>
                    <tr><td>نوع الرقم</td><td><?= htmlspecialchars($response_data['numbertype'] ?? '') ?></td></tr>
                    <tr><td>المزود</td><td><?= htmlspecialchars($response_data['provider'] ?? '') ?></td></tr>
                </table>

                <?php if (!empty($response_data['Names'])): ?>
                <h4>الأسماء:</h4>
                <table>
                    <tr><th>الاسم</th></tr>
                    <?php foreach ($response_data['Names'] as $name): ?>
                        <tr><td><?= htmlspecialchars($name['name'] ?? '') ?></td></tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            <?php else: ?>
                <p><?= htmlspecialchars($response_data) ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="google-translate"><div id="google_translate_element"></div></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'ar',
                includedLanguages: 'en,ar',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
