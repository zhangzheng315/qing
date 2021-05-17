<?php

use think\Route;

// 产品与服务
//视频云服务
Route::rule('private', 'index/deploy/index');                       //私有化部署
Route::rule('live', 'index/video_cloud/index');                     //轻直播平台

// 更多直播功能
Route::rule('function', 'index/more_live/index');                   //功能大全

//定制化服务
Route::rule('develop', 'index/customized_service/index');           //直播集成开发
Route::rule('develop/app', 'index/customized_service/applets');     //APP/小程序开发
Route::rule('develop-app', 'index/customized_service/applets');     //APP/小程序开发

//现场服务
Route::rule('service', 'index/site_service/index');                 //现场服务

// 解决方案
Route::rule('solution', 'index/solution/index');                    // 解决方案首页
Route::rule('solution/medical', 'index/industry/medicalCare');      // 行业-医疗直播
Route::rule('solution-medical', 'index/industry/medicalCare');      // 行业-医疗直播
Route::rule('solution/education', 'index/industry/education');      // 行业-教育直播
Route::rule('solution-education', 'index/industry/education');      // 行业-教育直播
Route::rule('solution/banking', 'index/industry/banking');          // 行业-金融直播
Route::rule('solution-banking', 'index/industry/banking');          // 行业-金融直播
Route::rule('solution-banking', 'index/industry/banking');          // 行业-金融直播
Route::rule('solution/media', 'index/industry/media');              // 行业-传媒直播
Route::rule('solution-media', 'index/industry/media');              // 行业-传媒直播

Route::rule('solution/train', 'index/solution/train');              // 场景-会议培训
Route::rule('solution-train', 'index/solution/train');              // 场景-会议培训
Route::rule('solution/market', 'index/solution/marketActivities');  // 场景-营销直播
Route::rule('solution-market', 'index/solution/marketActivities');  // 场景-营销直播
Route::rule('solution/online', 'index/solution/onlineRetailers');   // 场景-电商直播
Route::rule('solution-online', 'index/solution/onlineRetailers');   // 场景-电商直播
Route::rule('solution/enterprise', '/index/solution/enterprise');   // 场景-空中宣讲会
Route::rule('solution-enterprise', '/index/solution/enterprise');   // 场景-空中宣讲会
Route::rule('solution/medicine', '/index/solution/medical');        // 场景-医学会议
Route::rule('solution-medicine', '/index/solution/medical');        // 场景-医学会议
Route::rule('solution/operation', 'index/solution/operation');      // 场景-手术示教
Route::rule('solution-operation', 'index/solution/operation');      // 场景-手术示教
Route::rule('solution/meeting', 'index/solution/meetingLive');      // 场景-大会直播
Route::rule('solution-meeting', 'index/solution/meetingLive');      // 场景-大会直播
Route::rule('solution/annual', 'index/solution/annualMeeting');     // 场景-年会直播
Route::rule('solution-annual', 'index/solution/annualMeeting');     // 场景-年会直播

// 案例中心
Route::rule('case', 'index/case_center/index');                     // 案例中心
Route::rule('case-detail/[:id]/[:type_id]','index/case_center/caseDetail');          // 案例详情
Route::rule('case/detail','index/case_center/caseDetail');          // 案例详情
Route::rule('case/medical','index/case_center/medical');            // 医疗案例
Route::rule('case-medical','index/case_center/medical');            // 医疗案例
Route::rule('case/education','index/case_center/education');        // 教育案例
Route::rule('case-education','index/case_center/education');        // 教育案例
Route::rule('case/finance','index/case_center/finance');            // 金融案例
Route::rule('case-finance/[:pid]/[:label]','index/case_center/finance');            // 金融案例
Route::rule('case/car','index/case_center/car');                    // 汽车案例
Route::rule('case-car','index/case_center/car');                    // 汽车案例
Route::rule('case/technology','index/case_center/technology');      // 科技案例
Route::rule('case-technology','index/case_center/technology');      // 科技案例
Route::rule('case/property','index/case_center/property');          // 地产案例
Route::rule('case-property','index/case_center/property');          // 地产案例

// 轻学院
Route::rule('blog', 'index/qing_school/index');                     // 轻学院内容中心
Route::rule('blog/demo', 'index/qing_school/caseAn');               // 轻学院案例解析
Route::rule('blog-demo', 'index/qing_school/caseAn');               // 轻学院案例解析
Route::rule('blog/dynamic', 'index/qing_school/products');          // 轻学院产品动态
Route::rule('blog-dynamic', 'index/qing_school/products');          // 轻学院产品动态
Route::rule('blog/archives', 'index/qing_school/liveNews');         // 轻学院直播资讯
Route::rule('blog-archives', 'index/qing_school/liveNews');         // 轻学院直播资讯
Route::rule('video', 'index/qing_school/videoCourse');              // 轻学院视频
Route::rule('guide', 'index/qing_school/article');                  // 直播百科
Route::rule('blog/detail','index/qing_school/newsDetail');          // 轻学院内容详情页
Route::rule('blog-detail/[:id]/[:pid]','index/qing_school/newsDetail');          // 轻学院内容详情页
Route::rule('video/tutorial','index/tutorial/index');               // 轻学院视频详情
Route::rule('video-tutorial/[:id]/[:browse]','index/tutorial/index');               // 轻学院视频详情
Route::rule('video/second','index/qing_school/courseSecond');       // 轻学院视频二级页面
Route::rule('video-second/[:pid]','index/qing_school/courseSecond');       // 轻学院视频二级页面

// 关于轻直播
Route::rule('about', 'index/about/index');                          // 关于我们
Route::rule('advantage', 'index/advantage/index');                  // 我们的优势
Route::rule('news', 'index/corporate_news/news');                   // 企业新闻
Route::rule('partner', 'index/channel/index');                      // 渠道合作
Route::rule('join', 'index/join/joinUs');                           // 加入我们
Route::rule('contact', 'index/contact/contactUs');                  // 联系我们
Route::rule('news/detail', 'index/corporate_news/corporateDetail'); // 企业新闻详情
Route::rule('news-detail/[:id]', 'index/corporate_news/corporateDetail'); // 企业新闻详情
Route::rule('news/list', 'index/corporate_news/categoryNews');      // 企业新闻列表
Route::rule('news-list', 'index/corporate_news/categoryNews');      // 企业新闻列表

//登陆
Route::rule('login', 'index/login/login');                          //登陆

//footer
Route::rule('statement', 'index/index/legalNotice');                //法律声明
Route::rule('licence', 'index/index/licence');                      //增值电信经营许可证
Route::rule('certificate', 'index/index/licence');                  //一系列证书
Route::rule('privacy', 'index/index/privacy');                      //隐私协议
Route::rule('protocol', 'index/index/serviceAgreement');            //服务协议 Links

//统计
Route::rule('tj','admin/bai_du_controller/getData');                //百度统计






