<?php
switch ($request->location[1]) {
    case 'image':
        image($request->location);
        break;
    case 'rss':
        handle_rss(urldecode($request->location[2]));
        break;
    case 'register':
        register();
        break;
    case 'update':
        Database::GetConnection()->update_user();
        break;
    case 'teapot':
        http_response_code(418);
        die("I am a teapot short and stout");
        break;
}
http_response_code(404);
die("resource not found");

function handle_rss($link)
{
    require_once "rssChannel.php";
    require_once "item.php";

    $rssXml = new SimpleXMLElement(file_get_contents($link));

    $rss_channel = new \RssChannel();
    $rss_channel->title = $rssXml->channel->title;
    foreach ($rssXml->xpath('//item') as $item) {
        $rss_channel->items[] = new \Item($item->link, $item->title, $item->description);
    }

    http_response_code(200);
    echo $rss_channel->to_html();
    exit(0);
}


function register()
{
    $error = array();
    if (($fname = $_REQUEST['firstname'])
        && ($lname = $_REQUEST['lastname'])
        && ($user = $_REQUEST['username'])
        && ($pw = $_REQUEST['password'])
        && ($email = $_REQUEST['email'])) {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (strlen($pw) > 7) {
                try {
                    if (!Database::GetConnection()->try_create_user($fname, $lname, $user, $pw, $email)) {
                        $error[] = "Unable to create user in database";
                    } else {

                    }
                } catch (Exception $e) {
                    $error[] = $e->getMessage();
                }
            } else {
                $error[] = "Minimum password length is 7";
            }
        } else {
            $error[] = "Missing fields";
        }
    }
    $_SESSION['register_error'] = $error;
    header('Location: /');
    exit;
}


//////////////////////////////////////////////
///  IMAGE API
/////////////////////////////////////////////
function image(array $request)
{
    if (isset($_SESSION['current_user'])) {
        switch ($request[2]) {
            case "upload":
                img_upload();
                break;
            case "rotate":
                img_rotate($request[3], $request[4]);
                break;
            case "gray":
                img_to_gray($request[3]);
                break;
            case "mirror":
                img_mirror($request[3]);
                break;
            case "revert":
                img_revert($request[3]);
                break;
            case "delete":
                img_delete($request[3]);
                break;
            case "newest":
                img_getnewest($request[3]);
                break;
        }

    } else {
        http_response_code(403);
        echo "Only a logged in user may manipulate images";
        die();
    }
}

function img_getnewest(string $img_id)
{
    $img = new Image($img_id);
    $file = fopen($img->newestVersion(), 'rb');
    header("Content-Type: image/jpeg");
    header("Content-Length: " . filesize($img->newestVersion()));
    fpassthru($file);
    die();
}

function img_delete(string $img_id)
{
    $img = new Image($img_id);
    array_map('unlink', glob($img->getDir() . "/*.*"));
    rmdir($img->getDir());
    die(json_encode("success"));
}

function img_revert(string $img_id)
{
    $img = new Image($img_id);
    if ($img->getLastVersion() > 0) {
        unlink($img->newestVersion());
        $img = new Image($img_id);
        make_thumbnail($img->newestVersion(), $img->getId());
        echo json_encode(ltrim($img->newestVersion(), $_SERVER['DOCUMENT_ROOT']));
        die();
    } else {
        http_response_code(400);
        echo json_encode("cant revert original image");
    }
}

function img_rotate(int $degrees, string $img_id)
{
    assert(in_array($degrees, [0, 90, 180, 270, 360]));
    $img = new Image($img_id);
    $processor = new Imagick($img->newestVersion());
    $processor->rotateImage(new ImagickPixel('#00000000'), $degrees);
    file_put_contents($img->get_new_version(), $processor);
    make_thumbnail($img->get_new_version(), $img->getId());
    echo json_encode(ltrim($img->get_new_version(), $_SERVER['DOCUMENT_ROOT']));
    die();
}

function img_to_gray($img_id)
{
    $img = new Image($img_id);
    $processor = new Imagick($img->newestVersion());
    $processor->transformImageColorspace(imagick::COLORSPACE_GRAY);
    file_put_contents($img->get_new_version(), $processor);
    make_thumbnail($img->get_new_version(), $img->getId());
    echo json_encode(ltrim($img->get_new_version(), $_SERVER['DOCUMENT_ROOT']));
    die();
}

function img_mirror($img_id)
{
    $img = new Image($img_id);
    $processor = new Imagick($img->newestVersion());
    $processor->flopImage();
    file_put_contents($img->get_new_version(), $processor);
    make_thumbnail($img->get_new_version(), $img->getId());
    echo json_encode(ltrim($img->get_new_version(), $_SERVER['DOCUMENT_ROOT']));
    die();
}

function img_upload()
{
    if (isset($_SESSION['current_user'])) {
        if (isset($_FILES['image']) && $file = $_FILES['image']) {
            if ($file['size'] < 1024 * 1024 * 5) {
                if (is_img($file['tmp_name'])) {

                    $id = getToken(16);
                    mkdir($_SERVER['DOCUMENT_ROOT'] . "/img/uploads/$id");
                    make_thumbnail($file['tmp_name'], $id);
                    $img = "img/uploads/$id/v0.jpeg";

                    if (move_uploaded_file($file['tmp_name'], $img)) {
                        echo json_encode([
                            "src" => ltrim($_SERVER['DOCUMENT_ROOT'] . "/img/uploads/$id/thumb.jpeg", $_SERVER['DOCUMENT_ROOT']),
                            "id" => $id
                        ]);
                    } else {
                        http_response_code(500);
                        echo json_encode("error");
                    }
                } else {
                    http_response_code(400);
                    die("Uploaded file not an image");
                }
            } else {
                http_response_code(413);
                die("Max Image upload 5MB");
            }
        }
    } else {
        http_response_code(403);
        die("Only logged in user can upload images");
    }
    die();
}

function make_thumbnail($img_path, $id)
{
    if (is_file($img_path)) {
        $processor = new Imagick(realpath($img_path));
        $processor->setImageFormat("jpeg");
        $processor->setCompression(Imagick::COMPRESSION_JPEG);
        $processor->setCompressionQuality(80);
        $width = $processor->getImageWidth();
        $height = $processor->getImageHeight();

        $max = max($width, $height);
        $ratio = 350.0 / $max;

        $processor->thumbnailImage($width * $ratio, $height * $ratio);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/img/uploads/$id/thumb.jpeg", $processor);
    }
}

function is_img($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];

    if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
        return true;
    }
    return false;
}

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max - 1)];
    }

    return $token;
}