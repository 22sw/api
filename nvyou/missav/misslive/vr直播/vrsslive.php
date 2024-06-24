<?php

//示例  搜索词 页码 主标签（girls men  trans couples） http://api.22ba4.top/missav/vrsslive.php?key=taylor&&primaryTag=girls&page=1


if(isset($_GET['key']) && isset($_GET['page'])&& isset($_GET['primaryTag'])&& $_GET['way'] === "search")) {
	
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
