<?php

// 获取所有分类 http://api.yujiameimei.com/hsck2/hsck2.php?getsort
// 搜索 http://api.yujiameimei.com/hsck2/hsck2.php?keyword=美女&page=1
// 获取某个分类下视频列表 http://api.yujiameimei.com/hsck2/hsck2.php?sort=/index.php/vod/type/id/1&page=1
// 获取视频详情 http://api.yujiameimei.com/hsck2/hsck2.php?id=/index.php/vod/play/id/19756/sid/1/nid/1.html


//调用方法  https://api.yilushunfeng.top/liebiancaiji/hsck2.php?sort=/index.php/vod/type/id/1&num_videos=100


function getlatestdomain($initialUrl) {
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        return file_get_contents($cacheFile);
    } else {
        $hostUrl = "http://hsck.net/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
        $domain = $hostmatches[1];
        $initialUrl = $domain . ":8899/?u=http://hsck.net/&p=/引用站点策略:strict-origin-when-cross-origin";
        $headers = get_headers($initialUrl, 1);
        $latestdomain = isset($headers['Location']) ? $headers['Location'] : '';

        file_put_contents($cacheFile, $latestdomain);

        return $latestdomain;
    }
}

$latestdomain = "https://hsck.la";

if (isset($_GET['getsort'])) {
    // ... 保持不变
} elseif (isset($_GET['sort']) && isset($_GET['num_videos'])) {
    $category = $_GET['sort'];
    $num_videos = intval($_GET['num_videos']);
    $videos = [];
    $page = 1;
    $empty_page_count = 0;

    header('Content-Type: text/plain');

    while (count($videos) < $num_videos) {
        $sourceUrl = $latestdomain . $category . "/page/$page.html";
        $sourceCode = file_get_contents($sourceUrl);
        preg_match('/stui-vodlist clearfix(.*?)stui-pannel-ft/s', $sourceCode, $matches);

        if (isset($matches[1])) {
            $content = $matches[1];
            $videoItems = explode('</li>', $content);

            foreach ($videoItems as $video) {
                preg_match('/href="(.*?)"/', $video, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] : '';

                if (!empty($id) && !in_array($id, array_column($videos, 'id'))) {
                    $detailUrl = $latestdomain . $id;
                    $detailCode = file_get_contents($detailUrl);
                    preg_match('/<title>(.*?) -/', $detailCode, $titleMatches);
                    $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
                    preg_match("/},\"url\":\"(.*?)\"/", $detailCode, $videoMatches);
                    $videoUrl = isset($videoMatches[1]) ? str_replace("\\","",$videoMatches[1]) : '';

                    if (!empty($title) && !empty($videoUrl)) {
                        $videoLine = $title . '|' . $videoUrl;
                        $videos[] = ['id' => $id, 'line' => $videoLine];
                        echo $videoLine . "\n";
                        flush();

                        if (count($videos) >= $num_videos) {
                            break;
                        }
                    }
                }
            }
            $empty_page_count = 0;
        } else {
            $empty_page_count++;
            if ($empty_page_count >= 2) {
                break;
            }
        }

        $page++;
    }
} elseif (isset($_GET['id'])) {
    // ... 保持不变
} elseif (isset($_GET['keyword']) && isset($_GET['page'])) {
    // ... 保持不变
}

?>
