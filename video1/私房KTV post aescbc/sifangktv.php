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

    
	$latestdomain =  'https://api1.fang2023api002.com';
	
	
	



   //echo $latestdomain . "<br>" . $imagedomain. "<br>" . $videoDomain. "<br>" . $searchDomain ;
   // $latestdomain = 'https://json-schem.kpehty.com';
	//$imagedomain = 'https://bstatic.164695.com/exclusive/';
   // $token = 'token=eyJ1c2VyX2lkIjo1MzEwODExNTQsImxhc3Rsb2dpbiI6MTcxNDY3MDI3M30.6ad32bb0477d5109b1097e46e4abf8ba.b55fd7f53f48c6707b5b8886e7bf1df14f417cd8dcfdf5cfe1bf949e';
	//$videoDomain =  'https://bspbf.657924.com:56443/exclusive/';
	//$searchDomain =  'https://bjk.kpehty.com';
	
	
	



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



$response['code'] = 'ok';

 
       array_push($videos1,
    [
        "id" => "73",
        "title" => "热门"
    ],
    [
        "id" => "169",
        "title" => "海角乱伦"
    ],
    [
        "id" => "70",
        "title" => "夫妻互换"
    ],
    [
        "id" => "142",
        "title" => "多人群p"
    ],
    [
        "id" => "182",
        "title" => "捉奸在床"
    ],
    [
        "id" => "146",
        "title" => "母子"
    ],
    [
        "id" => "147",
        "title" => "父女"
    ],
    [
        "id" => "148",
        "title" => "兄妹"
    ],
    [
        "id" => "149",
        "title" => "姐弟"
    ],
    [
        "id" => "156",
        "title" => "岳母"
    ],
    [
        "id" => "150",
        "title" => "儿媳"
    ],
    [
        "id" => "152",
        "title" => "舅侄"
    ],
    [
        "id" => "151",
        "title" => "嫂子"
    ],
    [
        "id" => "153",
        "title" => "爷孙"
    ],
    [
        "id" => "155",
        "title" => "师生"
    ],
    [
        "id" => "154",
        "title" => "小姨子"
    ],
    [
        "id" => "157",
        "title" => "全家乱伦"
    ],
    [
        "id" => "113",
        "title" => "迷奸强奸"
    ],
    [
        "id" => "158",
        "title" => "调教SM"
    ],
    [
        "id" => "71",
        "title" => "偷拍窥伺"
    ],
    [
        "id" => "65",
        "title" => "黑料吃瓜"
    ]
);

array_push($videos2,
    [
        "id" => "1",
        "title" => "精选专区"
    ],
    [
        "id" => "162",
        "title" => "原创微剧"
    ],
    [
        "id" => "79",
        "title" => "户外露出"
    ],
    [
        "id" => "86",
        "title" => "AI换脸"
    ],
    [
        "id" => "87",
        "title" => "经典三级"
    ],
    [
        "id" => "129",
        "title" => "少女娇喘"
    ],
    [
        "id" => "184",
        "title" => "反差母狗"
    ],
    [
        "id" => "180",
        "title" => "白虎巨乳"
    ],
    [
        "id" => "111",
        "title" => "女同蕾丝"
    ],
    [
        "id" => "109",
        "title" => "摄像破解"
    ],
    [
        "id" => "64",
        "title" => "成人综艺"
    ],
    [
        "id" => "77",
        "title" => "韩国主播"
    ],
    [
        "id" => "179",
        "title" => "大屌萌妹"
    ],
    [
        "id" => "201",
        "title" => "泄密外流"
    ],
    [
        "id" => "200",
        "title" => "NTR绿帽"
    ],
    [
        "id" => "199",
        "title" => "真实约炮"
    ],
    [
        "id" => "198",
        "title" => "偷情出轨"
    ]
);

array_push($videos3,
    [
        "id" => "3",
        "title" => "国产精选"
    ],
    [
        "id" => "63",
        "title" => "国产最新"
    ],
    [
        "id" => "41",
        "title" => "网红主播"
    ],
    [
        "id" => "44",
        "title" => "情侣自拍"
    ],
    [
        "id" => "62",
        "title" => "网黄女神"
    ],
    [
        "id" => "80",
        "title" => "车震野战"
    ],
    [
        "id" => "167",
        "title" => "街边爱情"
    ],
    [
        "id" => "197",
        "title" => "按摩会所"
    ],
    [
        "id" => "82",
        "title" => "熟女少妇"
    ],
    [
        "id" => "106",
        "title" => "勾引搭讪"
    ],
    [
        "id" => "43",
        "title" => "探花偷拍"
    ],
    [
        "id" => "166",
        "title" => "舔逼大神"
    ],
    [
        "id" => "108",
        "title" => "自慰潮喷"
    ]
);
		
		array_push($videos4,
    [
        "id" => "2",
        "title" => "华语原创"
    ],
    [
        "id" => "34",
        "title" => "91制片厂"
    ],
    [
        "id" => "35",
        "title" => "果冻传媒"
    ],
    [
        "id" => "93",
        "title" => "SA国际"
    ],
    [
        "id" => "164",
        "title" => "佳丽AV"
    ],
    [
        "id" => "165",
        "title" => "起点传媒"
    ],
    [
        "id" => "92",
        "title" => "杏吧传媒"
    ],
    [
        "id" => "91",
        "title" => "星空无限"
    ],
    [
        "id" => "90",
        "title" => "糖心Vlog"
    ],
    [
        "id" => "33",
        "title" => "麻豆传媒"
    ],
    [
        "id" => "37",
        "title" => "天美传媒"
    ],
    [
        "id" => "38",
        "title" => "蜜桃传媒"
    ],
    [
        "id" => "39",
        "title" => "皇家华人"
    ]
);

