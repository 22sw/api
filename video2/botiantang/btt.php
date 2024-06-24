<?php

// 获取所有分类 https://api.yujiameimei.com/sjdy/91dy.php?getsort
// 搜索  https://api.yujiameimei.com/sjdy/91dy.php?keyword=美女&page=2

// 获取某个分类下视频列表   https://api.yujiameimei.com/sjdy/91dy.php?sort=/wuyejuchang/lunli/&page=1
// 获取视频详情  https://api.yujiameimei.com/sjdy/91dy.php?id=/wuyejuchang/lunli/119135/player-0-0.html

// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
"User-Agent: Mozilla/5.0 (Linux; U; Android 4.0.2; en-us; Galaxy Nexus Build/ICL53F) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30\r\n" ,
    ]
]);


	


    //$domain = getRedirectUrl($initialUrl);
    $latestDomain = "https://bttzyw3.com";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
     // 获取分类接口
    $sourceUrl =  $latestDomain ;

    // 获取源码
   // $sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	//$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
    // 利用正则表达式截取文本
	
    preg_match('/分类<\/div>(.*?)<\/ul>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('a>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
           // preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		//$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
            preg_match('/href="(.*?)"/', $category, $idMatches);
            $id = isset($idMatches[1]) ? $idMatches[1] : '';
			$id = str_replace(".html","",$id);

            // 提取标题
            preg_match('/<span>(.*?)</', $category, $titleMatches);
            $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) && strpos($id, "vod") !== false) {
                // 构建分类对象
                $categoryObject = [
                    //'image' => $image,
                    'id' => $id,
                    'title' => $title
                ];

 
                // 将分类对象添加到结果数组中
                $result[] = $categoryObject;
            }
        }

        // 构建返回对象
        $response = [
            'code' => count($result) > 0 ? 'ok' : null,
            'categories' => $result
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
    // 获取参数
$category = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
 
	
	$sourceUrl = $latestDomain . $category . "-p-$page.html";
    
	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	//$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
	

// 利用正则表达式截取文本
preg_match('/page-header clearfix(.*?)kscont/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
   //$content = preg_replace('/text\/javascript" src="/', '', $content, 5);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('data-target=', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        //$image = $domain.$image ;
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';
        
        // 提取标题
        preg_match('/title">(.*?)</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		// 提取日期
        preg_match('/v-data-r">(.*?)</', $video, $dateMatches);
        $date = isset($dateMatches[1]) ? $dateMatches[1] : '';



        // 构建视频对象
		if(!empty($id)){
        $videoObject = [
            'image' => $image,
            'id' => $id,
			'date' => $date,
            'title' => $title
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }
    }
    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'videos' => $result
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // 如果未匹配到结果，则返回错误信息
    echo json_encode(['code' => null]);
}
} elseif(isset($_GET['id'])) {
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

    
     
        // 构建目标网站的 URL
        $sourceUrl = $latestDomain . $videoId;

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl, false, $context);
		$sourceCode = preg_replace('/type="text" value="/', '', $sourceCode, 1);
	     

       
   
     // echo $sourceCode;
        // 从源码中截取标题
        preg_match('/class="active">(.*?)<\/a>/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/type=\"text\" value=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : ''; 
		
		

        // 从源码中截取视频地址
        preg_match("/vPath = '(.*?)'/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ?  $videoMatches[1] : '';
      
        
		
		
        // 构建返回对象
        $response = [];
        if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
        } else {
            $response = [
                'code' => null
            ];
        }

        // 获取推荐视频列表
        preg_match('/row hot-recom(.*?)row m-hot/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            // 移除第一个 '<div class="' 
            //$recommendationsText = preg_replace('/<div class="/', '', $recommendationsText, 1);
            // Split by 'views'
            $recommendations = explode('item-video-container', $recommendationsText);
            // Get the actual count
            $actualCount = count($recommendations) - 1;
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ?( $domain . $imageMatches[1]) : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';

                preg_match('/title">(.*?)</', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
				
				preg_match('/v-data-r">(.*?)</', $recommendation, $dateMatches);
                $date = isset($dateMatches[1]) ? $dateMatches[1] : '';

                if (!empty($id) && strpos($image, "upload") !== false) {
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                }
            }
            // Add recommendations to the response
            $response['recommend'] = $recommendationList;
			
            // Return the updated response
			 header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Return the response without recommendations if not found
			 // 输出 JSON 格式数据
        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    
}
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

// 获取参数
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 构建目标网站的 URL
$sourceUrl = $latestDomain . "/?s=vod-search-wd-$keyword-p-$page.html";


// 获取源码
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	//$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
	

// 利用正则表达式截取文本
preg_match('/page-header clearfix(.*?)kscont/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
   //$content = preg_replace('/text\/javascript" src="/', '', $content, 5);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('data-target=', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        //$image = $domain.$image ;
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';
        
        // 提取标题
        preg_match('/title m-hide">(.*?)</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		// 提取日期
        preg_match('/v-data-r m-hide">(.*?)</', $video, $dateMatches);
        $date = isset($dateMatches[1]) ? $dateMatches[1] : '';



        // 构建视频对象
		if(!empty($id)){
        $videoObject = [
            'image' => $image,
            'id' => $id,
			'date' => $date,
            'title' => $title
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }
    }
    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'videos' => $result
    ];

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // 如果未匹配到结果，则返回错误信息
    echo json_encode(['code' => null]);
}

}


?>
