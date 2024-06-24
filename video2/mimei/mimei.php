<?php

// 获取所有分类 https://api.yujiameimei.com/mimei/mimei.php?getsort
// 搜索  https://api.yujiameimei.com/mimei/mimei.php?keyword=美女&page=1

// 获取某个分类下视频列表   https://api.yujiameimei.com/mimei/mimei.php?sort=/suoyoushipin/guochan&page=2
// 获取视频详情  https://api.yujiameimei.com/mimei/mimei.php?id=/suoyoushipin/dongman/64681.html

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
        $initialUrl = "https://ddfovfq.info";
        $headers = get_headers($initialUrl, 1);
        $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
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
	
	

// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     $redirectUrl = getRedirectUrl($initialUrl);
    

	//构建目标链接
	$sourceUrl = $redirectUrl . "/suoyoushipin/guochan/";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
     
    // 利用正则表达式截取文本
    preg_match('/hend(.*?)dist/s', $sourceCode, $matches);

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
            if (!empty($id && strpos($id,'suoyou')!==false ) ) {
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
	
	$redirectUrl = getRedirectUrl($initialUrl);
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		if($page==1){
			$sourceUrl = $redirectUrl . "$category/";
		}else{
			$sourceUrl = $redirectUrl . "$category/index_$page.html"; 
		}	
		
		
		
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);

	// 利用正则表达式截取文本
	preg_match('/items(.*?)pageDiv/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('</figure>', $content);
    
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
       // preg_match('/href="(.*?)"/', $video, $idMatches);
       // $id = isset($idMatches[1]) ? $idMatches[1] : '';
        // 提取标题
       // preg_match('/title="(.*?)"/', $video, $titleMatches);
       // $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取更新日期
        preg_match('/<span>(.*?)</', $video, $durationMatches);
        $date = isset($durationMatches[1]) ? $durationMatches[1] : '';
		
		// 提取视频ID 标题
        preg_match('/href="(.*?)" title="(.*?)">/', $video, $datamatches);
        $id = $datamatches[1] ; 
        $title = $datamatches[2]; 
        


        // 检查视频ID是否包含 'vodplay'，如果包含则添加到结果数组
    if (strpos($id, 'suoyou') !== false) {
        // 构建视频对象
        $videoObject = [
		    'id' => $id,
            'image' => $image,
			'title' => $title,
			//'duration' => $duration,
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
} elseif(isset($_GET['id'])) {
	
	$redirectUrl = getRedirectUrl($initialUrl);
	
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
   // $sourceCode = preg_replace('/<li><a/', '', $sourceCode, 1);
	
	
        // 从源码中截取标题
        preg_match('/正在播放：(.*?)</', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/poster=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		// 从源码中jsurl
       // preg_match("/\[\"(.*?)\"/", $jsurlCode, $jsurlMatches);
       // $jsurl = isset($jsurlMatches[1]) ? $jsurlMatches[1] : '';
		
		// 从源码中截取mp4地址
        preg_match("/toolsid1\" data-clipboard-text=\"(.*?)\"/", $sourceCode, $video2Matches);
        $videoUrl2 = isset($video2Matches[1]) ? $video2Matches[1] : '';
		
		// 从源码中截取视频地址2
        preg_match("/vHLSurl = \"(.*?)\"/", $sourceCode, $video2Matches);
        $videoUrl3 = isset($video2Matches[1]) ? $video2Matches[1] : '';

        // 从源码中截取视频地址
        preg_match("/vservers = \['(.*?)'/", $sourceCode, $videoMatches);
        $videoUrl1 = isset($videoMatches[1]) ? $videoMatches[1] : '';
    
	    //$videoUrl =$jsurl . $videoUrl1;
		$videoUrl = $videoUrl1 . $videoUrl3 ;
        

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
               // 'image' => $imageUrl,
			   'mp4' => $videoUrl2,
                'video' => $videoUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/猜你喜欢(.*?)neiye-botto/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
           
            // Split by 'views'
            $recommendations = explode('</figure>', $recommendationsText);
            //移除最后一个空数组
            array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ?( $domain . $imageMatches[1]) : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] : '';

                preg_match('/title="(.*?)"/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

                
        
		// 提取时长
        preg_match('/<p>(.*?)<\/p>/s', $recommendation, $dateMatches);
        $date = isset($dateMatches[1]) ? $dateMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<i class="fa fa-heart"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*<span class="pull-right"><i class="fa fa-eye"><\/i>&nbsp;(\d+)&nbsp;&nbsp;<\/span>\s*(\d{2}-\d{2})/', $recommendation, $datamatches);
        $dianzan = $datamatches[1]; 
        $playtimes = $datamatches[2]; 
        //$date = $datamatches[3]; 
		
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                        //'playtimes' => $playtimes,
						'date' => $date,
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
 elseif(isset($_GET['keyword']) && isset($_GET['page'])) {

    $redirectUrl = getRedirectUrl($initialUrl);
    // 获取参数
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
   // $sourceUrl = $redirectUrl . "/vodsearch/$keyword----------$page---.html";


    // 准备POST请求的数据
$postData = array(
    'className' => 'ed5315ea37ade2181edbd8b27b3fc881',
    'keyword' => $keyword,
    'limit' => 24,
    'page' => $page
);


// 初始化cURL
$curl = curl_init();

// 设置cURL选项
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.3bmmjla.life/Api/getSearch',
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
            'id' => $item['titleurl'],
            'image' => "https://3bmmaeh.life/pic" . $item['titlepic'],
            'createTime' => $item['newstime'],
            'videoInfoId' => $item['id'],
            'title' => $item['title']
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
