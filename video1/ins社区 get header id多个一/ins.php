<?php

// 获取所有分类 https://api.yujiameimei.com/ins/ins.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/ins/ins.php?sort=121&page=1
// 搜索  https://api.yujiameimei.com/ins/ins.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/ins/ins.php?id=110573



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" ."Authorization:BearereyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ5aW5zdTgwMCIsImxvZ2luX3R5cGUiOiIzIiwiaXNzIjoiaCIsImlhdCI6MTcxNDczMzQzNywianRpIjoiMjQwNTAzMDA4MDQxMSJ9.yB9nNtd8Gn1jWHtlI2O8eXa-7w9OM9-IsJcUP-NGej7zJRLNF14DJ-qGhbBCIPXXAXyxI9zaaN-mkOQ4iQaXuQ\r\n".
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.09\r\n" ,
        
    ]
]);




// 获取当前时间的十三位时间戳
list($t1, $t2) = explode(' ', microtime());
$str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);



function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        $hostUrl = "https://g3f8d.com/api/Webapi_v1/index/getConfig";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/today_url":"(.*?)"/s', $hostCode, $hostmatches);
        $latestdomain = $hostmatches[1];
		$latestdomain = str_replace("\\","",$latestdomain);
		
       // $initialUrl = "https://0u2t9.lol";
        //$headers = get_headers($initialUrl, 1);
        //$redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		//echo $redirectUrl;
		//重定向的多个域名是数组
		// 确保 $redirectUrl 是一个字符串
		//if (is_array($redirectUrl)) {
		//    $redirectUrl = $redirectUrl[0];
		//}
       // $redirectUrl= rtrim($redirectUrl);

       // preg_match("~^(.*?)\/https:~", $redirectUrl, $matches);
		//$redirectUrl = $matches[1];
        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
    }
}



//账号密码  yinsu800   yunsu001
//最新地址  https://mvnchxfy.xyz

    //api地址
    $latestdomain  = 'https://x.ins620.com';
	$imgdomain  = 'https://insimgs.chinahsdy.com';
	

// 判断是否传入了参数
if (isset($_GET['getsort'])) {
   
  
    $sourceUrl = "$latestdomain/api/post/app/p/tag/private/list?tagType=1";
   
    $sourceCode = file_get_contents($sourceUrl, false, $context);

// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 解析数据并修改键名
if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data'] as $category) {
		//if ( $category['des'] !='成人图片'  && $category['des'] !='情色小说' ){
        $categoryItem = [
            'id' => $category['tagId'],
            'title' => $category['tagCnName'],
            'image' => $imgdomain .$category['imgUrl'],
            'tagType' => $category['tagType']
            
        ];
        $categories[] = $categoryItem;
   // }
    }
    // 将处理过的数组命名为 categories
    $response['categories'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);



} else if (isset($_GET['sort']) && isset($_GET['page'])) {
    $category = isset($_GET['sort']) ? $_GET['sort'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
    //if ($page == 1) {
    //    $sourceUrl = "$latestdomain/rar.ashx?tags=全部&action=getvideos&vtype=$category&pageindex=$page&pagesize=20";
    //} else {
        $sourceUrl = "$latestdomain/api/post/app/p/post/private/type/page?sortType=1&page=$page&limit=6&sort=asc&type=1&tagId=$category";
    // } 

	
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl,false,$context);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data']['list'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
            'id' => $category['postId'],
            'title' => $category['title'],
            'image' => $category['headImgUrl'] . '.txt',
			 'content' => $category['content'],
			 'isvip' => $category['vipFlag'],
			 'views' => $category['viewCount'],
            'date' => date('Y-m-d',$category['createTimestamp']/1000)
        ];
        $categories[] = $categoryItem;
    }
   //}
    // 将处理过的数组命名为 categories
    $response['videos'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
	

    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    
	
	
	$sourceUrl = "$latestdomain/api/post/app/p/post/private/search/page?page=$page&limit=19&sort=asc&type=1&sortType=1&content=$keyword";
    // } 

	 
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl,false,$context);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

    if (!empty($dataArray)) {
    $categories = [];
    foreach ($dataArray['data']['list'] as $category) {
		//if ($category['need_vip']!=1 ){
        $categoryItem = [
             'id' => $category['postId'],
            'title' => $category['title'],
            'image' => $category['headImgUrl'] .'.txt',
			 'content' => $category['content'],
			 'isvip' => $category['vipFlag'],
			 'views' => $category['viewCount'],
            'date' => date('Y-m-d',$category['createTimestamp']/1000)
        ];
        $categories[] = $categoryItem;
   // }
    }
    // 将处理过的数组命名为 categories
    $response['videos'] = $categories;
    // 设置 code 为 ok 或 null
    $response['code'] = !empty($categories) ? 'ok' : 'null';
} else {
    // 数据为空，设置 code 为 null
    $response['code'] = 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);	
	
	
} else if(isset($_GET['id']) ) {
	
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    $sourceUrl = "$latestdomain/api/post/app/p/post/private/info/$id";
	 $sourceUrl2 = "$latestdomain/api/post/app/p/post/private/page?page=1&num=20&sort=asc&type=1";


// 发送 POST 请求并获取响应
$sourceCode = file_get_contents($sourceUrl, false, $context);
$sourceCode2 = file_get_contents($sourceUrl2, false, $context);
// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);
$dataArray2 = json_decode($sourceCode2, true);
// 初始化返回对象
$response = [];

// 设置 code 值
$response['code'] = 'null';

// 处理 videoinfo 对象
if (isset($dataArray['data'])) {
    $videoinfo = $dataArray['data'];
	$response['id'] = isset($videoinfo['postId']) ? $videoinfo['postId'] : '';
    $response['video'] =  isset($dataArray['data']['files'][0]['postVideoFile']['postparam']['play_url']) ?  $dataArray['data']['files'][0]['postVideoFile']['postparam']['play_url'] : '';
	$response['mp4'] =  isset($dataArray['data']['files'][0]['postVideoFile']['postparam']['download_url']) ?  $dataArray['data']['files'][0]['postVideoFile']['postparam']['download_url'] : '';
    $response['title'] = isset($videoinfo['title']) ? $videoinfo['title'] : '';
    $response['image'] = isset($videoinfo['userImg']) ? $videoinfo['userImg'] . '.txt' : '';
}

// 处理相关视频数据
$response['recommend'] = []; // 初始化相关视频数据

if (isset($dataArray2['data'])) {
    $suggestion_list = $dataArray2['data'];
    $categories = [];

    foreach ($suggestion_list as $video) {
		//if ($video['need_vip']!=1 ){
        $videoItem = [
             'id' => $video['postId'],
            'title' => $video['title'],
            'image' => $video['headImgUrl'] .'.txt',
			 'content' => $video['content'],
			 'isvip' => $video['vipFlag'],
			 'views' => $video['viewCount'],
            'date' => date('Y-m-d',$video['createTimestamp']/1000)
        ];
        $categories[] = $videoItem;
   // }
}
    // 将处理过的相关视频数据命名为 categories
    $response['recommend'] = $categories;

    // 设置 code 值
    $response['code'] = !empty($categories) ? 'ok' : 'null';
}

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
}

?>
