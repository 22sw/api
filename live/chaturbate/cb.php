<?php

    //  两个域名获取数据方式不一样
    //  https://chaturbate.com/
    //  https://chaturbate.com.tw/  
	
	//获取直播间 getsort=a 全部性别 f女性 m男性 c情侣 t变性人
	//https://api.yujiameimei.com/cblive/cb.php?getsort=a
	//获取直播地址
	//https://api.yujiameimei.com/cblive/cb.php?id=lau__1
	//中文主播【id是数字】 https://api.yujiameimei.com/live/chaturbate/cb.php?id=150579793
	//获取画质【可能只有第一个直播地址能取到画质】
	//https://api.yujiameimei.com/cblive/cb.php?url=https://edge18-nrt.live.mmcdn.com/live-hls/amlst:lau__1-sd-4911237e8d3c7e8a64514b2c7db34fefbd59337f4e4f1a1b51752d0650dd1999_trns_h264/playlist.m3u8
	
	
	/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
		"Content-Type:application/x-www-form-urlencoded\r\n".
		"Cookie:username=yinsu900; cbuid=c458dfaa-7c0d-de8c-1be2-8527c0d67188; id=yinsu900; cf_clearance=8G6xO7nYKJZ_5osxLTZZtX9aQqs9kDjsQoax1ikZA7w-1714404599-1.0.1.1-AJ6rM.tB5PMcEvdt3WL9r5kLLqoMlEXCOouyhKg472xZ_EuYE49uo_yT0syEZUViR6INuVuFdoDWacx5yM3vUg\r\n".
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.09\r\n" ,
        
    ]
]);

	
	// 格式化时间函数
function formatTime($seconds) {
    // 将秒数转换为分钟
    $minutes = round($seconds / 60);

    // 如果时间大于60分钟，则转换成小时
    if ($minutes >= 60) {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        return $hours . '小时 ' . $remainingMinutes . '分钟';
    } else {
        return $minutes . '分钟';
    }
}





// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
	    '全部' => "a",
		'中文主播' => "cn",
        '美女' => "f",
        '帅哥' => "m",
        '情侣' => "c",
        '人妖' => "t"
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
	
	$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    
	$currentTimestamp = time();
// 获取源码

if($sort==="cn"){
	
    $sourceUrl = 'https://chaturbate.com.tw/sc/data.php?method=sp';
	
}else{
	$sourceUrl = 'https://chaturbate.com.tw/sc/data.php?method=all';
}




$sourceCode = file_get_contents($sourceUrl);

// 解析 JSON 数据为数组
$data = json_decode($sourceCode, true);

// 初始化结果数组
$models = [];

