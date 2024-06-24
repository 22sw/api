<?php

// 获取所有分类 https://api.yujiameimei.com/xiangjiao/xj.php?getsort
// 获取某个分类下视频列表 https://api.yujiameimei.com/xiangjiao/xj.php?sort=14&page=1
// 搜索 https://api.yujiameimei.com/xiangjiao/xj.php?keyword=美女&page=1
// 获取视频详情 https://api.yujiameimei.com/xiangjiao/xj.php?id=62634

//调用方法 https://api.yilushunfeng.top/liebiancaiji/xj.php?category=0&num_videos=300


/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        "User-Agent: okhttp/3.14.9\r\n" .
        "Cookie: xxx_api_auth=6631643966663733303237306265666365373431386432343032316665346630\r\n",
    ]
]);

// 获取当前时间的十三位时间戳
list($t1, $t2) = explode(' ', microtime());
$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

$latestdomain = 'https://ios.bxguwen.com';

// 判断是否传入了分类参数和视频数量
if (isset($_GET['category']) && isset($_GET['num_videos'])) {
    $category = $_GET['category'];
    $num_videos = intval($_GET['num_videos']);
    $videos = [];
    $seen_videos = [];
    $page = 1;
    $empty_page_count = 0;

    header('Content-Type: text/plain');

    while (count($videos) < $num_videos) {
        // 构建目标网站的 URL
        $sourceUrl = "$latestdomain/vod/listing-$category-0-0-0-0-0-0-0-0-$page?timestamp=$str_time";
        $sourceCode = file_get_contents($sourceUrl, false, $context);

        // 将获取的 JSON 数据转换为 PHP 数组
        $dataArray = json_decode($sourceCode, true);

        // 处理视频数组
        if (!empty($dataArray['data']['vodrows'])) {
            foreach ($dataArray['data']['vodrows'] as $video) {
                if ($video['isvip'] != 1 && !in_array($video['vodid'], $seen_videos)) {
                    $id = $video['vodid'];
                    $detailUrl = "$latestdomain/vod/reqplay/$id";
                    $detailCode = file_get_contents($detailUrl, false, $context);
                    $detailArray = json_decode($detailCode, true);

                    if (!empty($detailArray['data'])) {
                        $videoUrl = $detailArray['data']['httpurl'] ?: $detailArray['data']['httpurl_preview'];
                        $videoLine = $video['title'] . '|' . $videoUrl;
                        $videos[] = $videoLine;
                        $seen_videos[] = $video['vodid'];
                        echo $videoLine . "\n";
                        flush(); // 强制输出缓冲区内容

                        if (count($videos) >= $num_videos) {
                            break;
                        }
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
} else {
    header('Content-Type: text/plain');
    echo "Missing parameters";
}

?>
