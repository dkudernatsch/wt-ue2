let grid = $('.grid').masonry({
    columnWidth: 150,
    itemSelector: '.grid-item'
});

grid.imagesLoaded().progress( function() {
    grid.masonry('layout');
});

$(document).ready(function () {
    $(".button-collapse").sideNav();
    $('.materialboxed').materialbox();
    $('.dropify').dropify();
    $('#file-upload').submit(upload_file);
    $('.modal').modal();
});

function setup_dropify() {
    $('.dropify').dropify();
}


function show(id) {
    $(id).show()
}

function hide(id) {
    $(id).hide();
}

function upload_file(ev) {
    let data = new FormData();
    data.append("image", ev.target['input-file'].files[0]);

    upload(data);

    return false;
}

function upload(e) {
    fetch('https://localhost/api/image/upload',
        { method: 'POST'
        , credentials: 'include'
        , body: e})
        .then(response => response.json())
        .then(success => {
            if(success !== 'error'){
                let img = document.createElement("img");
                img.setAttribute("src", success.src);
                img.setAttribute("data-imgid", success.id);
                img.className = 'grid-item';
                img.onclick = function () {
                    selectImage(this);
                };
                let $img = $([img]);
                grid.append( $img ).masonry( 'appended', $img );
                setTimeout($('.grid').masonry({
                    columnWidth: 150,
                    itemSelector: '.grid-item'
                }), 100);
            }
        })
        .catch(error => console.log(error));
}

////////////////////////////////////////////////////////////////////

const ImageAction = Object.freeze({
    TurnClockwise: "rotate/90/",
    TurnCounterClockwise: "rotate/270/",
    Gray: "gray/",
    Mirror: "mirror/",
    Revert: "revert/",
    Delete: "delete/"
});

function selectImage(image){
    $('#gallery_modal').data('imgid', $(image).data('imgid'));
    $('#modal_img').attr('src', 'api/image/newest/'+$(image).data('imgid')+"?t="+Math.random());
    $('.modal').modal('open');
}

async function modifyImage(imageAction) {
    let imgid = $('#gallery_modal').data('imgid');
    if(imgid !== ""){
        let response = await fetch("api/image/"+imageAction+imgid, {credentials:"include"});
        let json = await response.json();
        if(imageAction !== ImageAction.Delete){
            $('#modal_img').attr('src', json.toString()+ "?t="+Math.random());
            let img = $('#images')
                .find(`[data-imgid="${imgid}"]`);
            img.attr('src', img.attr('src') +"?t="+ Math.random());
        }else{
            $('#modal_img').attr('src', '');
            let img = $('#images')
                .find(`[data-imgid="${imgid}"]`);
            img.remove();
        }
        setTimeout(() => {
            $('.grid').masonry({
                columnWidth: 150,
                itemSelector: '.grid-item'
            });
        }, 100);
    }
}
