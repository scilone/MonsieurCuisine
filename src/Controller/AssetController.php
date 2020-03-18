<?php

namespace App\Controller;

class AssetController
{
    public function img(string $url = '')
    {
        $strFile    = base64_decode($url);
        $strFileExt = end(explode('.', $strFile));

        if ($strFileExt == 'jpg' or $strFileExt == 'jpeg') {
            header('Content-Type: image/jpeg');
        } elseif ($strFileExt == 'png') {
            header('Content-Type: image/png');
        } elseif ($strFileExt == 'gif') {
            header('Content-Type: image/gif');
        } else {
            die('not supported');
        }

        if ($strFile !== '') {
            $cacheEnds = 60 * 60 * 24 * 365;
            header("Pragma: public");
            header("Cache-Control: maxage=" . $cacheEnds);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cacheEnds) . ' GMT');

            echo file_get_contents($strFile);
        }
    }

    public function mkv(string $url = '')
    {
        $strFile = base64_decode($url);

        if ($strFile !== '') {
            $ch = curl_init($strFile);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'CSTVPlayer');
            curl_exec($ch);
        }
    }

    public function downloadExternal(string $name, string $url)
    {
        $name = base64_decode($name);
        $url  = base64_decode($url);

        header('Content-type: video/x-matroska');
        header('Content-Disposition: attachment; filename="' . $name . '.mkv"');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'CSTVPlayer');
        curl_exec($ch);
    }

    public function manifest()
    {
        header('Content-type: application/json');

        echo
        '{
    "name": "CSTV web",
    "theme_color": "#000",
    "orientation":  "landscape",
    "background_color": "#000",
    "display": "standalone"
}'
        ;
    }
}
