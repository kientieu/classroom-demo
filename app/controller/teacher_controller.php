<?php
    require_once 'vendor/autoload.php';

class teacher_controller extends base_controller
{
    function __construct(){
        $this->name = 'teacher';
    }

    public function index() {
        $this->process('teacher_view',array());
    }

    public function people($classid) {
        $this->process('people_teacher_view',$classid);
    }
    public function stream($classid) {
        $this->process('stream_teacher_view',$classid);
    }
    public function comment_view($classid){
        $this->process('comment_view', $classid);
    }

    public function add_class($classid){
        $this->process('add_class',$classid);
    }

    public function comment($classid)
    {
        $this->process('comment_view',$classid);
    }

    public static function add_new_cmt($content, $notiId, $username) {
        return comment_model::add_new_cmt($content, $notiId, $username);
    }
}