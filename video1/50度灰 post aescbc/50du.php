<?php

// 获取所有分类 https://api.yujiameimei.com/caoliu/caoliu.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/caoliu/caoliu.php?sort=1760182312696303617&page=2
// 搜索  https://api.yujiameimei.com/caoliui/caoliu.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/caoliu/caoliu.php?id=1778051363881811969



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        //'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        //"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        
        "Content-Type:text/plain". 
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0",
        
    ]
]);

    // 获取当前时间的十三位时间戳
    list($t1, $t2) = explode(' ', microtime());
    $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

    
	$latestdomain =  'https://api2.uixzqsfubx.com';
	

	
	



   //echo $latestdomain . "<br>" . $imagedomain. "<br>" . $videoDomain. "<br>" . $searchDomain ;
   // $latestdomain = 'https://json-schem.kpehty.com';
	//$imagedomain = 'https://bstatic.164695.com/exclusive/';
   // $token = 'token=eyJ1c2VyX2lkIjo1MzEwODExNTQsImxhc3Rsb2dpbiI6MTcxNDY3MDI3M30.6ad32bb0477d5109b1097e46e4abf8ba.b55fd7f53f48c6707b5b8886e7bf1df14f417cd8dcfdf5cfe1bf949e';
	//$videoDomain =  'https://bspbf.657924.com:56443/exclusive/';
	//$searchDomain =  'https://bjk.kpehty.com';
	
	
	$encryption_key = '7205a6c3883caf95b52db5b534e12ec3'; // 密钥
    $encryption_iv = '81d7beac44a86f43'; // 向量



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    
	$videos1 = [];
	
	array_push($videos1,
    [
        "id" => 73,
        "title" => "推荐"
    ],
    [
        "id" => 5465451,
        "title" => "制片厂"
    ],
    [
        "id" => 19,
        "title" => "今日头条"
    ],
    [
        "id" => 16,
        "title" => "最新"
    ],
    [
        "id" => 14,
        "title" => "少女"
    ],
    [
        "id" => 17,
        "title" => "偷窥"
    ],
    [
        "id" => 12,
        "title" => "SM"
    ],
    [
        "id" => 18,
        "title" => "人妖"
    ],
    [
        "id" => 13,
        "title" => "人兽"
    ],
    [
        "id" => 15,
        "title" => "恋物"
    ]
);


 $sourceUrl = $latestdomain . "/pwa.php/api/MvSearch/getStyle";

 
    $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => ""
    );
	
	
 
      

// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);



$params1_encrypted = bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}7205a6c3883caf95b52db5b534e12ec3";

// 使用SHA256加密参数3
$params4 = hash('sha256', $params3);

// 使用MD5加密参数4
$params5 = md5($params4);

// 构造参数7
$params7 = "client=pwa&timestamp={$timestamp}&data={$params1_encrypted}&sign={$params5}";

// 构造POST请求
$post_data = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $params7
    )
);


// 发送POST请求
$context  = stream_context_create($post_data);
$result = file_get_contents($sourceUrl, false, $context);





// 处理返回结果
$sourceCode1 = json_decode($result, true);
$sourceCode = $sourceCode1['data'];


//if (!empty($sourceCode)) {
    // 解密数据
    $decrypted_data = openssl_decrypt(hex2bin($sourceCode), 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv);
	$decrypted_data = trim($decrypted_data, '"');
$decrypted_data = str_replace('\\','',$decrypted_data);


	 
	  
	// echo $decrypted_data;
    // 解析JSON数据
	
    $dataArray = json_decode($decrypted_data, true);
    // 提取数据列表
    //$list = $decoded_data['data']['list'];

// 初始化结果数组
$result = array(
    "code" => "ok",
	
    "sort" => "首页",
	"首页" => $videos1,
);

 

