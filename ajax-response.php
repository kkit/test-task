<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;
use Psr\Http\Client\ClientExceptionInterface;

$url = '';
$res = '';

if(isset($_POST["url"]) && strlen($_POST["url"]) > 0) {
    $url = $_POST["url"];
} else {
    //echo "<span class='text-danger'>Unknown url</span>";
    echo "Unknown url";
    exit();
}

$dom = new Dom;
try {
    $dom->loadFromUrl($url);
} catch (ClientExceptionInterface $e) {
    //echo "<span class='text-danger'>Error load url</span>";
    echo "Error load url";
    exit();
}

// весь контент
$content = $dom->getElementById('pjax-container');
$res = $content->innerHtml;

// код html
$res_html = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $res);

// ссылки
$links = $content->find('a');
foreach ($links as $link) {
    $href = $link->getAttribute('href');

    if (strpos($href, 'http') !== false) { // убираем относительные ссылки
        $hrefs =  $hrefs . $href."<br>";
    }
}

// изображения
$images = $content->find('img');
foreach ($images as $img) {
    $imgs =  $imgs . $img."<br>";
}

echo '
<div class="vstack gap-2 pt-3">
    <p class="text-success">Результат</p><div class="bg-light border pb-3 res-block">'.$res.'</div>
    <p class="text-success">Результат в виде HTML</p><div class="bg-light border pb-3 res-block">'.$res_html.'</div>
    <p class="text-success">Ссылки</p><div class="bg-light border pb-3 res-block">'.$hrefs.'</div>
    <p class="text-success">Изображения</p><div class="bg-light border pb-3 res-block-img">'.$imgs.'</div>
</div>
';

