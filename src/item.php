<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 14.05.18
 * Time: 15:22
 */

class Item {
    public $link;
    public $title;
    public $desc;

    /**
     * Item constructor.
     * @param $link
     * @param $title
     * @param $desc
     */
    public function __construct($link, $title, $desc)
    {
        $this->link = $link;
        $this->title = $title;
        $this->desc = $desc;
    }

    public function to_html(){
        return "<div class='card'><div class='card-content'><h2 class='card-title'>".$this->title."</h2>".$this->desc."</div><a class='btn-block waves-block card-action' href='".$this->link."'>To the article</a></div>";
    }
}