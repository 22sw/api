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

	
    $latestDomain = "https://guatl.com";
 
 //发布地址 https://51cg56.me
 
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
  


	//构建目标链接
	$sourceUrl = $latestDomain. "/index.php/category/cgxw/";
    // 获取源码
	
	 $sourceCode = getSource($sourceUrl);
 
    // 利用正则表达式截取文本
    preg_match('/menu-menu-1(.*?)<\/ul>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
 //echo $content ;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];
        
		
		// 添加首页项
        $result[] = [
            'id' => '/index.php/',
            'title' => '首页'
        ];
		
		
        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
         //   preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		//$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
            preg_match('/<a href="(.*?)">(.*?)<\/a>/s', $category, $idMatches);
            $id = isset($idMatches[1]) ? $idMatches[1] : '';
			 $id = str_replace($latestDomain,"",$id);
			 
			 
             $title = isset($idMatches[2]) ? $idMatches[2] : '';
			 
            // 提取标题
          // preg_match('/<span class="span"(.*?)data-v-1b58669b>(.*?)<\/span>/s', $category, $titlematches);
          // $title = isset($titlematches[2]) ? $titlematches[2] : '';

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
                // 构建分类对象
                $categoryObject = [
                    //'count' => $count,
                    'id' => ($id == "/") ? "/page/" : $id,
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

	 if($category ==="/index.php/"){
		 $sourceUrl = $latestDomain . $category ."page/" . $page . "/";
		 
	 }else{
		 $sourceUrl = $latestDomain . $category . $page . "/";
	 }
	
	// 构建目标网站的 URL
		
		
	 
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
    //echo $sourceCode;////
	// 利用正则表达式截取文本
	preg_match('/role="main">(.*?)contentinfo/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</article>', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/loadBannerDirect\(\'(.*?)\'/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace($latestDomain,"",$id);
        // 提取标题
        preg_match('/headline">\s*(.*?)\s*</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		// 提取日期
        preg_match('/<span itemprop="datePublished" content="(.*?)">(.*?)<\/span>/s', $video, $dateMatches);
        $date = isset($dateMatches[2]) ? $dateMatches[2] : '';
		
		// 提取描述
        preg_match('/<span>(.*?)</s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
    
	


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($description, 'AI科技') == false && !empty($title)  ) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'description' => $description,
			//'duration' => $duration,
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
    
	//header('Content-Type: application/json');
    
    // 获取参数
    $keyword = isset($_GET['keyword']) ? urldecode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 构建目标网站的 URL
		
		$sourceUrl = $latestDomain .  "/index.php/search/$keyword/$page/";
	  
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
    //echo $sourceCode;////
	// 利用正则表达式截取文本
	preg_match('/id="archive(.*?)contentinfo/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</article>', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/loadBannerDirect\(\'(.*?)\'/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 $id = str_replace($latestDomain,"",$id);
        // 提取标题
        preg_match('/headline">\s*(.*?)\s*</', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		// 提取日期
        preg_match('/<span itemprop="datePublished" content="(.*?)">(.*?)<\/span>/s', $video, $dateMatches);
        $date = isset($dateMatches[2]) ? $dateMatches[2] : '';
    
	
    // 提取描述
        preg_match('/<span>(.*?)</s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
    
	


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($description, 'AI科技') == false && !empty($title)  ) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
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





}elseif(isset($_GET['id'])) {
	
	 



    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestDomain . $videoId;

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);
        $sourceCode = str_replace("\\","",$sourceCode);


        preg_match('/articleBody">(.*?)<div class="dplayer/s', $sourceCode, $imgMatches);
		$imageCode = $imgMatches[1];
		$imageCode = preg_replace("/\/usr/", "$redirectUrl\/usr", $imageCode);
		$imageCode = preg_replace("/href=\"\/archives/", "href=\"$redirectUrl\/archives", $imageCode);
		$imageCode = str_replace("></p>","style=\"width:100%;\" ></p>",$imageCode);
		
		 
		
		 
		// 从源码中截取标题代码
        preg_match('/"name headline">(.*?)<\/h1>/s', $sourceCode, $titleCodeMatches);
        $titleCode	= isset($titleCodeMatches[1]) ?'<h1>'. $titleCodeMatches[1] .'</h1>': '';
		

		 
	
        // 从源码中截取标题
       // preg_match('/detail-title" data-v-ce3d4daa>(.*?)<\/h1>/', $sourceCode, $titleMatches);
       // $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        
		// 使用 preg_match_all 获取所有匹配的图片 URL
preg_match_all("/<img src=\"(.*?)\"/", $imageCode, $imageMatches);
// 提取匹配到的图片 URL
$imageUrls = isset($imageMatches[1]) ? $imageMatches[1] : array();
// 在每个图片 URL 前后加上符号，并使用 " 分割符进行分割
$imageUrls = array_map(function($url) { return '$' . $url . '@'; }, $imageUrls);

// 使用 implode 函数将所有图片 URL 用 " 分割符进行分割连接起来
$totalImageUrl = implode('分割', $imageUrls);
           

		// 使用正则表达式从 $videoCode 中提取视频地址
preg_match_all('/"url":"(.*?)"/', $sourceCode, $videoMatches);
// 提取匹配到的视频地址


$videoUrls = isset($videoMatches[1]) ? $videoMatches[1] : array();
// 在每个地址前加上 $ 符号，后加上 @ 符号
$videoUrls = array_map(function($url) { return '$' . $url . '@'; }, $videoUrls);
// 将链接数组连接成一个字符串，并且使用 " 分割符进行分割
$totalvideoUrl = implode('分割', $videoUrls);



// 判断数组是否为空
if (!empty($videoUrls)) {
    // 将视频链接和文字说明关联起来
    $videosWithNames = array_combine($videoUrls, range(1, count($videoUrls)));

    // 生成视频标签的 HTML 代码
    $videoCode = '';
    foreach ($videosWithNames as $url => $index) {
        $name = "视频{$index}";
        $videoCode .= "<p>{$name}</p>"; // 在视频标签前加上文字说明
        $videoCode .= str_replace("$", "<video   width=\"100%\" height=\"\"\nsrc=\"", $url);
        $videoCode = str_replace("@", "\" type=\"video/mp4\" poster=\"https://img.9a34b7.com/2024/1/15/31956444011c04398d6070f212ea9086.jpg\" \nautoplay=\"autoplay\" controls=\"controls\" loop=\"-1\"></br><p>你的浏览器不支持video标签.</p></br></video>", $videoCode);
        $videoCode .= "<br>"; // 添加换行
    }
} else {
    // 数组为空时的处理
    $videoCode = "<p>没有视频资源</p>";
}


//echo $videoCode;
        

        //拼接所有代码
$contentCode = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Read TXT File</title>
</head>
<body>'   . $titleCode  .$imageCode .$videoCode. '</body>
</html>' ;

        // 构建返回对象
        $response = [];
        
            $response = [
                'code' => 'ok',
                'title' => $title,
                'video' => $totalvideoUrl,
                'image' => $totalImageUrl,
				'titleCode' => $titleCode,
				'picCode' => $imageCode,
				'imgcode' => $contentCode, 
				'videocode' => $videoCode
                //'recommend' => []
            ];
        header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
}


?>
