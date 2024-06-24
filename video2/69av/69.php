<?php

// 获取所有分类 https://api.yujiameimei.com/69av/69.php?getsort

// 获取某个分类下视频列表   https://api.yujiameimei.com/69av/69.php?sort=/categories/all&page=2

// 搜索  https://api.yujiameimei.com/69av/69.php?keyword=美女&page=1

// 获取视频详情  https://api.yujiameimei.com/69av/69.php?id=/video/55084


/// 设置流上下文选项
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
        $hostUrl = "https://6a6a4.top/";
        $hostCode = file_get_contents($hostUrl);
        preg_match('/<a class="" href="(.*?)"/', $hostCode, $hostmatches);
        $domain = $hostmatches[1] ;
		$latestdomain = $domain;
		//$latestdomain =  "https://69a9392.xyz";
		
        //$initialUrl =  "https://a.91selfie.com/1.php";
       // $headers = get_headers($initialUrl, 1);
        //$redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
		 
    
        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $latestdomain);

        // 返回获取到的重定向 URL
        return $latestdomain;
    }
}
	
	//$latestdomain="https://www.38dav.com";
	$latestdomain = getRedirectUrl($initialUrl);
	
	//$latestdomain = "https://69av.one";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     


	//构建目标链接
	$sourceUrl = $latestdomain . "/categories/all";
    // 获取源码
    //$sourceCode = file_get_contents($sourceUrl);
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	$sourceCode = str_replace('data-nav','"data-nav',$sourceCode);
	  
    // 利用正则表达式截取文本
    preg_match('/<div class="out-menu bottom(.*?)torrents/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
		//echo $content;
		 
 
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
        // 移除数组末尾的空成员
       // array_pop($categories);

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



            // 提取标题【备用，如果下面失效】
           //preg_match('/<a href=(.*?)>(.*?)<\/a>/s', $category, $matches);
		   // 提取匹配到的数字和文本内容
           //$id = isset($matches[1]) ? $matches[1] : '';
           //$title = isset($matches[2]) ? trim($matches[2]) : '';
		   //$id = str_ireplace(' "data-nav=categories-all','',$id);
		   //$id = str_ireplace(' "data-nav=categories-91','',$id);
		   
		   
		    preg_match('/<a\s+(.*?)>(.*?)<\/a>/s', $category, $matches); 
// 提取匹配到的数字和文本内容
$attributes = isset($matches[1]) ? $matches[1] : '';
$attributes = str_replace('data-nav="categories-all"', '', $attributes);

preg_match('/href="(.*?)"/', $attributes, $hrefMatch);
$id = isset($hrefMatch[1]) ? $hrefMatch[1] : '';

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

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		//if($page==1){
			$sourceUrl = $latestdomain . $category ."/". $page;
		//}else{
		//	 $sourceUrl = "{$latestdomain}{$category}index_{$page}.html";
		//}
		
       
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl,false,$context);
   // $sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
	// 利用正则表达式截取文本
	preg_match('/video-av-data(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];
      //echo $content;
    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('观看', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? 'https:'.  str_replace('?','.webp?',$imageMatches[1]) : '';
		 
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/alt=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
       // preg_match('/text-right">(.*?)</s', $video, $durationMatches);
        //$date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/duration>(.*?)</s', $video, $datamatches);
       // $duration = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
        $duration = isset($datamatches[1]) ? trim($datamatches[1]) : '';


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
   if (strpos($id, '/') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'views' => $duration,
			//'dianzan' => $dianzan,
            //'playtimes' => $playtimes,
			'date' => $duration
			
            
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

     
// 获取参数
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
$sourceUrl = $latestdomain . "/search/{$keyword}/{$page}?"; 


// 获取源码
$sourceCode = file_get_contents($sourceUrl, false, $context);
 //$sourceCode = str_replace('rel="nofollow"><img class=','',$sourceCode);
 
preg_match('/video-av-data(.*?)pagination/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 使用分隔符号“留言”分割得到数组
    $videos = explode('观看', $content);
	 

    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];
	//echo $content;

    // 循环处理每个视频
    foreach ($videos as $video) {
       // 提取图片地址
        preg_match('/data-src="(.*?)"/s', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? 'https:'.  str_replace('?','.webp?',$imageMatches[1]) : '';
		
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/alt=\"(.*?)\"/s', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
       // preg_match('/text-right">(.*?)</s', $video, $durationMatches);
        //$date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/duration> (.*?) <\/div>/', $video, $datamatches);
       // $duration = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
        $duration = isset($datamatches[1]) ? $datamatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'id' => $id,
            'image' => $image,
            'title' => $title,
            'duration' => $duration
            //'date' => $date
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
	header('Content-Type: application/json');
    echo json_encode(['code' => "普通用户，间隔30秒再搜索"]);
}

}elseif(isset($_GET['id'])) {
	
	 
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain  .  $videoId ;
        // 获取源码
        $sourceCode = file_get_contents($sourceUrl,false,$context);
		$suffixCode = file_get_contents("https://xewl.xyz/69av/js/t.69av.js?f3db3c652aba1fafe1f",false,$context);
		
		preg_match('/space_cdn_hash:"(.*?)"/s', $suffixCode, $suffixMatches);
		$suffix = isset($suffixMatches[1]) ? $suffixMatches[1] : '';
		//echo $sourceCode;
 
// 将文本a中的第一和第二个“<a href=”替换为空
   // $sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	    
		// 从源码中图片
        preg_match('/thumbnailUrl": "(.*?)"/s', $sourceCode, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        $image = str_replace('?','.webp?',$image);
        // 从源码中截取标题
        preg_match('/description content="(.*?)"/s', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
         
        // 从源码中截取线路地址
       preg_match('/space_hosts": (.*?)cdn_host/', $sourceCode, $imageMatches);
        $hostUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
		//$hostUrl = str_replace("\\","",$hostUrl);
       // $hostUrl = json_decode($hostUrl);
	   
	   // 解码 Unicode 字符
	   $hostUrl = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($match) {
 	      return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
	   }, $hostUrl);
	   // 使用正则表达式提取域名
        //preg_match_all('/"([^"]+)"/', $hostUrl, $matches);
	   
	   // 提取匹配结果中的域名部分
        //$hostUrl3 = $matches[1];

        //echo $hostUrl;
	   preg_match('/线路1", "(.*?)"]/', $hostUrl, $hostdataMatches);
		$hostUrl1=isset($hostdataMatches[1]) ? $hostdataMatches[1] : '';
		preg_match('/线路2", "(.*?)"]/', $hostUrl, $hostdataMatches);
		$hostUrl2=isset($hostdataMatches[1]) ? $hostdataMatches[1] : '';
		 preg_match('/线路3", "(.*?)"]/', $hostUrl, $hostdataMatches);
		$hostUrl3=isset($hostdataMatches[1]) ? $hostdataMatches[1] : '';
		preg_match('/线路4", "(.*?)"]/', $hostUrl, $hostdataMatches);
		$hostUrl4=isset($hostdataMatches[1]) ? $hostdataMatches[1] : '';
		preg_match('/主线路", "(.*?)"]/', $hostUrl, $hostdataMatches);
		$hostUrl0=isset($hostdataMatches[1]) ? $hostdataMatches[1] : '';
		 

        // 从源码中截取视频地址
        preg_match('/m3u8_url": "(.*?)\"/s', $sourceCode, $videoMatches);
		$videoori =  $latestdomain . $videoMatches[1];
        $videoUrl = isset($videoMatches[1]) ? str_replace('.m3u8','/g.m3u8?h=' . $suffix,$videoMatches[1]) : '';
		$videoUrl = str_replace('m3u8/','',$videoUrl);
		$videoUrl = str_replace('video','videos',$videoUrl);
		
		 $videoUrl= 'https://' .$hostUrl2 . $videoUrl;
		 preg_match('/^.+(?=\?et)/', $videoUrl, $vvmatches);
		 $videoUrl = isset($vvmatches[0]) ? $vvmatches[0] : '';
		 
		//echo $hostUrl;
  
        

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $image,
				'hostUrl0' => $hostUrl0,
				'hostUrl1' => $hostUrl1,
				'hostUrl2' => $hostUrl2,
				'hostUrl3' => $hostUrl3,
				'hostUrl4' => $hostUrl4, 
				//'hostUrl' => $hostUrl,
				'videoori' => $videoori,
                'video' => $videoUrl,
				 
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }
       
        // 获取推荐视频列表
        preg_match('/相关影片(.*?)更多/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('观看 ', $recommendationsText);
            array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                 // 提取图片地址
        preg_match('/data-src="(.*?)"/s', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ? 'https:'.  str_replace('?','.webp?',$imageMatches[1]) : '';
		
		

        // 提取视频ID
        preg_match('/href="(.*?)"/s', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
		 
        // 提取标题
        preg_match('/alt=\"(.*?)\"/s', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
		
		
	
	// 提取更新日期
       // preg_match('/text-right">(.*?)</s', $recommendation, $durationMatches);
        //$date = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/duration> (.*?) <\/div>/', $recommendation, $datamatches);
       // $duration = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
        $duration = isset($datamatches[1]) ? $datamatches[1] : '';
		
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'views' => $views,
                       // 'playtimes' => $playtimes,
						'duration' => $duration,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                
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



?>
