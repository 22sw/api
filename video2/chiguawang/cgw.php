<?php

// 获取所有分类 http://api.yujiameimei.com/hlbdy/hlbdy.php?getsort
// 搜索  http://api.yujiameimei.com/hlbdy/hlbdy.php?keyword=美女&page=1

// 获取某个分类下视频列表   http://api.yujiameimei.com/hlbdy/hlbdy.php?sort=/category/6.html&page=1
// 获取视频详情  http://api.yujiameimei.com/hlbdy/hlbdy.php?id=/archives/49972.html




function getSource($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 忽略证书
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ));

    $source = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $source;
}

	
    $latestDomain = "https://cgh10.top";
 
 //发布地址 https://51cg56.me
 
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
  



	//构建目标链接
	$sourceUrl = $latestDomain. "/front/api/v1/channels/list";
    // 获取源码
	
	 $sourceCode = getSource($sourceUrl);
 
    // 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
	$categories[] = [
		"id"=>666,
		'type' => 666,
		"title" =>"首页"
		];
	
	
    foreach ($dataArray['data'] as $category) {
        // 初始化子分类的 id 字符串
         
        
        // 遍历子分类，将 id 连接起来
        //foreach ($category['child'] as $child) {
        //    $childIds .= $child['id'] . ',';
        //}
        
        // 去除最后一个逗号
       // $childIds = rtrim($childIds, ',');
        
        // 构建分类项
		//if($category['title']!=='极品幼幼'){
        $categoryItem = [
            'id' => $category['id'],
			'type' => $category['type'],
            'title' => $category['name']
        ];
      //  }
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
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	 
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	 if($category ===666){
	 $sourceUrl = $latestDomain . "/front/api/v1/posts/listByChannel?pageNo={$page}&pageSize=10&total=5006";
		 
	 }else{
		 $sourceUrl = $latestDomain . "/front/api/v1/posts/listByChannel?pageNo={$page}&pageSize=10&total=0&channelId={$category}";
	 }
	
	// 构建目标网站的 URL
		
		
	 
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
    //echo $sourceCode;////
	 // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data']['content'])) {
        $videos = [];
		
		
		
        foreach ($dataArray['data']['content'] as $video) {
            // 修改键名
			//if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['id']  ,
                'title' => $video['title'],
                 'image' => $video['thumbnail'],
                'views' => $video['views'],
				'video' => $video['videoUrl'],
                'sort' => $video['channel']['name'],
                'date' => date("Y-m-d H:i:s", $video['created']/1000),
                'description' => $video['summary']
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


} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {
    
	//header('Content-Type: application/json');
    
    // 获取参数
    $keyword = isset($_GET['keyword']) ? urlencode ($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 构建目标网站的 URL
		
		$sourceUrl = $latestDomain .  "/front/api/v1/search?pageNo=" . $page . "&pageSize=10&kw=" .$keyword;
	  
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
     
	 // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    // 处理视频数组
    if (!empty($dataArray['data']['content'])) {
        $videos = [];
		
		
		
        foreach ($dataArray['data']['content'] as $video) {
            // 修改键名
			//if ($video['isvip']!= 1) {
            $videoItem = [
                'id' => $video['id']  ,
                'title' => $video['title'],
                'image' => $video['thumbnail'],
                'views' => $video['views'],
				'video' => $video['videoUrl'],
                'sort' => $video['channel']['name'],
                'date' => date("Y-m-d H:i:s", $video['created']/1000),
                'description' => $video['summary']
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






}elseif(isset($_GET['id'])) {
	
	
	
function processData($data) {
    // 处理content中的图片链接
    $content = $data['data']['content'];
    $pattern = '/!\[\]\((.*?)\)/';
    preg_match_all($pattern, $content, $matches);
    
    $imageCodes = [];
    foreach ($matches[1] as $imageUrl) {
        $imageCodes[] = '<p><img src="' . $imageUrl . '" alt="" style="max-width:100%;"></p>';
    }
    $content = preg_replace($pattern, '<p><img src="$1" alt="" style="max-width:100%;"></p>', $content);

    // 处理videoUrl
    $videoUrls = explode(",", $data['data']['videoUrl']);
    $videoCodes = [];
    foreach ($videoUrls as $index => $videoUrl) {
        $videoCodes[] = "视频" . ($index + 1) . '<video width="100%" height="" src="' . trim($videoUrl) . '" type="video/mp4" poster="https://img.9a34b7.com/2024/1/15/31956444011c04398d6070f212ea9086.jpg" autoplay="autoplay" controls="controls" loop="-1"><p></p></video>';
    }
    $videoOutput = implode(",", $videoCodes);

    // 更新数据
    $data['data']['content'] = $content;
    $data['data']['videoUrl'] = $videoOutput;

    return [
        'data' => $data,
        'imageCodes' => $imageCodes,
        'videoCodes' => $videoCodes
    ];
}


 

    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
     
        $sourceUrl = $latestDomain . "/front/api/v1/posts/view/" . $videoId;

    $jsonData = file_get_contents($sourceUrl);

// 解码JSON数据
$data = json_decode($jsonData, true);

// 提取需要的字段
$content = $data['data']['content'];
$title = $data['data']['title'];
$videoUrls = $data['data']['videoUrl'];
$cover = $data['data']['thumbnail'];


// 删除 content 中第一个 --- 到第二个 --- 中间的文本
$content = preg_replace('/---.*?---/s', '', $content);

// 处理 content 字段中的图片链接
$pattern = '/!\[\]\((.*?)\)/';
$replacement = '<img src="$1" alt="" style="max-width:100%;">';
$modifiedContent = preg_replace($pattern, $replacement, $content);

// 提取所有图片链接并处理 totalImageUrl 字段
preg_match_all($pattern, $content, $matches);
$imageUrls = $matches[1];
$totalImageUrlArray = [];

foreach ($imageUrls as $imageUrl) {
    $totalImageUrlArray[] = '$' . $imageUrl . '@';
}

$totalImageUrl = implode("分割", $totalImageUrlArray);

// 处理 title 字段
$modifiedTitle = "<h1>" . $title . "</h1>";
$titleCode = $modifiedTitle;

// 处理 videoUrl 字段
$videoUrlArray = explode(",", $videoUrls);
$modifiedVideoUrls = [];
$totalVideoUrlArray = [];

foreach ($videoUrlArray as $index => $videoUrl) {
    $videoNumber = $index + 1;
    $modifiedVideoUrls[] = '视频' . $videoNumber . '<video width="100%" height="" src="' . $videoUrl . '" type="video/mp4" poster="' .$cover . '" autoplay="autoplay" controls="controls" loop="-1"> <p></p> </video>';
    $totalVideoUrlArray[] = '$' . $videoUrl . '@';
}

$modifiedVideoUrlString = implode("\n", $modifiedVideoUrls);
$totalVideoUrl = implode("分割", $totalVideoUrlArray);

// 生成最终的HTML代码
$contentCode = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Read TXT File</title>
</head>
<body>' . $titleCode . $modifiedContent . $modifiedVideoUrlString . '</body>
</html>';

// 构建返回对象
$response = [
    'code' => 'ok',
    'title' => $title,
    'video' => $totalVideoUrl,
    'image' => $totalImageUrl,
    'titleCode' => $titleCode,
    'picCode' => $modifiedContent,
    'imgcode' => $contentCode,
    'videocode' => $modifiedVideoUrlString
];

        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
}


?>
