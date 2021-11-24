<?php
namespace Admin\Controller;
use Base\BaseController;
use Admin\Model\UserModel;

final class Index extends BaseController{

    public function index(){
        $this->accessPage();

        $userModel = new UserModel;
         //查询老师人数
         $teacherNum   =  $userModel->rowCount("position = '老师'");

         //查询学生人数
         $studentNum   =  $userModel->rowCount("position = '学生'");

         //查询未缴费人数
        $status   =  $userModel->rowCount("status = 0");

        $this->smarty->assign("teacherNum",$teacherNum);
        $this->smarty->assign("studentNum",$studentNum);
        $this->smarty->assign("status",$status);
        $this->smarty->display("Index/index.html"); 
    }

}