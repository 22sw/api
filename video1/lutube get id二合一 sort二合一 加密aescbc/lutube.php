<?php

// 获取所有分类 https://api.yujiameimei.com/lutube/lutube.php?getsort
// 获取某个分类下视频列表   https://api.yujiameimei.com/lutube/lutube.php?sort=15&page=1
// 搜索  https://api.yujiameimei.com/lutube/lutube.php?keyword=美女&page=1
// 获取视频详情【这里需要传入参数&type long或short ，长或者短视频 】  https://api.yujiameimei.com/lutube/lutube.php?id=196602&type=long



function decryptDataFromURL($url) {
    // 创建一个 cURL 句柄
    $ch = curl_init();

    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应数据而不是直接输出
    curl_setopt($ch, CURLOPT_HEADER, true); // 返回响应头

    // 添加 Cookie
    curl_setopt($ch, CURLOPT_COOKIE, "_ga=GA1.1.1785320966.1714753907; lu_s=71acddeb-6adf-4823-a1ce-47402c78e460; _ga_50EZPWKYX0=GS1.1.1714811215.2.1.1714811282.0.0.0");

    // 忽略 SSL 证书验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // 发送请求并获取响应
    $response = curl_exec($ch);

    // 检查是否发生错误
    if ($response === false) {
        echo "cURL Error: " . curl_error($ch);
        exit;
    }

    // 获取响应头信息
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);

    // 解析响应头
    $headers = [];
    foreach (explode("\r\n", $header) as $i => $line) {
        if ($i === 0) {
            $headers['http_code'] = $line;
        } else {
            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
    }

    // 获取 x-vtag 字段的值
    $x_vtag = isset($headers['x-vtag']) ? $headers['x-vtag'] : 'Not Found';

    // 计算 IV
    $IV = substr(md5($x_vtag), 8, 16); // 计算 MD5 哈希值并截取16字节

    // 解密响应数据
    $response_body = substr($response, $header_size); // 获取响应体部分
    $encrypted_data = base64_decode($response_body); // 解码响应体
    $key = '322b63a3be0567ae7cae7a2f368ee38a'; // 秘钥

    // 使用 OpenSSL 解密数据
    $decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $IV);

    // 输出解密后的明文
  //header('Content-Type: application/json');
     // echo $decrypted_data;
    return $decrypted_data;
    // 关闭 cURL 句柄
    curl_close($ch);
}




    // 获取当前时间的十三位时间戳
    list($t1, $t2) = explode(' ', microtime());
    $str_time = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);
    
	//token经常会变  需要重新抓包
	$token = 'token=eyJ1c2VyX2lkIjoyNDkzOTE3NTgsImxhc3Rsb2dpbiI6NCwiZXhwaXJlZCI6MTcxNTA3NDg1OH0.f6341c4b7b003d41e4c90c185796c44b.72b80528a2b88bc1458ccc719e22cd86dfc422e43de0692d9a87e65d';
	
	
    //最新落地页  在进入首页时，地址栏会显示 下面的3个url
	$newurl =  'https://web-aws.wfjcm.com';
	
    $latestdomain = 'https://pwc-aws.dingdongzhh.net';
    
	//host  id处需要
	$host =  'https://tez.haydibahse.com';
	
	$imgUrl = "https://imz.shixianzixun.net";
	
	//videourl  需要抓包
	$videourl = "https://pwc-aws.shixianzixun.net";
	
	
	
// 判断是否传入了参数
if (isset($_GET['getsort'])) {
    
	
	//$url = "$latestdomain/api/v1/menu?$token";
   
   $url = "$latestdomain/api/v1/menu?$token";
  // echo  $url;
   
    $sourceCode = decryptDataFromURL($url);
    //echo  $sourceCode;
   // $sourceCode = decryptDataFromURL($url);

   // echo $sourceCode;
	
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];
	
	// 处理长视频数组
