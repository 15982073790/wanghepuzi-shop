该项目是workerman-jsonrpc改来的,是用来启动各个服务
启动的服务名/端口号/进程数在这里设置: 
./Applications/Config.php
每个服务代码放的位置为服务器固定位置
/data/rpcwww
具体查看 ./Applications/worker/start.php文件46行代码

以debug（调试）方式启动

php start.php start

以daemon（守护进程）方式启动

php start.php start -d

停止
php start.php stop

重启
php start.php restart

平滑重启
php start.php reload

查看状态
php start.php status