array_push($videos5,
    [
        "id" => "159",
        "title" => "暗网猎奇"
    ],
    [
        "id" => "178",
        "title" => "真实缅北"
    ],
    [
        "id" => "176",
        "title" => "暗网揭秘"
    ],
    [
        "id" => "160",
        "title" => "猎奇重口"
    ],
    [
        "id" => "161",
        "title" => "惊悚色情"
    ],
    [
        "id" => "137",
        "title" => "人兽杂交"
    ],
    [
        "id" => "177",
        "title" => "孕妇乳交"
    ],
    [
        "id" => "112",
        "title" => "校园霸凌"
    ],
    [
        "id" => "196",
        "title" => "调教男奴"
    ]
);

array_push($videos6,
    [
        "id" => "75",
        "title" => "制服最爱"
    ],
    [
        "id" => "97",
        "title" => "JK"
    ],
    [
        "id" => "98",
        "title" => "OL"
    ],
    [
        "id" => "102",
        "title" => "女仆"
    ],
    [
        "id" => "100",
        "title" => "护士"
    ],
    [
        "id" => "101",
        "title" => "旗袍"
    ],
    [
        "id" => "99",
        "title" => "空姐"
    ],
    [
        "id" => "174",
        "title" => "汉服"
    ],
    [
        "id" => "173",
        "title" => "校服"
    ],
    [
        "id" => "104",
        "title" => "死库水"
    ],
    [
        "id" => "103",
        "title" => "丝袜"
    ],
    [
        "id" => "185",
        "title" => "情趣"
    ],
    [
        "id" => "171",
        "title" => "Coser"
    ]
);

		
		array_push($videos7,
    [
        "id" => "4",
        "title" => "日韩欧美"
    ],
    [
        "id" => "141",
        "title" => "无码FC2"
    ],
    [
        "id" => "114",
        "title" => "中文字幕 "
    ],
    [
        "id" => "183",
        "title" => "水果派"
    ],
    [
        "id" => "46",
        "title" => "无码流出"
    ],
    [
        "id" => "115",
        "title" => "多P群交"
    ],
    [
        "id" => "117",
        "title" => "按摩SPA"
    ],
    [
        "id" => "186",
        "title" => "电车痴汉"
    ],
    [
        "id" => "120",
        "title" => "丝袜制服"
    ],
    [
        "id" => "118",
        "title" => "出轨侵犯"
    ],
    [
        "id" => "122",
        "title" => "强奸轮奸"
    ],
    [
        "id" => "119",
        "title" => "家庭乱伦"
    ],
    [
        "id" => "88",
        "title" => "韩国三级"
    ],
    [
        "id" => "125",
        "title" => "三级伦理"
    ],
    [
        "id" => "47",
        "title" => "欧美女神"
    ],
    [
        "id" => "187",
        "title" => "黑人巨根"
    ],
    [
        "id" => "170",
        "title" => "异域风情"
    ]
);

array_push($videos8,
    [
        "id" => "5",
        "title" => "少女动漫"
    ],
    [
        "id" => "49",
        "title" => "H动漫"
    ],
    [
        "id" => "50",
        "title" => "剧场番剧"
    ],
    [
        "id" => "51",
        "title" => "3D动画"
    ],
    [
        "id" => "52",
        "title" => "同人COS"
    ],
    [
        "id" => "189",
        "title" => "ACG成人"
    ],
    [
        "id" => "188",
        "title" => "动漫VR"
    ],
    [
        "id" => "181",
        "title" => "巨乳萝莉"
    ],
    [
        "id" => "128",
        "title" => "漫改作品"
    ],
    [
        "id" => "191",
        "title" => "无码动漫"
    ],
    [
        "id" => "194",
        "title" => "恋爱喜剧"
    ],
    [
        "id" => "190",
        "title" => "女王样"
    ],
    [
        "id" => "195",
        "title" => "母女丼"
    ]
);


