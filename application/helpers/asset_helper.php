<?php 

function loadCss($paths){
    if(!empty($paths) && is_array($paths)){
        $csses = "";
        foreach ($paths as $path) {
            $csses .= '<link rel="stylesheet" href="' . base_url($path) .'">';
        }
        return $csses;
    }
}
function loadJS($paths){
    if(!empty($paths) && is_array($paths)){
        $jses = "";
        foreach ($paths as $path) {
            $jses .= '<script src="' . base_url($path) .'"></script>';
        }
        return $jses;
    }
}