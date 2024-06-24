<?php

//  示例  主标签  https://api.22ba4.top/missav/misslive.php?way=new&primaryTag=女主播&page=2
// primaryTag  女主播  情侣  男主播  变性人

//示例  请求方式 搜索词 页码 主标签（girls men  trans couples） https://api.22ba4.top/missav/misslive.php?way=search&primaryTag=girls&key=lan&page=1


// 主标签名称和标签地址的映射关系
$primaryTagMapping = array(
    "女主播" => "girls",
    "情侣" => "couples",
    "男主播" => "men",
    "变性人" => "trans"
);

if(isset($_GET['primaryTag']) && isset($_GET['page'])&& isset($_GET['way'])&& $_GET['way'] === "new") {

// 获取主标签参数
$primaryTag = isset($_GET['primaryTag']) ? $_GET['primaryTag'] : "";
// 获取页码参数
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// 获取请求方式参数
$way = isset($_GET['way']) ? $_GET['way'] : 1;
// 计算偏移量
$offset = ($page - 1) * 12;

// 检查主标签是否有效
if (!array_key_exists($primaryTag, $primaryTagMapping)) {
    // 如果主标签无效，返回错误信息
    $responseData = array(
        'code' => 'error',
        'message' => 'Invalid primaryTag'
    );
} else {
    // 获取主标签对应的值
    $primaryTagValue = $primaryTagMapping[$primaryTag];
    // 构建采集网站的URL
    $url = "https://vr.myavlive.com/api/vr/v2/models?uniq=q14k8&limit=12&offset={$offset}&primaryTag={$primaryTagValue}";

    // 获取网站数据
    $html = file_get_contents($url);

    if ($html === false) {
        // 处理请求失败的情况
        $responseData = array(
            'code' => 'error',
            'message' => 'Failed to fetch data from the target website.'
        );
    } else {
        // 将JSON数据解码为PHP数组
        $data = json_decode($html, true);

        // 初始化响应数据
        $responseData = array('code' => null);

        // 如果models数组不为空，则将code设置为ok
        if (!empty($data['models'])) {
            $responseData['code'] = 'ok';

            // 循环处理models数组内的每个成员
            foreach ($data['models'] as &$model) {
                // 如果isOnline为false，则跳过该成员
                if (isset($model['isOnline']) && $model['isOnline'] === false) {
                    continue;
                }

                // 获取hlsPlaylist成员id的键值
                if (isset($model['hlsPlaylist'])) {
                    preg_match('/hls\/(.*?)\//', $model['hlsPlaylist'], $matches);
                    if (isset($matches[1])) {
                        // 保留原始的id
                        $model['originalId'] = $matches[1];
                        // 替换到目标URL的主播id处
                        $model['hlsPlaylist'] = "https://edge-hls.doppiocdn.net/hls/" . $matches[1] . "_vr/master/" . $matches[1] . "_vr_auto.m3u8?playlistType=lowLatency";
                    }
                }

                // 只保留指定键
                $model = array_intersect_key($model, array_flip(['gender', 'avatarUrl', 'country', 'previewUrlThumbBig', 'previewUrlThumbSmall', 'username', 'hlsPlaylist', 'status', 'viewersCount', 'id', 'isOnline', 'originalId']));
            }

            // 移除无效的数据（isOnline为false的成员）
            $data['models'] = array_filter($data['models'], function ($model) {
                return isset($model['isOnline']) && $model['isOnline'] === true;
            });

            // 重新索引数组键值
            $data['models'] = array_values($data['models']);
        }

        // 将响应数据设置到$responseData数组中
        $responseData['models'] = $data['models'];
    }
}

// 设置响应头为JSON格式
header('Content-Type: application/json');

// 输出数据
echo json_encode($responseData, JSON_PRETTY_PRINT);
} elseif(isset($_GET['key']) && isset($_GET['page'])&& isset($_GET['primaryTag'])&& $_GET['way'] === "search") {
	
// 获取请求参数
$searchWord = isset($_GET['key']) ? $_GET['key'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$primaryTag = isset($_GET['primaryTag']) ? $_GET['primaryTag'] : '';


// 计算偏移量
$offset = ($page - 1) * 12;

// 构建目标网站的 URL
$targetUrl = "https://vr.myavlive.com/api/vr/v4/models/search/group/username?uniq=8q94y&query=$searchWord&limit=12&offset={$offset}&primaryTag={$primaryTag}";

// 获取目标网站的源码
$sourceCode = file_get_contents($targetUrl);

// 解析源码
$data = json_decode($sourceCode, true);

if ($data && isset($data['models'])) {
    // 循环处理每个成员
    foreach ($data['models'] as &$member) {
        // 替换 hlsPlaylist
        if (isset($member['hlsPlaylist'])) {
            $hlsPlaylist = [];
            foreach ($member['hlsPlaylist'] as $playlist) {
                // 从 hlsPlaylist 成员中提取 id
                $id = isset($playlist['id']) ? $playlist['id'] : '';
                // 替换成新的 URL
                $newUrl = "https://edge-hls.doppiocdn.net/hls/$id\_vr/master/$id\_vr_auto.m3u8?playlistType=lowLatency";
                $hlsPlaylist[] = $newUrl;
            }
            $member['hlsPlaylist'] = $hlsPlaylist;
        }
        
        // 只保留指定键，且当 isOnline 为 true 时才添加进数组
        $filteredMember = array_intersect_key($member, array_flip(['gender', 'avatarUrl', 'country', 'previewUrlThumbBig', 'previewUrlThumbSmall', 'username', 'hlsPlaylist', 'status', 'viewersCount', 'id', 'isOnline']));
        if ($filteredMember['isOnline']) {
            $filteredData['models'][] = $filteredMember;
        }
    }
    
    // 输出最终结果
    header('Content-Type: application/json');
    echo json_encode($filteredData);
} else {
    echo json_encode(['error' => 'Data not found']);
}
}



?>
