<?php

// 获取所有分类 https://api.yujiameimei.com/155/155.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/155/155.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/155.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/155/155.php?id=62634


 



	$latestdomain = "https://www.uaa003.com";
// 备用域名
// https://www.uaa.com  https://www.uaa001.com  https://www.uaa004.com

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    $sourceUrl = $latestdomain . "/api/video/app/video/categories";

    // 使用 cURL 进行请求
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $sourceUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $sourceCode = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $response = [
            'code' => 'error',
            'message' => 'Request Error: ' . curl_error($ch)
        ];
        echo json_encode($response);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // 检查 HTTP 响应代码
    if ($httpCode != 200) {
        $response = [
            'code' => 'error',
            'message' => 'HTTP Error Code: ' . $httpCode
        ];
        echo json_encode($response);
        exit;
    }

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 解析数据并修改键名
    if (!empty($dataArray)) {
        $categories = [];
        foreach ($dataArray['model'] as $category) {
            // if ($category['des'] != '成人图片' && $category['des'] != '情色小说') {
            $categoryItem = [
                'sortid' => $category['id'],
                'id' => $category['name'],
                'title' => $category['name'],
                'sort' => $category['sort'],
                'date' => $category['updateTime']
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
	
	
    $category = isset($_GET['sort']) ? urlencode($_GET['sort']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL

   
   if($category === urlencode("里番") || $category ===  urlencode("泡面番") ){
	   $sourceUrl = $latestdomain . "/api/video/app/video/search?category={$category}&keyword=&orderType=1&origin=3&page={$page}&searchType=1&size=32&tag=";
	   
   }elseif($category ===  urlencode("无码流出") || $category ===  urlencode("高清AV") ){
	   $sourceUrl = $latestdomain . "/api/video/app/video/search?category={$category}&keyword=&orderType=1&origin=2&page={$page}&searchType=1&size=32&tag=";
	   
   }else{
	   $sourceUrl = $latestdomain . "/api/video/app/video/search?category={$category}&keyword=&orderType=1&origin=1&page={$page}&searchType=1&size=32&tag=";
	   
   }
   
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);

	// 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['model']['data'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
		    'videoid' => $category['id'],
            'id' => $category['url'],
            'title' => $category['title'],
            'image' => $category['coverUrl'],
			  'sort' => $category['categories'],
			 
            'date' => $category['updateTime']
        ];
        $categories[] = $categoryItem;
   // }
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
	
	
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
  
$sourceUrl = "$latestdomain/api/video/app/video/search?category=&keyword=$keyword&orderType=0&origin=&page=$page&searchType=1&size=32&tag="; 

	 
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
 // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['model']['data'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
            'videoid' => $category['id'],
            'id' => $category['url'],
            'title' => $category['title'],
            'image' => $category['coverUrl'],
			 'sort' => $category['categories'],
			 
            'date' => $category['updateTime']
        ];
        $categories[] = $categoryItem;
   // }
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


// 发送 POST 请求并获取响应
//$sourceCode = file_get_contents("$latestdomain/apiv2/video/$id");

// 将获取的 JSON 数据转换为 PHP 数组
//$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 设置 code 值
$response['code'] = 'null';

// 处理 videoinfo 对象
//if (isset($dataArray['data'])) {
    $videoinfo = $dataArray['data'];
    $response['video'] =  $id;
    $response['title'] = '';
    $response['image'] =  '';
//}

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
