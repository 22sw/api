<?php

// 获取所有分类 https://api.yujiameimei.com/5g/5g.php?getsort
// 搜索  https://api.yujiameimei.com/5g/5g.php?keyword=美女&page=1

// 获取某个分类下视频列表   https://api.yujiameimei.com/5g/5g.php?sort=/h/国产/&page=1
// 获取视频详情  https://api.yujiameimei.com/5g/5g.php?id=/113637/index.html


// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .

"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36 Edg/123.0.0.0\r\n" ,
    ]
]);



function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 1; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
        //$hostUrl = "https://hbufz.mom/";
        //$hostCode = file_get_contents($hostUrl);
        //preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
        //$domain = $hostmatches[1];
        $initialUrl = "https://qyerec.6kc5q6.lol";
        $headers = get_headers($initialUrl, 1);
        $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		 preg_match('/http(.*?)\/index/s', $redirectUrl, $Urlmatches);
		$redirectUrl = 'http'.$Urlmatches[1];
		
		//echo $redirectUrl;
		//重定向的多个域名是数组
		// 确保 $redirectUrl 是一个字符串
		if (is_array($redirectUrl)) {
		    $redirectUrl = $redirectUrl[0];
		}
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
	//固定链接
		$redirectUrl = "https://16xefg.pwsdspm.xyz";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
    
    

	//构建目标链接
	$sourceUrl = $redirectUrl . "/h/大陆/";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    //echo $sourceUrl ;
    // 利用正则表达式截取文本
    preg_match('/首页(.*?)go_search2/s', $sourceCode, $matches);

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
           preg_match('/<a href="(.*?)" target="_self">(.*?)<\/a>/s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $id = isset($matches[1]) ? $matches[1] : '';
		   $id= urldecode(preg_replace('/\?.*/', '', $id));
		      
           $title = isset($matches[2]) ? trim($matches[2]) : '';

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) && strpos($title,"小说")===false && strpos($title,"图片")===false    ) {
                // 构建分类对象
                $categoryObject = [
                   // 'count' => $count,
                    'id' => "/dhd/" . $title .  "/",
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

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		if($page==1){
			$sourceUrl = $redirectUrl . $category;
		}else{
		    $sourceUrl = $redirectUrl . "{$category}page/{$page}/"; 
		}	
		
		
		
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
  //echo $sourceCode;
	// 利用正则表达式截取文本
	preg_match('/waterfall(.*?)pagination/s', $sourceCode, $matches);

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
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        $image = str_replace('webp.js','jpg',$image);
        // 提取视频ID
       // preg_match('/href="(.*?)"/', $video, $idMatches);
       // $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
       // preg_match('/title="(.*?)"/', $video, $titleMatches);
       // $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取更新日期
        preg_match('/href="(.*?)"/', $video, $durationMatches);
        $id = isset($durationMatches[1]) ? $durationMatches[1] : '';
		$id = $id .'index.html';
		// 提取视频ID 标题
        preg_match('/alt="(.*?)"/', $video, $datamatches);
         
        $title = isset($datamatches[1]) ? $datamatches[1] : '';
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($id, '/') !== false) {
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
       // $jsurlCode = file_get_contents("https://mcr69tje.hebeimanlong.com/gs.js");
	   preg_match("/所有标签(.*?)下载观看/s", $sourceCode, $mp4CodeMatches);
         $mp4Code = $mp4CodeMatches[1];


// 将文本a中的第一和第二个“<a href=”替换为空
    $sourceCode = preg_replace('/innerHTML = "/', '', $sourceCode, 9);
	
	
        // 从源码中截取标题
        preg_match('/innerHTML = "(.*?)"/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/id=\"purl\">(.*?)</", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		// 从源码中jsurl
       // preg_match("/\[\"(.*?)\"/", $jsurlCode, $jsurlMatches);
       // $jsurl = isset($jsurlMatches[1]) ? $jsurlMatches[1] : '';
		
		// 从源码中截取mp4地址
        preg_match("/downloadurl\">(.*?)</", $sourceCode, $video2Matches);
        $videoUrl2 = isset($video2Matches[1]) ? $video2Matches[1] : '';
		
		// 从源码中截取mp4地址
        preg_match("/downloadomain = \[\"(.*?)\"/", $sourceCode, $video2Matches);
        $videoUrl4 = isset($video2Matches[1]) ? $video2Matches[1] : '';
		
		// 从源码中截取视频地址2
        preg_match("/vpath\">(.*?)</", $sourceCode, $video2Matches);
        $videoUrl3 = isset($video2Matches[1]) ? $video2Matches[1] : '';

        // 从源码中截取视频地址
        preg_match("/item: '\[\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl1 = isset($videoMatches[1]) ? $videoMatches[1] : '';
    
	    //$videoUrl =$jsurl . $videoUrl1;
		$videoUrl = $videoUrl1 . $videoUrl3 ;
        

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
               'image' => $imageUrl,
			   'mp4' => $videoUrl4 .$videoUrl2 ,
                'video' => $videoUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

       $sourceUrl = 'https://16xefg.pwsdspm.xyz/index.json';
$sourceCode = file_get_contents($sourceUrl,false,$context);

preg_match('/\[(.*?)\]/s', $sourceCode, $matches);
$sourceCode = '['. $matches[1] .']';

// 解析 JSON 数据
$sourceData = json_decode($sourceCode, true);

if ($sourceData !== null) {
    // 获取推荐视频列表
    $recommendationList = [];

    // 如果推荐视频数量大于 20，则随机选择 20 个视频
    if (count($sourceData) > 20) {
        $randomKeys = array_rand($sourceData, 20);

        foreach ($randomKeys as $key) {
            $item = $sourceData[$key];

            // 修改键名
            $image = isset($item['c']) ? $item['c'] : '';
			$image = str_replace('webp.js','jpg',$image);
            $id = isset($item['k']) ? $item['k']. "index.html" : '';
            $title = isset($item['t']) ? $item['t'] : '';

            // 构建推荐视频对象
            $recommendationObject = [
                'image' => $image,
                'id' => $id,
                'title' => $title
            ];

            // 将推荐视频对象添加到推荐视频列表中
            $recommendationList[] = $recommendationObject;
        }
    } else {
        // 如果推荐视频数量不足 20 个，则全部加入
        foreach ($sourceData as $item) {
            // 修改键名
            $image = isset($item['c']) ? $item['c'] : '';
			 $image = str_replace('webp.js','jpg',$image);
            $id = isset($item['k']) ? $item['k']. "index.html" : '';
			
            $title = isset($item['t']) ? $item['t'] : '';

            // 构建推荐视频对象
            $recommendationObject = [
                'image' => $image,
                 'id' => $id ,
                'title' => $title
            ];

            // 将推荐视频对象添加到推荐视频列表中
            $recommendationList[] = $recommendationObject;
        }
    }

    // 将推荐视频列表添加到主要的响应对象中
    $response['recommend'] = $recommendationList;

    // 输出 JSON 格式数据
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    // 如果没有找到推荐视频列表，则返回主要的响应对象
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}



    
}
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

     
    // 获取参数
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
   // $sourceUrl = $redirectUrl . "/vodsearch/$keyword----------$page---.html";


    // 准备POST请求的数据
$postData = array(
    'source' => 'v2',
    'title' => $keyword,
    'size' => 24,
    'current' => $page
);


// 初始化cURL
$curl = curl_init();

// 设置cURL选项
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://s.sfcw5k.mom/search',
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
            'id' => $item['pageUrl'] . 'index.html',
            'image' => "https://5gixb.xyz:1443" . $item['videoImgUrl'],
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
