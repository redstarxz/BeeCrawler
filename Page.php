<?php
/**
* @file Page.php
* @brief 网页提取url类
* @author redstar, 332506119@qq.com
* @version 1
* @date 2013-06-04
*/

class Page
{
    public static function getUrlsFromPage($document)
    {
        $match = array();
        preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links);
        while(list($key,$val) = each($links[2])) {
            if(!empty($val))
                $match[] = $val;
        }
        while(list($key,$val) = each($links[3])) {
            if(!empty($val))
                $match[] = $val;
        }
        foreach ($match as $key => $url) {
            if (substr($url, 0, 4) !== 'http') {
                unset($match[$key]); 
            }
        }
        //return array($match,$links[4]);
        return array_unique($match);
    } 

}
