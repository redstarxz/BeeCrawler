BeeCrawler
==========

BeeCrawler is a light webpage spider!

简介
====
BeeCrawler 是因为一道笔试题而开发的一个轻量级网页爬虫系统， 借鉴蜜蜂中蜂王，工蜂的角色， 蜂王

用来发号命令确定要爬取的目标， 工蜂领受任务后，执行具体的爬取工作。

（特别感谢面试官指出程序存在的种种问题，小小的系统，如果要用在生产实际中，需要考虑到各种可能的异常）


实现原理
========

1， 部署远程beanstalkd队列服务器

2， 在一台机器上启动蜂王脚本（php Bee.php 1）, 蜂王会向队列里push入目标网址，初始化工蜂的任务

3， 由部署在各个机器上的工蜂（php Bee.php 0）去获取队列中的任务，执行网页爬取, 将爬取到的网页存入本地文件

4， 工蜂在执行爬取过程中也会向队列push新找到的link

5,  爬取过程一直持续，直到队列中没有任务为止


第三方依赖
==========

1. 需要安装beanstalkd， 下载地址： https://github.com/kr/beanstalkd/archive/v1.9.tar.gz

2. beanstalkd的php客户端实现      代码地址：https://github.com/pda/pheanstalk

配置
====

配置比较简单，主要是配置beanstalkd的服务地址， tube名称， 数据存储目录， 爬取最大深度，目标网址

配置在Init.php中即可

性能
====
为最大限度提升爬取网页性能，BeeCrawler支持分布式部署，只需在不同机器上启动Bee.php脚本即可


todo
====

url 去重， 防止进入死循环， 可以考虑用一个key-value系统存储已经crawl过的网页url

超时设置，可以用curl设置请求超时，配合队列，实现超时重试策略

worker脚本监控，可以考虑用supervice工具进行监控，避免脚本意外退出

当前用file_get_contents去拉取网页，今后可以考虑使用curl，结合curl的并发请求能力，可以提高爬取性能

队列目前是单点， 在worker性能好的情况下，问题不大，今后可以考虑将队列也多机化部署

蜂王脚本不能重复启动，可以考虑加锁解决

异常处理，以及失败重试

