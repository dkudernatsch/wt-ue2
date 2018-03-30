<?php
switch ($request->location[1]){
    case 'image': image();
        break;
    case 'teapot':
        http_response_code(418);
        die("I am a teapot short and stout");
    default: {
        http_response_code(404);
        die("resource not found");
    }
}

function image(){
    if(isset($_FILES['image']) && $file = $_FILES['image']) {
        if($file['size'] < 1024 * 1024 * 5) {
            if(is_img($file['tmp_name'])) {

                $img = "img/uploads/" . getToken(10) . $file['name'];

                if (move_uploaded_file($file['tmp_name'], $img)) {
                    echo json_encode(ltrim($img, $_SERVER['DOCUMENT_ROOT']));
                } else {
                    http_response_code(500);
                    echo json_encode("error");
                }
            } else {
                http_response_code(400);
                die("Uploaded file not an image");
            }
        }else{
            http_response_code(413);
            die("Max Image upload 5MB");
        }
    }
    die();
}

function is_img($path){
    $a = getimagesize($path);
    $image_type = $a[2];

    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}