<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306
//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?sort=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1


  



// 解析 GET 请求参数
// 判断是否传入了参数
    $latestdomain ="https://hongkongdollvideo.com";
	
	
if (isset($_GET['actress'])  ) {
	
$sourceUrl =$latestdomain ."/stars/";

$sourceCode = file_get_contents($sourceUrl);

  $sourceCode=str_replace("href=\"#1\">","",$sourceCode);

//echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/row mt-2 px-2(.*?)<script>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];

        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</a>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/<img src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
           preg_match('/href="(.*?)"/s', $category, $matches);
		    $id = isset($matches[1]) ?str_replace("&pg=1","",$matches[1]) : '';
			
		   // 提取标题
           preg_match('/title="(.*?)"/s', $category, $matches);
		   $title = isset($matches[1]) ?  $matches[1]  : '';
		   
		   // 提取数量题
           preg_match('/count">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]  : '';

            // 提取数据
           preg_match('/href="(.*?)">(.*?)<span>(.*?)<\/span>/s', $category, $datamatches);
		   $id = isset($datamatches[1]) ?$datamatches[1] : '';
		   $title = isset($datamatches[2]) ?  $datamatches[2]  : '';
		   $number = isset($datamatches[3]) ?  $datamatches[3]  : '';
		   
           

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
                // 构建分类对象
                $categoryObject = [
                    //'count' => $count,
                    'id' => $id,
					'image' => "https://www.diwangzhijia0.com/uploadenterprise/d66dc186-637b-4029-9ecd-1776b80b9583/1615656927411864517.jpg",
                    'title' => $title,
					'number' => $number ."部作品"
                ];

                // 将分类对象添加到结果数组中
                $result[] = $categoryObject;
            }
        }

        // 构建返回对象
        $response = [
            'code' => count($result) > 0 ? 'ok' : null,
            'actresses' => $result
        ];

        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 如果未匹配到结果，则返回错误信息
        echo json_encode(['code' => null]);
    }
	
	
	


	
// 判断是否存在GET请求参数
}else if(isset($_GET['sort']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $category = $_GET['sort'];
    $page = $_GET['page'];

     
        $url = $latestdomain .$category."/$page.html";
        
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/class="page-title-box(.*?)row mt-3 mb-3/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('video-item', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/data-src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ?   $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/title="(.*?)"/s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/date">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                // 检查图片是否为空bg-opacity-75">
               if (!empty($id)) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" => $id,
                        "image" => $image,
                        "date" => $number,
						"title" => $title
                    );
                    $videos[] = $video;
                }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
        
    
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $keyword = $_GET['keyword'];
    $page = $_GET['page'];

       
      $url = $latestdomain ."/search/$keyword/$page.html" ;
        
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/class="page-title-box(.*?)row mt-3 mb-3/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('video-item', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/data-src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ? $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/title="(.*?)"/s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/date">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                // 检查图片是否为空bg-opacity-75">
               if (!empty($id)) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" =>  $id,
                        "image" => $image,
                        "date" => $number,
						"title" => $title
                    );
                    $videos[] = $video;
               }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
    
}elseif(isset($_GET['id'])) {
    
    // 获取参数
    $id = isset($_GET['id']) ? $_GET['id'] : '';
     
        // 构建目标网站的 URL
        $sourceUrl = $latestdomain .  $id;
		
        // 获取源码
        $sourceCode = file_get_contents($sourceUrl);

// 将文本a中的第一和第二个“<a href=”替换为空
    //$sourceCode = preg_replace('/url/', '', $sourceCode, 2);
	
	
        // 从源码中截取标题
        preg_match('/name":"(.*?)"/', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
        
        // 从源码中截取图片地址
        preg_match("/poster\":\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        //preg_match("/<iframe src=\"(.*?)\"/", $sourceCode, $videoMatches);
        //$videoUrl = isset($videoMatches[1]) ? $latestdomain . $videoMatches[1] : '';

        //$Urlcode =  file_get_contents($videoUrl);
		
		// 从源码中截取视频地址
        //preg_match("/m3u8url =  '(.*?)'/", $Urlcode, $videoMatches);
       // $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
		
		// 从源码中截取hash_id
        preg_match("/video\/(.*?).html/", $id, $hash_idMatches);
        $hash_id = isset($hash_idMatches[1]) ? $hash_idMatches[1] : '';
		
		//从源码中截取 video_arg
        preg_match("/embed\/(.*?)\"/", $sourceCode, $video_argMatches);
        $video_arg = isset($video_argMatches[1]) ? $video_argMatches[1] : '';
		
		//从源码中截取 encryptedString
        preg_match("/arg\":\"(.*?)\"/", $sourceCode, $encryptedStringMatches);
        $encryptedString = isset($encryptedStringMatches[1]) ? $encryptedStringMatches[1] : '';
		
		
		// 解密视频地址
		function base64UrlEncode($input) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($input));
}

