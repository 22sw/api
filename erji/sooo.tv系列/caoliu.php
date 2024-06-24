<?php

// 获取所有分类 https://api.yujiameimei.com/caoliu/caoliu.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/caoliu/caoliu.php?sort=1760182312696303617&page=2
// 搜索  https://api.yujiameimei.com/caoliui/caoliu.php?keyword=美女&page=1
// 获取视频详情  https://api.yujiameimei.com/caoliu/caoliu.php?id=1778051363881811969



/// 设置流上下文选项
$context = stream_context_create([
    'http' => [
        //'header' => "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
        //"Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6\r\n" .
        
        "Content-Type:text/plain". 
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0",
        
    ]
]);

    // 获取当前时间的十三位时间戳
    list($t1, $t2) = explode(' ', microtime());
    $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);

    
	$newurl =  'https://534552.com';
	
	
	
	function decompress($data, $encoding) {
    switch ($encoding) {
        case 'gzip':
            return gzdecode($data);
        case 'deflate':
            return gzinflate($data);
        case 'br':
            return brotli_uncompress($data);
        default:
            return $data;
    }
}

function fetch_urlconfig($urlconfig, $headers) {
    // 初始化 cURL 会话
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $urlconfig);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return false;
    } else {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        preg_match('/Content-Encoding:\s*(\S+)/i', $header, $matches);
        $encoding = $matches[1] ?? '';

        $body = decompress($body, $encoding);

        return $body;
    }
    
    curl_close($ch);
}

$headers = [
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Encoding: gzip, deflate, br, zstd",
    "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cache-Control: max-age=0",
    "Referer: ".$newurl."/",
    "Sec-Ch-Ua: \"Chromium\";v=\"124\", \"Microsoft Edge\";v=\"124\", \"Not-A.Brand\";v=\"99\"",
    "Sec-Ch-Ua-Mobile: ?0",
    "Sec-Ch-Ua-Platform: \"Windows\"",
    "Sec-Fetch-Dest: document",
    "Sec-Fetch-Mode: navigate",
    "Sec-Fetch-Site: same-origin",
    "Sec-Fetch-User: ?1",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0"
];


$urlconfig = $newurl. "/json/config.js?" . mt_rand(100000000, 999999999);
$response = fetch_urlconfig($urlconfig, $headers);
if ($response !== false) {
    //echo "Response from $url:\n";
    //echo $response;
}



// 使用正则表达式匹配 videoDomain
preg_match("/videoDomain\":\"(.*?)\"/", $response, $videourlMatches);

// 获取匹配到的 videoDomain 字符串
$videoDomainString = isset($videourlMatches[1]) ? $videourlMatches[1] : '';

// 将 videoDomain 字符串分割成数组
$videoDomains = explode("\\n", $videoDomainString);

// 如果 $videoDomains 是一个数组且不为空，随机选择一个 videoDomain
if (is_array($videoDomains) && !empty($videoDomains)) {
    $randomVideoDomain = $videoDomains[array_rand($videoDomains)];
    // 去除可能的转义符号
    $videoDomain = str_replace("\\", "", $randomVideoDomain);
    //echo "Randomly selected videoDomain: $videoDomain";
} else {
    //echo "No videoDomain found in the configuration code.";
	$videoDomain = 'https://bspbf.649328.com/exclusive/';
}  



// 使用正则表达式匹配 searchDomain
preg_match("/\"cdn\":\"(.*?)\"/", $response, $searchurlMatches);

// 获取匹配到的 videoDomain 字符串
$searchDomain = isset($searchurlMatches[1]) ? $searchurlMatches[1] : '';



// 使用正则表达式匹配 latestdomain
preg_match("/jsonCDN\":\"(.*?)\"/", $response, $latestMatches);

// 获取匹配到的 videoDomain 字符串
$latestdomain = isset($latestMatches[1]) ? $latestMatches[1] : '';


	
	
	// 使用正则表达式匹配 imagedomain
preg_match("/\"fileDomain\":\"(.*?)\"/", $response, $imageMatches);

// 获取匹配到的 videoDomain 字符串
$imagedomain = isset($imageMatches[1]) ? $imageMatches[1] : '';
 
 

 



   //echo $latestdomain . "<br>" . $imagedomain. "<br>" . $videoDomain. "<br>" . $searchDomain ;
   // $latestdomain = 'https://json-schem.kpehty.com';
	//$imagedomain = 'https://bstatic.164695.com/exclusive/';
   // $token = 'token=eyJ1c2VyX2lkIjo1MzEwODExNTQsImxhc3Rsb2dpbiI6MTcxNDY3MDI3M30.6ad32bb0477d5109b1097e46e4abf8ba.b55fd7f53f48c6707b5b8886e7bf1df14f417cd8dcfdf5cfe1bf949e';
	//$videoDomain =  'https://bspbf.657924.com:56443/exclusive/';
	//$searchDomain =  'https://bjk.kpehty.com';
	
	
	



// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    
	
	$sourceUrl = "$latestdomain/json/zone_2.json?$str_time";
$sourceCode = file_get_contents($sourceUrl, false, $context);

// 解码 base64 编码的密文
$sourceCode = base64_decode($sourceCode);

// 解密秘钥
$key = '6E31ECDEF3EEC0E6';

// 解密算法
$sourceCode = openssl_decrypt($sourceCode, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);

// 输出解密后的数据


// 将获取的 JSON 数据转换为 PHP 数组
$dataArray = json_decode($sourceCode, true);

// 初始化返回对象
$response = [];

// 处理视频数组
$videos = [];

$response['code'] = 'ok';


// 处理长视频数组
if (!empty($dataArray['repository_zone_1002'])) {
    foreach ($dataArray['repository_zone_1002'] as $video) {
        // 构建视频项的关联数组
        $videoItem = [
            'categorie' => '宅男福利',
            'id' => $video['repositoryZoneId'],
            'title' => $video['repositoryZoneName'],
            'image' => $imagedomain . $video['icon'],
            'date' => $video['updateTime'],
            'parentId' => $video['parentId']
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
}

// 处理短视频数组
$videosshort= [];

if (!empty($dataArray['repository_zone_1001'])) {
    foreach ($dataArray['repository_zone_1001'] as $video) {
        // 构建视频项的关联数组
        $videoItem = [
            'categorie' => '草榴AV',
            'id' => $video['repositoryZoneId'],
            'title' => $video['repositoryZoneName'],
            'image' => $imagedomain . $video['icon'],
            'date' => $video['updateTime'],
            'parentId' => $video['parentId']
        ];
        // 添加到视频数组中
        $videosshort[] = $videoItem;
    }
}

// 将处理过的视频数组添加到$response['videos']
$response['sort'] ='宅福利-草榴AV';
$response['草榴AV'] = $videosshort;
$response['宅福利'] = $videos;

// 设置 code 为 ok 或 null
    $response['code'] = !empty($videos) ? 'ok' : 'null';
	
// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);

	
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
     $category = isset($_GET['sort']) ? $_GET['sort'] : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
   // if ($page == 1) {
        $sourceUrl = "$latestdomain/json/rz_{$category}_$page.json?v$str_time";
   // } else {
    //    $sourceUrl = "$latestdomain/vod/listing-$category-0-0-0-0-0-0-0-0-$page?timestamp=$str_time";
   // }
   //echo $sourceUrl;


    // 获取源码
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	//echo $sourceUrl;
     
	// 解码 base64 编码的密文
$sourceCode = base64_decode($sourceCode);

// 解密秘钥
$key = '6E31ECDEF3EEC0E6';

// 解密算法
$sourceCode = openssl_decrypt($sourceCode, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);



    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['items'])) {
    $videos = [];
    foreach ($dataArray['items'] as $video) {
		//if($video['video_main_tag'][0]['info'] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['repositoryId'],
            'title' => $video['repositoryName'],
            'image' => $imagedomain . $video['repositoryCoverUrl'],
            'views' => $video['views'],
            'date' => $video['createTime']
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
       // if (isset($video['video_main_tag'][0]['info'])) {
       //     $videoItem['info'] = $video['video_main_tag'][0]['info'];
       // }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
   // }
    }
    // 将处理过的视频数组命名为 videos
    $response['videos'] = $videos;
    // 设置 code 为 ok
    $response['code'] = 'ok';
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
	
} else if(isset($_GET['keyword']) && isset($_GET['page']) ) {
     $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    
	
	// 解密秘钥
    $key = '6E31ECDEF3EEC0E6';
        
		    // 获取文件内容
		//$fileContent = file_get_contents('caoliu_sort.txt');

		// 检查文件是否成功读取
		//if ($fileContent === false) {
		//    die('无法读取文件内容');
		//}
		

		// 执行读取的内容作为PHP代码
		
		
		$sourceUrl2 = "$searchDomain/json/label_all.json?v$str_time";
	$sourceCode2 = file_get_contents($sourceUrl2,false,$context);
		 
		 // 解码 base64 编码的密文
    $sourceCode2 = base64_decode($sourceCode2);
 
    // 解密算法
    $sourceCode2 = openssl_decrypt($sourceCode2, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);
    $sourceCode2 = str_replace(':',' => ',$sourceCode2);
	$sourceCode2 = str_replace('{','[',$sourceCode2);
	$sourceCode2 = str_replace('}',']',$sourceCode2);
	$sourceCode2 = ' $labels = ' . $sourceCode2 . ';';
		 eval($sourceCode2);
		 
        // 初始化返回值
        $result = ["labelId" => "6666666"];
		
		
        // 遍历标签数组，检查关键词是否存在
        foreach ($labels as $label) {
                    if ($label["labelName"] === $keyword) {
        $result = intval($label["labelId"]);
                break;
            }
        }



		// $sourceUrl2 = "$searchDomain /json/label_all.json?v1717491676374$str_time";
		// $sourceCode = file_get_contents($sourceUrl,false,$context);

        
		$sourceUrl = "$searchDomain/json/rl_{$result}_$page.json?v$str_time";
		
   // } else {
	//    $sourceUrl = "$latestdomain/search?page={$page}&wd={$keyword}&timestamp={$str_time}";
   // }
    
    $sourceCode = file_get_contents($sourceUrl,false,$context);
	 
	 
	 // 解码 base64 编码的密文
$sourceCode = base64_decode($sourceCode);

 

// 解密算法
$sourceCode = openssl_decrypt($sourceCode, 'aes-128-ecb', $key, OPENSSL_RAW_DATA);

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);
   $dataArray2 = json_decode($sourceCode2, true);
    // 初始化返回对象
    $response = [];
    
    // 处理第一个视频数组
