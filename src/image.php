<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 10.04.18
 * Time: 20:29
 */

class Image
{
    private $id;
    private $dir;
    private $versions = [];
    private $last_version = 0;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->dir = $_SERVER['DOCUMENT_ROOT']."/img/uploads/$id/";
        $it  = new CallbackFilterIterator(new DirectoryIterator($this->dir),
            function ($file) {
                return $file->isFile() && preg_match("/v(\d)+.jpeg/", $file->getFilename());
        });
        foreach($it as $key => $value){
            $version = [];
            preg_match("/v(?<version>\d+).jpeg/", $value->getFilename(), $version);

            if((int) $version["version"] > $this->last_version)
                $this->last_version = (int) $version['version'];

            $this->versions[(int)$version['version']] = $value->getFilename();
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getThumbnail(){
        return $this->getDir()."thumb.jpeg?t=".time();
    }

    /**
     * @return string filename
     */
    public function newestVersion(){
        return $this->dir.$this->versions[$this->last_version];
    }

    public function get_new_version(){
        return $this->dir."v".($this->last_version+1).".jpeg";
    }

    /**
     * @return int
     */
    public function getLastVersion(): int
    {
        return $this->last_version;
    }

}