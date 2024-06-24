<?php

// 获取所有分类 https://api.yujiameimei.com/sihu/shyk.php?getsort
// 搜索[没有搜索功能]  http://api.yujiameimei.com/hsck/hsck.php?keyword=美女&page=1

// 获取某个分类下视频列表  https://api.yujiameimei.com/sihu/shyk.php?sort=/movie/gaoqing/&page=1
// 获取视频详情  https://api.yujiameimei.com/sihu/shyk.php?id=/html/202404/84966.html

function getRedirectUrl($initialUrl) {
    // 检查缓存文件是否存在并且未过期
    $cacheFile = 'redirect_cache.txt';
    $expirationTime = 3600; // 缓存过期时间（单位：秒）

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expirationTime)) {
        // 如果缓存文件存在且未过期，则直接从缓存文件中读取重定向 URL
        
        return file_get_contents($cacheFile);
    } else {
        // 获取重定向后的目标地址
       // $hostUrl = "https://www.x2e2a.com/";
       //$hostCode = file_get_contents($hostUrl);
       // preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
       // $domain = $hostmatches[1];
        $initialUrl = "https://www.x6b9d.com";
        $headers = get_headers($initialUrl, 1);
        $redirectUrl = isset($headers['Location']) ?  str_replace("m/","m",$headers['Location']) : '';

        
        // 将获取到的重定向 URL 写入缓存文件
        file_put_contents($cacheFile, $redirectUrl);

        // 返回获取到的重定向 URL
        return $redirectUrl;
    }
}
	
	//$redirectUrl = getRedirectUrl($initialUrl);
	
	$redirectUrl = "https://www.4hu.tv";

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     


	//构建目标链接
	$sourceUrl = $redirectUrl . "/video/zipai/";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
 
 
    // 利用正则表达式截取文本
    preg_match('/menu clearfix(.*?)subMenuBox/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</dd>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
            preg_match('/href="(.*?)"/', $category, $idMatches);
            $id = isset($idMatches[1]) ? $idMatches[1] : '';

            // 提取标题
           preg_match('/document.write\(d\(\'(.*?)\'/s', $category, $matches);
           $title = isset($matches[1]) ? trim($matches[1]) : '';
          $title= base64_decode($title);
		  
            // 判断 id 不为空且 image 包含 "/categories/"
            if (strpos($id, '/') !== false ) {
                // 构建分类对象
                $categoryObject = [
                    'count' => $count,
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
		if($page==1){
			
		    $sourceUrl = $redirectUrl . "{$category}";
		}else{
			$sourceUrl = $redirectUrl . $category . "index_{$page}.html";
		}
		
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);
     
	// 利用正则表达式截取文本
	preg_match('/row col5 clearfix(.*?)btmBox/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</dl>', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/data-original="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
		
		// 提取图片地址
        preg_match('/src="(.*?)"/', $video, $image2Matches);
        $image2 = isset($imageMatches[1]) ? ( $redirectUrl . $image2Matches[1]) : '';
		

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/document.write\(d\(\'(.*?)\'/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? base64_decode ($titleMatches[1]) : '';
    
	// 提取更新日期
        preg_match('/<i>(.*?)</', $video, $dateMatches);
        $date = isset($dateMatches[1]) ? $dateMatches[1] : '';
		
		


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($id, 'html') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
			'image' => $image,
            'image_base64' => $image,
			'image_loading' => $image2,
			'title' => $title,
			//'duration' => $duration,
			//'dianzan' => $dianzan,
           // 'playtimes' => $playtimes,
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
     
        // 构建目标网站的 URL
        $sourceUrl = $redirectUrl . $videoId;

        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);
        $videojscode = file_get_contents($redirectUrl . "/static/base.js");
		
// 将文本a中的第一和第二个“<a href=”替换为空
    $sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/<h3 class="title"><script type="text\/javascript">document.write\(d\(\'(.*?)\'/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? str_replace('名称：','',base64_decode($titleMatches[1])) : '';

        // 从源码中截取图片地址
        preg_match("/posterImg=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		// 从源码中提取urljs
        preg_match("/0x17,'(.*?)\|iplay/", $videojscode, $videojsMatches);
       $videojs = isset($videojsMatches[1]) ? $videojsMatches[1] : '';
		
		// 从源码中提取MP4
        preg_match("/url\" value\=\"(.*?)\"/", $sourceCode, $mp4UrlMatches);
       $mp4Url = isset($mp4UrlMatches[1]) ? $mp4UrlMatches[1] : '';
	   
		 
		// 使用 "|" 分割字符串为数组
		$parts = explode("|", $videojs);

		// 提取第 8 和第 9 个元素
		$host2 = isset($parts[9]) ? $parts[9] : null;
		$host1 = isset($parts[8]) ? $parts[8] : null;
        $host3 = isset($parts[0]) ? $parts[0] : null;
		

        // 从源码中截取视频地址
       preg_match("/\+\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl1 = isset($videoMatches[1]) ? $videoMatches[1] : '';
        $videoUrl='https://' . $host1. '.' .$host2 . "." . $host3. $videoUrl1 ;
		//https://jscdn.pw6j118p.com/newhd/202404/661aab38e2519513f3e79ef6/hls/index.m3u8
		
         

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $videoUrl,
				'mp4' => $mp4Url,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/row col5 clearfix(.*?)<script src=/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            
            // 分割 'views'
            $recommendations = explode('</dl>', $recommendationsText);
            
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
        preg_match('/data-original="(.*?)"/', $recommendation, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
		
		// 提取图片地址
        preg_match('/src="(.*?)"/', $recommendation, $image2Matches);
        $image2 = isset($imageMatches[1]) ? ( $redirectUrl . $image2Matches[1]) : '';
		

        // 提取视频ID
        preg_match('/href="(.*?)"/', $recommendation, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
        preg_match('/document.write\(d\(\'(.*?)\'/', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? base64_decode ($titleMatches[1]) : '';
    
	// 提取更新日期
        preg_match('/<i>(.*?)</', $recommendation, $dateMatches);
        $date = isset($dateMatches[1]) ? $dateMatches[1] : '';
		$date = html_entity_decode($date, ENT_COMPAT | ENT_HTML401, 'UTF-8');
		// 解码   Unicode 编码的 HTML 实体编码  
		if (strpos($id, 'html') !== false) {
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                        //'playtimes' => $playtimes,
						'like' => $date,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
					 }
					
   
                
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
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

     
    // 获取参数
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
    $sourceUrl = $redirectUrl . "/vodsearch/$keyword----------$page---.html";


    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    preg_match('/stui-vodlist clearfix(.*?)stui-pannel-ft/s', $sourceCode, $matches);



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
        preg_match('/data-original="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';
        $image = $domain . $image;

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
        preg_match('/<i class="fa fa-heart"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*<span class="pull-right"><i class="fa fa-eye"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*(\d{2}-\d{2})/', $video, $datamatches);
        $dianzan = $datamatches[1]; 
        $playtimes = $datamatches[2]; 
        $date = $datamatches[3]; 
		
        // 构建视频对象
         $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			'duration' => $duration,
			'dianzan' => $dianzan,
            'playtimes' => $playtimes,
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
