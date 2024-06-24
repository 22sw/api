<?php

// 最佳 获取频道筛选（mian regoin rank）   https://api.22ba4.top/xv/pornstars.php?data
// 频道获取列表 mian regoin rank page  https://api.22ba4.top/xv/pornstars.php?main=/porn-actresses-index&region=/asia&rank=/from/worldwide/3months&page=1
// 某个频道视频列表  channels  page  https://api.22ba4.top/xv/pornstars.php?pornstars=/enjoypornpass&page=2



// 获取参数
$main = isset($_GET['main']) ? $_GET['main'] : '';
$region = isset($_GET['region']) ? $_GET['region'] : '';
$rank = isset($_GET['rank']) ? $_GET['rank'] : '' ;
$page = isset($_GET['page']) ? intval($_GET['page'])-1 : 1;
$data = isset($_GET['data']) ? $_GET['data'] : '';
$pornstars = isset($_GET['pornstars']) ? $_GET['pornstars'] : '';


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
]);//


if(isset($_GET['main']) && $_GET['page']) {
// 构建目标网站的 URL
   $sourceUrl  = "https://www.xvideos.com{$main}{$region}{$rank}/{$page}";
   
    
// 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);



// 利用正则表达式截取文本
preg_match('/class="mozaique(.*?)id="ad-footer/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
    //$content = trim($content)
	
	

    // 将文本a中的第一和第二个“<a href=”替换为空
   // $content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('p></', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/<img src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/<a href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		
        // 提取标题
         preg_match('/#(\d+).*?>([^<]+)<\/a>/', $video, $titleMatches);
        
		if (isset($titleMatches[1]) && isset($titleMatches[2])) {
        $number = $titleMatches[1];
        $name = $titleMatches[2];
        $title = "#".$number . " " . $name;
        }

       // $title = preg_replace('/\//', ' ', $title, 1);
		
        // 提取视频数量
         preg_match('/<span class="with-sub">\s*(.*?)\s*<\/span>/', $video, $numberMatches);
        $number = (isset($numberMatches[1]) ? $numberMatches[1] : '');
		
		// 提取订阅数量
        // preg_match('/<strong>(.*?)<\/strong>/', $video, $quantityMatches);
        //$quantity = (isset($quantityMatches[1]) ? $quantityMatches[1] : '') . $id;
		
		



        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
			'number' => $number,
            'title' => $title
			
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }

    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'data' => $result
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
} else {
    // 如果未匹配到结果，则返回错误信息
    echo json_encode(['code' => null]);
	
}
} elseif(isset($_GET['data'])) {
	
	// 构建目标网站的 URL
   $sourceUrl  = "https://www.xvideos.com/pornstars-index";
   
    
// 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	// 利用正则表达式截取文本
preg_match('/ordered-label-list(.*?)<\/ul><\/div>	/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 利用正则表达式进一步分割内容
    preg_match('/profile-links hidden(.*?)ul>/s', $content, $mainMatches);
    preg_match('/geo-links hidden(.*?)ul>/s', $content, $regionMatches);
    preg_match('/time-links hidden(.*?)ul>/s', $content, $rankMatches);

    // 如果匹配到结果
    if (isset($mainMatches[1]) && isset($regionMatches[1]) && isset($rankMatches[1])) {
        // 分别提取三个部分的内容
        $contentmain = $mainMatches[1];
        $contentregion = $regionMatches[1];
        $contentrank = $rankMatches[1];

        // 初始化结果数组
        $main = [];
        $region = [];
        $rank = [];

        // 循环处理 contentmain 中的视频
        $mainItems = explode('li><', $contentmain); // 使用 '<a' 进行分割
        foreach ($mainItems as $item) {
            // 提取 id 和 title
            preg_match('/href="(.*?)">(.*?)<\/a>/', $item, $matches);
            $id = isset($matches[1]) ? $matches[1] : '';
            $title = isset($matches[2]) ? $matches[2] : '';
            // 添加到结果数组
            if (!empty($id)) {
                $main[] = ['id' => $id, 'title' => $title];
            }
        }

        // 循环处理 contentregion 中的视频
        $regionItems = explode('li><', $contentregion); // 使用 '<a' 进行分割
        foreach ($regionItems as $item) {
            // 提取 id 和 title
            preg_match('/href="\/pornstars-index(.*?)">(.*?)<\/a>/', $item, $matches);
            $id = isset($matches[1]) ? $matches[1] : '';
            $title = isset($matches[2]) ? $matches[2] : '';
            // 添加到结果数组
            if (!empty($title)) {
                $region[] = ['id' => $id, 'title' => $title];
            }
        }

        // 循环处理 contentrank 中的视频
        $rankItems = explode('li><', $contentrank); // 使用 '<a' 进行分割
        foreach ($rankItems as $item) {
            // 提取 id 和 title
            preg_match('/href="\/pornstars-index(.*?)">(.*?)<\/a>/', $item, $matches);
            $id = isset($matches[1]) ? $matches[1] : '';
            $title = isset($matches[2]) ? $matches[2] : '';
            // 添加到结果数组
            if (!empty($title)) {
                $rank[] = ['id' => $id, 'title' => $title];
            }
        } 

        // 构建返回对象
        $response = [
            'code' => 'ok',
            'main' => $main,
            'region' => $region,
            'rank' => $rank
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到任何结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
} else {
    // 如果未匹配到结果，则返回错误信息
   // echo json_encode(['code' => null]);
}



}  elseif (isset($_GET['pornstars']) && isset($_GET['page'])) {
	
	
	// 构建目标网站的 URL
  // $sourceUrl  = "https://www.xvideos.com$id#_tabVideos,page-$page";
   // $sourceUrl  = "https://www.xvideos.com/channels$channels/videos/best/$page";
   
// 获取源码
   // $sourceCode = file_get_contents($sourceUrl);
   // $sourceCode = file_get_contents($sourceUrl, false, $context);
//echo $sourceUrl;
//echo $sourceCode;

//header('Content-Type: application/json');
   // echo $sourceCode;
	


// 获取给定链接的 JSON 数据
$url =  "https://www.xvideos.com$pornstars/videos/best/$page";




$jsonData = file_get_contents($url, false, $context);

// 将 JSON 数据解码为关联数组
$data = json_decode($jsonData, true);

// 如果成功解码 JSON 数据
if ($data !== null && isset($data['videos'])) {
    // 遍历 videos 数组
    foreach ($data['videos'] as &$video) {
        // 仅保留指定的键值，并修改键名
        $video = [
            'id' => $video['u'],
			'imagesmall' => $video['if'],
            'image' => $video['if'],
			'imagebig' => $video['ip'],
            'title' => $video['t'],
			'duration' => $video['d'],
			'seetimes' => $video['n'],
			'rate' => $video['r'],
        ];
    }
    // 释放引用，防止后续操作影响原数组
    unset($video);

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // 如果无法解码 JSON 数据或者 videos 数组不存在，则返回原始的 JSON 数据
    header('Content-Type: application/json');
    echo $jsonData;
}






	
}

?>