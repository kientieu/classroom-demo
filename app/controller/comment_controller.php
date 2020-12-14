<?php

class comment_controller
{
    public static function get_cmt_by_notiId($notiId) {
        return comment_model::get_cmt_by_notiId($notiId);
    }

    public static function del_cmt_by_id($cmt_ID){
        return comment_model::delete_cmt($cmt_ID);
    }
}