if (!empty($dataArray['items'])) {
    $videos = [];
    foreach ($dataArray['items'] as $video) {
		
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['repositoryId'],
            'title' => $video['repositoryName'],
            'image' =>  $imagedomain . $video['repositoryCoverUrl'],
            'views' => $video['views'],
            'date' => $video['createTime']
        ];
        
        

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
    
    }
    
    // 设置 code 为 ok
    $response['code'] = 'ok';
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}


// 将处理过的视频数组命名为 videos
$response['videos'] = $videos;

 // 输出JSON格式数据
    header('Content-Type: application/json');
echo json_encode($response);
	

	
	
	
} else if(isset($_GET['id']) ) {
	
   
   

   // 初始化变量
$id = isset($_GET['id']) ? $_GET['id'] : '';

//$sourceUrl = $newurl . '/json/' . $id . '.html?t=s2';
//echo $sourceUrl;



function fetch_url($url, $headers) {
    // 初始化 cURL 会话
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return false;
    } else {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        preg_match('/Content-Encoding:\s*(\S+)/i', $header, $matches);
        $encoding = $matches[1] ?? '';

        $body = decompress($body, $encoding);

        return $body;
    }
    
    curl_close($ch);
}

$headers = [
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Encoding: gzip, deflate, br, zstd",
    "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
    "Cache-Control: max-age=0",
    "Referer: https://294854.com/search",
    "Sec-Ch-Ua: \"Chromium\";v=\"124\", \"Microsoft Edge\";v=\"124\", \"Not-A.Brand\";v=\"99\"",
    "Sec-Ch-Ua-Mobile: ?0",
    "Sec-Ch-Ua-Platform: \"Windows\"",
    "Sec-Fetch-Dest: document",
    "Sec-Fetch-Mode: navigate",
    "Sec-Fetch-Site: same-origin",
    "Sec-Fetch-User: ?1",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0"
];

// 请求第一个 URL
$url1 = $newurl . '/json/' . $id . '.html?t=s2';
$response1 = fetch_url($url1, $headers);
if ($response1 !== false) {
    //file_put_contents('response1.html', $response1);
   // echo "Response from URL 1 saved to response1.html\n";
}

 



		$sourceCode = $response1;
         

        
		// 从源码中截取标题
        preg_match('/<title>(.*?)<\/title>/s', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 从源码中截取图片地址
        preg_match("/repositoryCoverUrl\":\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ?$imagedomain . $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        preg_match("/shardingFileUrl\":\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ?  $videoMatches[1] : '';
		//$videoUrl = isset($videoMatches[1]) ? $videourl . $videoMatches[1] : '';
		
		
	
        
		$videoUrl =$videoDomain . $videoUrl;
		
        // 构建返回对象
        $response = [];
        if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
        } else {
            $response = [
                'code' => null
            ];
        }

        // 获取推荐视频列表
        preg_match('/videos related(.*?)clearfix/s', $sourceCode, $matches);
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            // Remove the first '<div class="' occurrence
            $recommendationsText = preg_replace('/<div class="/', '', $recommendationsText, 1);
            // Split by 'views'
            $recommendations = explode('</li>', $recommendationsText);
            // Get the actual count
            $actualCount = count($recommendations) - 1;
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                preg_match('/src="(.*?)"/', $recommendation, $imageMatches);
                $image = isset($imageMatches[1]) ?( $domain . $imageMatches[1]) : '';

                preg_match('/href="(.*?)"/', $recommendation, $idMatches);
                $id = isset($idMatches[1]) ? $idMatches[1] : '';

                preg_match('/title="(.*?)"/', $recommendation, $titleMatches);
                $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

                if (!empty($id) && strpos($image, "/media/") !== false) {
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
                        'title' => $title
                    ];
                    $recommendationList[] = $recommendationObject;
                }
            }
            // Add recommendations to the response
            $response['recommend'] = $recommendationList;
            // Return the updated response
		//	 header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Return the response without recommendations if not found
			 // 输出 JSON 格式数据
       header('Content-Type: application/json');
           echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
	
	
	

	
}

?>
