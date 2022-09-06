### 脚本
```
# commands目录下复制`TestCommand`修改
# 命令: php artisan + $signature 字段内容

php artisan command:test
```

### 定时任务
```
# linux下

crontab -e
```
```
# 添加如下内容,项目目录对应

*/1 * * * * cd /opt/sites/laravel && php artisan schedule:run >> /dev/null 2>&1
```
