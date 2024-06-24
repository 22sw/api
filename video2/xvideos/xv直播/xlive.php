<?php


//   主播id 获取直播url  https://api.22ba4.top/xv/xlive.php?id=1343375
//   sex性别 girls guys trans  https://api.22ba4.top/xv/xlive.php?sex=girls

// 获取参数


$sex = isset($_GET['sex']) ? $_GET['sex'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 获取当前时间的十三位时间戳
	
list($t1, $t2) = explode(' ', microtime());
$timestamp = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);


// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .

"Cookie: session_ath=black;html5_pref=%7B%22SQ%22%3Afalse%2C%22MUTE%22%3Afalse%2C%22VOLUME%22%3A1%2C%22FORCENOPICTURE%22%3Afalse%2C%22FORCENOAUTOBUFFER%22%3Afalse%2C%22FORCENATIVEHLS%22%3Afalse%2C%22PLAUTOPLAY%22%3Atrue%2C%22CHROMECAST%22%3Afalse%2C%22EXPANDED%22%3Afalse%2C%22FORCENOLOOP%22%3Afalse%7D; xv_nbview=1; html5_networkspeed=41543; PHPSESSID=1bbtf4u7c3s98mcgpe85lrp3ua; has_visited=1; service=girls; language=en; BILLING_TEST_SUB_GROUP_4=NEW; BILLING_TEST_GROUP_4=GROUP_B%3A%3Av8; _gcl_au=1.1.448349762.1713712556; _gid=GA1.2.701795849.1713712563; source_code=default; layout04=1; started=1713712560; screen_name=guestUser_789719694; params=dG9rZW49MzY3MDMmdG9rZW5fZW5jPVFGQlNSVm89JmN0aT04Jm1vZGVsX2lkPTEzMTU1NTgmaG9zdD1jaGF0MDAzLnZzMy5jb20mcG9ydD0xJnNpdGVpZD0xMjQ3NTgmY2hhdF9wb3J0X2ZsYXNoPTEmdmlkZW9faG9zdD12aWRlby1ncHUwMDYtdHNzLW55LnZzMy5jb20mdmlkZW9fcG9ydD0wJmF1ZGlvX3BvcnQ9MCZ4bWxfcG9ydD00MTIwMQ%3D%3D; mp_code=d4vn3; _ga=GA1.2.317487428.1713712563; _ga_EGYWBHZHQV=GS1.1.1713717324.2.1.1713717324.0.0.0; zone-cap-3614151=1%3B1713718773; last_views=%5B%2278888921-1713627446%22%2C%2280609231-1713627592%22%2C%2280771513-1713627602%22%2C%2280396141-1713627654%22%2C%2276667963-1713629209%22%2C%2277434611-1713629241%22%2C%2270483157-1713633301%22%2C%2264098415-1713634338%22%2C%2280343687-1713712285%22%2C%2280116107-1713713722%22%2C%2280079983-1713718765%22%2C%229779334-1713718771%22%5D; pending_thumb=%7B%22t%22%3A%5B%5D%2C%22s%22%3A%5B%5D%2C%22p%22%3A%5B%5D%2C%22r%22%3A%5B%5D%7D; session_token=2978f5c7644b63d9XT5D_s3TMwcTx2TzhodrYN5IQYjlPBk3ilLq8vqIygnUXoPOZ-gugFx-c8mAiQ4XgK0ceqeHin7zJw8w9EnV925AsnV4TQTdu1c0_jMA9C8BVA4hyuqaeQ3OB8Yo28-VEKSTZIeko9B_qcHCb2pvpSR-7_E6dSgVpDC3nEI3UXf1v-tX72VhjMFxplrtQD5bBFY9vF8X-fUYCtKDUNQaWmz7WUrV8dyczl0UKczfoJe95rSgGhDKHy-bx8ib8YReRJB3DKFz8koF6t2M2WQChg%3D%3D\r\n" .
"Host: cams.xvideos.com\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);




if(isset($_GET['sex'])) {
    $sex = isset($_GET['sex']) ? $_GET['sex'] : '';

    if (!empty($sex)) {
        $sourceUrl  = "https://cams.xvideos.com/live/$sex/";
        $sourceCode = file_get_contents($sourceUrl);

        preg_match('/homePageData__ = \{(.*?)\'favorites/s', $sourceCode, $matches);
        $Content = $matches[1];

        $Content = preg_replace('/\}\,    \]\,/', '}]}', $Content);
        $Content = preg_replace('/\'models\': \[/', '{"code": "ok","models": [', $Content);

        $data = json_decode($Content, true);

        if ($data !== null && isset($data['models'])) {
            foreach ($data['models'] as &$video) {
                $video = [
                    'id' => $video['model_id'],
					'screencaps' => "https://live-screencaps.vscdns.com/{$video['model_id']}-desktop.jpg",
                    'avatar' => $video['image'],
					'room_status' => $video['room_status'],
					'video_host' => $video['video_host'],
					'name' => $video['display'],
					'model_seo_name' => $video['model_seo_name'],
                    'topic' => $video['topic'],
                    'sex' => $video['service'],
                    'location' => $video['location'],
                ];
            }
            unset($video);

            header('Content-Type: application/json');
            echo json_encode($data);
			//echo $Content;
        } else {
            // 如果无法解码 JSON 数据或者 models 数组不存在，则返回空数组
            header('Content-Type: application/json');
            echo json_encode(['code' => null, 'models' => []]);
        }
    }
}
elseif(isset($_GET['id']) ) {
	
	// 构建目标网站
    $sourceUrl = "https://cams.xvideos.com/ws/chat/get-stream-urls.php?model_id=$id&video_host=video-gpu002-mojo-eu.vs3.com&t=$timestamp";

// 获取源码
$sourceCode = file_get_contents($sourceUrl);

// 解析 JSON 数据
$data = json_decode($sourceCode, true);

// 检查是否成功解析 JSON 数据
if ($data !== null) {
    // 获取 hls 数组中的第一个元素的 url
    $url1 = isset($data['data']['hls'][0]['url']) ? $data['data']['hls'][0]['url'] : null;
    $url1 = str_replace('//', 'https://', $url1);
    // 获取 llhls 数组中的第一个元素的 url
    $url2 = isset($data['data']['llhls'][0]['url']) ? $data['data']['llhls'][0]['url'] : null;
    $url2 = str_replace('//', 'https://', $url2);
	
	$urlCode = file_get_contents($url2);
	
	// 按换行符拆分 $sourceCode 成数组
$lines = explode("\n", $urlCode);

// 取得倒数第二行文本
$lastLine = $lines[count($lines) - 2];

// 检查文本中是否包含 "m3u8"
if (strpos($lastLine, "m3u8") !== false) {
    // 如果包含，则拼接链接
    $play_m3u8 = "https://hls.vscdns.com/" . $lastLine;
} else {
    // 如果不包含，则返回 Null
    $play_m3u8 = $url2;
	
	
}

// 返回结果
//return $result;
	
	
    // 返回 url1 和 url2
    $response = [
        'play_m3u8' => $play_m3u8
        
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // 如果无法解析 JSON 数据，则返回错误信息
    echo json_encode(['error' => 'Failed to parse JSON data']);
}
	
	
	
	
	
}else{
	// 如果 id 参数不存在，则返回错误信息
    echo json_encode(["error" => "ID parameter is missing"]);
	
}




?>
