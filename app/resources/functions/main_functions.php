<?php
    function as_object($data){
        if (is_object($data)) {
            return $data;
        }
        return json_decode(json_encode((array) $data));
    }
?>