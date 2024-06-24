<?php

// 获取所有分类 https://api.yujiameimei.com/comic/litumh.php?getsort

// 获取某个分类下漫画列表   https://api.yujiameimei.com/comic/litumh.php?sort=/comics/kk-2&page=2

// 搜索  https://api.yujiameimei.com/comic/litumh.php?keyword=人妻&page=1

// 获取漫画详情  https://api.yujiameimei.com/comic/litumh.php?id=/comic/id-65c5cdc831d02/2.html


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
	$latestdomain="https://litu100.xyz";
    
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
   


	//构建目标链接
	$sourceUrl = $latestdomain . "/comics.html";
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	//$sourceCode =str_replace("</a>","</a>分割",$sourceCode);
    // 利用正则表达式截取文本
    preg_match('/漫画分类(.*?)center/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
 //echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</a>', $content);
        
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
           preg_match('/<a href="(.*?)"><div>(.*?)<\/div>/s', $category, $matches);

           // 提取匹配到的数字和文本内容 
           $title = isset($matches[2]) ? trim($matches[2]) : '';
		   $id = isset($matches[1]) ? $matches[1] : '';
		   $id = str_replace(".html","",$id);


            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
			////	if (strpos($id,"html")!= false ) {
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
		 
			$sourceUrl = "{$latestdomain}$category/$page.html";
		  
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
    $sourceCode = str_replace('target="_blank"><','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/class="list(.*?)class="pager/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('tag', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		//if (strpos($image, 'https') === false) {
		//	$image = "https:" . $image;
		//}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace(".html","/1.html",$id);
		  
        // 提取标题
        preg_match('/target="_blank">(.*?)</s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/<\/div><div><div>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';
		
		// 提取章节
        preg_match('/<\/div><div><div>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/#f00;">(.*?)</s', $video, $zhangjiematches);
        $zhangjie = isset($zhangjiematches[1]) ? "共" . trim($zhangjiematches[1]) . "话": '';
        //$playtimes = $datamatches[2]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'zhangjie' => $zhangjie,
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
    $sourceUrl = $latestdomain . "/comics/kk-$keyword/$page.html"; 


    // 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
    $sourceCode = str_replace('target="_blank"><','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/class="list(.*?)class="pager/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“留言”分割得到数组
    $videos = explode('tag', $content);

    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		//if (strpos($image, 'https') === false) {
		//	$image = "https:" . $image;
		//}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace(".html","/1.html",$id);
        // 提取标题
        preg_match('/target="_blank">(.*?)</s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/<\/div><div><div>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';
		
		// 提取章节
        preg_match('/<\/div><div><div>(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1]) : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/#f00;">(.*?)</s', $video, $zhangjiematches);
        $zhangjie = isset($zhangjiematches[1]) ? "共" . trim($zhangjiematches[1]) . "话": '';
        //$playtimes = $datamatches[2]; 
       // $date = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'zhangjie' => $zhangjie,
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

}elseif(isset($_GET['id'])) {
	
	 
// 获取视频ID参数
$videoId = isset($_GET['id']) ? $_GET['id'] : '';

// 构建目标网站的 URL
$sourceUrl = $latestdomain . $videoId;

// 获取源码
$sourceCode = file_get_contents($sourceUrl);







// 截取章节源码
preg_match('/漫画章节(.*?)<div class="center/s', $sourceCode, $matches);
$chaptercontent = $matches[1] ?? '';

// 以 "</a>" 标签分割章节内容
$chapterList = explode('</a>', $chaptercontent);
array_pop($chapterList);
// 初始化章节数组
$chapters = [];

// 遍历每个成员
foreach ($chapterList as $chapterItem) {
    // 提取章节标题
    // 使用第一个正则表达式
    preg_match("/checked=\"true\">(.*?)</s", $chapterItem, $titleMatches);
    $title = $titleMatches[1] ?? '';

    // 如果第一个正则未匹配到标题，则尝试第二个正则
    if (empty($title)) {
        preg_match("/class=\"tag\">(.*?)</s", $chapterItem, $titleMatches);
        $title = $titleMatches[1] ?? '';
    }

    // 提取章节链接
    preg_match("/href=\"(.*?)\"/s", $chapterItem, $idMatches);
    $id = isset($idMatches[1]) ? str_replace('\\"', '', $idMatches[1]) : '';

    // 构建章节对象
    $chapter = ['title' => $title, 'id' => $id];

    // 添加到章节数组中
    $chapters[] = $chapter;
}


// 提取当前章节标题
preg_match("/title\" content=\"(.*?)\"/s", $sourceCode, $titleMatches);
$title = $titleMatches[1] ?? '';

// 提取当前章节链接
preg_match("/url\" content=\"(.*?)\"/s", $sourceCode, $idMatches);
$id = isset($idMatches[1]) ? str_replace('\\"', '', $idMatches[1]) : '';




// 使用 DOMDocument 解析 HTML 内容
$dom = new DOMDocument();
@$dom->loadHTML($sourceCode);

// 获取所有 <img> 标签
$images = $dom->getElementsByTagName('img');

// 初始化图片链接数组
$imageLinks = [];

// 遍历所有 <img> 标签，提取符合条件的图片链接
foreach ($images as $image) {
    $src = $image->getAttribute('src');
    if (strpos($src, 'https://img') === 0  ) {
        $imageLinks[] = $src;
    }
}

// 获取所有 data-src 属性
$dataSrcAttributes = $dom->getElementsByTagName('*');
foreach ($dataSrcAttributes as $element) {
    $dataSrc = $element->getAttribute('data-src');
    if (strpos($dataSrc, 'https://img') === 0 ) {
        $imageLinks[] = $dataSrc;
    }
}

// 格式化链接并将它们添加到结果数组中
$formattedLinks = [];
foreach ($imageLinks as $link) {
    $formattedLinks[] = '$' . $link . '@';
}

// 使用 "分割" 将链接分开并添加到响应中
$response['content'] = $sourceUrl;

$response['content'] =  '
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
    </style>
</head>
<body>
    <p>跳转中，请稍候...</p>
    <p>如果您的浏览器没有自动跳转，请 <a href="' . htmlspecialchars($sourceUrl) . '">点击这里</a>。</p>
</body>
</html>
';

//$response['content'] = implode('分割', $formattedLinks);


// 构建返回对象
$response['code'] = 'ok';
$response['title'] = $title;
$response['id'] = $id;
$response['chapters'] = $chapters;

// 返回 JSON 格式的响应
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);


}

?>
