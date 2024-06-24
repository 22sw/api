<?php

// 获取所有分类 https://api.yujiameimei.com/qisemao/qsm.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/qisemao/qsm.php?sort=149&page=1
// 搜索  https://api.yujiameimei.com/qisemao/qsm.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/qisemao/qsm.php?id=10906



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

$latestdomain = 'https://api.fswnp.cn';

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
   
   
   // 定义分类名称与对应数字索引的映射关系
    $categories = [
        '小编推荐' => 192, 
        '全网最热视频' => 125,
		'最热' => 'zuire',
		'最新' => 'zuixin',
		//稀有
        '顶臀强摸' => 177,
        '精品男同' => 168,
        '真实乱伦' => 190,
        '真实迷奸' => 191,
        '街射涂鸦' => 178,
        '女厕偷拍' => 174,
        '神奇妓院' => 149,
        '人妖伪娘' => 171,
        '足交足控' => 172,
        '"ASMR颅内高潮' => 180,
		//国产
		'国产传媒AV' => 126,
        '全网最热视频' => 125,
        '国产乱伦"' => 118,
        '香艳职场' => 140,
        '自拍偷拍' => 123,
        '良家反差婊' => 151,
		'国产自拍' => 122,
        '国产主播' => 120,
        '福利姬' => 135,
		'女神自慰' => 137 ,
        '经典三级' =>  121,
		//日韩
        '水果派-中文解说' => 132,
		'日本无码' => 119,
        '中文字幕' => 136,
        '强奸迷奸"' => 128,
        '制服诱惑' => 129,
        '日本乱伦' => 134,
        '日本人妻' => 193,
		 '群P群交' => 195,
		'日本女优' => 117,
        '日本有码' => 130,
		'角色剧情' => 194,
        '韩国演艺圈偷拍' => 154,
        
		//重口
        'SM重度调教' => 135,
        '人与兽' => 186,
		'虐阴脱垂' => 182,
        '呕吐排泄' => 183,
        '暗黑触手"' => 169,
        '惊悚猎奇' => 153,
		//欧美
        '欧美性爱' => 131,
        '激情女同' => 133,
		'欧美乱伦' => 167,
        '神奇妓院' => 149,
		'金钱万能' => 152,
		//动漫
        '精品动漫' => 127,
        '3D动漫"' => 147,
        '真人coser' => 148
		
       
		
		
		
    ];

    // 初始化分类数组
    $categoriesArray = [];

    // 遍历分类名称与数字索引的映射关系，构建ID和Title的形式
    foreach ($categories as $title => $index) {
        $categoriesArray[] = [
            'id' => $index,
            'title' => $title
        ];
    }

    // 构建返回对象
    $response = [
        'code' => !empty($categoriesArray) ? 'ok' : 'null',
        'categories' => $categoriesArray
    ];

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);




} else if (isset($_GET['sort']) && isset($_GET['page'])) {
	
    $category = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 根据不同的参数构建不同的请求 URL 和请求参数
if ($category === 'zuixin' || $category === 'zuire') {
    $url = "$latestdomain/api/home/$category";
    $postData = http_build_query([
        'limit' => 10,
        'page' => $page
    ]);
} else {
    $url = "$latestdomain/api/home/video_list";
    $postData = http_build_query([
        'page' => $page,
        'limit' => 10,
		'sort' =>  1,
        'category_id' =>  $category
		


    ]);
}

// 设置 cURL 选项
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 忽略证书验证
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// 添加请求头
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'appauthorization:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvcWlzZW1hby54eXoiLCJhdWQiOiJxaXNlbWFvX3VzZXIiLCJpYXQiOjE3MTQ3MDM3MzgsIm5iZiI6MTcxNDcwMzczOCwiZXhwIjoyMTQ1ODg4MDAwLCJ1c2VyIjp7InVpZCI6MTkzNzQ1MTR9fQ.fV63wvSzpT418wegizDgwqRFYLCvaM07Ntg_VvRqWhc'
]);

// 执行 cURL 请求并获取返回结果
$responseJson = curl_exec($ch);
 
// 关闭 cURL 句柄
curl_close($ch);

// 将返回的 JSON 数据解析为 PHP 数组
$responseArray = json_decode($responseJson, true);

// 初始化返回对象
$response = [
    'code' => 'null',
    'videos' => []
];

