<?php

// 检查是否有参数传递
// https://api.22ba4.top/avtb/sort.php?way
    
	
     //获取最新域名
    $hostUrl = "http://hsck.net/";
    $hostCode = file_get_contents($hostUrl);
    preg_match('/var strU="(.*?):8899/s', $hostCode, $hostmatches);
    $domain = $hostmatches[1];

	
    // 获取分类接口
    $initialUrl = $domain . ":8899/?u=http://hsck.net/&p=/引用站点策略:strict-origin-when-cross-origin";
   // $sourceUrl = str_replace("https","http",$domain . "/vodtype/1.html");
	
	 // 获取重定向后的目标地址
    $headers = get_headers($initialUrl, 1);
    $redirectUrl = isset($headers['Location']) ? $headers['Location'] : '';
	
	$sourceUrl = $redirectUrl . "/vodtype/1.html";
    // 获取源码
    $sourceCode = file_get_contents($sourceUrl);

    // 利用正则表达式截取文本
    preg_match('/stui-pannel(.*?)<\/ul>/s', $sourceCode, $matches);

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
            preg_match('/<img src="(.*?)"/', $category, $imageMatches);
		$image = isset($imageMatches[1]) ? ( $domain . $imageMatches[1]) : '';

            // 提取分类ID，并替换 "https://www.kedou.xxx/categories" 为空
            preg_match('/href="(.*?)"/', $category, $idMatches);
            $id = isset($idMatches[1]) ? $idMatches[1] : '';

            // 提取标题
           preg_match('/<span[^>]*>(\d+)<\/span>\s*([^<]+)/s', $category, $matches);

           // 提取匹配到的数字和文本内容
           $count = isset($matches[1]) ? $matches[1] : '';
           $title = isset($matches[2]) ? trim($matches[2]) : '';

            // 判断 id 不为空且 image 包含 "/categories/"
            if (!empty($id) ) {
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

?>
