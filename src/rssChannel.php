<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 14.05.18
 * Time: 14:45
 */

class RssChannel
{
    public $title;
    public $items = array();

    public function to_html(){

        $items_html = "";

        foreach ($this->items as $item){
            $items_html.= $item->to_html();
        }

        return "<h1>".$this->title."</h1>".$items_html;

    }

}

