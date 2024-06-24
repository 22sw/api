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

$sourceUrl = 'https://jishuge.vip/';
$cacheFile = 'jishuge_cache.txt';
$redirectedUrl = getRedirectedUrl($sourceUrl, $cacheFile);

$latestdomain =$redirectedUrl;

    //森林
	//$latestdomain ="https://slapibf.com";
	//Jkun
	// $latestdomain ="https://www.jkunzyapi.com";
	//探探
	//$latestdomain ="https://xn---jishugedz-at-gmail-com-js70b9z1czvjwn82b.jishuge-vip.com";
	//155
   // $latestdomain ="https://155api.com";
   //老鸭
	//$latestdomain ="https://api.apilyzy.com";




// 判断是否传入了参数
if (isset($_GET['getsort'])) {
	
    $sourceUrl = "$latestdomain/list_1.html";
   
    $sourceCode = file_get_contents($sourceUrl);

    // 利用正则表达式截取文本
    preg_match('/分类筛选(.*?)text-align/s', $sourceCode, $matches);

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
           // preg_match('/href="(.*?)"/', $category, $idMatches);
            //$id = isset($idMatches[1]) ? $idMatches[1] : '';

            // 提取标题
           preg_match('/href="(.*?)">(.*?)</s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $id = isset($matches[1]) ? $matches[1] : '';
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
	$category = str_replace(".html","",$category);
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
$sourceUrl = "$latestdomain{$category}_{$page}.html";
      
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);

	// 利用正则表达式截取文本
	preg_match('/ucontent(.*?)clear/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</li>', $content);
    
    

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
       // preg_match('/src="(.*?)"/', $video, $imageMatches);
       // $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		
        // 提取标题
        preg_match('/title">\n(.*?)</s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/<div class="description">\s*(.*?)\s*<\/div>/s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
		
		
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
     if(!empty($id)){
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => '',
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
        'list' => $result
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
  
$sourceUrl = "$latestdomain/list-{$keyword}_$page.html"; 
	 
    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);

	// 利用正则表达式截取文本
	preg_match('/ucontent(.*?)clear/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</li>', $content);
    
    

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
       // preg_match('/src="(.*?)"/', $video, $imageMatches);
       // $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		
        // 提取标题
        preg_match('/title">\n(.*?)</s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/<div class="description">\s*(.*?)\s*<\/div>/s', $video, $descriptionMatches);
        $description = isset($descriptionMatches[1]) ? $descriptionMatches[1] : '';
		
		
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if(!empty($id)){
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
           'image' => '',
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
        'list' => $result
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
   
	$sourceUrl = $latestdomain . "/".$id;
    $sourceCode = file_get_contents($sourceUrl );
	
   // $sourceCode = str_replace("<script>(","", $sourceCode) ;
	
	$sourceCode = str_replace('src="/static','src="' . $latestdomain.'/static', $sourceCode) ;
	$sourceCode = str_replace('href="/static','href="' . $latestdomain.'/static', $sourceCode) ;
	
	
	$novelCode = $sourceCode ;
	
	$novelCode =  str_replace("appbox","", $novelCode) ;
	  // 提取视频ID
        preg_match('/<\/h1>(.*?)<\/div>/s', $sourceCode, $code1Matches);
        $code1 = isset($code1Matches[1]) ? $code1Matches[1] ."</div>" : '';
		
		 preg_match('/<script>(.*?)<\/script>/s', $sourceCode, $code2Matches);
        $code2 = isset($code2Matches[1]) ? "<script>".$code2Matches[1] ."</script>" : '';
		
		$htmlContent = "<link href=\"https://xn---jishugedz-at-gmail-com-js70b9z1czvjwn82b.jishuge-vip.com/static/style.css?22228\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />
<script src=\"https://xn---jishugedz-at-gmail-com-js70b9z1czvjwn82b.jishuge-vip.com/static/main.js?202402\" type=\"application/javascript\"></script>
<script src=\"https://xn---jishugedz-at-gmail-com-js70b9z1czvjwn82b.jishuge-vip.com/static/jishuge.js\" type=\"application/javascript\"></script>
<script src=\"https://s0.pstatp.com/cdn/expire-1-M/jquery/2.2.0/jquery.min.js\" type=\"application/javascript\"></script>
<script src=\"https://s0.pstatp.com/cdn/expire-1-M/crypto-js/4.0.0/crypto-js.min.js\" type=\"application/javascript\"></script>";
		
		
		
		
		
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
   // $response['novel'] ='<meta http-equiv="refresh" content="0; url='. $sourceUrl .'">';
	 
	
//}
    $response['novel'] =  '
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2;url=' . htmlspecialchars($sourceUrl) . '">
    <title>跳转中...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        #countup {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <script>
        let countup = 0;
        function updateCountup() {
            countup++;
            document.getElementById("countup").textContent = countup;
        }
        setInterval(updateCountup, 1000);
        window.onload = updateCountup;
    </script>
</head>
<body>
    <p>沉浸模式载入中，耐心等待...</p>
    <p>已等待 <span id="countup">0</span> 秒。</p>
    <!--<p>如果您的浏览器没有自动跳转，请 <a href="' . htmlspecialchars($sourceUrl) . '">点击这里</a>。</p>-->
</body>
</html>
';

 


// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
