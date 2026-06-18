<?php
/*
 * proxy.php
 * Chuyển tiếp request đến thuemail.net
 * để bypass CORS từ trình duyệt
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$action = $_POST['action'] ?? '';

/* ================================
   LOGIN - lấy PHPSESSID
================================ */
if($action === 'login'){

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(!$username || !$password){
        echo json_encode(['error' => 'Thiếu thông tin']);
        exit;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://thuemail.net/dangnhap',
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'username' => $username,
            'password' => $password,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER         => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
        CURLOPT_TIMEOUT        => 15,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    /* Lấy PHPSESSID từ Set-Cookie header */
    preg_match('/Set-Cookie:\s*PHPSESSID=([^;]+)/i', $response, $m);

    if(!empty($m[1])){
        echo json_encode([
            'status'     => 'success',
            'phpsessid'  => $m[1],
        ]);
    }else{
        echo json_encode(['error' => 'Đăng nhập thất bại. Kiểm tra lại tài khoản.']);
    }
    exit;
}

/* ================================
   STOCK - lấy danh sách sản phẩm
================================ */
if($action === 'stock'){

    $phpsessid = trim($_POST['phpsessid'] ?? '');

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://thuemail.net/index',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
        CURLOPT_COOKIE         => 'PHPSESSID=' . $phpsessid,
        CURLOPT_TIMEOUT        => 15,
    ]);

    $html = curl_exec($ch);
    curl_close($ch);

    /* Parse tên sản phẩm, product_id, giá, stock */
    $products = [];
    preg_match_all(
        '/selectPackage\(\'[^\']+\',\s*this,\s*(\d+),\s*(\d+),\s*(\d+),\s*\'([^\']+)\'\)/s',
        $html, $matches, PREG_SET_ORDER
    );

    /* Lấy tên card header */
    preg_match_all('/<div class="card-header[^"]*">([^<]+)<\/div>/', $html, $headers);
    $cardNames = array_map('trim', $headers[1] ?? []);

    /* Gom theo card */
    $cardIdx = 0;
    $lastId  = null;
    foreach($matches as $m){
        $pid   = (int)$m[1];
        $price = (int)$m[2];
        $stock = (int)$m[3];
        $name  = trim($m[4]);

        $products[] = [
            'product_id' => $pid,
            'name'       => $name,
            'price'      => $price,
            'stock'      => $stock,
            'available'  => $stock > 0,
        ];
    }

    echo json_encode(['status' => 'success', 'products' => $products]);
    exit;
}

/* ================================
   BUY - mua mail
================================ */
if($action === 'buy'){

    $phpsessid  = trim($_POST['phpsessid']  ?? '');
    $product_id = (int)($_POST['product_id'] ?? 0);
    $qty        = (int)($_POST['qty']        ?? 1);

    if(!$phpsessid || !$product_id || $qty < 1){
        echo json_encode(['error' => 'Thiếu thông tin']);
        exit;
    }

    $postData = "product_id=$product_id&qty=$qty";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://thuemail.net/api_muahang',
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
        CURLOPT_COOKIE         => 'PHPSESSID=' . $phpsessid,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/x-www-form-urlencoded',
            'Referer: https://thuemail.net/index',
            'Origin: https://thuemail.net',
        ],
        CURLOPT_TIMEOUT        => 20,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if(!$data){
        echo json_encode(['error' => 'Lỗi kết nối tới thuemail.net']);
        exit;
    }

    echo json_encode($data);
    exit;
}

echo json_encode(['error' => 'Action không hợp lệ']);
