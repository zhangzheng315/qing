<?php
use think\Route;
// 案例部分
Route::rule('case','index/case_center/index'); // 案例中心
Route::rule('medical','index/case_center/medical'); // 医疗案例
Route::rule('caseDetail','index/case_center/caseDetail'); // 案例详情
Route::rule('finance','index/case_center/finance'); // 金融案例
Route::rule('education','index/case_center/education'); // 教育案例
Route::rule('car','index/case_center/car'); // 汽车案例
Route::rule('technology','index/case_center/technology'); // 科技案例
Route::rule('property','index/case_center/property'); // 地产案例

// 关于我们
Route::rule('about','index/about/index'); // 关于我们
// 我们的优势
Route::rule('advantage','index/advantage/index'); // 我们的优势
// 渠道合作
Route::rule('channel','index/channel/index'); // 渠道合作
// 定制化服务
Route::rule('customized','index/customized_service/index'); 
// 私有化部署-视频云服务
Route::rule('deploy','index/deploy/index'); 


Route::rule('docs','index/docs/index');  // 直播百科
Route::rule('article','index/docs/article');  // 直播百科
// 更多直播功能
Route::rule('more_live','index/more_live/index');  // 直播百科

// 轻学院
Route::rule('blog','index/qing_school/index');  // 轻学院内容中心
Route::rule('video','index/qing_school/videoCourse');  // 轻学院视频
// Route::rule('video','index/qing_school/videoCourse');  // 轻学院视频二级页面
Route::rule('blog/case','index/qing_school/caseAn');  // 轻学院内容中心



