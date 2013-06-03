<?php
require_once 'Init.php';
require_once 'MyQueue.php';
require_once 'Page.php';
require_once 'Task.php';

/**
* @file Bee.php
* @brief 蜜蜂,职责是采集网页
* @author redstar, 332506119@qq.com
* @version 1
* @date 2013-06-04
*/
class Bee
{
    // 1:蜂王 or 0:工蜂
    private $isMaster;
    // 最大抓取深度
    private $crawlDeepth;
    // 当前的任务
    private $currentTask;

    public function __construct($isMaster = false, $dep = 2)
    {
        $this->isMaster = $isMaster;
        $this->crawlDeepth = $dep;
    }

    public function getMaxDep()
    {
        return $this->crawlDeepth;
    }

    public function getTask()
    {
        $this->currentTask = MyQueue::getInstance()->getJob();
    }

    public function doTask()
    {
        $this->currentTask->execute($this);
    }

    /**
    * @brief 将爬取到的url入队列
    *
    * @param $level 当前的爬取深度
    * @param $page 爬取到的网页
    *
    * @return Boolean
    */
    public function produceTask($level, $page)
    {
        $urls = Page::getUrlsFromPage($page);
        if (!is_array($urls)) {
            return false;
        }
        foreach ($urls as $url) {
            $task = new Task($level, $url);
            MyQueue::getInstance()->putTask($task);
        }
        return true;
    }

    /**
    * @brief initTarget 
    * 蜂王的初始化
    * @param $target 待爬取的目标url
    *
    * @return Boolean
    */
    public function initTarget($target)
    {
        $task = new Task(0, $target);
        MyQueue::getInstance()->putTask($task);
        return true;
    }

    public function work()
    {
        // 蜂王只初始化一次
        if ($this->isMaster) {
            // set target url
            $this->initTarget(TARGET_URL);
            echo 'Boss Bee set crawl target: '.TARGET_URL."\n";
            // Boss also need work too...
            //exit;
        }
        // 蜜蜂开始工作
        while (1) {
            // get task from queue
            $this->getTask();
            $this->doTask();
            // bee need to have a sleep
            sleep(1);
        }
    }
}

function help()
{
    echo "Usage: php ".__FILE__. " flag\n";
    echo "flag:0 means worker bee\n";
    echo "flag:1 means boss bee\n";
    echo "notice: only one boss bee is allowed!\n";
    exit(0);
}

function parseCommandLine($argv, $argc)
{
    if ($argc != 2) {
        help();
    }
    $flag = intval($argv[1]);
    if (in_array($flag, array(1,2))) {
        help();
    }
    return $flag;
}
// 从命名行传入参数来决定是否是蜂王
$bee = new Bee(parseCommandLine($argv, $argc));
$bee->work();
