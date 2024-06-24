<?php

// 获取所有分类 http://api.yujiameimei.com/mogu/mogu.php?getsort
// 搜索  http://api.yujiameimei.com/mogu/mogu.php?keyword=美女&page=1

// 获取某个分类下视频列表   http://api.yujiameimei.com/mogu/mogu.php?sort=/index.php/vod/type/id/1&page=1
// 获取视频详情  http://api.yujiameimei.com/mogu/mogu.php?id=/index.php/vod/play/id/19756/sid/1/nid/1.html

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
	$latestdomain = "https://rou.video";
    //发布地址 https://www.7000.me/
	
	
// 检查是否有参数传递
if(isset($_GET['getsort']) ) {
	
     

// 获取源码
$sourceCode = file_get_contents($latestdomain . '/cat');

// 提取所有子菜单
preg_match_all('/grid grid-cols-2(.*?)class="text/s', $sourceCode, $matches);
$subMenus = $matches[1];

// 初始化结果数组
$國產AV = [];
$麻豆傳媒 = [];
$OnlyFans = [];
$探花 = [];

// 分别处理每个子菜单
foreach ($subMenus as $index => $subMenu) {
    $categories = explode('underline', $subMenu);
    foreach ($categories as $category) {
        preg_match('/href="(.*?)">(.*?)<\/a>/s', $category, $matches);
        $id = isset($matches[1]) ? $matches[1] : '';
        $title = isset($matches[2]) ? trim($matches[2]) : '';
        $categoryObject = [];
        if(!empty($id)){
            $categoryObject = [
                'id' => $id,
                'title' => $title
            ];
        }
        switch ($index) {
            case 0:
                if (!empty($categoryObject)) {
                    $國產AV[] = $categoryObject;
                }
                break;
            case 1:
                if (!empty($categoryObject)) {
                    $麻豆傳媒[] = $categoryObject;
                }
                break;
            case 2:
                if (!empty($categoryObject)) {
                    $OnlyFans[] = $categoryObject;
                }
                break;
            case 3:
                if (!empty($categoryObject)) {
                    $探花[] = $categoryObject;
                }
                break;
        }
    }
}


// 构建返回对象
$response = [
    'code' => (count($國產AV) > 0 || count($麻豆傳媒) > 0 || count($OnlyFans) > 0 || count($探花) > 0) ? 'ok' : null,
    'sort' => "國產AV-91大神-麻豆傳媒-OnlyFans",
	
    '國產AV' => $國產AV,
	'91大神' => $探花,
    '麻豆傳媒' => $麻豆傳媒,
    'OnlyFans' => $OnlyFans
    
];

// 输出 JSON 格式数据
header('Content-Type: application/json');
echo json_encode($response);




		
		
	
	
	
} elseif(isset($_GET['sort']) && isset($_GET['page'])) {
	
	
	
    // 获取参数
	$category = isset($_GET['sort']) ? $_GET['sort'] : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	//$category=str_replace(".html","",$category);
	
	// 构建目标网站的 URL
		 
		$sourceUrl = $latestdomain . $category . "?order=viewCount&page=" . $page;
	//echo $sourceUrl;
	
	// 获取源码
	$sourceCode = file_get_contents($sourceUrl);

	// 利用正则表达式截取文本
	preg_match('/col-span-3(.*?)modal modal-middle/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    //$content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    //$content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('my-auto', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';
        if(strpos($image,"http")===false){
			$image = $latestdomain . $image;
		}
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? str_replace("&","$",str_replace("detail","play",str_replace(".html","-1-1.html",$idMatches[1]))) : '';
        // 提取标题
        preg_match('/alt="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/rounded-sm">(.*?)</', $video, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/bg-gray-900">(.*?)</s', $video, $datamatches);
         
        $views =  isset($datamatches[1]) ? $datamatches[1] : '';
        //$date = $duration = isset($datamatches[1]) ? $datamatches[1] : '';


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
    $keyword = isset($_GET['keyword']) ? urlencode($_GET['keyword']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // 构建目标网站的 URL
    $sourceUrl = $latestdomain . "/search?q=$keyword&t=&page=$page";

     
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);
    // 利用正则表达式截取文本
	preg_match('/grid grid-cols-2(.*?)drawer-side/s', $sourceCode, $matches);

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
    $videos = explode('shadow p-1', $content);
    
    // 移除数组末尾的空成员
    //array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ?  $imageMatches[1] : '';
        
		
        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? str_replace("&","$",str_replace("detail","play",str_replace(".html","-1-1.html",$idMatches[1]))) : '';
        // 提取标题
        preg_match('/alt="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
       preg_match('/rounded-sm">(.*?)</', $video, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/bg-gray-900">(.*?)</s', $video, $datamatches);
         
        $views =   isset($datamatches[1]) ? $datamatches[1] : '';
        //$date = $duration = isset($datamatches[1]) ? $datamatches[1] : '';


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

}elseif(isset($_GET['id'])) {
	
	
	
    // 获取参数
    $videoId = isset($_GET['id']) ? $_GET['id'] : '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain .'/api' . $videoId;
		$sourceUrl2 = $latestdomain .'/api/v/watching';
		$sourceUrl3 = $latestdomain  . $videoId;



        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);
        $dataArray = json_decode($sourceCode, true);
		
		// 获取源码
        $sourceCode2 = file_get_contents($sourceUrl2);
        $dataArray2 = json_decode($sourceCode2, true);
		
		//此处注释掉加载速度更快，但无推荐视频   $sourceCode3 = file_get_contents($sourceUrl3);
		
		
// 将文本a中的第一和第二个“<a href=”替换为空
    //$sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/<title>(.*?)-/', $sourceCode3, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match('/image" content="(.*?)"/s', $sourceCode3, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        preg_match('/videoUrl":"(.*?)"/s', $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';

// 定义正则表达式模式来匹配域名部分
$pattern = '/https:\/\/[^\/]+/';

// 使用正则表达式进行匹配
preg_match($pattern, $videoUrl, $matches);

// 提取并输出域名部分
if (!empty($matches[0])) {
    $domain = $matches[0];
    //echo "域名部分: " . $domain;
} else {
    //echo "没有匹配到域名部分";
}



        $urlCode=  file_get_contents($videoUrl);
		
         // 定义正则表达式模式来匹配m3u8地址
$pattern = '/\/hls2\/[^\s]+\.m3u8\?auth=[^\s]+&exp=[^\s]+&v=[^\s]+/';

// 使用正则表达式进行匹配
preg_match_all($pattern, $urlCode, $matches);

// 提取最后一个匹配的m3u8地址
if (!empty($matches[0])) {
    $last_m3u8_url = $matches[0][count($matches[0]) - 1];
   // echo "最后一个m3u8地址: " . $last_m3u8_url;
} else {
    //echo "没有匹配到m3u8地址";
}


        

        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
               'image' => $imageUrl,
                'video' => $domain . $last_m3u8_url,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/md:col-span-1(.*?)m-auto/s', $sourceCode3, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            
            // Split by 'views'
            $recommendations = explode('grid grid-cols', $recommendationsText);
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
        preg_match('/class=" line-clamp-1">(.*?)</', $recommendation, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
    
	// 提取时长
        preg_match('/bg-gray-900">(.*?)</', $recommendation, $durationMatches);
        $duration = isset($durationMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/<span class="pull-right">(.*?)<\/span>(.*?)<\/p>/s', $recommendation, $datamatches);
         
        $views =  isset($datamatches[1]) ? $datamatches[1] : '';
        $date =  isset($datamatches[2]) ? $datamatches[2] : '';
				if(!empty($id)){
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                        //'views' => $views,
						'date' => $duration . '次播放',
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


?>
