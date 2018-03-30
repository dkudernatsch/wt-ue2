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
    fetch('https://localhost/api/image',
        { method: 'POST'
        , body: e})
        .then(response => response.json())
        .then(success => {
            if(success !== 'error'){
                let img = document.createElement("img");
                img.setAttribute("src", success);
                img.className = 'grid-item';
                let $img = $([img]);
                grid.append( $img ).masonry( 'appended', $img );
                grid = $('.grid').masonry({
                    columnWidth: 150,
                    itemSelector: '.grid-item'
                });
            }
        })
        .catch(error => console.log(error));
}