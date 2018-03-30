<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 29.03.18
 * Time: 06:08
 */

class Gallery
{

    function __construct()
    {
        $it = new FilesystemIterator($_SERVER['DOCUMENT_ROOT']."/img/uploads/");
        foreach($it as $file){
            $this->images[] = $file;
        }
    }

    private $show_upload;
    private $images = array();

    /**
     * @return boolean
     */
    public function shouldShowUpload(): bool
    {
        return $this->show_upload;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

}