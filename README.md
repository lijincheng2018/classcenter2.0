# 班级信息中心V2.0（Classcenter2.0)



## 目录介绍

目录分为src和sql，其中src中为程序源码，sql中的为数据库SQL代码



## 使用方法

将src中的代码上传至服务器（需要支持PHP且版本大于或等于7.0），修改`config.php`和`login_config.php`，将数据库账号和密码填入这两个文件。

将sql中的SQL文件导入数据库中，这是两个不一样的数据库，其中`classcenter_info`主要储存系统消息，包括账号密码、班级开放列表、班级数据库名等等，`classcenter_template`为班级数据库，每个班级对应一个（有几个班级创建几个一样的即可），数据库名可以修改，只要在`classcenter_info`中设定好即可。

手动将用户消息导入这两个数据库中，`classcenter_info`主要储存账号密码，所有班级用户的账号密码都储存于此，`classcenter_template`中主要储存用户详细资料，每个班级单独储存。

在数据库中导入密码时，需要导入密码的密文，加密方法为：md5(md5(原文) + 'ljc_sys') 【其中的'+'为字符串连接符】
