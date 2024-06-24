


<?php

// 创建一个 cURL 句柄
$ch = curl_init();

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, "https://pwc-aws.wfjcm.com/api/v2/long/search/recommend?token=eyJ1c2VyX2lkIjoyNDkzOTE3NTgsImxhc3Rsb2dpbiI6NCwiZXhwaXJlZCI6MTcxNTAxNjc3MX0.868a9ab174622c551ed8b0385bee3662.54019aea85a19c08c2dd7efd3159c58664975ce9c9ec4317b7e05271"); // 替换为实际的 API 地址
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应数据而不是直接输出
curl_setopt($ch, CURLOPT_HEADER, true); // 返回响应头

// 发送请求并获取响应
$response = curl_exec($ch);

// 获取响应头信息
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);

// 解析响应头
$headers = [];
foreach (explode("\r\n", $header) as $i => $line) {
    if ($i === 0) {
        $headers['http_code'] = $line;
    } else {
        list ($key, $value) = explode(': ', $line);
        $headers[$key] = $value;
    }
}

// 获取 x-vtag 字段的值
$x_vtag = $headers['x-vtag'];

// 输出 x-vtag 字段的值
echo "x-vtag: " . $x_vtag;

// 关闭 cURL 句柄
curl_close($ch);

?>
