<?php

// 获取所有分类 https://api.yujiameimei.com/pali/pali.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/pali/pali.php?sort=long&category=制服&page=1
// 搜索  https://api.yujiameimei.com/pali/pali.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/pali/pali.php?id=208481



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        //'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        //"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        
        "Platform:h5". 
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0",
        
    ]
]);

    // 获取当前时间的十三位时间戳
    list($t1, $t2) = explode(' ', microtime());
    $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

    $latestdomain = 'https://apih.yaowanzhuan.net';
    $token = 'token=eyJ1c2VyX2lkIjo1MzEwODExNTQsImxhc3Rsb2dpbiI6MTcxNDY3MDI3M30.6ad32bb0477d5109b1097e46e4abf8ba.b55fd7f53f48c6707b5b8886e7bf1df14f417cd8dcfdf5cfe1bf949e';
	$newurl =  'https://apiaws.zgwrg.com';
	
// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    
	
	$sourceUrl = "$latestdomain/v1/initial?type=1";
   // } else {
	//    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // }
	
    $sourceCode = file_get_contents($sourceUrl,false,$context);
    
	
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
	
	// 处理长视频数组
if (!empty($dataArray['response']['categories']['long'])) {
    $videos = [];
    foreach ($dataArray['response']['categories']['long'] as $video) {
        // 修改键名
        $videoItem = [
			'categorie' => 'long',
            'id' => $video['category_id'],
            'title' => $video['category_name'],
            'image' => $video['category_image'],
            'real_category' => $video['real_category'],
			'sort' => 'long' . '&category=' . $video['real_category'] 
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
}

// 处理短视频数组
if (!empty($dataArray['response']['categories']['short'])) {
    foreach ($dataArray['response']['categories']['short'] as $video) {
        // 构建短视频项的关联数组
        $videoItem = [
		    'categorie' => 'short',
            'id' => $video['category_id'],
            'title' => $video['category_name'],
            'image' => $video['category_image'],
            'real_category' => $video['real_category'],
			'sort' => 'short' . '&category=' . $video['real_category'] 
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
}

// 将处理过的视频数组命名为 videos
$response['categories'] = $videos;

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
     $category = isset($_GET['sort']) ? $_GET['sort'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
   // if ($page == 1) {
        $sourceUrl = "$latestdomain/pwa/videos/list?page=$page&{$token}&video_type=$category";
   // } else {
    //    $sourceUrl = "$latestdomain/vod/listing-$category-0-0-0-0-0-0-0-0-$page?timestamp=$str_time";
   // }
   

    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
	//echo $sourceUrl;

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['response']['videos'])) {
    $videos = [];
    foreach ($dataArray['response']['videos'] as $video) {
		if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['video_id'],
            'title' => $video['video_title'],
            'image' => $video['video_thumb'],
            'views' => $video['video_views'],
            'date' => $video['video_upload_date'],
            'duration' => $video['video_duration']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
        if (isset($video['video_main_tag'][0]['info'])) {
            $videoItem['info'] = $video['video_main_tag'][0]['info'];
        }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
    }
    }
    // 将处理过的视频数组命名为 videos
    $response['videos'] = $videos;
    // 设置 code 为 ok
    $response['code'] = 'ok';
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
    //if ($page == 1) {
        $sourceUrl = "$latestdomain/pwa/videos/short/search?keyword={$keyword}&order=1&{$token}&page=$page";
		
		$sourceUrl2 = "$latestdomain/pwa/videos/short/search?keyword={$keyword}&order=1&{$token}&page=$page";
		
   // } else {
	//    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // }
   
	 
    $sourceCode = file_get_contents($sourceUrl,false,$context);
    $sourceCode2 = file_get_contents($sourceUrl2,false,$context);
	 
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);
   $dataArray2 = json_decode($sourceCode2, true);
    // 初始化返回对象
    $response = [];
    
    // 处理第一个视频数组
if (!empty($dataArray['response']['videos'])) {
    $videos = [];
    foreach ($dataArray['response']['videos'] as $video) {
		if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['video_id'],
            'title' => $video['video_title'],
            'image' => $video['video_thumb'],
            'views' => $video['video_views'],
            'date' => $video['video_upload_date'],
            'duration' => $video['video_duration']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
        if (isset($video['video_main_tag'][0]['info'])) {
            $videoItem['info'] = $video['video_main_tag'][0]['info'];
        }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
    }
    }
    
    // 设置 code 为 ok
    $response['code'] = 'ok';
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}

// 处理第二个视频数组
if (!empty($dataArray2['response']['videos'])) {
    foreach ($dataArray2['response']['videos'] as $video) {
		if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['video_id'],
            'title' => $video['video_title'],
            'image' => $video['video_thumb'],
            'views' => $video['video_views'],
            'date' => $video['video_upload_date'],
            'duration' => $video['video_duration']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
        if (isset($video['video_main_tag'][0]['info'])) {
            $videoItem['info'] = $video['video_main_tag'][0]['info'];
        }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
		}
    }
}

