<?php

// 获取所有分类 https://api.yujiameimei.com/madouzq/madou.php?getsort

// 获取某个分类下视频列表   https://api.yujiameimei.com/madouzq/madou.php?sort=/index.php/vod/type/id/1&page=1

// 搜索  https://api.yujiameimei.com/madouzq/madou.php?keyword=美女&page=3

// 获取视频详情  https://api.yujiameimei.com/madouzq/madou.php?id=/index.php/vod/play/id/9566/sid/1/nid/1.html


/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);


function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "https://f2dzy2.com/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/URL=(.*?)\/1.php/s', $hostCode, $hostmatches);
        $domain = $hostmatches[1];
		$redirectUrl = $domain;
        //$initialUrl =  "https://a.91selfie.com/1.php";
       // $headers = get_headers($initialUrl, 1);
        //$redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $redirectUrl);

        // 返回获取到的重定向 URL
        return $redirectUrl;
    }
}
	
	$latestdomain="https://91md.me";
    //发布地址 https://www.7000.me/
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
    // $redirectUrl = getRedirectUrl($initialUrl);


	//构建目标链接
	$sourceUrl = $latestdomain ;
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	
    // 利用正则表达式截取文本
    preg_match('/网站首页(.*?)<\/ol>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
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
           // preg_match('/href="(.*?)"/', $category, $idMatches);
            //$id = isset($idMatches[1]) ? $idMatches[1] : '';

            // 提取标题
           preg_match('/href="(.*?)">(.*?)<span>/s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $id = isset($matches[1]) ? $matches[1] : '';
           $title = isset($matches[2]) ? trim($matches[2]) : '';
		  $id = str_ireplace('.html','',$id);
		   


            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
                // 构建分类对象
                $categoryObject = [
                   // 'count' => $count,
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
	
	//$redirectUrl = getRedirectUrl($initialUrl);
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		//if($page==1){
			$sourceUrl = "{$latestdomain}{$category}/page/$page.html";
		//}else{
		//	 $sourceUrl = "{$latestdomain}{$category}index_{$page}.html";
		//} 
       
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
   // $sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/detail_right_span(.*?)<\/ul>/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</li>', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		 
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/title=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/<i>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<strong>(.*?)</s', $video, $datamatches);
        //$dianzan = $datamatches[1]; 
        $views = $datamatches[1]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'views' => $views,
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			'date' => $date
			
            
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

    //$redirectUrl = getRedirectUrl($initialUrl);
// 获取参数
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
    $sourceUrl = $latestdomain . "/index.php/vod/search/page/$page/wd/$keyword.html"; 


// 获取源码
$sourceCode = file_get_contents($sourceUrl, false, $context);
 //$sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
 
preg_match('/detail_right_span(.*?)text-center/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“留言”分割得到数组
    $videos = explode('</li>', $content);

    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		 
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/title=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/<i>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<strong>(.*?)</s', $video, $datamatches);
        //$dianzan = $datamatches[1]; 
        $views = $datamatches[1]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 构建视频对象
        $videoObject = [
            'id' => $id,
            'image' => $image,
            'title' => $title,
            'views' => $views,
            'date' => $date
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
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
	header('Content-Type: application/json');
    echo json_encode(['code' => "普通用户，间隔30秒再搜索"]);
}

}elseif(isset($_GET['id'])) {
	
	//$redirectUrl = getRedirectUrl($initialUrl);
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain  .  $videoId ;
        // 获取源码
        $sourceCode = file_get_contents($sourceUrl,false,$context);

// 将文本a中的第一和第二个“<a href=”替换为空
   // $sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match("/class=\"title\">(.*?)</s", $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
         
        // 从源码中截取图片地址
       // preg_match("/var picurl = '(.*?)'/", $sourceCode, $imageMatches);
        //$imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        preg_match("/},\"url\":\"(.*?)\"/s", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? str_replace('\\','',$videoMatches[1]) : '';
		
  
        

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                //'image' => $imageUrl,
                'video' => $videoUrl,
				 
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }
       
        // 获取推荐视频列表
        preg_match('/moreVideo(.*?)footer_logo/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('</li>', $recommendationsText);
            array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
             // 提取图片地址
        preg_match('/src="(.*?)"/s', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		 
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/title=\"(.*?)\"/s', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/<i>(.*?)</s', $recommendation, $durationMatches);
        $date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<strong>(.*?)</s', $recommendation, $datamatches);
        //$dianzan = $datamatches[1]; 
        $views = $datamatches[1]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';
		
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						'views' => $views,
                       // 'playtimes' => $playtimes,
						'date' => $date,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                
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
