<?php 
/**
 * @author Hamzaoui Med Nour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 * @throws PDOException
 */
class UserController {

    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
     const   primaryKey = "uid";

     //--------------------------------------------------------------------------
     // @property mixed.
     //--------------------------------------------------------------------------
     public $uid, $full_name, $email, $phone, $username, $passwd, $user_status, $register_date;

    //--------------------------------------------------------------------------
    // Setters and Getters.
    //--------------------------------------------------------------------------
     public function getUid(){
          return $this->uid ;
     }
     public function setUid($pUid){
          $this->uid = $pUid ;
          return $this;
     }
     public function getFullName(){
          return $this->full_name ;
     }
     public function setFullName($pName){
          $this->full_name = $pName ;
          return $this;
     }
     public function getEmail(){
          return $this->email ;
     }
     public function setEmail($pEmail){
          $this->email = Handler::escape($pEmail) ;
          return $this;
     }
     public function getPhone(){
          return $this->phone ;
     }
     public function setPhone($pPhone){
          $this->phone = Handler::getNumber($pPhone) ;
          return $this;
     }
     public function getUserStatus(){
          return $this->user_status ;
     }
     public function setUserStatus($pUserStatus){
          $this->user_status = $pUserStatus ;
          return $this;
     }
     public function getUsername(){
          return $this->username ;
     }
     public function setUsername($pUsername){
          $this->username = $pUsername ;
          return $this;
     }
     public function getPasswd(){
          return $this->passwd ;
     }
     public function setPasswd($pPasswd){
          $this->passwd = $pPasswd ;
          return $this;
     }
     public function getRegisterDate(){
          return $this->register_date ;
     }
     public function setRegisterDate($pRegisterDate){
          $this->register_date = $pRegisterDate ;
          return $this;
     }
}
?>