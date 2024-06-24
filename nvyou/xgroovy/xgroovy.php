<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306
//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?sort=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1


  /// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        "Cookie:PHPSESSID=89fb9e7485a955d4a88b8d1c24fc9b3b; xg_exo_sub_id=100; xg_exo_sub_video_id=320420; kt_tcookie=1; _ga=GA1.1.330577956.1716990335; xg_country=cn; xg_cookie_accept=true; kt_ips=172.104.162.22%2C175.8.123.229; xg_real_country=sg; _ga_8QQJ5BC4PZ=GS1.1.1716990335.1.1.1716993709.0.0.0". 
        "Content-Type:text/plain". 
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0",
        
    ]
]);




// 解析 GET 请求参数
// 判断是否传入了参数
    $latestdomain ="https://cn.xgroovy.com";
	
	
if (isset($_GET['actress'])  ) {
	
// $redirectUrl = getRedirectUrl($initialUrl);
$page = $_GET['page'];

	//构建目标链接
	$sourceUrl = $latestdomain ."/pornstars/$page/";
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl);
	
    // 利用正则表达式截取文本
    preg_match('/list-models(.*?)pagination/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('mi trending', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
             preg_match('/src="(.*?)"/', $category, $imageMatches);
		     $image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
            preg_match('/videos">(.*?)</', $category, $NumberMatches);
            $Number = isset($NumberMatches[1]) ? TRIM($NumberMatches[1])  : '';

            // 提取id
           preg_match('/href="(.*?)"/s', $category, $matches);
		    $id = isset($matches[1]) ?str_replace($latestdomain,"",$matches[1]) : '';
			
		   // 提取标题
           preg_match('/title">(.*?)</s', $category, $matches);
		   $title = isset($matches[1]) ?  $matches[1]  : '';
		   


            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
                // 构建分类对象
                $categoryObject = [
                   // 'count' => $count,
                    'id' => $id,
					'image' => $image,
					'number' => $Number,
                    'title' => $title
                ];

                // 将分类对象添加到结果数组中
                $result[] = $categoryObject;
            }
        }

        // 构建返回对象
        $response = [
            'code' => count($result) > 0 ? 'ok' : null,
            'actresses' => $result
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
	
	
	


	
// 判断是否存在GET请求参数
}else if(isset($_GET['sort']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $category = $_GET['sort'];
    $page = $_GET['page'];

     
        $url = $latestdomain .$category.$page ."/" ;
        
		
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/list-videos(.*?)pagination/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('加入标签', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ?   $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/title">\s*(.*?)\s*</s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/duration">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';
		   
		   // 提取数量题
           preg_match("/is-hd hd(.*?)\"/s", $category, $qualitymatches);
		   $quality = isset($qualitymatches[1]) ?  trim($qualitymatches[1]) . 'p'   : '';

                // 检查图片是否为空bg-opacity-75">
               if (!empty($id)) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" => $id,
                        "image" => $image,
                        "date" => $number . "  " .$quality ,
						"title" => $title
                    );
                    $videos[] = $video;
                }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
        
    
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $keyword = $_GET['keyword'];
    $page = $_GET['page'];

       
      $url = $latestdomain ."/search/$keyword/$page/" ;
        
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/list-videos(.*?)pagination/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('加入标签', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ?   $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/title">\s*(.*?)\s*</s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/duration">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';
		   
		   // 提取数量题
           preg_match("/is-hd hd(.*?)\"/s", $category, $qualitymatches);
		   $quality = isset($qualitymatches[1]) ?  trim($qualitymatches[1]) . 'p'   : '';
		   
                // 检查图片是否为空bg-opacity-75">
               if (!empty($id)) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" => $id,
                        "image" => $image,
                        "date" => $number . "  " .$quality ,
						"title" => $title
                    );
                    $videos[] = $video;
                }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
    
}elseif(isset($_GET['id'])) {
    
	
	// 获取参数
    $id = isset($_GET['id']) ? $_GET['id'] : '';
	
	
	
	            
	




	
    
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain .  $id;
		
        // 获取源码
        $sourceCode = file_get_contents($sourceUrl,false,$context);
    //echo $sourceCode ;
	// 从源码中截取图片地址
       // preg_match("/video-holder(.*?)document.getElementById/", $sourceCode, $codeMatches);
      // $sourceCode = isset($codeMatches[1]) ? $codeMatches[1] : '';
		
		
// 将文本a中的第一和第二个“<a href=”替换为空
    //$sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/name": "(.*?)"/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
        
        // 从源码中截取图片地址
        preg_match("/thumbnailUrl\": \"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        //preg_match("/contentUrl\": \"(.*?)\"/", $sourceCode, $videoMatches);
        //$videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';


        if(strpos($sourceCode,'video_source_4" src="')!==false){
			preg_match("/video_source_4\" src=\"(.*?)\"/", $sourceCode, $videoMatches);
            $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
			
		}elseif(strpos($sourceCode,'video_source_3" src="')!==false){
			preg_match("/video_source_3\" src=\"(.*?)\"/", $sourceCode, $videoMatches);
            $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
			
		}elseif(strpos($sourceCode,'video_source_2" src="')!==false){
			preg_match("/video_source_2\" src=\"(.*?)\"/", $sourceCode, $videoMatches);
            $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
			
		}else{
			preg_match("/video_source_1\" src=\"(.*?)\"/", $sourceCode, $videoMatches);
            $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
			
		}
		$url = $videoUrl;
		// 获取最终URL

		
        //$Urlcode =  file_get_contents($videoUrl);
		
		// 从源码中截取视频地址
        //preg_match("/m3u8url =  '(.*?)'/", $Urlcode, $videoMatches);
       // $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
		
		 




function getFinalUrl($url, $cookies) {
    $ch = curl_init($url);

    // 设置初始cURL选项
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应而不是直接输出
    curl_setopt($ch, CURLOPT_HEADER, true); // 包括头部信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // 不自动跟随重定向
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // 最大重定向次数
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略证书验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 忽略证书验证
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0");

    $redirectUrl = $url;

    for ($i = 0; $i < 2; $i++) { // 处理两次重定向
        curl_setopt($ch, CURLOPT_URL, $redirectUrl);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies[$i]);

        // 执行cURL会话
        $response = curl_exec($ch);

        // 检查是否发生错误
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch) . "\n";
            curl_close($ch);
            return false;
        }

        // 获取响应的头部信息
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);

        // 检查是否有Location头部（重定向）
        $matches = [];
        if (preg_match('/^Location:\s*(.*)$/mi', $header, $matches)) {
            $newUrl = trim($matches[1]);
            $redirectUrl = $newUrl;
        } else {
            break;
        }
    }

    // 关闭cURL会话
    curl_close($ch);

    return $redirectUrl;
}

