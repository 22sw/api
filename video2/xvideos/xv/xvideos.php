<?php

// 获取所有分类 http://api.yujiameimei.com/savsp/savsp.php?getsort
// 搜索  http://api.yujiameimei.com/savsp/savsp.php?keyword=美女&page=1

// 获取某个分类下视频列表   https://api.yujiameimei.com/savsp/savsp.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/savsp/savsp.php?id=/v/154024/1/1/



// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .

"Cookie:session_ath=light; html5_pref=%7B%22SQ%22%3Afalse%2C%22MUTE%22%3Afalse%2C%22VOLUME%22%3A1%2C%22FORCENOPICTURE%22%3Afalse%2C%22FORCENOAUTOBUFFER%22%3Afalse%2C%22FORCENATIVEHLS%22%3Afalse%2C%22PLAUTOPLAY%22%3Atrue%2C%22CHROMECAST%22%3Afalse%2C%22EXPANDED%22%3Afalse%2C%22FORCENOLOOP%22%3Afalse%7D; last_views=%5B%2280906875-1715061299%22%2C%2278320713-1715061997%22%2C%2278322537-1715062936%22%5D; cit=73d233a776220a4bh8WZgAcy01I4e1iZ7XTG1A%3D%3D; session_token=b8736f8eda88d729UZUOeUJg7S70sbOcPFIT0y073K9IjiZ44GoWQJ-jsTXQu2-Q5JtMby7ZauQ7UU0cwCcwssmqmYnjFElioo5ot3mpK7axLPFiUer-eosHaTbNoxZ3FvNRdJzKIWbgkKbTvxGvyficwuzJlGS4zNP_My75czCC9Tun440AaWomlLcauwEgbH6t_xTsEga3eZxZRufwIi5_xi6F_uta9z3tKkaXyHFP0JfiAEH7SqXMbe4%3D; pending_thumb=%7B%22t%22%3A%5B%2276988385%22%2C%2278163359%22%2C%2263359609%22%5D%2C%22s%22%3A%5B%5D%2C%22p%22%3A%5B%5D%2C%22r%22%3A%5B%5D%7D\r\n" .
"Host: www.xvideos91.com\r\n" .
"Sec-Fetch-Site: same-origin\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0\r\n" ,
    ]
]);//






 	
	//$latestdomain = getlatestdomain($initialUrl);
	$latestdomain = "https://www.xvideos91.com";
    //发布地址 https://www.7000.me/
	
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     


	//构建目标链接
	$sourceUrl = $latestdomain ;
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl,false,$context);

    // 利用正则表达式截取文本
    preg_match('/分类(.*?)所有標簽/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
        //    preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		//$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

           // 提取标题
           preg_match('/<a href="(.*?)">(.*?)<\/a>/s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $id = isset($matches[1]) ? $matches[1] : '';
           $title = isset($matches[2]) ? trim($matches[2]) : '';
		  //$id = str_ireplace('.html','',$id);

            // 判断 id 不为空且 image 包含 "/categories/"
           // if (strpos($id,'/m')===false    ) { 
			if (!empty($id)   ) { 
                // 构建分类对象
                $categoryObject = [
                    //'count' => $count,
                    'id' => $id,
                    'title' => $title
                ];

                // 将分类对象添加到结果数组中
                $result[] = $categoryObject;
            }
        }

        // 构建返回对象
        $response = [
            'code' => count($result) > 0 ? 'ok' : null,
            'categories' => $result
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page'])-1 : 1;

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		if ($page===0){
			
			$sourceUrl = $latestdomain . $category ;
		}else{
			$sourceUrl = $latestdomain . $category . "/" .$page ;
		}
		
	 
	// 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);


//echo $sourceUrl;

// 利用正则表达式截取文本
preg_match('/class="mozaique(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
   // $content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</script>', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/<a href="(.*?)"/', $video, $idMatches);
        //$id = isset($idMatches[1]) ? $idMatches[1] : '';
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
// 提取时长
        preg_match('/<span class="duration">(.*?)<\/span>/', $video, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';
		 // 提取作者名
        preg_match('/class="name">(.*?)<\/span>/', $video, $channelsMatches);
        $channels = isset($channelsMatches[1]) ? $channelsMatches[1] : '';
        // 提取视频大小
        preg_match('/<span><span class="sprfluous"> - <\/span> (.*?) <span/', $video, $sizeMatches);
        $size = isset($sizeMatches[1]) ? $sizeMatches[1] : '';
		// 提取分辨率
        preg_match('/<span class="video-hd-mark">(.*?)<\/span>/', $video, $qualityMatches);
        $quality = isset($qualityMatches[1]) ? $qualityMatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
            'title' => $title,
			'duration' => $duration,
            'channels' => $channels,
            'size' => $size,
			'quality' => $quality
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }

    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'videos' => $result
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
} else {
    // 如果未匹配到结果，则返回错误信息
    echo json_encode(['code' => null]);
}	
	
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

    // 获取参数
$keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
//$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$page = isset($_GET['page']) ? intval($_GET['page'])-1 : 1;


   // 构建目标网站的 URL
   $sourceUrl  = "https://www.xvideos.com/?k={$keyword}&p={$page}";

    
// 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);


//echo $sourceUrl;

// 利用正则表达式截取文本
preg_match('/class="mozaique(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
   // $content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</script>', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/<a href="(.*?)"/', $video, $idMatches);
        //$id = isset($idMatches[1]) ? $idMatches[1] : '';
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
// 提取时长
        preg_match('/<span class="duration">(.*?)<\/span>/', $video, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';
		 // 提取作者名
        preg_match('/class="name">(.*?)<\/span>/', $video, $channelsMatches);
        $channels = isset($channelsMatches[1]) ? $channelsMatches[1] : '';
        // 提取视频大小
        preg_match('/<span><span class="sprfluous"> - <\/span> (.*?) <span/', $video, $sizeMatches);
        $size = isset($sizeMatches[1]) ? $sizeMatches[1] : '';
		// 提取分辨率
        preg_match('/<span class="video-hd-mark">(.*?)<\/span>/', $video, $qualityMatches);
        $quality = isset($qualityMatches[1]) ? $qualityMatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
            'title' => $title,
			'duration' => $duration,
            'channels' => $channels,
            'size' => $size,
			'quality' => $quality
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }

    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'videos' => $result
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
} else {
    // 如果未匹配到结果，则返回错误信息
    echo json_encode(['code' => null]);
	
}


}



elseif(isset($_GET['id'])) {
	
	
	
     // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

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
        $sourceUrl = "https://www.xvideos.com$videoId";
      
        // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);

        // 从源码中截取标题
        preg_match('/html5player.setVideoTitle\(\'(.*?)\'\)/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match('/html5player.setThumbUrl169\(\'(.*?)\'\)/', $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 从源码中截取时长
        preg_match('/duration\"\>(.*?)<\/span>/', $sourceCode, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';

        // 从源码中截取画质
        preg_match('/video-hd-mark\"\>(.*?)<\/span>/', $sourceCode, $qualityMatches);
        $quality = isset($qualityMatches[1]) ? $qualityMatches[1] : '';

        // 从源码中截取视频地址
        preg_match('/html5player.setVideoUrlLow\(\'(.*?)\'\)/', $sourceCode, $url3gpMatches);
        $url3gp = isset($url3gpMatches[1]) ? $url3gpMatches[1] : '';
		
		preg_match('/html5player.setVideoUrlLow\(\'(.*?)\'\)/', $sourceCode, $mp4lowMatches);
        $mp4low = isset($mp4lowMatches[1]) ? $mp4lowMatches[1] : '';
		
		preg_match('/html5player.setVideoUrlHigh\(\'(.*?)\'\)/', $sourceCode, $mp4HighMatches);
        $mp4High = isset($mp4HighMatches[1]) ? $mp4HighMatches[1] : '';
		
		preg_match('/html5player.setVideoHLS\(\'(.*?)\'\)/', $sourceCode, $m3u8Matches);
        $m3u8 = isset($m3u8Matches[1]) ? $m3u8Matches[1] : '';
		
		// 获取m3u8多地址
		$m3u8muit = file_get_contents($m3u8);
		//替换hls.m3u8
		$m3u8head = preg_replace('/hls.m3u8/', 'hls-', $m3u8, 1);
		
		
		
		
		preg_match('/1080p-(.*?).m3u8/', $m3u8muit, $url1080pMatches);
        $url1080p = isset($url1080pMatches[1]) ? $url1080pMatches[1] : '';
		if (!empty($url1080p)) {
		$url1080p =$m3u8head . '1080p-'. $url1080p . '.m3u8';
		}elseif (strpos($m3u8muit, "1080p") !== false) {
   		 $url1080p =$m3u8head . '1080p.m3u8';;
		} else {
 		  $url1080p=null;
		}
		
		
		preg_match('/720p-(.*?).m3u8/', $m3u8muit, $url720pMatches);
        $url720p = isset($url720pMatches[1]) ? $url720pMatches[1] : '';
		if (!empty($url720p)) {
			$url720p =$m3u8head . '720p-'. $url720p . '.m3u8';
		}elseif (strpos($m3u8muit, "720p") !== false) {
   		 $url720p =$m3u8head . '720p.m3u8';;
		} else {
 		  $url720p=null;
		}
		
		preg_match('/480p-(.*?).m3u8/', $m3u8muit, $url480pMatches);
        $url480p = isset($url480pMatches[1]) ? $url480pMatches[1] : '';
		if (!empty($url480p)) {
		$url480p =$m3u8head . '480p-' . $url480p . '.m3u8';
		}elseif (strpos($m3u8muit, "480p") !== false) {
   		 $url480p =$m3u8head . '480p.m3u8';;
		} else {
 		  $url480p=null;
		}
		
		preg_match('/360p-(.*?).m3u8/', $m3u8muit, $url360pMatches);
        $url360p = isset($url360pMatches[1]) ? $url360pMatches[1] : '';
		if (!empty($url360p)) {
		$url360p =$m3u8head . '360p-'. $url360p . '.m3u8';
		}elseif (strpos($m3u8muit, "360p") !== false) {
   		 $url360p =$m3u8head . '360p.m3u8';;
		} else {
 		  $url360p=null;
		}

         preg_match('/250p-(.*?).m3u8/', $m3u8muit, $url250pMatches);
        $url250p = isset($url250pMatches[1]) ? $url250pMatches[1] : '';
		if (!empty($url250p)) {
		$url250p =$m3u8head . '250p-'. $url250p . '.m3u8';
		}elseif (strpos($m3u8muit, "250p") !== false) {
   		 $url250p =$m3u8head . '250p.m3u8';;
		} else {
 		  $url250p=null;
		}
		
		
		if  (!empty($url1080p)){
			$videoUrl = $url1080p;
			
		}elseif(!empty($url720p)){
			$videoUrl = $url720p;
        }elseif(!empty($url480p)){
			$videoUrl = $url480p;
        }elseif(!empty($url360p)){
			$videoUrl = $url360p;
        }elseif(!empty($url250p)){
			$videoUrl = $url250p;
        }else{
			$videoUrl = $mp4High;
		}



        // 构建返回对象
        $response = [];
        if (!empty($url3gp)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
				'duration' => $duration,
				'quality' => $quality,
                '3gp' => $url3gp,
				'mp4low' => $mp4low,
				'mp4High' => $mp4High,
                'm3u8' => $m3u8,
                '1080p' => $url1080p,
				'720p' => $url720p,
                '480p' => $url480p,
                '360p' => $url360p,
				'250p' => $url250p,
				'video' => $videoUrl,
				
                'related' => []
            ];
        } else {
            $response = [
                'code' => null
            ];
        }

         // 获取推荐视频列表
        preg_match('/related=(.*?);window/s', $sourceCode, $matches);
       // if (isset($matches[1])) {
            $recommendationsText = $matches[1];
			
			//echo $recommendationsText;
			 
			 $sourceCode = $recommendationsText;

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

 

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray  as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['u'],
            'title' => $category['tf'],
            'iamge' => $category['i'],
            'duration' => $category['d'],
            'size' => $category['n'] 
        ];
        $categories[] = $categoryItem;
   // }
    }
    // 将处理过的数组命名为 categories
    $response['recommend'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);
        
    
}
 


?>
