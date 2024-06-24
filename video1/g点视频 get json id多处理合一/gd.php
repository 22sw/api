<?php

// 获取所有分类 https://api.yujiameimei.com/gdian/gd.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/gdian/gd.php?sort=25&page=1
// 搜索  https://api.yujiameimei.com/gdian/gd.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/gdian/gd.php?id=52785



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.09\r\n" ,
        
    ]
]);

// 获取当前时间的十三位时间戳
list($t1, $t2) = explode(' ', microtime());
$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);



function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "https://g3f8d.com/api/Webapi_v1/index/getConfig";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/today_url":"(.*?)"/s', $hostCode, $hostmatches);
        $latestdomain = $hostmatches[1];
		$latestdomain = str_replace("\\","",$latestdomain);
		
       // $initialUrl = "https://0u2t9.lol";
        //$headers = get_headers($initialUrl, 1);
        //$redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		//echo $redirectUrl;
		//重定向的多个域名是数组
		// 确保 $redirectUrl 是一个字符串
		//if (is_array($redirectUrl)) {
		//    $redirectUrl = $redirectUrl[0];
		//}
       // $redirectUrl= rtrim($redirectUrl);

       // preg_match("~^(.*?)\/https:~", $redirectUrl, $matches);
		//$redirectUrl = $matches[1];
        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
    }
}




$latestdomain = getRedirectUrl($initialUrl);

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
   
  
    $sourceUrl = "$latestdomain/apiv2/labels";
   
    $sourceCode = file_get_contents($sourceUrl, false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data']['video'] as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['label_id'],
            'title' => $category['label_name'],
            'image' => $category['label_icon'],
            'label_type' => $category['label_type']
            
        ];
        $categories[] = $categoryItem;
   // }
    }
    // 将处理过的数组命名为 categories
    $response['categories'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);



} else if (isset($_GET['sort']) && isset($_GET['page'])) {
    $category = isset($_GET['sort']) ? $_GET['sort'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
    //if ($page == 1) {
    //    $sourceUrl = "$latestdomain/rar.ashx?tags=全部&action=getvideos&vtype=$category&pageindex=$page&pagesize=20";
    //} else {
        $sourceUrl = "$latestdomain/apiv2/video/search?is_av=0&sort=latest&page=$page&num=20&label_ids[]=$category";
    // }

	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data']['data'] as $category) {
		if ($category['need_vip']!=1 ){
        $categoryItem = [
            'id' => $category['movie_id'],
            'title' => $category['movie_name'],
            'image' => $category['movie_cover'],
			 'views' => $category['watch_count'],
			 'isvip' => $category['need_vip'],
			 'score' => $category['movie_score'],
            'date' => date('Y-m-d',$category['push_time']),
            'duration' => $category['movie_long']
        ];
        $categories[] = $categoryItem;
    }
   }
    // 将处理过的数组命名为 categories
    $response['videos'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	

    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    
	
	
	$sourceUrl = "$latestdomain/apiv2/video/search?is_av=0&keyword=$keyword&sort=latest&page=$page&num=10";
    // }https://g3f8d.com/apiv2/video/search?is_av=0&keyword=%E7%BE%8E%E5%A5%B3&sort=latest&page=1&num=10

	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data']['data'] as $category) {
		if ($category['need_vip']!=1 ){
        $categoryItem = [
            'id' => $category['movie_id'],
            'title' => $category['movie_name'],
            'image' => $category['movie_cover'],
			 'views' => $category['watch_count'],
			 'isvip' => $category['need_vip'],
			 'score' => $category['movie_score'],
            'date' => date('Y-m-d',$category['push_time']),
            'duration' => $category['movie_long']
        ];
        $categories[] = $categoryItem;
    }
    }
    // 将处理过的数组命名为 categories
    $response['videos'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);	
	
	
} else if(isset($_GET['id']) ) {
	
    $id = isset($_GET['id']) ? $_GET['id'] : '';

$options = []; // 这里应该定义 $options 变量，用于创建上下文流

// 创建上下文流
$context = stream_context_create($options);

// 发送 POST 请求并获取响应
$sourceCode = file_get_contents("$latestdomain/apiv2/video/$id", false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 设置 code 值
$response['code'] = 'null';

// 处理 videoinfo 对象
if (isset($dataArray['data'])) {
    $videoinfo = $dataArray['data'];
    $response['video'] =  isset($dataArray['data']['movie_m3u8_url'][0]['url']) ? $latestdomain . $dataArray['data']['movie_m3u8_url'][0]['url'] : '';
    $response['title'] = isset($videoinfo['movie_name']) ? $videoinfo['movie_name'] : '';
    $response['image'] = isset($videoinfo['movie_cover']) ? $videoinfo['movie_cover'] : '';
}

// 处理相关视频数据
$response['recommend'] = []; // 初始化相关视频数据

if (isset($dataArray['data']['suggestion_list'])) {
    $suggestion_list = $dataArray['data']['suggestion_list'];
    $categories = [];

    foreach ($suggestion_list as $video) {
		if ($video['need_vip']!=1 ){
        $videoItem = [
            'id' => $video['movie_id'],
            'title' => $video['movie_name'],
            'image' => $video['movie_cover'],
            'views' => $video['watch_count'],
            'isvip' => $video['need_vip'],
            'score' => $video['movie_score'],
            'date' => date('Y-m-d', $video['push_time']),
            'duration' => $video['movie_long']
        ];
        $categories[] = $videoItem;
    }
}
    // 将处理过的相关视频数据命名为 categories
    $response['recommend'] = $categories;

    // 设置 code 值
    $response['code'] = !empty($categories) ? 'ok' : 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
