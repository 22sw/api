<?php
$url = "https://new.mybelle.shop/upload/upload/20230831/2023083112280044866!360x0.jpeg";

// 初始化 cURL 会话
$ch = curl_init($url);

// 设置为返回响应而不是直接输出
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

// 忽略 SSL 证书验证
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// 执行 cURL 请求
$response = curl_exec($ch);

// 获取HTTP状态码
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// 关闭 cURL 会话
curl_close($ch);

if ($http_code == 200) {
    // 设置正确的Content-Type头
    header("Content-Type: image/jpeg");
    echo $response;
} else {
    // 处理错误情况
    header("HTTP/1.1 404 Not Found");
    echo "Image not found or error occurred.";
}
?>