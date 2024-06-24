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

function fetchSourceCode($dynamicUrl) {
    $url = 'http://coolaf.com/tool/ajaxgp';
    $referer = 'http://coolaf.com/tool/gp?u=' . $dynamicUrl;

    // 定义 POST 字段
    $postFields = [
        'url' => $dynamicUrl,
        'seltype' => 'post',
        'ck' => '',
        'header' => '',
        'parms' => '',
        'proxy' => '',
        'code' => 'utf8',
        'j' => '1',
        'ct' => 'application/x-www-form-urlencoded'
    ];

    // 将 POST 字段转换为 multipart/form-data 格式
    $delimiter = '----WebKitFormBoundaryiPRUis6kUJBV5cyv';
    $postData = '';
    foreach ($postFields as $name => $value) {
        $postData .= "--$delimiter\r\n";
        $postData .= "Content-Disposition: form-data; name=\"" . $name . "\"\r\n\r\n";
        $postData .= $value . "\r\n";
    }
    $postData .= "--$delimiter--\r\n";

    $ch = curl_init();

    // 设置请求 URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // 设置 cURL 选项以发送 POST 请求
    curl_setopt($ch, CURLOPT_POST, true);

    // 设置 POST 字段数据
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // 设置适当的请求头
    $headers = [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
        'Connection: keep-alive',
        'Content-Type: multipart/form-data; boundary=' . $delimiter,
        'Cookie: urladd=; iris.language=zh; Hm_lvt_18f18be6e58f13d87192835c8c15fdca=1717917917,1718028745,1718088105,1718710957; _gid=GA1.2.1615161170.1718710957; _ga_G59JK03TFH=GS1.1.1718710957.47.1.1718711692.60.0.0; _ga=GA1.2.1496962078.1713201055; _gat_gtag_UA_75491253_1=1; Hm_lpvt_18f18be6e58f13d87192835c8c15fdca=1718711693',
        'Host: coolaf.com',
        'Origin: http://coolaf.com',
        'Referer: ' . $referer,
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0',
        'X-Requested-With: XMLHttpRequest'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // 获取响应而不是直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 执行请求并获取响应
    $response = curl_exec($ch);

    // 检查是否有错误
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        $sourceCode = null;
    } else {
        // 解析 JSON 响应
        $jsonResponse = json_decode($response, true);
        if (isset($jsonResponse['data']['response'])) {
            $sourceCode = $jsonResponse['data']['response'];
        } else {
            echo 'Error: Invalid JSON response';
            $sourceCode = null;
        }
    }

    // 关闭 cURL 会话
    curl_close($ch);

    return $sourceCode;
}




function getRedirectUrl($hostUrl) {
    // 缓存文件路径和过期时间
    $cacheFile = 'jinmantt_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    // 检查缓存文件是否存在且未过期
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 读取缓存文件中的重定向 URL
        $cachedUrl = file_get_contents($cacheFile);
        if ($cachedUrl !== false) {
            return $cachedUrl;
        }
    }

    // 获取重定向后的目标地址
    $hostCode = file_get_contents($hostUrl);
	 
     

    if (preg_match('/分流2<br \/>\s*(.*?)<br \/>/s', $hostCode, $hostmatches)) {
        $newdomain = $hostmatches[1];
        
        // 将获取到的重定向 URL 写入缓存文件
        if (file_put_contents($cacheFile, $newdomain) === false) {
            // 处理无法写入缓存文件的情况
            return "Error: Unable to write to cache file.";
        }

        return $newdomain;
    } else {
        // 处理正则表达式匹配失败的情况
        return "Error: Unable to match domain.";
    }
}

$hostUrl = "https://jmcomic1.ltd";
$newdomain = "https://" .getRedirectUrl($hostUrl);

 
	
	//$latestdomain="https://18comic.vip";
    
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
    // 定义分类名称与对应数字索引的映射关系
    $categories = [
	
	    '最新' => "/albums?o=mr", 
		'cosplay' => "/albums/another/sub/cosplay", 
		'3D' => "/search/photos?search_query=3D", 
		'CG' => "/search/photos?search_query=CG", 
		
	    '总排行' => "/albums?o=mv", 
		'月排行' => "/albums?t=m&o=mv",
        '周排行' => "/albums?o=mv&t=w", 
		'同人' => "/albums/doujin",
		'单本' => "/albums/single",
		'短篇' => "/albums/short",
		'其他类' => "/albums/another",
		'韩漫' => "/albums/hanman",
		//'娜娜姐姐' => 532,
        '韩漫' => "/albums/meiman"
        
    ];

    // 初始化分类数组
    $categoriesArray = [];

    // 遍历分类名称与数字索引的映射关系，构建ID和Title的形式
    foreach ($categories as $title => $index) {
        $categoriesArray[] = [
            'id' => $index,
            'title' => $title
        ];
    }

    // 构建返回对象
    $response = [
        'code' => !empty($categoriesArray) ? 'ok' : 'null',
        'categories' => $categoriesArray
    ];

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	 
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$category = str_replace("$","&",$category);
	
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
 
	// 构建目标网站的 URL
		 
			$sourceUrl = "{$newdomain}$category&page=$page";
		  $sourceCode = fetchSourceCode($sourceUrl);
	 
	 
	// 利用正则表达式截取文本
	preg_match('/row m-0">(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('clearfix', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-original="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		
		
		if (strpos($image, 'https') === false) {
			 preg_match('/src="(.*?)"/s', $video, $imageMatches);
            $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace(".html","/1.html",$id);
		  
        // 提取标题
        preg_match('/title="(.*?)"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/text-white">(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1])."个点赞" : '';
		
		 
		
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
			'zhangjie' => $date,
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
    $sourceUrl = $newdomain . "/search/photos?main_tag=0&search_query=$keyword&page=$page"; 


    $sourceCode = fetchSourceCode($sourceUrl);
	//echo  $sourceUrl;
	// 利用正则表达式截取文本
	preg_match('/row m-0">(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('clearfix', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        // 提取图片地址
        preg_match('/data-original="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		
		
		if (strpos($image, 'https') === false) {
			 preg_match('/src="(.*?)"/s', $video, $imageMatches);
            $image = isset($imageMatches[1]) ?   $imageMatches[1] : '';
		}
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		  $id = str_replace(".html","/1.html",$id);
		  
        // 提取标题
        preg_match('/title="(.*?)"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
        preg_match('/text-white">(.*?)</s', $video, $durationMatches);
        $date = isset($titleMatches[1]) ? trim($durationMatches[1])."个点赞" : '';
		
		 
		
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
			'zhangjie' => $date,
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
	
	 
// 获取视频ID参数
$videoId = isset($_GET['id']) ? $_GET['id'] : '';

// 构建目标网站的 URL
$sourceUrl = $newdomain . $videoId;

// 获取源码
//$sourceCode = file_get_contents($sourceUrl);
//$sourceCode = fetchSourceCode($sourceUrl);






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
$response['title'] = "";
$response['id'] = "";
$response['chapters'] = "";

// 返回 JSON 格式的响应
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);


}

?>
