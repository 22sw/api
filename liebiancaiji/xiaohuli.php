<?php

// 获取所有分类 https://api.yujiameimei.com/mogu/mg.php?getsort
// 获取某个分类下视频列表 https://api.yujiameimei.com/mogu/mg.php?sort=34,36,35,37&page=1
// 搜索 https://api.yujiameimei.com/mogu/mg.php?keyword=美女&page=1
// 获取视频详情 https://api.yujiameimei.com/mogu/mg.php?id=67391



//调用方法  https://api.yilushunfeng.top/liebiancaiji/xiaohuli.php?category=1637462294991433730&num_videos=500



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0\r\n",
    ]
]);

// 获取当前时间的十三位时间戳
list($t1, $t2) = explode(' ', microtime());
$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

$latestdomain = 'https://ld.xhlld24073.cyou';

// 获取视频列表并实时输出
if (isset($_GET['category']) && isset($_GET['num_videos'])) {
    $category = $_GET['category'];
    $num_videos = intval($_GET['num_videos']);
    $videos = [];
    $page = 1;
    $empty_page_count = 0;

    header('Content-Type: text/plain');

    while (count($videos) < $num_videos) {
        $sourceUrl = "$latestdomain/view/videoList/$category/$page/80";
        $sourceCode = file_get_contents($sourceUrl, false, $context);
        $dataArray = json_decode($sourceCode, true);

        if (!empty($dataArray['data']['list'])) {
            foreach ($dataArray['data']['list'] as $video) {
                if (!in_array($video['playUrl'], array_column($videos, 'id'))) {
                    $videoLine = $video['title'] . '|' . $video['playUrl'];
                    $videos[] = $videoLine;
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

?>
