<?php

// 获取所有分类 http://api.yujiameimei.com/hsck/hsck.php?getsort
// 搜索  http://api.yujiameimei.com/hsck/hsck.php?keyword=美女&page=1

// 获取某个分类下视频列表   http://api.yujiameimei.com/hsck/hsck.php?sort=/vodtype/8.html&page=1
// 获取视频详情  http://api.yujiameimei.com/hsck/hsck.php?id=/vodplay/45058-1-1.html

function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        //$hostUrl = "https://hbufz.mom/";
        //$hostCode = file_get_contents($hostUrl);
        //preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
        //$domain = $hostmatches[1];
        $initialUrl = "https://0u2t9.lol";
        $headers = get_headers($initialUrl, 1);
        $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		//echo $redirectUrl;
		//重定向的多个域名是数组
		// 确保 $redirectUrl 是一个字符串
		if (is_array($redirectUrl)) {
		    $redirectUrl = $redirectUrl[0];
		}
        //$redirectUrl= rtrim($redirectUrl);
        $redirectUrl= rtrim($redirectUrl);
		
       // preg_match("~^(.*?)\/https:~", $redirectUrl, $matches);
		//$redirectUrl = $matches[1];
        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $redirectUrl);

        // 返回获取到的重定向 URL
        return $redirectUrl;
    }
}


	//$redirectUrl = getRedirectUrl($initialUrl);
	$redirectUrl = "https://1mm65m.kh3dab.mom";
	
	$videoUrl = "https://avxsgtfj.xyz:32768/v/";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     
    

	//构建目标链接
	$sourceUrl = $redirectUrl . "/video/?video=1";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
	 
	// 将文本a中的第一个“<a href=”替换为空
    $sourceCode = preg_replace('/pure-u-1 footlist/', '', $sourceCode, 1);
   //echo $sourceCode;
   
	
    // 利用正则表达式截取文本
    preg_match('/pure-u-1 navigations(.*?)pure-u-1 videos/s', $sourceCode, $matches);

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
        //    preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		//$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
          //  preg_match('/href="(.*?)"/', $category, $idMatches);
           // $id = isset($idMatches[1]) ? $idMatches[1] : '';

            // 提取标题
           preg_match('/<a href="(.*?)">(.*?)<\/a>/s', $category, $matches);

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
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	 
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		if($page==1){
			$sourceUrl = $redirectUrl . "$category/";
		}else{
			$sourceUrl = $redirectUrl . "$category/page/$page/"; 
		}	
		
		
		
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);

	// 利用正则表达式截取文本
	preg_match('/videos bbox(.*?)pure-g nav/s', $sourceCode, $matches);

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
        preg_match('/data-src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  str_replace(".js","",$imageMatches[1]) : '';

        // 提取视频ID
       // preg_match('/href="(.*?)"/', $video, $idMatches);
       // $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
       // preg_match('/title="(.*?)"/', $video, $titleMatches);
       // $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
       // preg_match('/right">(.*?)</', $video, $durationMatches);
       // $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取视频ID 标题
        preg_match('/<p><a href="(.*?)">(.*?)<\/a>/', $video, $datamatches);
        $id = $datamatches[1] . "index.html"; 
        $title = $datamatches[2]; 
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($id, 'video') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title
			//'duration' => $duration,
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
$sourceUrl = $redirectUrl . $videoId;

// 获取源码
$sourceCode = file_get_contents($sourceUrl);

// 从源码中截取标题
preg_match('/<h1 class="lhgt">(.*?)<\/h1>/', $sourceCode, $titleMatches);
$title = isset($titleMatches[1]) ? $titleMatches[1] : '';

// 从源码中截取图片地址
preg_match("/poster=\"(.*?)\"/", $sourceCode, $imageMatches);
$imageUrl = isset($imageMatches[1]) ? str_replace(".js","",$imageMatches[1]) : '';

// 从源码中截取mp4地址
preg_match("/所有标签(.*?)下载观看/s", $sourceCode, $mp4CodeMatches);
$mp4Code = $mp4CodeMatches[1];
preg_match("/<li><a href=\"(.*?)\" target/", $mp4Code, $video2Matches);
$videoUrl2 = isset($video2Matches[1]) ? str_replace("\\","",$video2Matches[1]) : '';

// 从源码中截取视频地址
preg_match("/none;\">(.*?)<\/span>/", $sourceCode, $videoMatches);
$videoUrl = isset($videoMatches[1]) ? $videoUrl . str_replace("\\","",$videoMatches[1]) : '';




$recommendUrl = "https://mcr69tje.hebeimanlong.com/index.json";
$recommendCode = file_get_contents($recommendUrl);
preg_match("/\[(.*?)\]/", $recommendCode, $recommendMatches);
$recommendCode = "[" .$recommendMatches[1] ."]";

$recommendCode  = str_replace(".js", "", $recommendCode);
//$recommendCode  = "{\"recommendations\":" . str_replace("';", "", $recommendCode) . "}";

 
 

// 解析 JSON 数据
$recommendData = json_decode($recommendCode, true);

// 检查是否成功解析 JSON 数据并且得到了数组
if (is_array($recommendData)) {
    // 获取数组长度
    $arrayLength = count($recommendData);

    // 要随机选择的成员数量
    $numberOfMembersToSelect = 20;

    // 如果数组长度小于等于要选择的成员数量，则直接返回整个数组
    if ($arrayLength <= $numberOfMembersToSelect) {
        $selectedMembers = $recommendData;
    } else {
        // 从数组中随机选择要返回的成员的索引
        $randomKeys = array_rand($recommendData, $numberOfMembersToSelect);

        // 如果随机选择的结果是一个索引，则转换为数组
        if (!is_array($randomKeys)) {
            $randomKeys = array($randomKeys);
        }

        // 通过随机选择的索引获取对应的成员
        $selectedMembers = [];
        foreach ($randomKeys as $key) {
            $selectedMembers[] = $recommendData[$key];
        }
    }

    // 构建返回对象
    $response = [
        'code' => 'ok',
        'title' => $title,
        'mp4' => $videoUrl2,
        'video' => $videoUrl,
        'recommend' => $selectedMembers
    ];
} else {
    // 解析失败或者未得到预期的数组格式
    $response = [
        'code' => 'error',
        'message' => 'Failed to parse JSON data or unexpected format'
    ];
}

// 返回更新后的响应
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);








        
    
}
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

     
    // 获取参数
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
   // $sourceUrl = $redirectUrl . "/vodsearch/$keyword----------$page---.html";


    // 准备POST请求的数据
