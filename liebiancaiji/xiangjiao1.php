<?php

// 获取所有分类 https://api.yujiameimei.com/xiangjiao/xj.php?getsort
// 获取某个分类下视频列表 https://api.yujiameimei.com/xiangjiao/xj.php?sort=14&page=1
// 搜索 https://api.yujiameimei.com/xiangjiao/xj.php?keyword=美女&page=1
// 获取视频详情 https://api.yujiameimei.com/xiangjiao/xj.php?id=62634

// 设置流上下文选项
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
} else if (isset($_GET['getsort'])) {
    // 获取所有分类的代码
    $categories = [
        '全部分类' => 0,
        '付费专栏' => 1,
        '最新' => 666,
        '短视频' => 888,
        '蓝光' => 434,
        '香蕉盲盒' => 537,
        '香蕉新干线' => 552,
        '香蕉秀' => 549,
        '蕉点盛宴' => 551,
        '玩偶姐姐' => 449,
        '娜娜姐姐' => 532,
        '自拍偷拍' => 4,
        '制服诱惑' => 5,
        '清纯少女' => 6,
        '辣妹大奶' => 7,
        '女同专属' => 8,
        '素人演出' => 9,
        '角色扮演' => 10,
        '成人动漫' => 11,
        '人妻熟女' => 12,
        '变态另类' => 13,
        '经典伦理' => 14
    ];

    // 初始化分类数组
    $categoriesArray = [];

    // 遍历分类名称与数字索引的映射关系，构建ID和Title的形式
    foreach ($categories as $title => $index) {
        $categoriesArray[] = [
            'id' => $index,
            'title' => $title
        ];
    }

    // 构建返回对象
    $response = [
        'code' => !empty($categoriesArray) ? 'ok' : 'null',
        'categories' => $categoriesArray
    ];

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else if (isset($_GET['keyword']) && isset($_GET['page'])) {
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
    $sourceCode = file_get_contents($sourceUrl, false, $context);
    $dataArray = json_decode($sourceCode, true);

    $response = [];

    if (!empty($dataArray['data']['vodrows'])) {
        $videos = [];
        foreach ($dataArray['data']['vodrows'] as $video) {
            $videoItem = [
                'id' => $video['vodid'],
                'title' => $video['title'],
                'image' => $video['coverpic'],
                'createtime' => $video['createtime'],
                'isvip' => $video['isvip'],
                'islimit' => $video['islimit'],
                'islimitv3' => $video['islimitv3'],
                'score' => $video['scorenum'],
                'duration' => $video['duration'],
                'date' => '时长：' . $video['duration'],
                'play_url' => $video['play_url'],
                'down_url' => $video['down_url']
            ];
            $videos[] = $videoItem;
        }
        $response['videos'] = $videos;
        $response['code'] = 'ok';
    } else {
        $response['code'] = 'null';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else if (isset($_GET['id'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    $sourceUrl = "$latestdomain/vod/reqplay/$id";
    $sourceCode = file_get_contents($sourceUrl, false, $context);
    $dataArray = json_decode($sourceCode, true);

    $response = [];

    if (!empty($dataArray['data'])) {
        $videoUrl = $dataArray['data']['httpurl'] ?: $dataArray['data']['httpurl_preview'];
        $response['video'] = $videoUrl;
        $response['cookie'] = "xxx_api_auth=" . $dataArray['data']['xxx_api_auth'];
        $response['code'] = 'ok';
    } else {
        $response['code'] = 'null';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>