if (!empty($dataArray['data']['long'])) {
    $videos = [];
    foreach ($dataArray['data']['long'] as $video) {
        // 修改键名
        $videoItem = [
			'categorie' => 'long',
            'id' => $video['id'],
            'title' => $video['name']
            
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
}

// 处理短视频数组
if (!empty($dataArray['data']['short'])) {
    foreach ($dataArray['data']['short'] as $video) {
        // 构建短视频项的关联数组
        $videoItem = [
		    'categorie' => 'short',
            'id' => $video['id'],
            'title' => $video['name']
        ];
        // 添加到视频数组中
        $videos[] = $videoItem;
    }
}

// 将处理过的视频数组命名为 videos
$response['categories'] = $videos;

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
	
} else if (isset($_GET['sort']) && isset($_GET['page'])) {
     $category = isset($_GET['sort']) ? intval($_GET['sort']) : 1;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

     // 构建目标网站的 URL
   // if ($page == 1) {
       // $sourceUrl = "$latestdomain/api/v4/menu/$category/layout?token=eyJ1c2VyX2lkIjoyNDkzOTE3NTgsImxhc3Rsb2dpbiI6NCwiZXhwaXJlZCI6MTcxNTA2MzY5MX0.004dfaedfd5202e503c94ea8d94dff3f.2d3cbe32584a21678dcb7708b79a57ec326a460c476b66854fcd6713&page=$page";
   // } else {
      $sourceUrl =  $latestdomain . "/api/v4/menu/$category/layout/" . (($category - 13) * 7 + 17) ."?$token&page=$page";
   // }
// $sourceUrl = 'https://pwc-aws.haydibahse.com/api/v4/menu/16/layout?token=eyJ1c2VyX2lkIjoyNDkzOTE3NTgsImxhc3Rsb2dpbiI6NCwiZXhwaXJlZCI6MTcxNTA2MzY5MX0.004dfaedfd5202e503c94ea8d94dff3f.2d3cbe32584a21678dcb7708b79a57ec326a460c476b66854fcd6713&page=1';

    $sourceCode = decryptDataFromURL($sourceUrl);
	 // echo$sourceCode;
	

    // 获取源码
   // $sourceCode = file_get_contents($sourceUrl);
	//echo $sourceUrl;

    

    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);

    // 初始化返回对象
    $response = [];

  // 处理视频数组
if (!empty($dataArray['data'])) {
    $videos = [];
    foreach ($dataArray['data'] as $video) {
		//if($video['main_tags'][0] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $imgUrl . $video['cover'],
            'views' => $video['video_views'],
            'date' => date("Y-m-d",$video['publish_time']),
            'duration' => round($video['duration']/60) ."分钟",
			'isvip' => isset($video['main_tags'][0]) ? $video['main_tags'][0] : '0'
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
         
           
         // $videoItem['isvip'] =  isset($video['main_tags'][0]) ? $video['main_tags'][0] : '0';

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
   
   
   $sourceUrl =  $latestdomain . "/api/v2/long/search/keyword?$token&query=$keyword&page= $page";
      $sourceUrl2 =  $latestdomain . "/api/v2/short/search/keyword?$token&query=$keyword&page= $page";
	  
    $sourceCode = decryptDataFromURL($sourceUrl);
    $sourceCode2 = decryptDataFromURL($sourceUrl2);

	 
	 
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);
   $dataArray2 = json_decode($sourceCode2, true);
    // 初始化返回对象
    $response = [];
    
    // 处理第一个视频数组
if (!empty($dataArray['data'])) {
    $videos = [];
    foreach ($dataArray['data'] as $video) {
		//if($video['main_tags'][0] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
		    'categorie' => 'long',
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $imgUrl . $video['cover'],
            'views' => $video['video_views'],
            'date' => date("Y-m-d",$video['publish_time']),
            'duration' => round($video['duration']/60) ."分钟",
			'isvip' => isset($video['main_tags'][0]) ? $video['main_tags'][0] : '0'
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
       // if (isset($video['video_main_tag'][0]['info'])) {
       //     $videoItem['info'] = $video['video_main_tag'][0]['info'];
       // }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
    //}
    }
    
    // 设置 code 为 ok
    $response['code'] = 'ok';
} else {
    // 视频数组为空，设置 code 为 null
    $response['code'] = 'null';
}

// 处理第二个视频数组
if (!empty($dataArray2['data'])) {
    foreach ($dataArray2['data'] as $video) {
		//if($video['main_tags'][0] !='VIP'){
        // 构建视频信息数组
        $videoItem = [
		    'categorie' => 'short',
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $imgUrl . $video['cover'],
            'views' => $video['video_views'],
            'date' => date("Y-m-d",$video['publish_time']),
            'duration' => round($video['duration']/60) ."分钟",
			'isvip' => isset($video['main_tags'][0]) ? $video['main_tags'][0] : '0'
        ];
        
        // 如果视频存在主标签信息，则添加主标签信息中的 'info' 到视频信息数组中
       // if (isset($video['video_main_tag'][0]['info'])) {
       //     $videoItem['info'] = $video['video_main_tag'][0]['info'];
       // }

        // 添加视频信息数组到视频数组中
        $videos[] = $videoItem;
	//	}
    }
}

// 将处理过的视频数组命名为 videos
$response['videos'] = $videos;

 // 输出JSON格式数据
    header('Content-Type: application/json');
echo json_encode($response);

	

	
	
	
} else if(isset($_GET['id']) ) {
	
   
   
   // 初始化变量
$id = isset($_GET['id']) ? $_GET['id'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';


$sourceUrl = $latestdomain . "/api/v2/$type/info/$id?$token&host=$host";
$sourceUrl2 = $latestdomain . "/api/v2/" . $type ."/recommend/" . $id . '?' . $token;




 $sourceCode = decryptDataFromURL($sourceUrl);
    $sourceCode2 = decryptDataFromURL($sourceUrl2);

	 
	 
    // 将获取的 JSON 数据转换为 PHP 数组
    $dataArray = json_decode($sourceCode, true);
   $dataArray2 = json_decode($sourceCode2, true);



// 初始化返回对象
$response = [];

// 设置 code 属性
$response['code'] = 'ok';

// 处理视频信息
if (!empty($dataArray['data'])) {
    $response['id'] = $dataArray['data']['id'];
    $response['title'] = $dataArray['data']['title'];
    $response['image'] =  $imgUrl . $dataArray['data']['cover'];
   // if (isset($dataArray['data']['urls'])) {
        $response['240p'] =  $videourl. $dataArray['data']['urls']['240'];
		//$response['480p'] =  $videourl . $dataArray['data']['urls']['480'];
		if (!empty($dataArray['data']['urls']['240p'])) {
   		 // 如果存在，则将 $response['480p'] 设置为完整的视频 URL
 		   $response['240p'] = $videourl . $dataArray['data']['urls']['240p'];
		} else {
		   // 如果不存在，则将 $response['480p'] 设置为空字符串
 			 $response['240p'] = '';
		}
		
		if (!empty($dataArray['data']['urls']['480'])) {
   		 // 如果存在，则将 $response['480p'] 设置为完整的视频 URL
 		   $response['480p'] = $videourl . $dataArray['data']['urls']['480'];
		} else {
		   // 如果不存在，则将 $response['480p'] 设置为空字符串
 			 $response['480p'] = '';
		}
		
		if (!empty($dataArray['data']['urls']['intro'])) {
   		 // 如果存在，则将 $response['480p'] 设置为完整的视频 URL
 		   $response['preview'] = $videourl . $dataArray['data']['urls']['intro'];
		} else {
		   // 如果不存在，则将 $response['480p'] 设置为空字符串
 			 $response['preview'] = '';
		}
		
		if (!empty($dataArray['data']['urls']['480'])) {
   		 // 如果存在，则将 $response['480p'] 设置为完整的视频 URL
 		   $response['video'] = $videourl . $dataArray['data']['urls']['480'];
		} else {
		   // 如果不存在，则将 $response['480p'] 设置为空字符串
 			 $response['video'] = '';
		}
		
		
		//$response['preview'] =  $videourl . $dataArray['data']['urls']['intro'];
		//$response['video'] =  $videourl . $dataArray['data']['urls']['480'];
   // }
    $response['code'] = 'ok'; // 设置 code 为 ok
} else {
    $response['code'] = 'null'; // 视频信息为空，设置 code 为 null
}

// 解析第二个请求的响应
$dataArray2 = json_decode($sourceCode2, true);

// 修改视频信息
if (!empty($dataArray2['data']['view'])) {
    $recommend = [];
    foreach ($dataArray2['data']['view'] as $video) {
		//if($video['main_tags'][0] !='VIP'){
        $modifiedVideo = [
             'categorie' => $type,
            'id' => $video['id'],
            'title' => $video['title'],
            'image' => $imgUrl . $video['cover'],
            'views' => $video['video_views'],
            'date' => date("Y-m-d",$video['publish_time']),
            'duration' => round($video['duration']/60) ."分钟",
			'isvip' => isset($video['main_tags'][0]) ? $video['main_tags'][0] : '0'
        ];
        if (isset($video['video_main_tag'][0]['info'])) {
            $modifiedVideo['info'] = $video['video_main_tag'][0]['info'];
        }
        $recommend[] = $modifiedVideo;
	//}
    }
    $response['recommend'] = $recommend;
}

// 输出JSON格式数据
header('Content-Type: application/json');
echo json_encode($response);
	
	
	

	
}

?>