$postData = array(
    'title' => $keyword,
    'current' => $page,
    'size' => 16,
    'source' => 'v1'
);


// 初始化cURL
$curl = curl_init();

// 设置cURL选项
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://s.20mgy.lol/search',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($postData), // 将数组转换为URL编码的字符串
    CURLOPT_SSL_VERIFYPEER => false, // 关闭SSL验证，如果不需要验证SSL证书，建议关闭，以提高性能
));

// 发起请求
$response = curl_exec($curl);

// 检查是否有错误发生
if ($response === false) {
    die(curl_error($curl));
}

// 关闭cURL
curl_close($curl);

// 初始化 code 和 result 变量
$code = null;
$result = array();

// 解码JSON响应为PHP数组
$data = json_decode($response, true);

// 如果返回的数组不为空
if (!empty($data['data'])) {
    // 遍历data数组下每个成员，修改键名并返回结果
    foreach ($data['data'] as $item) {
        $result[] = array(
            'id' => $item['pageUrl'],
            'image' => "https://2e68cq.8goaimpicg.com:8443" . $item['videoImgUrl'],
            'createTime' => $item['createTime'],
            'videoInfoId' => $item['videoInfoId'],
            'title' => $item['videoTitle']
        );
    }
    // 设置 code 为 'ok'
    $code = 'ok';
} else {
    // 设置 code 为 null
    $code = null;
}

// 构建最终返回的数组
$responseData = array(
    'code' => $code,
    'videos' => $result
);

// 设置响应头
header('Content-Type: application/json');

// 将结果输出为JSON格式
echo json_encode($responseData, JSON_UNESCAPED_UNICODE);


}


?>