// 初始URL
$url = $videoUrl;

// 定义不同阶段的Cookie
$cookies = [
    "PHPSESSID=89fb9e7485a955d4a88b8d1c24fc9b3b; kt_tcookie=1; _ga=GA1.1.330577956.1716990335; xg_country=cn; xg_cookie_accept=true; kt_ips=172.104.162.22%2C175.8.123.229; xg_real_country=sg; _ga_8QQJ5BC4PZ=GS1.1.1716990335.1.1.1716994342.0.0.0", // 第一次重定向的Cookie
    "PHPSESSID=89fb9e7485a955d4a88b8d1c24fc9b3b; _ga=GA1.1.330577956.1716990335; xg_country=cn; xg_cookie_accept=true; xg_real_country=sg; _ga_8QQJ5BC4PZ=GS1.1.1716990335.1.1.1716994342.0.0.0" // 第二次重定向的Cookie
];

// 获取最终URL
$finalUrl = getFinalUrl($url, $cookies);








        
		
        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $finalUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/相关视频(.*?)<script>/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('ico-fav-0', $recommendationsText);
            //array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
            preg_match('/src="(.*?)"/s', $recommendation, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $recommendation, $idmatches);
		    $id = isset($idmatches[1]) ?  str_replace( $latestdomain,"",$idmatches[1]) : '';
			
		   // 提取标题
           preg_match('/title">\s*(.*?)\s*</s', $recommendation, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取更新日期
           preg_match('/duration">(.*?)</s', $recommendation, $numbermatches);
		   $date = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                
         
		if(!empty($id)){
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                       // 'playtimes' => $playtimes,
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
