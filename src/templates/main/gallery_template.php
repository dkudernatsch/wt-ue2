<?php
$gallery = new Gallery();
?>
<div class="gallery-container">
    <div id="images" class="grid">
        <?php
        foreach ($gallery->getImages() as $img) {
            echo "<img class='grid-item' src=" . ltrim($img->getThumbnail(), $_SERVER["DOCUMENT_ROOT"]) . " data-imgid=".$img->getId()." onclick='selectImage(this)'>";
            ?>
            <?php
        }
        ?>
    </div>
    <p></p>
    <?php if($_SESSION['current_user']) {?>
    <div class="file-upload-form">
        <form id="file-upload">
            <label for="input-file">Upload your own files</label>
            <input type="file" id="input-file" class="dropify" data-allowed-file-extensions="jpeg gif jpg png"/>
            <input class="waves-effect waves-light blue darken-2 btn" type="submit" value="Submit">
        </form>
    </div>
    <?php }?>
    <?php require_once "gallery_modal_template.php"?>
</div>
