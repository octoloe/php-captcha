<?php

session_start();
function create_captcha($text)
{
    $width = 400;
    $height = 200;
    $fontfile = 'assets/font/OpenSans-Italic-VariableFont.ttf';

    $image_real = imagecreatefromjpeg('assets/images/abstract-formed-by-color-dissolving-water(onlyyouqj_freepik).jpg');

    $image = imagecreatetruecolor($width, $height);
    imagecopyresampled($image, $image_real, 0, 0, 0, 0, $width, $height, $width, $height);

    $white = imagecolorallocate($image, 255, 255, 255);

    $black = imagecolorallocate($image, 0, 0, 0);
    imagefill($image, 0, 0, $white);
    imagettftext($image, 25, rand(-22, 22), $width / 4, 60, $black, $fontfile, $text);

    $warped_image = imagecreatetruecolor($width, $height);
    imagefill($warped_image, 0, 0, imagecolorallocate($warped_image, 255, 255, 255));

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $index = imagecolorat($image, $x, $y);

            $color_component = imagecolorsforindex($image, $index);

            $color = imagecolorallocate($warped_image, $color_component['red'], $color_component['green'], $color_component['blue']);

            $imageX = $x;

            $imageY = $y + sin($x / 10) * 10;
            imagesetpixel($warped_image, $imageX, $imageY, $color);
        }
    }

    $path = 'captcha.jpg';

    imagejpeg($warped_image, $path, 100);
    imagedestroy($warped_image);
    imagedestroy($image);

    return $path;
}

$filename = session_id();

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einloggen</title>

    <!-- Bootstrap Css -->
    <link href="assets/bootstrap5.3.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">

        <h3 class="text-center m-5">PHP - Captcha</h3>

        <div class="mb-3">
            <?php
            if (count($_POST) > 0) {

                $number = file_get_contents($filename);
                if ($_POST['number'] == $number) {
                    header("Location: /php/php-captcha/thankyou.php");
                } else {
                    echo
                    "<div class='text-center text-danger fw-bolder mt-5'>
                       Das eingetippte Captcha stimmt nicht!
                    </div";
                    $text = rand(10000, 99999);
                    $myimage = create_captcha($text);
                    file_put_contents($filename, $text);
                }
            }
            ?>
        </div>

        <div class="col-3 position-absolute top-50 start-50 translate-middle">

            <div class="card shadow p-4 mx-auto">

                <form method="POST">

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Captcha</label>
                            <input type="text" name="number" class="form-control" />
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <input type="submit" class="btn btn-outline-info btn-sm mt-3" value="Einloggen" />
                            </div>
                        </div>
                </form>

                <div class="row">
                    <div class="col">
                        <img src="captcha.jpg" class="img-fluid mt-5" alt="Blauer Hintergrund mit Zahlen" />
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap Scripts -->
    <script src="assets/bootstrap5.3.min.js"></script>
    <script src="stop-spam.js" type="text/javascript"></script>
</body>

</html>