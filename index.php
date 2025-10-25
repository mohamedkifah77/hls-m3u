<?php
// السماح بالتشغيل من أي موقع (لمنع مشاكل CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// قراءة الرابط المطلوب من المتغير url
if (!isset($_GET['url'])) {
    die("missing 'url' parameter");
}

$url = $_GET['url'];
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    die("invalid URL");
}

// إذا تم تمرير بيانات هيدرات مشفّرة
$headers = [];
if (isset($_GET['data'])) {
    $decoded = base64_decode($_GET['data']);
    $parts = explode('|', $decoded);
    foreach ($parts as $p) {
        if (strpos($p, '=') !== false) {
            list($key, $val) = explode('=', $p, 2);
            $key = trim($key, "\" ");
            $val = trim($val, "\" ");
            $headers[] = "$key: $val";
        }
    }
}

// تهيئة cURL لجلب المحتوى
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
if (!empty($headers)) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}

$response = curl_exec($ch);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

// إرسال نوع المحتوى الصحيح
if ($content_type) {
    header("Content-Type: $content_type");
}

// طباعة المحتوى القادم من المصدر
echo $response;
