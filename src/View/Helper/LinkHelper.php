<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LinkHelper extends Helper
{
    public function linkify($text)
    {
        $pattern = '/\[(.*?)\]\((https?:\/\/.*?)\)/';
        $replacement = '<a href="$2">$1</a>';
        $text = preg_replace($pattern, $replacement, $text);
        return $text;
    }
}
