<?php

// 获取所有分类 http://api.yujiameimei.com/savsp/savsp.php?getsort
// 搜索  http://api.yujiameimei.com/savsp/savsp.php?keyword=美女&page=1

// 获取某个分类下视频列表   https://api.yujiameimei.com/savsp/savsp.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/savsp/savsp.php?id=/v/154024/1/1/

function getlatestdomain($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "http://hsck.net/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
        $domain = $hostmatches[1];
        $initialUrl = $domain . ":8899/?u=http://hsck.net/&p=/引用站点策略:strict-origin-when-cross-origin";
        $headers = get_headers($initialUrl, 1);
        $latestdomain = isset($headers['Location']) ? $headers['Location'] : '';

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
    }
}
	
	//$latestdomain = getlatestdomain($initialUrl);
	$latestdomain = "https://www.savdz.cc";
    //发布地址 https://www.7000.me/
	
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     


	// 构建目标链接
$latestdomain = 'https://www.savdz.cc'; // 请替换为实际的域名
$sourceUrl = $latestdomain . "/t/1";

// 获取源码
$sourceCode = file_get_contents($sourceUrl);

// 利用正则表达式截取文本
preg_match('/md-flex-hc f-16 wap-roll(.*?)header-user md-flex-sh/s', $sourceCode, $matches);

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
        // 提取标题
        preg_match('/href="(.*?)"><i class="(.*?)"><\/i>(.*?)<\/a>/s', $category, $matches);

        // 提取匹配到的数字和文本内容
        $id = isset($matches[1]) ? str_replace('t','s',$matches[1]) : '';
        $title = isset($matches[3]) ? trim($matches[3]) : '';

        // 判断 id 不为空且不包含特定字符串
        if (strpos($id,'/m') === false && strpos($id,'/label') === false && strpos($id,'/p') === false && strpos($id,'/index') === false) {
            // 构建分类对象
            $categoryObject = [
                'id' => $id,
                'title' => $title
            ];

            // 将分类对象添加到结果数组中
            $result[] = $categoryObject;
        }
    }

    // 将最后5个分类移动到前5个位置
    if (count($result) > 5) {
        $lastFive = array_splice($result, -5);
        $result = array_merge($lastFive, $result);
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

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		
		$sourceUrl = $latestdomain . $category . "--------$page---/";
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);

	// 利用正则表达式截取文本
	preg_match('/row row-space8 row-m-space7(.*?)pagebar md-flex-wc mb20/s', $sourceCode, $matches);

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
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/right">(.*?)</', $video, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/riqi f-20 mr3"><\/i>(.*?)</s', $video, $datamatches);
         
        //$views =  $duration = isset($datamatches[1]) ? $datamatches[1] : '';
        $date = $duration = isset($datamatches[1]) ? $datamatches[1] : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($id, '/v') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
			//'dianzan' => $dianzan,
            //'views' => $views,
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
} elseif(isset($_GET['id'])) {
	
	
	
    // 获取参数
$videoId = isset($_GET['id']) ? $_GET['id'] : '';

// 使用正则表达式匹配ID
preg_match('/\/v\/(\d+)\//', $videoId, $IdMatches);

// 检查是否匹配并提取ID
$Id = isset($IdMatches[1]) ? $IdMatches[1] : '';

// 确保$Id不为空
if (empty($Id)) {
    die("Invalid video ID.");
}

// 构建目标网站的 URL
$latestdomain = 'https://www.savdz.cc'; // 请替换为实际的域名
$verifyUrl = $latestdomain . "/index.php/ajax/pwd.html?id={$Id}&mid=1&type=4&pwd=SAVDZ.ME";
$sourceUrl2 = $latestdomain . '/vod/player/id/' . $Id . '/nid/1/sid/1/';
$sourceUrl = $latestdomain . $videoId;

// 设置请求头
$headers = [
    "Host: www.savdz.cc",
    "Connection: keep-alive",
    "Cache-Control: max-age=0",
    'sec-ch-ua: "Microsoft Edge";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
    "sec-ch-ua-mobile: ?0",
    'sec-ch-ua-platform: "Windows"',
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Sec-Fetch-Site: same-origin",
    "Sec-Fetch-Mode: navigate",
    "Sec-Fetch-User: ?1",
    "Sec-Fetch-Dest: document",
    "Referer: $sourceUrl2", 
    "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cookie: uid=2564; popup=1; PHPSESSID=v24c1l5gv0auui3tpd00c8n1jp; user_id=64447; user_name=a2888202; group_id=2; group_name=%E9%BB%98%E8%AE%A4%E4%BC%9A%E5%91%98; user_check=2c6754597d89bd49c7b950861c522ef1; user_portrait=%2Fstatic%2Fimages%2Ftouxiang.png"
];

// 创建一个带有所有请求头的上下文选项
$options = [
    "http" => [
        "header" => implode("\r\n", $headers)
    ]
];

// 创建上下文资源
$context = stream_context_create($options);

// 尝试获取视频URL
$attempt = 0;
$maxAttempts = 2;
$videoUrl = '';

while ($attempt < $maxAttempts && empty($videoUrl)) {
    // 发送验证请求
    $verifyResponse = file_get_contents($verifyUrl, false, $context);

    // 验证请求成功后获取真实数据
    $sourceCode2 = file_get_contents($sourceUrl2, false, $context);

    // 从源码中截取视频地址
    preg_match("/},\"url\":\"(.*?)\"/", $sourceCode2, $videoMatches);
    $videoUrl = isset($videoMatches[1]) ? str_replace("\\", "", $videoMatches[1]) : '';

    $attempt++;
}

// 构建目标网站的 URL
$sourceUrl = $latestdomain . $videoId;

// 获取源码
$sourceCode = file_get_contents($sourceUrl);

// 从源码中截取标题
preg_match('/<title>(.*?)av在线播放/s', $sourceCode, $titleMatches);
$title = isset($titleMatches[1]) ? $titleMatches[1] : '';

// 从源码中截取图片地址
preg_match("/image\" content\=\"(.*?)\"/", $sourceCode, $imageMatches);
$imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';

// 构建返回对象
$response = [
    'code' => !empty($videoUrl) ? 'ok' : null,
    'title' => $title,
    'image' => $imageUrl,
    'video' => $videoUrl,
    'recommend' => []
];

// 获取推荐视频列表
preg_match('/fr">查看全部(.*?)<\/ul>/s', $sourceCode, $matches);
if (isset($matches[1])) {
    $recommendationsText = $matches[1];
    
    // Split by 'views'
    $recommendations = explode('</li>', $recommendationsText);
    // 移除最后一个元素
    array_pop($recommendations);
    
    $recommendationList = [];
    foreach ($recommendations as $recommendation) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
        
        // 提取更新日期 点赞 播放次数
        preg_match('/riqi f-20 mr3"><\/i>(.*?)</s', $recommendation, $datamatches);
        $views =  $duration = isset($datamatches[1]) ? $datamatches[1] : '';

        $recommendationObject = [
            'image' => $image,
            'id' => $id,
            'views' => $views,
            'title' => $title
        ];
        $recommendationList[] = $recommendationObject;
    }
    // Add recommendations to the response
    $response['recommend'] = $recommendationList;
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
}
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

    
    // 获取参数
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
    $sourceUrl = $latestdomain . "/so/$keyword----------$page---/";


    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    preg_match('/row row-space8 row-m-space7(.*?)pagebar md-flex-wc mb20/s', $sourceCode, $matches);



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
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/right">(.*?)</', $video, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/riqi f-20 mr3"><\/i>(.*?)</s', $video, $datamatches);
         
        //$views =  $duration = isset($datamatches[1]) ? $datamatches[1] : '';
        $date = $duration = isset($datamatches[1]) ? $datamatches[1] : '';
		
        // 构建视频对象
         $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
			//'dianzan' => $dianzan,
            //'views' => $views,
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
    echo json_encode(['code' => null]);
}

}


?>