function base64UrlDecode($input) {
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $input));
}

function strDecode($string, $key) {
    $string = base64_decode($string);
    $len = strlen($key);
    $code = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $k = $i % $len;
        $code .= chr(ord($string[$i]) ^ ord($key[$k]));
    }
    return urldecode(base64_decode($code));
}

function generateVideoAddress($hash_id, $video_arg, $encryptedString) {
    $timestamp = substr($video_arg, -10);
    $reversed = strrev($hash_id . '-' . $timestamp);
    $key = base64UrlEncode($reversed);

    // 解码视频源
    $videoSrc = strDecode($encryptedString, $key);

    return $videoSrc;
}

// 示例调用
//$hash_id = '06eb8d571092bce7';
//$video_arg = '4c411756464351541608445e404c44430d4c4b4d01001c52505c540606081a53580e4b4f5a55575e1b5f55430c17060005504b09001e01001b565e03560b04000f4c0d5757544a1f5901130e161416405810105c411308135c46124647021b1f5f0c0a5e585e5c56505d0a5a42515055584d07565e1e4659415f0419060806041853501403001d09070055194050415d554d0e4954131e135c53155e6b5150120d41540f56530a55010557060d0a5653525446441716745056';
//$encryptedString = 'LyIHRy0cHF8DWgBfADhpXxc8FFkAAC4CFGYxRBcuKRAXF2wdLFhlGwM/CwEvPRdfLyplSgg6fhMpPgwbAD86SBcAIRMQABMwAy4YGwM/CEkDORBKHz5hBxQABxMALipDBwApdjsTXQIADD4CLD4bRgEVbA4=';

$videoAddress = generateVideoAddress($hash_id, $video_arg, $encryptedString);
//echo $videoAddress;
$videoUrl=$videoAddress;





        
		
        // 构建返回对象
        $response = [];
        //if (!empty($videoUrl)) {
            $response = [
                'code' => 'ok',
                'title' => $title,
                'image' => $imageUrl,
                'video' => $videoUrl,
                'recommend' => []
            ];
       // } else {
       //     $response = [
        //        'code' => null
         //   ];
       // }

        // 获取推荐视频列表
        preg_match('/相关推荐(.*?)<script>/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('video-item', $recommendationsText);
            //array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
            preg_match('/data-src="(.*?)"/s', $recommendation, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/href="(.*?)"/s', $recommendation, $idmatches);
		    $id = isset($idmatches[1]) ?  str_replace( $latestdomain,"",$idmatches[1]) : '';
			
		   // 提取标题
           preg_match('/alt="(.*?)"/s', $recommendation, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取更新日期
           preg_match('/date">(.*?)</s', $recommendation, $numbermatches);
		   $date = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                
         
		if(!empty($id)){
                    $recommendationObject = [
                        'image' => $image,
                        'id' => $id,
						//'duration' => $duration,
                       // 'playtimes' => $playtimes,
						'date' => $number,
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
