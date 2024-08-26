<?php
namespace App\View\Helper;

use Cake\View\Helper;

class LinkHelper extends Helper
{
    public function linkify($text)
    {
        $patternMarkdown = '/\[(.*?)\]\((https?:\/\/.*?)\)/';
        $replacementMarkdown = '<a href="$2">$1</a>';
        $text = preg_replace($patternMarkdown, $replacementMarkdown, $text);
        $patternPlain = '/(?<!<a href=")(https?:\/\/[^\s]+)/';
        $replacementPlain = '<a href="$1" target="_blank">$1</a>';
        $text = preg_replace($patternPlain, $replacementPlain, $text);
        $text = nl2br($text);

        return $text;
    }
}
