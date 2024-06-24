<?php

// 获取所有分类 https://api.yujiameimei.com/comic/weimi.php?getsort

// 获取某个分类下漫画列表   https://api.yujiameimei.com/comic/weimi.php?sort=/mid/weme&page=2

// 搜索  https://api.yujiameimei.com/comic/weimi.php?keyword=人妻&page=1

// 获取漫画详情  https://api.yujiameimei.com/comic/weimi.php?id=/archives/18798


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
	$latestdomain="https://weme.su";
    
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
   


	//构建目标链接
	$sourceUrl = $latestdomain . "/all";
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	$sourceCode =str_replace("home\">首页","all\">首页",$sourceCode);
    // 利用正则表达式截取文本
    preg_match('/nav-main">(.*?)sub-menu/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
 //echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        //echo $categories;
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
          //  $id = isset($idMatches[1]) ? $idMatches[1] : '';
		//	$id = str_replace("/albums-index-","",$id);

            // 提取标题
           preg_match('/<a href="(.*?)">(.*?)<\/a>/s', $category, $matches);

           // 提取匹配到的数字和文本内容 
           $title = isset($matches[2]) ? trim($matches[2]) : '';
		   $id = isset($matches[1]) ? $matches[1] : '';
		   $id = str_replace("/.","",$id);


            // 判断 id 不为空且 image 包含 "/categories/"
           // if (!empty($id) ) {
				if (strpos($id,"http")=== false ) {
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
		 
			$sourceUrl = "{$latestdomain}$category/page/$page";
		  
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
   // $sourceCode = str_replace('target="_blank"><','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/posts grids  clearfix(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('excerpt', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		//if (strpos($image, 'https') === false) {
		//	$image = "https:" . $image;
		//}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace("/.","",$id);
		  
        // 提取标题
        preg_match('/alt=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/class="cat"><a href=(.*?)>(.*?)<\/a>/s', $video, $durationMatches);
        $zhangjie = isset($durationMatches[2]) ? trim($durationMatches[2]) : '';
		
		// 提取章节
        preg_match('/<\/div><div><div>(.*?)</s', $video, $durationMatches);
       // $zhangjie = isset($titleMatches[2]) ? trim($durationMatches[2]) : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/#f00;">(.*?)</s', $video, $zhangjiematches);
       // $zhangjie = isset($zhangjiematches[1]) ? "共" . trim($zhangjiematches[1]) . "话": '';
        //$playtimes = $datamatches[2]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'zhangjie' => $zhangjie
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			//'date' => $date
			
            
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
    $sourceUrl = $latestdomain . "/page/$page?s=$keyword"; 


    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
   // $sourceCode = str_replace('target="_blank"><','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/posts grids  clearfix(.*?)<footer class="footer/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“留言”分割得到数组
    $videos = explode('excerpt', $content);

    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
       // 提取图片地址
        preg_match('/data-src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		//if (strpos($image, 'https') === false) {
		//	$image = "https:" . $image;
		//}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace("/.","",$id);
		  
        // 提取标题
        preg_match('/alt=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/class="cat"><a href=(.*?)>(.*?)<\/a>/s', $video, $durationMatches);
        $zhangjie = isset($durationMatches[2]) ? trim($durationMatches[2]) : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'zhangjie' => $zhangjie
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			//'date' => $date
			
            
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

}elseif(isset($_GET['id'])) {
	
	 
// 获取视频ID参数
$videoId = isset($_GET['id']) ? $_GET['id'] : '';

// 构建目标网站的 URL
$sourceUrl = $latestdomain . $videoId;

// 获取源码
$sourceCode = file_get_contents($sourceUrl);




// 提取当前章节标题
preg_match("/<title>(.*?) - 微密猫/s", $sourceCode, $titleMatches);
$title = $titleMatches[1] ?? '';

// 提取当前章节链接
preg_match("/data-url=\"(.*?)\"/s", $sourceCode, $idMatches);
$id = isset($idMatches[1]) ? str_replace('/.', '', $idMatches[1]) : '';




preg_match("/<figure(.*?)code-block/s", $sourceCode, $imagesMatches);
$imagesCode = $imagesMatches[1] ?? '';

/// 匹配所有 <img> 标签中的 data-original 属性
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $imagesCode, $matches);

$imageLinks = $matches[1] ?? [];

// 格式化链接并添加到结果数组中
$formattedLinks = [];
foreach ($imageLinks as $link) {
    $formattedLinks[] = '$' . $link . '@';
}

// 使用 "分割" 将链接分开并添加到响应中
$response['content'] = implode('分割', $formattedLinks);


 



// 定义用于存储链接的数组
$videoLinks = [];


// 使用正则表达式匹配 m3u8 地址
preg_match_all('/https?:\/\/.*?\.m3u8/i', $sourceCode, $m3u8Matches);
foreach ($m3u8Matches[0] as $m3u8Link) {
    // 格式化链接并添加到数组中
    $videoLinks[] = '$' . $m3u8Link . '@';
}

// 使用正则表达式匹配 mp4 地址
preg_match_all('/https?:\/\/.*?\.mp4/i', $sourceCode, $mp4Matches);
foreach ($mp4Matches[0] as $mp4Link) {
    // 格式化链接并添加到数组中
    $videoLinks[] = '$' . $mp4Link . '@';
}

// 使用 "分割" 将链接分开并添加到响应中
$response['videos'] = implode('分割', $videoLinks);



// 构建返回对象
$response['code'] = 'ok';
$response['title'] = $title;
$response['id'] = $id;



// 返回 JSON 格式的响应
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);


}

?>
