<?php

// https://api.22ba4.top/missav/kedouwo.php?sort=swag&page=2


// 获取参数
$category = isset($_GET['sort']) ? $_GET['sort'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
// 获取当前时间的十三位时间戳
   list($t1, $t2) = explode(' ', microtime());
   $timestamp = sprintf('%u', (floatval($t1) + floatval($t2)) * 1000);
	
// 构建目标网站的 URL
$sourceUrl = "https://www.kedou.xxx/categories/$category/?mode=async&function=get_block&block_id=list_videos_common_videos_list&sort_by=post_date&from=0$page&_=$timestamp";

// 获取源码
$sourceCode = file_get_contents($sourceUrl);

// 利用正则表达式截取文本
preg_match('/list-videos(.*?)pagination-holder/s', $sourceCode, $matches);

// 如果匹配到结果
if (isset($matches[1])) {
    // 提取内容
    $content = $matches[1];

    // 将文本a中的第一和第二个“<a href=”替换为空
    $content = preg_replace('/<a href="/', '', $content, 2);

    // 将文本a中的第一和第二个“<img src=”替换为空
    $content = preg_replace('/<img src="/', '', $content, 2);

    // 使用分隔符号“rating positive”分割得到数组
    $videos = explode('rating positive', $content);
    
    // 移除数组末尾的空成员
    array_pop($videos);

    // 初始化结果数组
    $result = [];

    // 循环处理每个视频
    foreach ($videos as $video) {
        // 提取图片地址
        preg_match('/src="(.*?)"/', $video, $imageMatches);
        $image = isset($imageMatches[1]) ? $imageMatches[1] : '';

        // 提取视频ID
        preg_match('/href="(.*?)"/', $video, $idMatches);
        $id = isset($idMatches[1]) ? $idMatches[1] : '';
        $id = isset($idMatches[1]) ? str_replace("https://www.kedou.xxx/videos", "", $idMatches[1]) : '';
        // 提取标题
        preg_match('/title="(.*?)"/', $video, $titleMatches);
        $title = isset($titleMatches[1]) ? $titleMatches[1] : '';

        // 构建视频对象
        $videoObject = [
            'image' => $image,
            'id' => $id,
            'title' => $title
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
?>
