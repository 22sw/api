<?php

// 获取所有分类 http://api.yujiameimei.com/tuoku8/tkb.php?getsort
// 搜索  http://api.yujiameimei.com/tuoku8/tkb.php?keyword=美女&page=1

// 获取某个分类下视频列表   https://api.yujiameimei.com/tuoku8/tkb.php?sort=138&page=1
// 获取视频详情  https://api.yujiameimei.com/tuoku8/tkb.php?id=5ba70ce9183d41f5c75dd46bad9ea593&url=480p_40792


// 创建请求头
$headers = [
    "Origin: https://www.tkbajd.life",
    "Referer: https://www.tkbajd.life/",
];

// 构建流上下文
$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
    'http' => [
        'header' => implode("\r\n", $headers),
    ],
]);



function getRedirectUrl($initialUrl) {
     //检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "https://github.com/tuoku8/tuoku8";
        $hostCode = file_get_contents($hostUrl);
		
        preg_match('/aria-label="Permalink: point_right: 【地址二】：(.*?)"/s', $hostCode, $hostmatches);
		$domain = $hostmatches[1];
		$initialUrl = $domain;
        $headers = get_headers($initialUrl, 1);
        $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		$redirectUrl = rtrim($redirectUrl, '/');
		
         //将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $redirectUrl);

         //返回获取到的重定向 URL
        return $redirectUrl;
    }
}
	//$redirectUrl = getRedirectUrl($initialUrl);


// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     $redirectUrl = getRedirectUrl($initialUrl);
    

	//构建目标链接
	$sourceUrl = "https://api.tkbajd.life/m/category?type=VIDEO&page=0&pageSize=5";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl, false, $context);

    // 解析 JSON 格式数据为 PHP 数组
    $data = json_decode($sourceCode, true);

    // 初始化结果数组
    $categories = [];

    // 遍历每个元素，修改键名和格式
    foreach ($data as $item) {
    // 修改键名和格式
    $category = [
        'id' => $item['id'],
		//'id' => "{$item['id']}",
        'title' => $item['name']
    ];
    
    // 将修改后的元素添加到结果数组中
    $categories[] = $category;
    }

    // 根据数组长度确定 code 的值
    $code = count($categories) > 0 ? 'ok' : null;

    // 构建返回对象
    $response = [
    'code' => $code,
    'categories' => $categories
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
    //echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	$redirectUrl = getRedirectUrl($initialUrl);
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page'])-1 : 1;
	
	// 构建目标网站的 URL
		
    $sourceUrl = "https://api.tkbajd.life/v/list?recommendType=NEW&page={$page}&pageSize=32&categoryId={$category}";

	// 获取源码
	$sourceCode = file_get_contents($sourceUrl, false, $context);

	 


// 解码JSON数据为PHP数组
$data = json_decode($sourceCode, true);

// 初始化结果数组
$videos = [];

// 检查是否存在content键
if (isset($data['content'])) {
    // 获取content数组
    $content = $data['content'];

    // 循环处理每个视频
    foreach ($content as $video) {
        // 构建视频对象
        $videoObject = [
            'id' => $video['id'],
			'title' => $video['title'],
			'img1' => $video['batch'],
			'img2' => str_replace('.jpg','',$video['thumbnail']),
			'img3' => "/thumbnail.jpg",
			'image' => "https://static.tkbajd.life/". $video['batch']."/" .str_replace('.jpg','',$video['thumbnail']) . "/thumbnail.jpg",
			'playCount' => $video['playCount'],
			'praise' => $video['praise'],
			'degrade' => $video['degrade'],
			'duration' => $video['duration'],
			'hd' => $video['hd'],
			'shortUrl' => $video['shortUrl']
            
        ];

        // 将视频对象添加到结果数组中
        $videos[] = $videoObject;
    }
}

// 构建返回对象
$response = [
    'code' => count($videos) > 0 ? 'ok' : null,
    'videos' => $videos
];

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
	
	
} elseif(isset($_GET['id']) && isset($_GET['url']) ) {
	
	$redirectUrl = getRedirectUrl($initialUrl);
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
    $url = isset($_GET['url']) ? $_GET['url'] : '';
	 
        // 构建目标网站的 URL
        $sourceUrl = "https://api.tkbajd.life/v/get?batch=$videoId&url=$url";
        $similarUrl = "https://api.tkbajd.life/v/list/similar?batch=$videoId&page=0&pageSize=8&url=$url";
        // 获取源码
    //    $sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	$similarCode = file_get_contents($similarUrl, false, $context);
	
	
// 将文本a中的第一和第二个“<a href=”替换为空
    $sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/\"title\":\"(.*?)\"/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/image\" content\=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		//提取时间戳
        preg_match("/\"t\":\"(.*?)\"/", $sourceCode, $timestampMatches);
        $timestamp = isset($timestampMatches[1]) ? $timestampMatches[1] : '';

        // 从源码中截取视频地址
        preg_match("/url\":\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? str_replace("\\","",$videoMatches[1]) : '';
$videoUrl ="https://resources.tkbajd.life/ $videoId/$url/output_hd.m3u8?md5=lqgRUDMlHaFq2jORu0CRsg&expires=$timestamp";
        


        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
               // 'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/stui-vodlist clearfix(.*?)ul>/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            
            // Split by 'views'
            $recommendations = explode('</li>', $recommendationsText);
            
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/data-original="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ?( $domain . $imageMatches[1]) : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] : '';

                preg_match('/1\/" >(.*?)<\/a>/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

                
        
		// 提取时长
        preg_match('/right">(.*?)</', $recommendation, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<i class="fa fa-heart"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*<span class="pull-right"><i class="fa fa-eye"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*(\d{2}-\d{2})/', $recommendation, $datamatches);
        $dianzan = $datamatches[1]; 
        $playtimes = $datamatches[2]; 
        $date = $datamatches[3]; 
		
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						'duration' => $duration,
                        'playtimes' => $playtimes,
						'date' => $date,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                
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
    
}
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

    $redirectUrl = getRedirectUrl($initialUrl);
    // 获取参数
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page'])-1 : 1;

    // 构建目标网站的 URL
    $sourceUrl = "https://api.tkbajd.life/v/list?recommendType=NEW&page=$page&pageSize=32&title=$keyword";


    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
	$sourceCode = file_get_contents($sourceUrl, false, $context);
    preg_match('/stui-vodlist clearfix(.*?)stui-pannel-ft/s', $sourceCode, $matches);


// 获取源码
	$sourceCode = file_get_contents($sourceUrl, false, $context);


// 解码JSON数据为PHP数组
$data = json_decode($sourceCode, true);

// 初始化结果数组
$videos = [];

// 检查是否存在content键
if (isset($data['content'])) {
    // 获取content数组
    $content = $data['content'];

    // 循环处理每个视频
    foreach ($content as $video) {
        // 构建视频对象
        $videoObject = [
            'id' => $video['id'],
			'title' => $video['title'],
			'img1' => $video['batch'],
			'img2' => str_replace('.jpg','',$video['thumbnail']),
			'img3' => "/thumbnail.jpg",
			'image' => "https://static.tkbajd.life/". $video['batch']."/" .str_replace('.jpg','',$video['thumbnail']) . "/thumbnail.jpg",
			'playCount' => $video['playCount'],
			'praise' => $video['praise'],
			'degrade' => $video['degrade'],
			'duration' => $video['duration'],
			'hd' => $video['hd'],
			'shortUrl' => $video['shortUrl']
            
        ];

        // 将视频对象添加到结果数组中
        $videos[] = $videoObject;
    }
}

// 构建返回对象
$response = [
    'code' => count($videos) > 0 ? 'ok' : null,
    'videos' => $videos
];

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);


}


?>