// 遍历每个分类
foreach ($dataArray['data'] as $category) {
    // 提取分类名称
    $categoryName = $category['name'];
    $result[$categoryName] = array();
    
    // 更新 sort 字段
    if ($result['sort'] !== "") {
        $result['sort'] .= "-";
    }
    $result['sort'] .=  $categoryName;
    
    // 遍历分类下的每个子分类
    foreach ($category['child'] as $child) {
        // 提取子分类的 id 和 name
        $childId = $child['id'];
        $childName = $child['name'];
        
        // 将子分类信息存入相应的分类数组中
        $result[$categoryName][] = array(
            'id' => $childId,
            'title' => $childName
        );
    }
}

// 输出结果
 // 输出JSON格式数据
   header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);


	
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
    
	// 获取GET参数
$category = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 定义接口地址
//$sourceUrl = $latestdomain . "/pwa.php/api/MvList/style";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1
if(  $category == 19 || $category == 16 || $category == 14 || $category == 17 || $category == 12 || $category == 18 || $category == 13 || $category == 15){
	$sourceUrl = $latestdomain . "/pwa.php/api/MvList/featured";
	 $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "", 
		"page" =>    intval($page),
		"tabId" => intval($category)
		
    );
	
}elseif($category == 73 ){
	$sourceUrl = $latestdomain . "/pwa.php/api/MvList/recommend";
	 $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "", 
		"page" =>    intval($page),
		"_t" => 1 
    );
	
}elseif($category == 5465451 ){
	$sourceUrl = $latestdomain . "/pwa.php/api/MvList/featuredzpc";
	 $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "", 
		"page" =>    intval($page),
		"_t" => 1
    );
	
}else{
	$sourceUrl = $latestdomain . "/pwa.php/api/MvList/style";
    $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "",
		"id" =>  intval($category),
		"orderBy" => "id",
		"page" =>    intval($page),
		"size" => 15
		
    );
}



// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1

$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}7205a6c3883caf95b52db5b534e12ec3";

// 使用SHA256加密参数3
$params4 = hash('sha256', $params3);

// 使用MD5加密参数4
$params5 = md5($params4);

// 构造参数7
$params7 = "client=pwa&timestamp={$timestamp}&data={$params1_encrypted}&sign={$params5}";

// 构造POST请求
$post_data = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $params7
    )
);


// 发送POST请求
$context  = stream_context_create($post_data);
$result = file_get_contents($sourceUrl, false, $context);

 


// 处理返回结果
$sourceCode1 = json_decode($result, true);
$sourceCode = $sourceCode1['data'];

//if (!empty($sourceCode)) {
    // 解密数据
    $decrypted_data = openssl_decrypt(hex2bin($sourceCode), 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv);
	$decrypted_data = trim($decrypted_data, '"');
$decrypted_data = str_replace('\\','',$decrypted_data);

    // 解析JSON数据
	
    $dataArray = json_decode($decrypted_data, true);
    // 提取数据列表
    //$list = $decoded_data['data']['list'];

    // 初始化视频数组
    //$videos = array();
    // 提取每个列表项的 id, title, cover_horizontal, created_at, duration
	
    // 将获取的 JSON 数据转换为 PHP 数组
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data']['list'])) {
    $videos = [];
	 
    foreach ($dataArray['data']['list'] as $video) {
		//if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
		
		    
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['thumb_cover'],
            'created_date' => $video['created_date'],
            'date' => $video['duration_str']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
       // if (isset($video['video_main_tag'][0]['info'])) {
       //     $videoItem['info'] = $video['video_main_tag'][0]['info'];
       // }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
   // }
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
        // 定义接口地址
// 定义接口地址
$sourceUrl = $latestdomain . "/pwa.php/api/MvSearch/video";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "",
		"keyword" =>  $keyword, 
		"page" =>    intval($page),
		"size" => 15
		
    );




// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1

$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}7205a6c3883caf95b52db5b534e12ec3";

// 使用SHA256加密参数3
$params4 = hash('sha256', $params3);

// 使用MD5加密参数4
$params5 = md5($params4);

// 构造参数7
$params7 = "client=pwa&timestamp={$timestamp}&data={$params1_encrypted}&sign={$params5}";

// 构造POST请求
$post_data = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $params7
    )
);


// 发送POST请求
$context  = stream_context_create($post_data);
$result = file_get_contents($sourceUrl, false, $context);

 


