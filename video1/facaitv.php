<?php

// 获取所有分类 https://api.yujiameimei.com/mogu/mg.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/mogu/mg.php?sort=34,36,35,37&page=1
// 搜索  https://api.yujiameimei.com/mogu/mg.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/mogu/mg.php?id=67391



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



$sortdomain = 'https://api.736136.com';
$latestdomain = 'https://spjk.zhbt01.com';
$typerword = 'xj';
$searchword = 'xjss';

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
   
  //此链接可获取分类  https://spjk.zhbt01.com/caiji/xj.php
   
   $sourceUrl = "$sortdomain/api/video/get/nav?id=70";
   
    $sourceCode = file_get_contents($sourceUrl, false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data'] as $category) {
        // 初始化子分类的 id 字符串
        //$childIds = '';
        
        // 遍历子分类，将 id 连接起来
        //foreach ($category['child'] as $child) {
        //    $childIds .= $child['id'] . ',';
        //}
        
        // 去除最后一个逗号
       // $childIds = rtrim($childIds, ',');
        
        // 构建分类项
		$id=$category['link'];
        $rightText = substr($id, strpos($id, "=") + 1);
		
        $categoryItem = [
            'id' => $rightText,
			'date' => $category['created_at'],
            'title' => $category['name']
        ];
        
        // 添加到分类数组中
        $categories[] = $categoryItem;
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
        $sourceUrl = "$latestdomain/caiji/xj.php?type=$category&p=$page";
   // } else {
    //    $sourceUrl = "$latestdomain/vod/listing-$category-0-0-0-0-0-0-0-0-$page?timestamp=$str_time";
    //}
 

    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
	$data = json_decode($sourceCode);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray[0]['data'])) {
        $videos = [];
        foreach ($dataArray[0]['data'] as $video) {
            // 修改键名
			//if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['address'],
                'title' => $video['title'],
                'image' => $video['img'],
                
                'date' => $video['time']
            ];
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
	
	
	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	
	
    $keyword = isset($_GET['keyword']) ? $_GET['keyword']  : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
    //if ($page == 1) {
        $sourceUrl = "$latestdomain/$searchword.php?type=$keyword&p=$page";
   // } else {   
	//    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // }
 
	
    $sourceCode = file_get_contents($sourceUrl,false,$context);
    
	
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
    
    // 处理视频数组
     if (!empty($dataArray[0]['data'])) {
        $videos = [];
        foreach ($dataArray[0]['data'] as $video) {
            // 修改键名
			//if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['address'],
                'title' => $video['title'],
                'image' => $video['img'],
                
                'date' => $video['time']
            ];
            // 添加到视频数组中
            $videos[] = $videoItem;
       // }
		 }
        // 将处理过的视频数组命名为 videos
        $response['videos'] = $videos;
        // 设置 code 为 ok
        $response['code'] = 'ok';
    }else {
        // 视频数组为空，设置 code 为 null
        $response['code'] = 'null';
    }

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
} else if(isset($_GET['id']) ) {
    
	
	
	$id = isset($_GET['id']) ? $_GET['id'] : '';

     
  

// 获取第一个URL的内容
$sourceUrl = $id;
$sourceCode = file_get_contents($sourceUrl);
 
 



// 初始化返回对象
$response = [];

// 设置 code
$response['code'] =  'ok' ;

// 处理视频数组
//if (!empty($dataArray['data'])) {
    // 从 data 对象中直接获取 httpurl 的值作为视频播放地址
    $response['video'] = $sourceCode ;
    $response['title'] = '';
    $response['image'] = '';
//}



// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
}

?>