// 将处理过的视频数组命名为 videos
$response['videos'] = $videos;

 // 输出JSON格式数据
    header('Content-Type: application/json');
echo json_encode($response);

	

	
	
	
} else if(isset($_GET['id']) ) {
	
   
   
   
   
   // 初始化变量
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sourceUrl = $newurl . '/pwa/video/info/' . $id . '?' . $token;
$sourceUrl2 = $newurl . '/pwa/videos/recommend/' . $id . '?' . $token;

// 初始化 cURL 句柄
$ch = curl_init();

// 设置第一个 URL 的 cURL 选项
curl_setopt($ch, CURLOPT_URL, $sourceUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Platform: h5']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// 执行 cURL 请求并获取返回结果
$sourceCode = curl_exec($ch);

// 检查是否有错误发生
if ($sourceCode === false) {
    echo 'cURL 错误: ' . curl_error($ch);
}

// 关闭 cURL 句柄
curl_close($ch);

// 初始化另一个 cURL 句柄
$ch2 = curl_init();

// 设置第二个 URL 的 cURL 选项
curl_setopt($ch2, CURLOPT_URL, $sourceUrl2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Platform: h5']);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);

// 执行 cURL 请求并获取返回结果
$sourceCode2 = curl_exec($ch2);

// 检查是否有错误发生
if ($sourceCode2 === false) {
    echo 'cURL 错误: ' . curl_error($ch2);
}

// 关闭 cURL 句柄
curl_close($ch2);

// 解密函数
function decryptAES($sourceCode, $key, $iv) {
    $decodedCipherText = base64_decode($sourceCode);
    $decryptedText = openssl_decrypt($decodedCipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $decryptedText;
}

// 执行解密
$decryptedText = decryptAES($sourceCode, 'ae52f7ffd2dd66ba5743bb180188b991', '8f00b204e9800998');

// 定义要匹配的正则表达式模式
$pattern = '/":(.*?)}}/';

// 进行正则匹配
preg_match($pattern, $decryptedText, $matches);

// 提取匹配结果
$result = isset($matches[1]) ? $matches[1] : '';
$sourceCode = '{"status":{"code":' .  $result.'}}';

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 设置 code 属性
$response['code'] = isset($dataArray['status']['code']) ? $dataArray['status']['code'] : '';

// 处理视频信息
if (!empty($dataArray['response'])) {
    $response['id'] = $dataArray['response']['video_id'];
    $response['title'] = $dataArray['response']['video_title'];
    $response['image'] = $dataArray['response']['thumb'];
    if (isset($dataArray['response']['video_urls']['intro'])) {
        $response['video'] = 'https://streamaws.dwozs.com' . $dataArray['response']['video_urls']['intro'] . '?' . $token;
    }
    $response['code'] = 'ok'; // 设置 code 为 ok
} else {
    $response['code'] = 'null'; // 视频信息为空，设置 code 为 null
}

// 解析第二个请求的响应
$dataArray2 = json_decode($sourceCode2, true);

// 修改视频信息
if (!empty($dataArray2['response']['videos'])) {
    $recommend = [];
    foreach ($dataArray2['response']['videos'] as $video) {
		if($video['video_main_tag'][0]['info'] !='VIP'){
        $modifiedVideo = [
            'id' => $video['video_id'],
            'title' => $video['video_title'],
            'image' => $video['video_thumb'],
            'views' => $video['video_views'],
            'date' => $video['video_upload_date'],
            'duration' => $video['video_duration']
        ];
        if (isset($video['video_main_tag'][0]['info'])) {
            $modifiedVideo['info'] = $video['video_main_tag'][0]['info'];
        }
        $recommend[] = $modifiedVideo;
	}
    }
    $response['recommend'] = $recommend;
}

// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
	

	
}

?>
