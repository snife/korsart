<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 17:45
 */

include("../scripts/connect.php");
include("../scripts/helpers.php");
include("../layouts/menu.php");
include("../layouts/footer.php");

?>

<!DOCTYPE html>

<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->

<head>

    <?php
        $pageResult = $mysqli->query("SELECT * FROM pages WHERE original_link = 'wedding/'");
        $page = $pageResult->fetch_assoc();

        $settingsResult = $mysqli->query("SELECT * FROM settings WHERE id = '1'");
        $settings = $settingsResult->fetch_assoc();
    ?>

    <meta charset="utf-8" />

    <title>Портфолио свадебных фотосесиий. Фотограф для свадьбы в Минске</title>

    <meta property="og:title" content="Портфолио свадебных фотосесиий. Фотограф для свадьбы в Минске" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>" />

    <meta name="description" content="Для того что бы узнать хорошего ли фотографа вы себе берёте на свадьбу, вам необходимо посмотреть его портфолио. Портфолио фотографа Александа как раз на этой страничке" />
    <meta name="keywords" content="Свабедное портфолио" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" href="/img/system/favicon.ico" />

    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="/css/main.css" />
    <link rel="stylesheet" type="text/css" href="/css/media.css" />
    <link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/lightview/css/lightview/lightview.css" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>
    <script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/notify.js"></script>
    <script type="text/javascript" src="/js/share.js"></script>
    <script type="text/javascript" src="/js/ya.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108937733-1"></script>

    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-108937733-1');
    </script>

    <style>
        #page-preloader {position: fixed; left: 0; top: 0; right: 0; bottom: 0; background: #fff; z-index: 100500;}
        #page-preloader .spinner {width: 32px; height: 32px; position: absolute; left: 50%; top: 50%; background: url('/img/system/spinner.gif') no-repeat 50% 50%; margin: -16px 0 0 -16px;}
    </style>

    <script type="text/javascript">
        $(window).on('load', function () {
            var $preloader = $('#page-preloader'), $spinner = $preloader.find('.spinner');
            $spinner.delay(500).fadeOut();
            $preloader.delay(850).fadeOut();
        });
    </script>

    <!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
    <!-- Google Analytics counter --><!-- /Google Analytics counter -->
</head>

<body>

    <div id="page-preloader"><span class="spinner"></span></div>

    <?php
        $categories = array();

        $categoryResult = $mysqli->query("SELECT * FROM categories WHERE sef_link <> 'about' ORDER BY priority");
        while($category = $categoryResult->fetch_assoc()) {
            if($category['id'] == BLOG_ID) {
                $subcategoryResult = $mysqli->query("SELECT * FROM blog_subcategories ORDER BY priority");
            } else {
                $subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$category['id']."' ORDER BY priority");
            }

            if($subcategoryResult->num_rows > 0) {
                $subcategories = array();

                while($subcategory = $subcategoryResult->fetch_assoc()) {
                    array_push($subcategories, $subcategory);
                }

                $category['subcategories'] = $subcategories;
            }
            array_push($categories, $category);
        }
    ?>

    <?= showMenu_2ndLevel(null, $categories, $settings) ?>

    <div class="sectionHeader">
        <br /><br />
        <div class='postHeader'>Свадебные галереи</div>
        <br /><br />
    </div>

    <div class="section gallerySection text-center" style="margin: 20px auto auto auto;">
        <?php
            $categoryResult = $mysqli->query("SELECT * FROM categories WHERE sef_link = 'wedding'");
            $category = $categoryResult->fetch_assoc();

            $galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$category['id']."' ORDER BY priority");
            while($gallery = $galleryResult->fetch_assoc()) {
                echo "
                    <a href='/wedding/".$gallery['sef_link']."'>                         
                        <div class='galleryPreview'>
                            <img src='/img/photos/gallery/main/".$gallery['photo']."' />
                            <div class='galleryName'>
                                <span>".$gallery['name']."</span>
                            </div>
                        </div>
                    </a>
                ";
            }
        ?>
    </div>

    <div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

    <?= showFooter() ?>

</body>

</html>