

<div id="gallery_modal" class="modal" data-imgid="">

    <div class="modal-footer">
        <div id="gallery_modal_button_container">
            <button onclick="modifyImage(ImageAction.TurnClockwise);" class="waves-effect waves-light blue darken-2 btn">turn clockwise</button>
            <button onclick="modifyImage(ImageAction.TurnCounterClockwise);" class="waves-effect waves-light blue darken-2 btn">turn counter clockwise</button>
            <button onclick="modifyImage(ImageAction.Gray);" class="waves-effect waves-light blue darken-2 btn">grayscale</button>
            <button onclick="modifyImage(ImageAction.Mirror);" class="waves-effect waves-light blue darken-2 btn">mirror</button>
            <button onclick="modifyImage(ImageAction.Revert);" class="waves-effect waves-light blue darken-2 btn">revert</button>
            <button onclick="modifyImage(ImageAction.Delete);" class="waves-effect waves-light red darken-2 btn modal-close">delete</button>
        </div>
    </div>
    <div class="modal-content">
        <img id="modal_img" class="center-block" src="">
    </div>
</div>