// 处理返回结果
if (!empty($responseArray['result']['list'])) {
    // 遍历 result-list 数组下的每个成员
    foreach ($responseArray['result']['list'] as $item) {
        // 提取所需信息
        $videoItem = [
            'id' => $item['id'],
            'title' => $item['title'],
            'image' => $item['thumb'],
            'video' =>$result = substr($item['path'], 0, strpos($item['path'], '?')) , 
            'total_time' => $item['total_time'],
            'create_time' => $item['create_time']
        ];
        // 将提取的信息添加到 videos 数组中
        $response['videos'][] = $videoItem;
    }
    // 如果 videos 不为空，则设置 code 为 'ok'
    $response['code'] = 'ok';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);






	
	
	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	
	
     $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;


    $url = "$latestdomain/api/home/search";
    $postData = http_build_query([
        'page' => $page,
        'limit' => 10,
		'sort' =>  1,
        'key_word' =>  $keyword
		
    ]);


// 设置 cURL 选项
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 忽略证书验证
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// 添加请求头
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'appauthorization:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvcWlzZW1hby54eXoiLCJhdWQiOiJxaXNlbWFvX3VzZXIiLCJpYXQiOjE3MTQ3MDM3MzgsIm5iZiI6MTcxNDcwMzczOCwiZXhwIjoyMTQ1ODg4MDAwLCJ1c2VyIjp7InVpZCI6MTkzNzQ1MTR9fQ.fV63wvSzpT418wegizDgwqRFYLCvaM07Ntg_VvRqWhc'
]);

// 执行 cURL 请求并获取返回结果
$responseJson = curl_exec($ch);
 
// 关闭 cURL 句柄
curl_close($ch);

// 将返回的 JSON 数据解析为 PHP 数组
$responseArray = json_decode($responseJson, true);

// 初始化返回对象
$response = [
    'code' => 'null',
    'videos' => []
];

// 处理返回结果
if (!empty($responseArray['result']['list'])) {
    // 遍历 result-list 数组下的每个成员
    foreach ($responseArray['result']['list'] as $item) {
        // 提取所需信息
        $videoItem = [
            'id' => $item['id'],
            'title' => $item['title'],
            'image' => $item['thumb'],
            'video' =>$result = substr($item['path'], 0, strpos($item['path'], '?')) , 
            'total_time' => $item['total_time'],
            'create_time' => $item['create_time']
        ];
        // 将提取的信息添加到 videos 数组中
        $response['videos'][] = $videoItem;
    }
    // 如果 videos 不为空，则设置 code 为 'ok'
    $response['code'] = 'ok';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
	
} else if(isset($_GET['id']) ) {
    
	
	
	$id = isset($_GET['id']) ? $_GET['id'] : ''; 


    $url = "$latestdomain/api/home/video_info";
    $postData = http_build_query([
        'video_id' => $id 
		
    ]);


// 设置 cURL 选项
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 忽略证书验证
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// 添加请求头
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'appauthorization:eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvcWlzZW1hby54eXoiLCJhdWQiOiJxaXNlbWFvX3VzZXIiLCJpYXQiOjE3MTQ3MDM3MzgsIm5iZiI6MTcxNDcwMzczOCwiZXhwIjoyMTQ1ODg4MDAwLCJ1c2VyIjp7InVpZCI6MTkzNzQ1MTR9fQ.fV63wvSzpT418wegizDgwqRFYLCvaM07Ntg_VvRqWhc'
]);

// 执行 cURL 请求并获取返回结果
$responseJson = curl_exec($ch);
 
// 关闭 cURL 句柄
curl_close($ch);

// 将返回的 JSON 数据解析为 PHP 数组
$responseArray = json_decode($responseJson, true);

// 初始化返回对象
$response = [
    'code' => 'null',
   //'videos' => []
];

// 处理返回结果
if (!empty($responseArray['result']['video_info'])) {
        // 提取所需信息
		
            $response['id'] = $responseArray['result']['video_info']['id'];
            $response['title'] = $responseArray['result']['video_info']['title'];
            $response['image'] = $responseArray['result']['video_info']['thumb'];
            $response['video'] = substr($responseArray['result']['video_info']['path'], 0, strpos($responseArray['result']['video_info']['path'], '?')) ;
            $response['total_time'] = $responseArray['result']['video_info']['total_time'];
            $response['create_time'] = $responseArray['result']['video_info']['create_time'];
    // 如果 videos 不为空，则设置 code 为 'ok'
    $response['code'] = 'ok';
}






// 处理推荐视频数组
if (!empty($responseArray['result']['video_list'])) {
    $videos = [];
    foreach ($responseArray['result']['video_list'] as $video) {
        // 修改键名
        $videoItem = [
           'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['thumb'],
            'video' => substr($video['path'], 0, strpos($video['path'], '?')) , 
            'total_time' => $video['total_time'],
            'create_time' => $video['create_time']
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
    // 将处理过的视频数组命名为 videos
    $response['recommend'] = $videos;
    // 设置 code 为 ok
    $response['code'] = 'ok';
}

// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
}

?>
