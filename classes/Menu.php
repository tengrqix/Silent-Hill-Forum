<?php
class Menu
{
    public static function getMenu(array $pages): string
    {
        $menuHtml = '';

        foreach ($pages as $name => $url) {
            $menuHtml .= "<li><a href=\"$url\">$name</a></li>";
        }

        return $menuHtml;
    }
}
