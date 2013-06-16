<?php
require_once 'pheanstalk/pheanstalk_init.php';
/**
* @file MyQueue.php
* @brief 存储待爬取的网页队列
* @author redstar, 332506119@qq.com
* @version 1
* @date 2013-06-04
*/
class MyQueue
{
    private static $instance;
    private $queue;

    private function __construct()
    {
        $this->queue = new Pheanstalk_Pheanstalk(QUEUE_SERVER);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getJob()
    {
       return $this->queue->watch(QUEUE_NAME)->ignore('default')->reserve();
    }

    public function putJob($job)
    {
        return $this->queue->useTube(QUEUE_NAME)->put($job);
    }

    public function deleteTask($job)
    {
        return $this->queue->delete($job); 
    }
}
