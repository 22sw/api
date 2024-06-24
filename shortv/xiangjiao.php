<?php

// 获取所有分类 https://api.yujiameimei.com/xiangjiao/xj.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/xiangjiao/xj.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/xj.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/xiangjiao/xj.php?id=62634





        // 获取当前时间的十三位时间戳
        list($t1, $t2) = explode(' ', microtime());
        $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

        //$latestdomain = 'https://678actainrhb.cjvapixj.com';
        $latestdomain = 'https://ios.bxguwen.com';
        $cookie ='xxx_api_auth=6631653435663161653466643661353837643133373834343861636563353363';


// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
	    
		'推荐' => "reqlist",
        '点赞' => "topzan",
		'最新' => "topnew", 
		'播放' => "topplay",
		'评论' => "topcomment",
		'金币' => "topcoin"
		
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

    $specialCategories = ["topcoin", "topcomment", "topplay", "topnew", "topzan"]; // 定义特殊的分类ID数组
     
	
	
// 构建目标网站的 URL
if (in_array($category, $specialCategories) ) {
	  
$sourceUrl = "$latestdomain/minivod/{$category}-0-0-0-0-0-0-0-0-0-0-$page";
	  
}else{
	
	$sourceUrl = "$latestdomain/minivod/reqlist";
	 
} 
		
		
    //echo $sourceUrl;
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data']['rows'])) {
        $videos = [];
        foreach ($dataArray['data']['rows'] as $video) {
            // 修改键名
		//	if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['vodrow']['vodid'],
                'title' => $video['vodrow']['title'],
                'image' => $video['vodrow']['coverpic'],
                'date' => $video['vodrow']['createtime'],
				'upnum' => $video['vodrow']['upnum'],
                'downcount' => $video['vodrow']['downcount_total'],
                'commentcount' => $video['vodrow']['commentcount'],
                'playcount' => $video['vodrow']['playcount_total'],
                'nickname' => $video['user']['nickname'],
                'avatar' => $video['user']['avatar_url'], 
                'mobi' => $video['user']['mobi'],
                'uid' => $video['user']['uid']
            ];
			
			  
	
            // 添加到视频数组中
            $videos[] = $videoItem;
     //  }
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
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
    //if ($page == 1) {
        $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // } else {
	//    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // }
	
    $sourceCode = file_get_contents($sourceUrl,false,$context);
    
	
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
    
    // 处理视频数组
    if (!empty($dataArray['data']['vodrows'])) {
        $videos = [];
        foreach ($dataArray['data']['vodrows'] as $video) {
            // 修改键名
		//	if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['vodid'],
                'title' => $video['title'],
                'image' => $video['coverpic'],
                'createtime' => $video['createtime'],
                'isvip' => $video['isvip'],
                'islimit' => $video['islimit'],
                'islimitv3' => $video['islimitv3'],
                'score' => $video['scorenum'],
                'duration' => $video['duration'],
				//'date' => '时长：' . $video['duration'],
                'play_url' => $video['play_url'],
                'down_url' => $video['down_url']
            ];
			
			// 设置 date 键
    if ($video['isvip'] == 1 || $video['islimit'] == 1 || $video['islimitv3'] == 1) {
        $videoItem['date'] = 'Vip专享_时长 ' . $video['duration'];
    } else {
        $videoItem['date'] = '时长 ' . $video['duration'];
    }
	
	
            // 添加到视频数组中
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
    
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 设置请求的URL
$sourceUrl = "$latestdomain/minivod/reqplay/$id";



// 定义一个函数来执行cURL请求
function fetchData($url) {
    $ch = curl_init($url);
	global $cookie;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: okhttp/3.14.9",
        "Content-Type: application/json",
        "Cookie: $cookie" 
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $sourceCode = curl_exec($ch);
    curl_close($ch);
    return $sourceCode;
}

// 初始化返回对象
$response = [];

// 定义一个函数来处理返回的数据
function processResponse($sourceCode) {
    global $response; // 引用全局 $response 变量
    $dataArray = json_decode($sourceCode, true);
    
    // 检查是否存在 retcode 且其值为 1，表示请求失败
    if (isset($dataArray['retcode']) && $dataArray['retcode'] == 1) {
        return false; // 请求失败
    }
    
    // 正常处理返回的数据
    if (!empty($dataArray['data'])) {
        $videoUrl = $dataArray['data']['httpurl'];
        if ($videoUrl === null) {
            $videoUrl = $dataArray['data']['httpurl_preview'];
        }
        $response['video'] = $videoUrl;
        $response['cookie'] = "xxx_api_auth=" . $dataArray['data']['xxx_api_auth'];
        return true; // 请求成功
    }
    
    $response['code'] = 'null';
    return false; // 数据为空
}

// 第一次请求
$sourceCode = fetchData($sourceUrl);
$success = processResponse($sourceCode);

// 如果第一次请求失败且 retcode 为 1，则尝试再次请求
if (!$success && isset(json_decode($sourceCode, true)['retcode']) && json_decode($sourceCode, true)['retcode'] == 1) {
    $sourceUrl = "$latestdomain/minivod/reqplay/$id";
    $sourceCode = fetchData($sourceUrl);
    $success = processResponse($sourceCode);
}

// 设置返回状态码
$response['code'] = $success ? 'ok' : 'error';

// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);


	
	
}

?>
