<?php
require_once 'MyQueue.php';
require_once 'Store.php';
require_once 'Page.php';
/**
* @file Task.php
* @brief 蜜蜂当前处理的任务
* @author redstar, 332506119@qq.com
* @version 1
* @date 2013-06-04
*/

class Task
{
    private $url;
    private $level;

    public function __construct($level, $url)
    {
        $this->level = $level;
        $this->url = $url;
    }

    public static function getTask()
    {
        $obj = MyQueue::getInstance()->getJob();
        $str = $obj->getData();
        return self::formJobTask($str, $obj);
    }

    public function getContent()
    {
        return json_encode(
            array(
                'level' => $this->level,
                'url' =>  $this->url
                )
        );
    }

    private static function formJobTask($str, $job)
    {
        $jobData = json_decode($str, true);
        $task = new self($jobData['level'], $jobData['url']);
        $task->job = $job;
        return $task;
    }

    /**
    * @brief execute 执行爬取任务
    *
    * @param $bee
    *
    * @return 
    */
    public function execute($bee)
    {
        echo "begin crawling $this->url \n";
        $page = file_get_contents($this->url);
        // 删除完成的任务
        MyQueue::getInstance()->deleteJob($this->job);
        if (!$page) {
            echo " oh, shit! this page is empty\n";
            return;
        }
        // save current page
        Store::savePage($this->level, $page, $this->url);
        
        if ($this->level <= $bee->getMaxDep()) {
            // reproduce task to queue
            $this->produceTask($this->level + 1, $page);
        } 
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
            self::putTask($level, $url);
        }
        return true;
    }

    public static function putTask($level, $url)
    {
        $task = new self($level, $url);
        MyQueue::getInstance()->putJob($task->getContent());
    }
}
