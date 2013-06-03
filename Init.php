<?php
// 抓取数据的仓库目录
define('STORE_DIR', '/home/work/crawl/data'); 
// 队列服务的ip
define('QUEUE_SERVER', '10.40.70.246'); 
// 队列服务tube名称
define('QUEUE_NAME', 'task'); 
// 需要抓取的目标网址
define('TARGET_URL', 'http://www.sina.com'); 
// 最大爬取深度
define('CRAWL_DEP', 2); 
