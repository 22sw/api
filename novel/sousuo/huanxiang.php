<?php

// 获取所有分类 https://api.yujiameimei.com/155/155.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/155/155.php?sort=14&page=1
// 搜索  https://api.yujiameimei.com/xiangjiao/155.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/155/155.php?id=62634


function getRedirectedUrl($url, $cacheFile, $cacheTime = 600) { // 10分钟 = 600秒
    // 检查缓存文件是否存在且未过期
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        // 从缓存文件读取内容
        $redirectedUrl = file_get_contents($cacheFile);
    } else {
        // 获取新的重定向 URL
        $headers = get_headers($url, 1); // 获取 URL 的头信息

        if ($headers && isset($headers['Location'])) {
            // 如果存在 Location 头信息，即重定向 URL
            if (is_array($headers['Location'])) {
                // 如果有多个重定向，取最后一个
                $redirectedUrl = end($headers['Location']);
            } else {
                $redirectedUrl = $headers['Location'];
            }

            // 将重定向地址写入缓存文件
            file_put_contents($cacheFile, $redirectedUrl);
        } else {
            // 没有重定向信息，使用原始 URL 或者返回 null
            $redirectedUrl = null;
        }
    }

    return $redirectedUrl;
}

	//$sourceUrl = 'https://jishuge.vip/';
	//$cacheFile = 'jishuge_cache.txt';
	//$redirectedUrl = getRedirectedUrl($sourceUrl, $cacheFile);

	//$latestdomain =$redirectedUrl;


    function aesDecrypt($encryptedData, $key, $iv) {
    $cipher = "AES-128-CBC"; // 加密方法
    $options = OPENSSL_RAW_DATA; // 原始数据选项
    
    // base64解码
    $encryptedData = base64_decode($encryptedData);
    
    // 使用openssl解密
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, $options, $iv);
    
    return $decryptedData;
    }

    $key = "eM4jgVfDGc1Cz0BY";
    $iv = "elEGmpnoR09ReC9u";



	$latestdomain ="http://hxtxt04.xyz/";
	
	//永久地址 https://yueliang.la/
	



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
	
    $sourceUrl = "$latestdomain";
   
    $sourceCode = file_get_contents($sourceUrl);

    // 利用正则表达式截取文本
    preg_match('/nav d">\s*(.*?)<\/nav>/s', $sourceCode, $matches);
    $sourceCode=  isset($matches[1]) ? $matches[1] : '';
	 
	 $decryptedData = aesDecrypt($sourceCode, $key, $iv);
	 
	preg_match('/<ul>(.*?)<\/ul>/s',  $decryptedData, $matches);
	  
	 
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
           preg_match('/<a href="(.*?)">(.*?)<\/a>/s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $id = isset($matches[1]) ? $matches[1] : '';
		  $id = str_replace($latestdomain,'',$id);
           $title = isset($matches[2]) ? trim($matches[2]) : '';
		  
		
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


} else if (isset($_GET['sort']) && isset($_GET['page'])) {
	
    $category = isset($_GET['sort']) ? $_GET['sort'] : '';
	 
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
    $sourceUrl = $latestdomain . $category ."&page=$page";
   
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
	
	

	// 利用正则表达式截取文本
	preg_match('/<\/h2>(.*?)<div class="clear">/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
	$content = str_replace("<div class=\"item d\">","",$content);
	

    

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</div>', $content);
    
    

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
		
		$video = aesDecrypt($video, $key, $iv);
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		
        // 提取标题
        preg_match('/title="(.*?)"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/<dd>(.*?)<\/dd>/s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
		  $description  = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
     if(!empty($id)){
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'date' => $description
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			
			
            
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
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	
	
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    // 构建目标网站的 URL
  
$sourceUrl = "$latestdomain/search.php?kw=$keyword&page= $page"; 
 
	 
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
	
	

	// 利用正则表达式截取文本
	preg_match('/<\/h2>(.*?)<div class="clear">/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
	$content = str_replace("<div class=\"item d\">","",$content);
	

    

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</div>', $content);
    
    

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
		
		$video = aesDecrypt($video, $key, $iv);
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/title="(.*?)"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/<dd>(.*?)<\/dd>/s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
		 $description  = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
     if(!empty($id)){
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'date' => $description
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			
			
            
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
	
	
} else if(isset($_GET['id']) ) {
	
    $id = isset($_GET['id']) ? $_GET['id'] : '';
   $sourceUrl = $latestdomain .'/' . $id;
   
   $sourceCode = file_get_contents($sourceUrl );
	  
	   
	   
  
	
	
	
   preg_match('/chaptercontent" class="d">(.*?)<\/div>/s', $sourceCode, $code2Matches);
   $novelCode = isset($code2Matches[1]) ? $code2Matches[1]: '';
	    $novelCode = aesDecrypt( $novelCode, $key, $iv);
	//$novelCode =  str_replace('<a href="/','<a href="' . $latestdomain . '/',$novelCode); 
		
		 
		
		
		
		
		
		
    // 将获取的 JSON 数据转换为 PHP 数组
  //  $dataArray = json_decode($sourceCode, true); 
	
    // 初始化返回对象
    $response = [];
    
    // 设置 code
    //$response['code'] = !empty($dataArray['list'][0]) ? 'ok' : 'null';
$response['code'] ='ok';
// 处理视频数组
//if (!empty($dataArray['list'][0])) {
    // 从 data 对象中直接获取 httpurl 的值作为视频播放地址
    // 设置视频对象
  
   
    $response['id'] = $sourceUrl;
    $response['image'] = '';
    $response['title'] = '';
	//$response['novel'] =$sourceCode;
    $response['novel'] = '<style>p { font-size: 23px; }</style>' . $novelCode;
	//$response['novel'] =$htmlContent ."<br>" . $code1 ."<br>" .  $code2;
	
//}


 


// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
