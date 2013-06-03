<?php
/**
* @file Store.php
* @brief 
* @author redstar, 332506119@qq.com
* @version 1
* @date 2013-06-04
*/

class Store
{

    /**
    * @brief 爬虫网页本地存储
    *
    * @param $level
    * @param $page
    * @param $url
    *
    * @return 
    */
    public static function savePage($level, $page, $url)
    {
        $dir = STORE_DIR.'/'.'level'.$level.'/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($dir.md5($url), $page);
        return true;
    }

}
