<?php

// 获取所有分类 https://api.yujiameimei.com/caoliu/caoliu.php?getsort
// 获取某个分类下视频列表 https://api.yujiameimei.com/caoliu/caoliu.php?sort=1760182312696303617&page=2
// 搜索 https://api.yujiameimei.com/caoliu/caoliu.php?keyword=美女&page=1
// 获取视频详情 https://api.yujiameimei.com/caoliu/caoliu.php?id=1778051363881811969

//调用方法  https://api.yilushunfeng.top/liebiancaiji/50du.php?category=73&num_videos=10


/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        "Content-Type:text/plain\r\n" .
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0\r\n",
    ]
]);

// 获取当前时间的十三位时间戳
list($t1, $t2) = explode(' ', microtime());
$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

$latestdomain = 'https://api2.uixzqsfubx.com';

$encryption_key = '7205a6c3883caf95b52db5b534e12ec3'; // 密钥
$encryption_iv = '81d7beac44a86f43'; // 向量

// 获取分类
if (isset($_GET['getsort'])) {
    // ... (保持不变)
}

// 获取视频列表并实时输出
else if (isset($_GET['category']) && isset($_GET['num_videos'])) {
    $category = $_GET['category'];
    $num_videos = intval($_GET['num_videos']);
    $videos = [];
    $seen_videos = [];
    $page = 1;
    $empty_page_count = 0;

    header('Content-Type: text/plain');

    while (count($videos) < $num_videos) {
        $timestamp = time();
        if ($category == 19 || $category == 16 || $category == 14 || $category == 17 || $category == 12 || $category == 18 || $category == 13 || $category == 15) {
            $sourceUrl = $latestdomain . "/pwa.php/api/MvList/featured";
            $params1 = [
                "system_oauth_type" => "pwa",
                "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
                "system_oauth_new_id" => "",
                "system_version" => "3.0.0",
                "system_app_type" => "",
                "system_build" => "",
                "system_build_id" => "",
                "page" => intval($page),
                "tabId" => intval($category)
            ];
        } elseif ($category == 73) {
            $sourceUrl = $latestdomain . "/pwa.php/api/MvList/recommend";
            $params1 = [
                "system_oauth_type" => "pwa",
                "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
                "system_oauth_new_id" => "",
                "system_version" => "3.0.0",
                "system_app_type" => "",
                "system_build" => "",
                "system_build_id" => "",
                "page" => intval($page),
                "_t" => 1
            ];
        } elseif ($category == 5465451) {
            $sourceUrl = $latestdomain . "/pwa.php/api/MvList/featuredzpc";
            $params1 = [
                "system_oauth_type" => "pwa",
                "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
                "system_oauth_new_id" => "",
                "system_version" => "3.0.0",
                "system_app_type" => "",
                "system_build" => "",
                "system_build_id" => "",
                "page" => intval($page),
                "_t" => 1
            ];
        } else {
            $sourceUrl = $latestdomain . "/pwa.php/api/MvList/style";
            $params1 = [
                "system_oauth_type" => "pwa",
                "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
                "system_oauth_new_id" => "",
                "system_version" => "3.0.0",
                "system_app_type" => "",
                "system_build" => "",
                "system_build_id" => "",
                "id" => intval($category),
                "orderBy" => "id",
                "page" => intval($page),
                "size" => 15
            ];
        }

        $params1_json = json_encode($params1);
        $params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));
        $params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}7205a6c3883caf95b52db5b534e12ec3";
        $params4 = hash('sha256', $params3);
        $params5 = md5($params4);
        $params7 = "client=pwa&timestamp={$timestamp}&data={$params1_encrypted}&sign={$params5}";

        $post_data = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $params7
            ]
        ];

        $context = stream_context_create($post_data);
        $result = file_get_contents($sourceUrl, false, $context);

        $sourceCode1 = json_decode($result, true);
        $sourceCode = $sourceCode1['data'];
        $decrypted_data = openssl_decrypt(hex2bin($sourceCode), 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv);
        $decrypted_data = trim($decrypted_data, '"');
        $decrypted_data = str_replace('\\', '', $decrypted_data);
        $dataArray = json_decode($decrypted_data, true);

        if (!empty($dataArray['data']['list'])) {
            foreach ($dataArray['data']['list'] as $video) {
                if (!in_array($video['id'], $seen_videos)) {
                    $videoLine = $video['title'] . '|' . $video['thumb_cover'];
                    $videos[] = $videoLine;
                    $seen_videos[] = $video['id'];
                    echo $videoLine . "\n";
                    flush(); // 强制输出缓冲区内容

                    if (count($videos) >= $num_videos) {
                        break;
                    }
                }
            }
            $empty_page_count = 0; // 重置空页计数器
        } else {
            $empty_page_count++;
            if ($empty_page_count >= 2) {
                break;
            }
        }

        $page++;
    }
}

// 处理搜索功能
else if (isset($_GET['keyword']) && isset($_GET['page'])) {
    // ... (保持不变)
}

// 获取视频详情
else if (isset($_GET['id'])) {
    // ... (保持不变)
}

?>
