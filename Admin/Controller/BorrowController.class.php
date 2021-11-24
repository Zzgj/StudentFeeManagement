<?php
namespace Admin\Controller;
use Base\BaseController;
use Admin\Model\BorrowModel;

final class Borrow extends BaseController{

    public function index(){
        $this->accessPage();

        $this->smarty->display("Borrow/index.html");
    }

    //Json借书和还书接口
    public function manage(){
        $this->accessJson();

        $bookId  =  $_POST['bookId'];
        $userId  =  $_POST['userId'];
        $action  =  $_POST['action'];

        if($userId == "" || $bookId == ""){
            $this->sendJsonMessage("请填写完整信息",1);
        }

        $borrowModel = new BorrowModel;
        if($action == "borrow"){
            //借书
            if($borrowModel->canBorrow($bookId,$userId)){
                $data = array(
                    "book_id"     =>  $bookId,
                    "user_id"     =>  $userId,
                    "borrow_date" =>  date("Y-m-d"),
                    "back_date"   =>  date("Y-m-d",strtotime("+2 month"))
                );
                if($borrowModel->insert($data)){
                    $this->sendJsonMessage("借书成功",0);
                }else{
                    $this->sendJsonMessage("借书失败",1);
                }
            }else{
                $this->sendJsonMessage("信息错误或该书已借出",1);
            }
        }else if($action == "return"){
            //还书
            if($borrowModel->canReturn($bookId,$userId)){
                if($borrowModel->delete("book_id={$bookId} AND user_id={$userId}")){
                    $this->sendJsonMessage("还书成功",0);
                }else{
                    $this->sendJsonMessage("还书失败",1);
                }
            }else{
                $this->sendJsonMessage("信息错误或该用户未借此书",1);
            }
        }else{
            $this->sendJsonMessage("参数错误",1);
        }
    }

    //Json投诉接口
    public function prolong(){
        $this->accessJson();
        $this->sendJsonMessage("投诉成功！",1);
    }

    //Json缴费接口
    public function returnBook(){
        $this->accessJson();

        $bookId = $_POST['bookId'];
        $userId = $_POST['userId'];

        $borrowModel = new BorrowModel;
        if($borrowModel->canReturn($bookId,$userId)){
            if($borrowModel->delete("book_id={$bookId} AND user_id={$userId}")){
                $this->sendJsonMessage("缴费成功",0);
            }else{
                $this->sendJsonMessage("缴费失败",1);
            }
        }else{
            $this->sendJsonMessage("信息错误",1);
        }
    }
}