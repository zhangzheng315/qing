$(function () {
    var tvInfo = JSON.parse(localStorage.getItem('tvInfo'))
    daovoice('init', {
        app_id: '04cefad5', // 必填，您的 APP 标识
        user_id: tvInfo ? tvInfo.id : '', // 必填，用户id
        email: tvInfo ? tvInfo.email : '', // 选填，邮箱地址
        name: tvInfo ? tvInfo.name : '', // 选填
    }, {
        // 可选
        launcher: {
            disableLauncherIcon: true, // 悬浮 ICON 是否显示
            defaultEnterView: 'list', // 'list' 或 'new' 默认是 list(在对话列表是空的时候默认切换到 new)
        },
    });
    daovoice('update')
})