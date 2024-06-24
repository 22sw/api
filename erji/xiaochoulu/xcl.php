<?php

// 获取所有分类 http://api.yujiameimei.com/mogu/mogu.php?getsort
// 搜索  http://api.yujiameimei.com/mogu/mogu.php?keyword=美女&page=1

// 获取某个分类下视频列表   http://api.yujiameimei.com/mogu/mogu.php?sort=/index.php/vod/type/id/1&page=1
// 获取视频详情  http://api.yujiameimei.com/mogu/mogu.php?id=/index.php/vod/play/id/19756/sid/1/nid/1.html

function getlatestdomain($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "http://hsck.net/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
        $domain = $hostmatches[1];
        $initialUrl = $domain . ":8899/?u=http://hsck.net/&p=/引用站点策略:strict-origin-when-cross-origin";
        $headers = get_headers($initialUrl, 1);
        $latestdomain = isset($headers['Location']) ? $headers['Location'] : '';

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
    }
}
	
	//$latestdomain = getlatestdomain($initialUrl);
	$latestdomain = "https://52.jokerlu-up.buzz";
     
	//https://jokerlu.com
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     

// 获取源码
$sourceCode = file_get_contents($latestdomain . '/vodtype/121/');

// 提取所有子菜单
preg_match('/class="menu4(.*?)menu119/s', $sourceCode, $sourceCodematches);
$sourceCode = $sourceCodematches[1];

preg_match_all('/class="cl-effect(.*?)<script>/s', $sourceCode, $matches);
$subMenus = $matches[1];

// 初始化结果数组
$国产传媒 = [];
$国产视频 = [];
$日本视频 = [];
$泰国视频 = [];
$欧美视频 = [];
$动画视频 = [];
$中文字幕 = [];

// 分别处理每个子菜单
foreach ($subMenus as $index => $subMenu) {
    if ($index < 7) {
        $categories = explode('</li>', $subMenu);
        foreach ($categories as $category) {
            preg_match('/href="(.*?)">(.*?)<\/a>/s', $category, $matches);
            $id = isset($matches[1]) ? $matches[1] : '';
			$id = rtrim($id, '/');
            $title = isset($matches[2]) ? trim($matches[2]) : '';
            if (strpos($id, "http") === false && !empty($id)) {
                $categoryObject = [
                    'id' => $id,
                    'title' => $title
                ];
                switch ($index) {
                    case 0:
                        $国产传媒[] = $categoryObject;
                        break;
                    case 1:
                        $国产视频[] = $categoryObject;
                        break;
                    case 2:
                        $日本视频[] = $categoryObject;
                        break;
                    case 3:
                        $泰国视频[] = $categoryObject;
                        break;
                    case 4:
                        $欧美视频[] = $categoryObject;
                        break;
                    case 5:
                        $动画视频[] = $categoryObject;
                        break;
                    case 6:
                        $中文字幕[] = $categoryObject;
                        break;
                }
            }
        }
    }
}

// 构建返回对象
$response = [
    'code' => (count($国产传媒) > 0 || count($国产视频) > 0 || count($日本视频) > 0 || count($泰国视频) > 0 || count($欧美视频) > 0 || count($动画视频) > 0 || count($中文字幕) > 0) ? 'ok' : null,
    'sort' => "国产传媒-国产视频-日本视频-泰国视频-欧美视频-动画视频-中文字幕",
    '国产传媒' => $国产传媒,
    '国产视频' => $国产视频,
    '日本视频' => $日本视频,
    '泰国视频' => $泰国视频,
    '欧美视频' => $欧美视频,
    '动画视频' => $动画视频,
    '中文字幕' => $中文字幕
];

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);



		
		
	
	
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	
	
    // 获取参数
	$category = isset($_GET['sort']) ? str_replace("$","&",$_GET['sort']) : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		 
		$sourceUrl = $latestdomain . $category . "-$page/" ;
	//echo $sourceUrl;
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);

	// 利用正则表达式截取文本
	preg_match('/recommended-grids english-grid(.*?)clearfix/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
//echo $content;
    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('ews</p>', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';
         
		 
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace('/index.php','',$id);
		
        // 提取标题
        preg_match('/title">(.*?)</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/right">(.*?)</', $video, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/views">(.*?)vi/s', $video, $datamatches);
         
        $views =  $duration = isset($datamatches[1]) ? $datamatches[1] : '';
        $date = $duration = isset($datamatches[2]) ? $datamatches[2] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
     if (!empty($id) ) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
			//'dianzan' => $dianzan,
            //'views' => $views,
			'date' => $views .'次播放'
			
            
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
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

    
    // 获取参数
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
    $sourceUrl = $latestdomain . "/vodsearch/$keyword----------$page---/";
    

    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
   // 利用正则表达式截取文本
	preg_match('/recommended-grids english-grid(.*?)clearfix/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
 
    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('ews</p>', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';
         
		 
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace('/index.php','',$id);
		 
        // 提取标题
        preg_match('/title">(.*?)</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/right">(.*?)</', $video, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/views">(.*?)vi/s', $video, $datamatches);
         
        $views =  $duration = isset($datamatches[1]) ? $datamatches[1] : '';
        $date = $duration = isset($datamatches[2]) ? $datamatches[2] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (!empty($id) ) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
			//'dianzan' => $dianzan,
            //'views' => $views,
			'date' => $views.'次播放'
			
            
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



}elseif(isset($_GET['id'])) {
	
	
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id']: '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain .'/index.php' . $videoId;

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);

// 将文本a中的第一和第二个“<a href=”替换为空
    //$sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/<h3>(.*?)</', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match('/\/"><img src="(.*?)"/', $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        preg_match('/":"","url":"(.*?)"/s', $sourceCode, $videoMatches);
		//$videoUrl = isset($videoMatches[1]) ? str_replace('O0O0OO0O0O','',$videoMatches[1]) : '';
        //$videoUrl = base64_decode($videoUrl);
        $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
		
		
         $dplayurl = $latestdomain. '/addons/dplayer/?url=' .$videoUrl ;
		  $dplayCode =  file_get_contents($dplayurl);
		  
		   // 从源码中截取视频地址
        preg_match('/urls = "(.*?)"/s', $dplayCode, $videoMatches);
		 
        $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
		 
		 

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
               'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/recommended-grids english-grid(.*?)clearfix/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            
            // Split by 'views'
            $recommendations = explode('ews</p>', $recommendationsText);
			// 移除最后一个元素
            //array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
        preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace('/index.php','',$id);
		 
        // 提取标题
        preg_match('/title">(.*?)</', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/<p>(.*?)</', $recommendation, $durationMatches);
        $date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/views">(.*?)vi/s', $recommendation, $datamatches);
         
        $views =   isset($datamatches[1]) ? $datamatches[1] : ''; 
		            if(!empty($id)){
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                        'views' => $views,
						'date' => $date,
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


?>