// 遍历每个成员并更名键名
foreach ($data as $model) {
    // 如果 $sort 为空或者为 a，添加所有成员进数组
    if ( $sort === 'a') {
        $models[] = [
            'sex' => isset($model['g']) ? $model['g'] : '',
            'name' => isset($model['u']) ? $model['u'] : '',
			'title' => isset($model['u']) ? $model['u'] : '',
			'id' => isset($model['u']) ? $model['u'] : '',
            'image' => isset($model['u']) ? 'https://thumb.live.mmcdn.com/riw/' . $model['u'] .'.jpg': '',
            'description' => isset($model['r']) ? $model['r'] : '',
            'viewers' => isset($model['n']) ? $model['n'] : '',
            'duration' => isset($model['s']) ? formatTime($model['s']) : '',
			 'date' => isset($model['s']) ? formatTime($model['s']) : '',
            'age' => isset($model['a']) ? $model['a'] : '',
			 'location' => isset($model['l']) ? $model['l'] : '',
            'tag' => isset($model['t']) ? $model['t'] : '',
           
        ];
    }elseif($sort === 'cn'){
		
		$models[] = [
            'sex' => isset($model['g']) ? $model['g'] : '',
            'name' => isset($model['u']) ? $model['u'] : '',
			'title' => isset($model['u']) ? $model['u'] : '',
			'id' => isset($model['id']) ? $model['id'] : '',
			
	        'image' => "https://img.strpst.com/thumbs/{$currentTimestamp}/{$model['id']}_webp",
            //'image' => isset($model['u']) ? 'https://thumb.live.mmcdn.com/riw/' . $model['u'] .'.jpg': '',
            'description' => isset($model['r']) ? $model['r'] : '',
            'viewers' => isset($model['n']) ? $model['n'] : '',
            'duration' => isset($model['s']) ? formatTime($model['s']) : '',
			 'date' => isset($model['n']) ? $model['n'] . "个观众" : '',
            'age' => isset($model['a']) ? $model['a'] : '',
			 'location' => isset($model['l']) ? $model['l'] : '',
            'tag' => isset($model['t']) ? $model['t'] : '',
           
        ];
		
		
	} else {
        // 只有当 $model['g'] 等于 $sort 时才加入数组
        if (strpos($model['g'], $sort) !== false) {
            $models[] = [
                'sex' => isset($model['g']) ? $model['g'] : '',
                'name' => isset($model['u']) ? $model['u'] : '',
				'title' => isset($model['u']) ? $model['u'] : '',
				'id' => isset($model['u']) ? $model['u'] : '',
                'image' => isset($model['u']) ? 'https://thumb.live.mmcdn.com/riw/' . $model['u'] .'.jpg': '',
                'description' => isset($model['r']) ? $model['r'] : '',
                'viewers' => isset($model['n']) ? $model['n'] : '',
                'duration' => isset($model['s']) ? formatTime($model['s']) : '',
				'date' => isset($model['s']) ? formatTime($model['s']) : '',
                'age' => isset($model['a']) ? $model['a'] : '',
                'location' => isset($model['l']) ? $model['l'] : '',
                'tag' => isset($model['t']) ? $model['t'] : '',
            ];
        }
    }
}

// 构建返回结果
$response = [
    'code' => !empty($models) ? 'OK' : null,
    'videos' => $models
];

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);



} else if (isset($_GET['id'])) {
    $id = $_GET['id'];
	
	//如果$id为数字，则直接生成链接
	if (is_numeric($id) !==false){
		
		$response = [
        'code' =>  'ok',
	'video' => "https://edge-hls.doppiocdn.com/hls/{$id}/master/{$id}_auto.m3u8" 
        //'playlist' => $result
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
		
	}else{ 
	
        $sourceUrl =  "https://chaturbate.com.tw/streamapi/?modelname=$id";
        $sourceCode = file_get_contents($sourceUrl);
    
	
	
	 
        // 解析数据并构建返回结果
        $urls = explode('url', $sourceCode);
        $result = [];

        foreach ($urls as $url) {
            preg_match('/":"(.*?)"/', $url, $idMatches);
            $id = isset($idMatches[1]) ? $idMatches[1] : '';

            if (!empty($id) && strpos($id, "http") !== false) {
                $result[] = ['url' => $id];
            }
        }
    
	    preg_match('/url":"(.*?)"/', $sourceCode, $videoMatches);
            $video = isset($videoMatches[1]) ? $videoMatches[1] : '';
		
	
		
        $response = [
            'code' => !empty($result) ? 'ok' : null,
	    	'video' => $video ,
            'playlist' => $result
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
	
}
	
	
	
} else if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $sourceUrl =  $url;
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	 
	$qianzhui = str_replace('playlist.m3u8','',$url);
	
    //echo $sourceCode;
    // 解析数据并构建返回结果
    $urls = explode('mp4a', $sourceCode);
    $result = [];

    foreach ($urls as $url) {
        preg_match('/RESOLUTION=(\d+x\d+)/', $url, $hzMatches);
        $resolution = isset($hzMatches[1]) ? $hzMatches[1] : '';
        
        preg_match('/chunklist(.*?)m3u8/', $url, $idMatches);
        //$id = isset($idMatches[1]) ? $idMatches[1] : '';
		$id = isset($idMatches[1]) ? $qianzhui . 'chunklist' .$idMatches[1] .'m3u8': '';


        if (!empty($id) && strpos($id, "http") !== false) {
            $result[] = [
			'resolution' => $resolution,
			'url' => $id
			];
        }
    }

    $response = [
        'code' => !empty($result) ? 'ok' : null,
        'playlist' => $result
        
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "未传入参数 'getsort' 或 'id'";
}
?>
