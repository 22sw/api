<?php

// 获取所有分类 https://api.yujiameimei.com/daxiaojie/dxj.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/daxiaojie/dxj.php?sort=1003&page=1
// 搜索  https://api.yujiameimei.com/daxiaojie/dxj.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/daxiaojie/dxj.php?id=97515



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

$latestdomain = 'https://pew6.com';

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
   
  
    $sourceUrl = "$latestdomain/rar.ashx?action=menulist";
   
    $sourceCode = file_get_contents($sourceUrl, false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data'] as $category) {
		if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['id'],
            'title' => $category['title'],
            'type' => $category['des'],
            'parentid' => $category['parentid'],
            'isshow' => $category['isshow'],
            'isstrong' => $category['isstrong']
        ];
        $categories[] = $categoryItem;
    }
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
        $sourceUrl = "$latestdomain/rar.ashx?tags=全部&action=getvideos&vtype=$category&pageindex=$page&pagesize=20";
    // }
	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data'] as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['id'],
            'title' => $category['title'],
            'image' => $category['coverimg'],
            'yuming' => $category['yuming'],
            'video' => $category['yuming'] . $category['vurl'],
            'mp4' => $category['downvurl'],
			'date' => $category['updatedate']
        ];
        $categories[] = $categoryItem;
    }
   // }
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
    
	// 构建 POST 请求参数
$postData = http_build_query([
    'action' => 'search',
    'p' => $keyword,
    'pageindex' =>$page,
    'pagesize' => 12,
    'channelid' => 0
]);

// 设置选项，包括请求头和请求体，以及忽略 SSL 验证
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postData,
        'ignore_errors' => true,
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
];

// 创建上下文流
$context = stream_context_create($options);

// 发送 POST 请求并获取响应
$sourceCode = file_get_contents('https://pew6.com/rar.ashx', false, $context);

  
	 
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
    
    if (!empty($dataArray)) {
    $categories = [];
	
    foreach ($dataArray['data'] as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['id'],
            'title' => $category['title'],
            'image' => $category['imgurl'],
            'yuming' => $category['yuming'],
           // 'video' => $category['yuming'] . $category['vurl'],
            //'mp4' => $category['downvurl'],
			'date' => $category['times']
        ];
        $categories[] = $categoryItem;
    }
   // }
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
   
$postData = http_build_query([
    'action' => 'getvideodetails',
    'vid' => $id
]);

// 设置选项，包括请求头和请求体，以及忽略 SSL 验证
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postData,
        'ignore_errors' => true,
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
];

// 创建上下文流
$context = stream_context_create($options);

// 发送 POST 请求并获取响应
$sourceCode = file_get_contents('https://pew6.com/rar.ashx', false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 设置 code 值
$response['code'] = 'null';

// 处理 videoinfo 对象
if (isset($dataArray['data']['videoinfo'])) {
    $videoinfo = $dataArray['data']['videoinfo'];
    $response['title'] = isset($videoinfo['title']) ? $videoinfo['title'] : '';
    $response['video'] = (isset($dataArray['data']['xl']['xl1']) ? $dataArray['data']['xl']['xl1'] : '') . (isset($videoinfo['vurl']) ? $videoinfo['vurl'] : '') ;
    $response['mp4'] =  (isset($dataArray['data']['xl']['down']) ? $dataArray['data']['xl']['down'] : '') . (isset($videoinfo['downvurl']) ? $videoinfo['downvurl'] : '');
    $response['image'] = isset($videoinfo['coverimg']) ? $videoinfo['coverimg'] : '';
}

// 处理 xl 对象
if (isset($dataArray['data']['xl'])) {
    $xl = $dataArray['data']['xl'];
    $response['xl1'] = isset($xl['xl1']) ? $xl['xl1'] : '';
    $response['xl2'] = isset($xl['xl2']) ? $xl['xl2'] : '';
    $response['vip1'] = isset($xl['vip1']) ? $xl['vip1'] : '';
    $response['vip2'] = isset($xl['vip2']) ? $xl['vip2'] : '';
    $response['downurl'] = isset($xl['down']) ? $xl['down'] : '';  
}

// 处理相关视频数据
if (isset($dataArray['data']['likevideos'])) {
    $likevideos = $dataArray['data']['likevideos'];
    $categories = [];

    foreach ($likevideos as $video) {
        $videoItem = [
            'id' => isset($video['id']) ? $video['id'] : '',
            'title' => isset($video['title']) ? $video['title'] : '',
            'image' => isset($video['coverimg']) ? $video['coverimg'] : '',
            'yuming' => isset($video['yuming']) ? $video['yuming'] : '',
            'updatedate' => isset($video['updatedate']) ? $video['updatedate'] : '',
            'video' => isset($video['vurl']) ? $video['vurl'] : '',
            'downvurl' => isset($video['downvurl']) ? $video['downvurl'] : ''
        ];
        $categories[] = $videoItem;
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
