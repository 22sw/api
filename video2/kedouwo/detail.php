<?php

//https://api.22ba4.top/missav/detail.php?id=/82696/swag-av3/


if(isset($_GET['id'])) {
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

    // 初始链接
    $initialUrl = "http://xiaobi132.com";

    // 获取重定向后的目标地址
    $headers = get_headers($initialUrl, 1);
    $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';

    if (!empty($redirectUrl)) {
        // 设置最新域名为重定向后的目标地址
        $latestDomain = $redirectUrl;
        
        // 构建目标网站的 URL
        $sourceUrl = "https://www.kedou.xxx/videos$videoId";

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);

        // 从源码中截取标题
        preg_match('/<h1>(.*?)<\/h1>/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/preview_url: '(.*?)'/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 从源码中截取视频地址
        preg_match("/video_alt_url: 'function\/0\/(.*?)\/'/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';

        // 将视频键值中的旧域名替换为最新域名
        $videoUrl = str_replace("https://www.kedou.xxx", $latestDomain, $videoUrl);

        // 构建返回对象
        $response = [];
        if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
        } else {
            $response = [
                'code' => null
            ];
        }

        // 获取推荐视频列表
        preg_match('/list-videos(.*?)footer-margin/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            // Remove the first '<div class="' occurrence
            $recommendationsText = preg_replace('/<div class="/', '', $recommendationsText, 1);
            // Split by 'views'
            $recommendations = explode('views', $recommendationsText);
            // Get the actual count
            $actualCount = count($recommendations) - 1;
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ? $imageMatches[1] : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? str_replace("https://www.kedou.xxx/videos", "", $idMatches[1]) : '';

                preg_match('/title="(.*?)"/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

                if (!empty($id) && strpos($image, "/videos_screenshots/") !== false) {
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                }
            }
            // Add recommendations to the response
            $response['recommend'] = $recommendationList;
            // Return the updated response
			 header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Return the response without recommendations if not found
			 // 输出 JSON 格式数据
        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else {
        // 如果未获取到重定向地址，则返回错误信息
        echo json_encode(['code' => null]);
    }
}


?>
