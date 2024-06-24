<?php

// https://api.22ba4.top/xv/tagall.php?tags


// 获取参数
$tags = isset($_GET['tags']) ? $_GET['tags'] : '';


// 获取当前时间的十位时间戳
    //$timestamp = time();
	
	// 获取当前时间的十三位时间戳
	//list($t1, $t2) = explode(' ', microtime());
    //$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);
	

// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .

"Cookie: session_ath=black;html5_pref=%7B%22SQ%22%3Afalse%2C%22MUTE%22%3Afalse%2C%22VOLUME%22%3A1%2C%22FORCENOPICTURE%22%3Afalse%2C%22FORCENOAUTOBUFFER%22%3Afalse%2C%22FORCENATIVEHLS%22%3Afalse%2C%22PLAUTOPLAY%22%3Atrue%2C%22CHROMECAST%22%3Afalse%2C%22EXPANDED%22%3Afalse%2C%22FORCENOLOOP%22%3Afalse%7D; xv_nbview=1; html5_networkspeed=41543; PHPSESSID=1bbtf4u7c3s98mcgpe85lrp3ua; has_visited=1; service=girls; language=en; BILLING_TEST_SUB_GROUP_4=NEW; BILLING_TEST_GROUP_4=GROUP_B%3A%3Av8; _gcl_au=1.1.448349762.1713712556; _gid=GA1.2.701795849.1713712563; source_code=default; layout04=1; started=1713712560; screen_name=guestUser_789719694; params=dG9rZW49MzY3MDMmdG9rZW5fZW5jPVFGQlNSVm89JmN0aT04Jm1vZGVsX2lkPTEzMTU1NTgmaG9zdD1jaGF0MDAzLnZzMy5jb20mcG9ydD0xJnNpdGVpZD0xMjQ3NTgmY2hhdF9wb3J0X2ZsYXNoPTEmdmlkZW9faG9zdD12aWRlby1ncHUwMDYtdHNzLW55LnZzMy5jb20mdmlkZW9fcG9ydD0wJmF1ZGlvX3BvcnQ9MCZ4bWxfcG9ydD00MTIwMQ%3D%3D; mp_code=d4vn3; _ga=GA1.2.317487428.1713712563; _ga_EGYWBHZHQV=GS1.1.1713717324.2.1.1713717324.0.0.0; zone-cap-3614151=1%3B1713718773; last_views=%5B%2278888921-1713627446%22%2C%2280609231-1713627592%22%2C%2280771513-1713627602%22%2C%2280396141-1713627654%22%2C%2276667963-1713629209%22%2C%2277434611-1713629241%22%2C%2270483157-1713633301%22%2C%2264098415-1713634338%22%2C%2280343687-1713712285%22%2C%2280116107-1713713722%22%2C%2280079983-1713718765%22%2C%229779334-1713718771%22%5D; pending_thumb=%7B%22t%22%3A%5B%5D%2C%22s%22%3A%5B%5D%2C%22p%22%3A%5B%5D%2C%22r%22%3A%5B%5D%7D; session_token=2978f5c7644b63d9XT5D_s3TMwcTx2TzhodrYN5IQYjlPBk3ilLq8vqIygnUXoPOZ-gugFx-c8mAiQ4XgK0ceqeHin7zJw8w9EnV925AsnV4TQTdu1c0_jMA9C8BVA4hyuqaeQ3OB8Yo28-VEKSTZIeko9B_qcHCb2pvpSR-7_E6dSgVpDC3nEI3UXf1v-tX72VhjMFxplrtQD5bBFY9vF8X-fUYCtKDUNQaWmz7WUrV8dyczl0UKczfoJe95rSgGhDKHy-bx8ib8YReRJB3DKFz8koF6t2M2WQChg%3D%3D\r\n" .
"Host: www.xvideos.com\r\n" .
"Sec-Fetch-Site: same-origin\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);

// 构建目标网站的 URL
   $sourceUrl = "https://www.xvideos.com/tags";

    
// 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);


//echo $sourceCode;

// 利用正则表达式截取文本
preg_match('/stripe black-stripe(.*?)stripe black-stripe/s', $sourceCode, $matches);

// 如果匹配到结果

    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“video-container”分割得到数组
    $videos = explode('</a>', $content);
    
    // 移除数组第一个元素
    //array_shift($videos);

    // 移除数组最后一个元素
    array_pop($videos);
	
    // 初始化结果数组
    $result = [];
    
    // 循环处理每个视频（所有语言）
    foreach ($videos as $video) {
        

        // 提取视频ID
        preg_match('/<a href="(.*?)"/', $video, $idMatches);
        //$id = isset($idMatches[1]) ? $idMatches[1] : '';
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
        
		// 提取语言种类
        preg_match('/"><\/span> (.*?) <\/b>/', $video, $langMatches);
        $lang = isset($langMatches[1]) ? $langMatches[1] : '';
		
		// 提取数量种类
        preg_match('/default">(.*?)<\/span>/', $video, $quantityMatches);
        $quantity = isset($quantityMatches[1]) ? $quantityMatches[1] : '';
		
        // 构建视频对象
        $videoObject = [
            'id' => $id,
            'title' => $title,
			 'lang' => $lang,
            'quantity' => $quantity
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }
	
	
	
	$tagall = [];
	preg_match('/id="tags"(.*?)<\/ul>/s', $sourceCode, $matches);

// 如果匹配到结果

    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“video-container”分割得到数组
    $videos = explode('</a>', $content);

    // 移除数组最后一个元素
    array_pop($videos);
	
// 循环处理每个视频(所有标签)
    foreach ($videos as $video) {
		
        preg_match('/<a href="(.*?)"/', $video, $idMatches);
        //$id = isset($idMatches[1]) ? $idMatches[1] : '';
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        
		// 提取语言种类
        preg_match('/<b>(.*?)<\/b>/', $video, $tagMatches);
        $tag = isset($tagMatches[1]) ? $tagMatches[1] : '';
		
		// 提取数量种类
        preg_match('/default">(.*?)<\/span>/', $video, $quantityMatches);
        $quantity = isset($quantityMatches[1]) ? $quantityMatches[1] : '';
		
        // 构建视频对象
        $tagObject = [
            'id' => $id,
			'tag' => $tag,
            'quantity' => $quantity
			
        ];

        // 将视频对象添加到结果数组中
        $tagall[] = $tagObject;
    }
	
	
    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'data' => $result,
		'tag' => $tagall
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	


?>