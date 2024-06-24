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

    
	$latestdomain =  'https://wmqapi.ttjclofat.net';
	
	
	



   //echo $latestdomain . "<br>" . $imagedomain. "<br>" . $videoDomain. "<br>" . $searchDomain ;
   // $latestdomain = 'https://json-schem.kpehty.com';
	//$imagedomain = 'https://bstatic.164695.com/exclusive/';
   // $token = 'token=eyJ1c2VyX2lkIjo1MzEwODExNTQsImxhc3Rsb2dpbiI6MTcxNDY3MDI3M30.6ad32bb0477d5109b1097e46e4abf8ba.b55fd7f53f48c6707b5b8886e7bf1df14f417cd8dcfdf5cfe1bf949e';
	//$videoDomain =  'https://bspbf.657924.com:56443/exclusive/';
	//$searchDomain =  'https://bjk.kpehty.com';
	
	
	
// 使用AES/CBC/PKCS7方式加密参数1
$encryption_key = 'cc88ddc9357ff461e08f047aedee692b'; // 密钥
$encryption_iv = 'e89225cfbbimgkcu'; // 向量


// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    
	


// 初始化返回对象
$response = [];

// 处理视频数组
$videos1 = [];
$videos2= [];
$videos3 = [];
$videos4= [];
$videos5 = [];
$videos6= [];
$videos7 = [];
$videos8= [];
$videos9 = [];
$videos10= [];
$videos11 = [];
$videos12= [];

$response['code'] = 'ok';

 
    // 获取文件内容
$fileContent = file_get_contents('sort.txt');

// 检查文件是否成功读取
if ($fileContent === false) {
    die('无法读取文件内容');
}

// 执行读取的内容作为PHP代码
eval($fileContent);


// 将处理过的视频数组添加到$response['videos']
$response['sort'] ='推荐-微密圈-发现-排行-原创-黑料-乱伦-国产-猎奇-日韩-动漫-欧美';
$response['推荐'] = $videos1;
$response['微密圈'] = $videos2;
$response['发现'] = $videos11;
$response['排行'] = $videos12;
$response['原创'] = $videos3;
$response['黑料'] = $videos4;
$response['乱伦'] = $videos5;
$response['国产'] = $videos6;
$response['猎奇'] = $videos7;
$response['日韩'] = $videos8;
$response['动漫'] = $videos9;
$response['欧美'] = $videos10;

// 设置 code 为 ok 或 null
    $response['code'] = !empty($videos8) ? 'ok' : 'null';
	
// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
    
	// 获取GET参数
$category = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 定义接口地址
$sourceUrl = $latestdomain . "/pwa.php";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1
if (is_numeric($category)) {
    // 处理数字类型的 $category
    if (in_array($category, [1, 2, 3, 5, 6, 7])) {
        $params1 = array(
            "oauth_type" => "pwa", 
            "oauth_id" => "m2OXtufTBXY7oCbl_1717127127523",
            "version" => "2.0.0",
            "mod" => "index",
            "code" => "hotLists",
            "type" => $category,
            "page" => intval($page)
        );
		 
    } else {
        // 处理其他数字类型的 $category
        $params1 = array(
            "oauth_type" => "pwa", 
            "oauth_id" => "m2OXtufTBXY7oCbl_1717127127523",
            "version" => "2.0.0",
            "mod" => "element",
            "code" => "getElementItembyId",
            "id" => intval($category),
            "type" => "new",
            "page" => intval($page)
        );
		 
    }
} else {
    // 处理非数字类型的 $category
    $params1 = array(
        "oauth_type" => "pwa", 
        "oauth_id" => "m2OXtufTBXY7oCbl_1717127127523",
        "version" => "2.0.0",
        "mod" => "index",
        "code" => "listByTag",
        "tags" => $category,
        "type" => "new",
        "page" => intval($page)
    );
	 
}

// 这里是对 $params1 的处理代码



// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1

$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}cc88ddc9357ff461e08f047aedee692b";

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
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
  
// 检查 $category 的类型和范围
if (is_numeric($category)) {
    if (in_array($category, [1, 2, 3, 5, 6, 7])) {
        $dataList = $dataArray['data']; // 对应于 $dataArray['data']
    } else {
        $dataList = $dataArray['data']['list']; // 对应于 $dataArray['data']['list']
    }
} else {
    $dataList = $dataArray['data']['items']; // 对应于 $dataArray['data']['items']
}

// 处理数据
if (!empty($dataList)) {
    $videos = [];
    
    foreach ($dataList as $video) {
        //if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['thumb'],
            'duration' => $video['durationStr'],
            'playUrl' => $video['playUrl'],
            'date' => $video['created_at']
        ];

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
        //}
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
$sourceUrl = $latestdomain . "/pwa.php";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "oauth_type" => "pwa",
        "oauth_id" => "T7rFuR3DOOM6Ev8u_1717123884300",
        "version" => "2.0.0",
        "mod" => "index",
        "code" => "search",
        "type" => 6, 
        "key" => $keyword ,
        "page" => intval($page)
    );
 


// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1

$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}cc88ddc9357ff461e08f047aedee692b";

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
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data'])) {
    $videos = [];
	 
    foreach ($dataArray['data'] as $video) {
		//if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['thumb'],
            'duration' => $video['durationStr'],
            'playUrl' => $video['playUrl'],
            'date' => $video['created_at']
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
$sourceUrl = $latestdomain . "/pwa.php";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "oauth_type" => "pwa",
        "oauth_id" => "m2OXtufTBXY7oCbl_1717127127523",
        "version" => "2.0.0",
        "mod" => "index",
        "code" => "detail", 
        "id"  => intval($id),
    );

 

// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1
$params1_encrypted = strtoupper(bin2hex(openssl_encrypt($params1_json, 'aes-256-cfb', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv)));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}cc88ddc9357ff461e08f047aedee692b";

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
     

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data'])) {
    
	// 设置 code 为 ok
    $response['code'] = 'ok';
    
		 
        // 构建视频信息数组
            $video= str_replace("10play","long",$dataArray['data']['previewUrl']);
			$new_url = preg_replace('/https:\/\/[^.]+\.bxmtrwl\.com/', 'https://long.bxmtrwl.com', $video);
			
			
            $response['preview_url'] = $dataArray['data']['previewUrl'];
			$response['video'] = $new_url;
			
            $response['title'] = $dataArray['data']['title'];
            $response['image'] = $dataArray['data']['thumb'];
            $response['duration'] = $dataArray['data']['durationStr'];
            $response['date'] = $dataArray['data']['created_at'];
        
        
       
     
    
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


    // 输出JSON格式数据
   header('Content-Type: application/json');
   echo json_encode($response);
   
   
   
}

?>