// 将处理过的视频数组添加到$response['videos']
$response['sort'] ='热门-精选专区-国产精选-华语原创-暗网猎奇-制服最爱-日韩欧美-少女动漫';
$response['热门'] = $videos1;
$response['精选专区'] = $videos2;
$response['国产精选'] = $videos3;
$response['华语原创'] = $videos4;
$response['暗网猎奇'] = $videos5;
$response['制服最爱'] = $videos6;
$response['日韩欧美'] = $videos7;
$response['少女动漫'] = $videos8;


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
$sourceUrl = $latestdomain . "/api.php/api/mv/list_construct";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1
if ($category == 1 || $category == 2 || $category == 3 || $category == 4 || $category == 5 || $category == 159 || $category == 73 || $category == 75) {
    $params1 = array(
        "oauth_id" => "69848d3ae2f7764d3c0a80f42a9489c1",
        "bundleId" => "com.pwa.sfktv",
        "version" => "1.0.1",
        "oauth_type" => "web",
        "language" => "zh",
        "via" => "pwa",
        "token" => "",
        "id" => intval($category),
        "_hash" => intval($timestamp),
        "page" => intval($page),
        "limit" => 15
    );
} else {
    // 处理其他情况的代码
	
	$params1 = array(
        "oauth_id" => "69848d3ae2f7764d3c0a80f42a9489c1",
        "bundleId" => "com.pwa.sfktv",
        "version" => "1.0.1",
        "oauth_type" => "web",
        "language" => "zh",
        "via" => "pwa",
        "token" => "",
        "id" => intval($category),   
        "page" => intval($page),
        "limit" => 15,
		"sort" => "new"
		
    );
	
}



// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1
$encryption_key = '2acf7e91e9864673'; // 密钥
$encryption_iv = '1c29882d3ddfcfd6'; // 向量
$params1_encrypted = base64_encode(openssl_encrypt($params1_json, 'aes-128-cbc', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}5589d41f92a597d016b037ac37db243d";

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
    $decrypted_data = openssl_decrypt(base64_decode($sourceCode), 'aes-128-cbc', '2acf7e91e9864673', OPENSSL_RAW_DATA, '1c29882d3ddfcfd6');
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
            'image' => $video['cover_horizontal'],
            'duration' => $video['duration'],
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
	
	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
    //if ($page == 1) {
        // 定义接口地址
$sourceUrl = $latestdomain . "/api.php/api/mv/search";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "oauth_id" => "69848d3ae2f7764d3c0a80f42a9489c1",
        "bundleId" => "com.pwa.sfktv",
        "version" => "1.0.1",
        "oauth_type" => "web",
        "language" => "zh",
        "via" => "pwa",
        "token" => "", 
        "page" => intval($page),
        "limit" => 18,
		"word" => $keyword,
		"type" => 1
    );




// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1
$encryption_key = '2acf7e91e9864673'; // 密钥
$encryption_iv = '1c29882d3ddfcfd6'; // 向量
$params1_encrypted = base64_encode(openssl_encrypt($params1_json, 'aes-128-cbc', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}5589d41f92a597d016b037ac37db243d";

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
    $decrypted_data = openssl_decrypt(base64_decode($sourceCode), 'aes-128-cbc', '2acf7e91e9864673', OPENSSL_RAW_DATA, '1c29882d3ddfcfd6');
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
if (!empty($dataArray['data'])) {
    $videos = [];
	 
    foreach ($dataArray['data'] as $video) {
		//if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $video['cover_horizontal'],
            'duration' => $video['duration'],
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
$sourceUrl = $latestdomain . "/api.php/api/mv/getDetail";

// 获取当前时间戳
$timestamp = time();
//$timestamp = "1716442908";


// 获取当前毫秒时间戳
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

// 构造参数1

    $params1 = array(
        "oauth_id" => "69848d3ae2f7764d3c0a80f42a9489c1",
        "bundleId" => "com.pwa.sfktv",
        "version" => "1.0.1",
        "oauth_type" => "web",
        "language" => "zh",
        "via" => "pwa",
        "token" => "", 
        "id"  => intval($id),
    );




// 将参数1转换为JSON字符串
$params1_json = json_encode($params1);

// 使用AES/CBC/PKCS7方式加密参数1
$encryption_key = '2acf7e91e9864673'; // 密钥
$encryption_iv = '1c29882d3ddfcfd6'; // 向量
$params1_encrypted = base64_encode(openssl_encrypt($params1_json, 'aes-128-cbc', $encryption_key, OPENSSL_RAW_DATA, $encryption_iv));

// 构造参数3
$params3 = "client=pwa&data={$params1_encrypted}&timestamp={$timestamp}5589d41f92a597d016b037ac37db243d";

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
    $decrypted_data = openssl_decrypt(base64_decode($sourceCode), 'aes-128-cbc', '2acf7e91e9864673', OPENSSL_RAW_DATA, '1c29882d3ddfcfd6');
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
            $video= str_replace("10play","long",$dataArray['data']['detail']['preview_url']);
			$new_url = preg_replace('/https:\/\/[^.]+\.bxmtrwl\.com/', 'https://long.bxmtrwl.com', $video);
			
			
            $response['preview_url'] = $dataArray['data']['detail']['preview_url'];
			$response['video'] = $new_url;
			
            $response['title'] = $dataArray['data']['detail']['title'];
            $response['image'] = $dataArray['data']['detail']['cover_horizontal'];
            $response['duration'] = $dataArray['data']['detail']['duration'];
            $response['date'] = $dataArray['data']['detail']['created_at'];
        
        
       
     
    
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


    // 输出JSON格式数据
   header('Content-Type: application/json');
   echo json_encode($response);
   
   
   
}

?>
