<?php

// 获取所有分类 https://api.yujiameimei.com/shenshimh/ssmh.php?getsort

// 获取某个分类下漫画列表   https://api.yujiameimei.com/shenshimh/ssmh.php?sort=cate-5.html&page=2

// 搜索  https://api.yujiameimei.com/shenshimh/ssmh.php?keyword=美女&page=2

// 获取漫画详情  https://api.yujiameimei.com/shenshimh/ssmh.php?id=/photos-gallery-aid-250361.html


/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);


function getRedirectUrl($hostUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "https://jmcomic1.ltd";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/<\/strong><\/span><br \/>\n(.*?)<br \/>/s', $hostCode, $hostmatches);
        $domain = $hostmatches[1];
		$latestdomain = $domain;
        //$initialUrl =  "https://a.91selfie.com/1.php";
       // $headers = get_headers($initialUrl, 1);
        //$latestdomain = isset($headers['Location']) ? $headers['Location'] : '';

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
		
    }
}

	// $latestdomain = getRedirectUrl($hostUrl);
	$latestdomain="https://www.hm09.lol";
    
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
   


	//构建目标链接
	$sourceUrl = $latestdomain . "/albums-index-cate-3.html";
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	$sourceCode =str_replace("</a>","</a>分割",$sourceCode);
    // 利用正则表达式截取文本
    preg_match('/首頁(.*?)論壇/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
 //echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('分割', $content);
        
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
			$id = str_replace("/albums-index-","",$id);

            // 提取标题
           preg_match('/<a[^>]*>\s*([^<]+)\s*<\/a>/', $category, $matches);

           // 提取匹配到的数字和文本内容 
           $title = isset($matches[1]) ? trim($matches[1]) : '';
		 //  $id = str_ireplace($redirectUrl . '/v.php?category=','',$id);
		   


            // 判断 id 不为空且 image 包含 "/categories/"
            if (strpos($id,"html")!= false ) {
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
	
	 
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
 
	// 构建目标网站的 URL
		 
			$sourceUrl = "{$latestdomain}/albums-index-page-$page-$category";
		  
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
   // $sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/gallary_wrap(.*?)f_left paginator/s', $sourceCode, $matches);

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
		if (strpos($image, 'https') === false) {
			$image = "https:" . $image;
		}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace("index","gallery",$id);
        // 提取标题
        preg_match('/title=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/創建於(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<i>(.*?)</s', $video, $datamatches);
        //$dianzan = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'views' => $duration,
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
        'list' => $result
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
    $sourceUrl = $latestdomain . "/search/index.php?q=$keyword&m=&syn=yes&f=_all&s=create_time_DESC&p=$page"; 


// 获取源码
$sourceCode = file_get_contents($sourceUrl, false, $context);
 //$sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
 
preg_match('/gallary_wrap(.*?)<\/ul>/s', $sourceCode, $matches);

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
		if (strpos($image, 'https') === false) {
			$image = "https:" . $image;
		}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace("index","gallery",$id);
        // 提取标题
        preg_match('/title=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/創建於(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';

        // 构建视频对象
        $videoObject = [
            'id' => $id,
            'image' => $image,
            'title' => $title,
            //'duration' => $duration,
           'date' => $date
        ];

        // 将视频对象添加到结果数组中
        $result[] = $videoObject;
    }

    // 构建返回对象
    $response = [
        'code' => count($result) > 0 ? 'ok' : null,
        'list' => $result
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
	
	 
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

        // 构建目标网站的 URL
        $sourceUrl = $latestdomain  .  $videoId ;
		
// 初始化 cURL 会话
$curl = curl_init();

// 设置 cURL 选项
curl_setopt_array($curl, array(
    CURLOPT_URL => $sourceUrl, // 设置要访问的 URL
    CURLOPT_RETURNTRANSFER => true, // 将响应作为字符串返回，而不是直接输出
    CURLOPT_FOLLOWLOCATION => true, // 跟随重定向
    CURLOPT_SSL_VERIFYPEER => false, // 禁用 SSL 证书验证（以防 SSL 证书无效）
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded', // 设置请求头 Content-Type
    ),
));

// 执行 cURL 请求并获取响应
$sourceCode = curl_exec($curl);

// 关闭 cURL 会话
curl_close($curl);

// 输出响应内容
 
// 将文本a中的第一和第二个“<a href=”替换为空
   // $sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match("/<h4 class=\"title\">(.*?)</s", $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
         
				

        // 从源码中截取图片地址
        preg_match("/imglist = (.*?);\"\)/s", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ?  str_replace('\\"','',$imageMatches[1])  : '';
		$imageUrl = str_replace("url: fast_img_host+","https:",$imageUrl);
		
		// 使用正则表达式匹配图片链接
		preg_match_all('/https?:\/\/\S+\.(?:jpg|png|jpeg)/', $imageUrl, $matches);

		// 提取的图片链接
		$imageLinks = $matches[0];

		// 遍历图片链接并添加字符 $ 前缀和字符 @ 后缀
		foreach ($imageLinks as &$link) {
		    $link = '$' . $link . '@';
		}
		// 输出处理后的图片链接
		 $image = implode('分割', $imageLinks);

     

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                //'title' => $title,
                //'image' => $imageUrl,
                'content' => $image,
				 
                'chapters' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }
       
        // 获取推荐视频列表
        preg_match('/stui-vodlist__bd clearfix(.*?)<\/ul>/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('</h4>', $recommendationsText);
            array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
        preg_match('/data-original="(.*?)"/s', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ? $latestdomain . $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/title="(.*?)"/s', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		//$date = isset($titleMatches[3]) ? $titleMatches[3] : '';  
	
	// 提取观看次数
        preg_match('/<i>(.*?)</s', $recommendation, $updateMatches);
        $date = isset($updateMatches[1]) ? $updateMatches[1] : '';
		
		// 提取观看次数
        preg_match('/<strong>(.*?)</s', $recommendation, $viewsMatches);
        $views = isset($viewsMatches[1]) ? $viewsMatches[1] : '';

                
        
		// 提取时长
        //preg_match('/duration">(.*?)</', $recommendation, $durationMatches);
        //$duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/text-right">(.*?)</', $recommendation, $datamatches);
        //$dianzan = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
        //$date = str_replace(' ','',$datamatches[1]); 
		$date = isset($datamatches[1]) ? $datamatches[1] : '';
		
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'views' => $views,
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
