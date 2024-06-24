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


	
	function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
		//获取最新域名
        $hostUrl = "http://www.avtbdizhi.cc/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/var urls(.*?)\]/s', $hostCode, $hostmatches);
        $hostcontent = $hostmatches[1];

        preg_match('/\'(.*?)\',/', $hostcontent, $domainmatches);
        $domain = isset($domainmatches[1]) ? $domainmatches[1] : '';
        $domain = str_replace(['a@v', 'c@d', 'a@b'], ['', '', '.'], $domain);
	    $domain = "https://www." . $domain ; // 在最后添加斜杠
        

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $domain);

        // 返回获取到的重定向 URL
        return $domain;
    }
}

    //$domain = getRedirectUrl($initialUrl);
    $domain = "http://m.91dyk.com";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
     // 获取分类接口
    $sourceUrl = "http://m.91dyk.com/wuyejuchang/";

    // 获取源码
   // $sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
    // 利用正则表达式截取文本
	
    preg_match('/<h3>分类<\/h3>(.*?)<\/ul>/s', $sourceCode, $matches);

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

            // 提取标题
            preg_match('/self">(.*?)</', $category, $titleMatches);
            $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) && strpos($id, "wuyejuchang") !== false) {
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
// 获取当前时间的十三位时间戳
   list($t1, $t2) = explode(' ', microtime());
   $timestamp = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);
	
	
	
	
	
// 构建目标网站的 URL
    if ($page==1){
        $sourceUrl = "http://m.91dyk.com{$category}";
    }else{
		$sourceUrl = "http://m.91dyk.com{$category}index$page.html";
	}
	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
	

// 利用正则表达式截取文本
preg_match('/con_zanpiantv_1(.*?)<\/ul>/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
   //$content = preg_replace('/text\/javascript" src="/', '', $content, 5);

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
        preg_match('/data-original="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        //$image = $domain.$image ;
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';
        
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
            'title' => $title
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
    echo json_encode(['code' => null]);
}
} elseif(isset($_GET['id'])) {
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';

    
     
        // 构建目标网站的 URL
        $sourceUrl = "http://m.91dyk.com$videoId";

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl, false, $context);
	    $sourceCode = iconv('GBK', 'UTF-8', $sourceCode);

       // 将文本a中的第一和第二个“<a href=”替换为空
       $sourceCode = preg_replace('/text\/javascript" src="/', '', $sourceCode, 5);
   
     // echo $sourceCode;
        // 从源码中截取标题
        preg_match('/alt="(.*?)\"/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/data-original=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : ''; 
		
		

        // 从源码中截取视频地址
        preg_match("/javascript\" src=\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? "http://m.91dyk.com" . $videoMatches[1] : '';
      
        $videoCode = file_get_contents($videoUrl, false, $context);
	    $videoCode = iconv('GBK', 'UTF-8', $videoCode);
		preg_match("/6E05(.*?)bd/s", $videoCode, $videourlMatches);
		$videoUrl= str_replace('$','',$videourlMatches[1]) ;
        $videoUrl ="https://" . preg_replace("/.*https:\/\//", "", $videoUrl);
		
		//echo  $videoCode;
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
        preg_match('/resize_list(.*?)<script>/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            // 移除第一个 '<div class="' 
            //$recommendationsText = preg_replace('/<div class="/', '', $recommendationsText, 1);
            // Split by 'views'
            $recommendations = explode('</li>', $recommendationsText);
            // Get the actual count
            $actualCount = count($recommendations) - 1;
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/data-original="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ?( $domain . $imageMatches[1]) : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';

                preg_match('/title="(.*?)"/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

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
// 将 UTF-8 转换为 GBK
$keyword = mb_convert_encoding($keyword, 'GBK', 'UTF-8');
// 使用 urlencode() 进行编码
$keyword = urlencode($keyword); 

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 构建目标网站的 URL
$sourceUrl = "http://m.91dyk.com/s.asp?page=$page&searchword=$keyword&searchtype=-1";


// 获取源码
    $sourceCode = file_get_contents($sourceUrl, false, $context);
	$sourceCode = iconv('GBK', 'UTF-8', $sourceCode);
    preg_match('/con_zanpiantv_1(.*?)<\/ul>/s', $sourceCode, $matches);

//echo $sourceUrl;




// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“</li>”分割得到数组
    $videos = explode('</li>', $content);

    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-original="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        //$image = $domain.$image ;
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] . "player-0-0.html" : '';
        
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
            'title' => $title
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
    echo json_encode(['code' => null]);
}

}


?>
