<?php

//分类  http://api.22ba4.top/missav/missav.php?tag=东京热&page=3
//搜索  http://api.22ba4.top/missav/missav.php?key=美女&page=3
//视频详情 http://api.22ba4.top/missav/missav.php?id=/cn/avsa-306
//女优视频列表 id（dm127/cn/actresses/波多野結衣） 页码 http://api.22ba4.top/missav/actresses.php?sort=/dm127/cn/actresses/波多野結衣&page=2
//女优列表 身高段 罩杯 年龄段 出道时间（2024年前） 页码  https://api.22ba4.top/missav/actresses.php?height=160-165&cup=b&age=20-24&debut=2024&page=1


  



// 解析 GET 请求参数
// 判断是否传入了参数
    $latestdomain ="https://xxooss.vip";
	
	
if (isset($_GET['getsort'])  ) {
	
	// 定义分类名称与对应数字索引的映射关系
    $categories = [
        '麻豆' => '麻豆',
        '91porn' => '91porn',
        '萝莉' => '萝莉',
        '潮吹' => '潮吹',
        'SM' => 'SM',
        '巨乳' => '巨乳',
        '探花' => '探花',
        '无码' => '无码'
         
    ];

    // 初始化分类数组
    $categoriesArray = [];

    // 遍历分类名称与数字索引的映射关系，构建ID和Title的形式
    foreach ($categories as $title => $index) {
        $categoriesArray[] = [
            'id' => $index,
            'title' => $title
        ];
    }

    // 构建返回对象
    $response = [
        'code' => !empty($categoriesArray) ? 'ok' : 'null',
        'categories' => $categoriesArray
    ];

    // 输出JSON格式数据
    header('Content-Type: application/json');
    echo json_encode($response);
	
	
	


	
// 判断是否存在GET请求参数
}else if(isset($_GET['sort']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $category = $_GET['sort'];
    $page = $_GET['page'];

     
        $url = $latestdomain ."/tag.php?url=$category&pg=" .$page;
        
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/xoxo2 blogroll2(.*?)<\/ul>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/<a href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ?   $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/img>(.*?)</s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/duration">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                // 检查图片是否为空bg-opacity-75">
               // if (!empty($image_match[1])) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" => "/".$id,
                        "image" => $image,
                        "date" => $number,
						"title" => $title
                    );
                    $videos[] = $video;
              //  }
            }
        }
        // 输出 JSON 格式数据
        header('Content-Type: application/json');
        
        echo json_encode(array("code" => "OK", "videos" => $videos));
        
    
} elseif(isset($_GET['keyword']) && isset($_GET['page'])) {
    // 处理分类列表接口请求
    $keyword = $_GET['keyword'];
    $page = $_GET['page'];

      
     $url = $latestdomain ."/tag.php?url=$keyword&pg=" .$page;
        
        $sourceCode = file_get_contents($url);

        //echo $sourceCode;
// 利用正则表达式截取文本
    preg_match('/xoxo2 blogroll2(.*?)<\/ul>/s', $sourceCode, $matches);

    // 如果匹配到结果
    if (isset($matches[1])) {
        // 提取内容
        $content = $matches[1];
//echo $content;
        // 使用分隔符号“rating positive”分割得到数组
        $categories = explode('</li>', $content);
        
        // 移除数组末尾的空成员
        array_pop($categories);

        // 初始化结果数组
        $result = [];

        // 循环处理每个分类
        foreach ($categories as $category) {
            // 提取图片地址
            preg_match('/src="(.*?)"/s', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/<a href="(.*?)"/s', $category, $idmatches);
		    $id = isset($idmatches[1]) ? $idmatches[1] : '';
			
		   // 提取标题
          preg_match('/img>(.*?)</s', $category, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/duration">(.*?)</s', $category, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                // 检查图片是否为空bg-opacity-75">
               // if (!empty($image_match[1])) {
                    // 替换 ID 中的文本 "$latestdomain" 为空
                    $id = str_replace("$latestdomain", "", $idmatches[1]);
					
                    $video = array(
                        "id" => "/". $id,
                        "image" => $image,
                        "date" => $number,
						"title" => $title
                    );
                    $videos[] = $video;
              //  }
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
        preg_match('/<title>(.*?)_XXOO/s', $sourceCode, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';
        
        // 从源码中截取图片地址
        preg_match("/poster=\"(.*?)\"/", $sourceCode, $imageMatches);
        $imageUrl = isset($imageMatches[1]) ? $imageMatches[1] : '';
        
		
		

        // 从源码中截取视频地址
        preg_match("/<iframe src=\"(.*?)\"/", $sourceCode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? $latestdomain . $videoMatches[1] : '';

        $Urlcode =  file_get_contents($videoUrl);
		
		// 从源码中截取视频地址
        preg_match("/m3u8url =  '(.*?)'/", $Urlcode, $videoMatches);
        $videoUrl = isset($videoMatches[1]) ? $videoMatches[1] : '';
		
		
        
		
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
        preg_match('/猜你喜歡(.*?)p-footer/s', $sourceCode, $matches);
		
		
        if (isset($matches[1])) {
            $recommendationsText = $matches[1];
            //echo $recommendationsText;
            // Split by 'views'
            $recommendations = explode('</p>', $recommendationsText);
            array_pop($recommendations);
            
            $recommendationList = [];
            foreach ($recommendations as $recommendation) {
                // 提取图片地址
            preg_match('/data-src="(.*?)"/s', $recommendation, $imageMatches);
		$image = isset($imageMatches[1]) ? $imageMatches[1] : '';

           // 提取id
          preg_match('/<a href="(.*?)"/s', $recommendation, $idmatches);
		    $id = isset($idmatches[1]) ? $idmatches[1] : '';
			
		   // 提取标题
           preg_match('/alt="(.*?)"/s', $recommendation, $titlematches);
		   $title = isset($titlematches[1]) ?  $titlematches[1] : '';
		   
		   // 提取数量题
           preg_match('/duration">(.*?)</s', $recommendation, $numbermatches);
		   $number = isset($numbermatches[1]) ?  $numbermatches[1]   : '';

                
        
		// 提取时长
        preg_match('/duration">(.*?)</', $recommendation, $durationMatches);
        $duration = isset($titleMatches[1]) ? $durationMatches[1] : '';
		
		// 提取更新日期 点赞 播放次数
        preg_match('/添加时间:<\/span>(.*?)</', $recommendation, $datamatches);
        //$dianzan = $datamatches[1]; 
        //$playtimes = $datamatches[2]; 
        $date = str_replace(' ','',$datamatches[1]); 
		
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
