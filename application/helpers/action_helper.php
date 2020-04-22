<?php 

function btn_view($uri, $name) {
    //if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-eye'></i>", "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
    //}
    return '';
}

function process_status($status){
    $class = $status == 1 ? 'bg-success' : 'bg-danger';
    $text = $status == 1 ? 'Active' : 'Inactive';
    return '<span class="status-tag '. $class . '">'. $text .'</span>';
}

function visibleButton($uri) {
    $explodeUri = explode('/', $uri);
    $permission = $explodeUri[0].'_'.$explodeUri[1];
    //if(permissionChecker($permission)) {
        return TRUE;
    //}
    //return false;
}
function escapeString($val) {
    $ci = & get_instance();
    $driver = $ci->db->dbdriver;

    if($driver == 'mysqli') {
        $db = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
    }

    return $val;
}
function pluck($array, $value, $key=NULL) {
    $returnArray = array();
    if(count($array)) {
        foreach ($array as $item) {
            if($key != NULL) {
                $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
            } else {
                $returnArray[] = $item->$value;
            }
        }
    }
    return $returnArray;
}
function groupby_key($array, $key) {
    $returnArray = array();
    if(count($array)) {
        foreach ($array as $item) {
            $returnArray[strtolower($item->$key)][] = $item;
        }
    }
    return $returnArray;
}
function groupby_arr($array, $key) {
    $returnArray = array();
    if(count($array)) {
        foreach ($array as $item) {
            $keyVal = strtolower($item->$key);
            if(empty($returnArray[$keyVal])){
                $returnArray[] = array();
            }
            array_push($returnArray[], $item);
        }
    }
    return $returnArray;
}