// 处理返回结果
$sourceCode1 = json_decode($result, true);
$sourceCode = $sourceCode1['data'];

//if (!empty($sourceCode)) {
    // 解密数据
    $decrypted_data = openssl_decrypt(hex2bin($sourceCode), 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv);
	$decrypted_data = trim($decrypted_data, '"');
$decrypted_data = str_replace('\\','',$decrypted_data);

    // 解析JSON数据
	
    $dataArray = json_decode($decrypted_data, true);
    // 提取数据列表
    //$list = $decoded_data['data']['list'];

    // 初始化视频数组
    //$videos = array();
    // 提取每个列表项的 id, title, cover_horizontal, created_at, duration
	
    // 将获取的 JSON 数据转换为 PHP 数组
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data']['list'])) {
    $videos = [];
	 
    foreach ($dataArray['data']['list'] as $video) {
		//if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            //'id' => $video['preview_video'];
			 
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['thumb_cover'],
            'created_date' => $video['created_date'],
            'date' => $video['duration_str']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
       // if (isset($video['video_main_tag'][0]['info'])) {
       //     $videoItem['info'] = $video['video_main_tag'][0]['info'];
       // }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
   // }
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

	

	
	
	
} else if(isset($_GET['id']) ) {
	
   
   

   // 初始化变量
$id = isset($_GET['id']) ? $_GET['id'] : '';

//$sourceUrl = $newurl . '/json/' . $id . '.html?t=s2';
//echo $sourceUrl;
$sourceUrl = $latestdomain . "/pwa.php/api/MvDetail/detail";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "system_oauth_type" => "pwa",
        "system_oauth_id" => "zSwgc7651BjLmeV3_1716463664423",
        "system_oauth_new_id" => "",
        "system_version" => "3.0.0",
        "system_app_type" => "",
        "system_build" => "",
        "system_build_id" => "",
		"id" =>    intval($id)
    );




// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1
$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}7205a6c3883caf95b52db5b534e12ec3";

// 使用SHA256加密参数3
$params4 = hash('sha256', $params3);

// 使用MD5加密参数4
$params5 = md5($params4);

// 构造参数7
$params7 = "client=pwa&timestamp={$timestamp}&data={$params1_encrypted}&sign={$params5}";

// 构造POST请求
$post_data = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $params7
    )
);


// 发送POST请求
$context  = stream_context_create($post_data);
$result = file_get_contents($sourceUrl, false, $context);

 


// 处理返回结果
$sourceCode1 = json_decode($result, true);
$sourceCode = $sourceCode1['data'];

//if (!empty($sourceCode)) {
    // 解密数据
    $decrypted_data = openssl_decrypt(hex2bin($sourceCode), 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv);
	$decrypted_data = trim($decrypted_data, '"');
$decrypted_data = str_replace('\\','',$decrypted_data);
    // 解析JSON数据
	  
    $dataArray = json_decode($decrypted_data, true);
    // 提取数据列表
    //$list = $decoded_data['data']['list'];

    // 初始化视频数组
    //$videos = array();
    // 提取每个列表项的 id, title, cover_horizontal, created_at, duration
	
    // 将获取的 JSON 数据转换为 PHP 数组
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data']['detail'])) {
    
	// 设置 code 为 ok
    $response['code'] = 'ok';
    
		 
        // 构建视频信息数组
            $video= $dataArray['data']['detail']['preview_video'];
			$new_url = preg_replace('/https:\/\/[^.]+\./', 'https://long.', $video);
			
			
            $response['preview_video'] = $dataArray['data']['detail']['preview_video'];
			$response['video'] = $new_url;
			
            $response['title'] = $dataArray['data']['detail']['title'];
            $response['image'] = $dataArray['data']['detail']['thumb_cover_str'];
            $response['refresh_at'] = $dataArray['data']['detail']['refresh_at'];
            $response['date'] = $dataArray['data']['detail']['duration_str'];
        
        
       
     
    
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


    // 输出JSON格式数据
   header('Content-Type: application/json');
   echo json_encode($response);
   
   
   
}

?>
