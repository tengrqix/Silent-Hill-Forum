<?php
function get_menu(array $pages){
    $menuItems = ''; 
    foreach($pages as $page_name => $page_url){
        $menuItems .= '<li><a href="' . $page_url . '">' . $page_name . '</a></li>';
    }
    return $menuItems;
}
?>
