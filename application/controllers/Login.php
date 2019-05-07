<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of employee
 *
 * @author YANIX-MRML
 */
class Login extends CI_Controller{
    //put your code here
    
    
    function _construct(){
        parent::_construct();
    }
    
    function index(){
        $data["webTitle"] = "AMS Dashboard";
        $data["page_javascript"] = "assets/js/dashboard-login.js";
        if($this->system_model->hasLoggedIn()){
            redirect("dashboard/home");     
        }else{
            $data["auth"] = $this->tools->mc_encrypt($this->input->ip_address(),ENCRYPTION_KEY);
            $this->load->view("login/login_page",$data);
        }
    }
    
    function login_authentication(){
        if($_POST){
            $auth = $this->tools->mc_decrypt($this->input->post("auth"),ENCRYPTION_KEY);
            if($auth==$this->input->ip_address()){
                $nowTime = $this->system_model->getTimeNow();
                $message = "Account not found. Authentication failed.";
                if(!$this->settings->offline){
                    
                    if($_POST["attendanceType"]=="employee"){
                        $dashboardAccount = $this->system_model->validateUser($_POST['username'],$_POST['password'],1);
                    }else{
                        $dashboardAccount = $this->system_model->validateUser($_POST['username'],$_POST['password'],2);
                    }
                    //$this->setUserSession($dashboardAccount);
                    if($dashboardAccount){
                            if($_POST["attendanceType"]=="employee"){
                                $this->system_model->setSession('account', array("faculty_id"=>$dashboardAccount['faculty_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],"university_id"=>$dashboardAccount['university_id'],
                                            "username"=>$dashboardAccount['username'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
                                $_SESSION['a_id'] = $dashboardAccount["faculty_id"];
                            }else{
                                /*$this->system_model->setSession('account', array("student_id"=>$dashboardAccount['student_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],"university_id"=>$dashboardAccount['university_id'],
                                            "username"=>$dashboardAccount['username'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
                                $_SESSION['a_id'] = $dashboardAccount["student_id"];    */
                            }
                            redirect("dashboard/home");
                    }else{
                        $message = count($dashboardAccount) . " " . $_POST["attendanceType"] . " Invalid username or password.";
                    }
                    //$data["auth"] = $this->tools->mc_encrypt($this->input->ip_address(),ENCRYPTION_KEY);
                    //$this->load->view("login/login_page",$data);
                }else{
                    $message = $this->settings->offline_message;
                }
                $_SESSION['message'] = $message;
                redirect("login");
            }
        }else{
            redirect("login");
        }
    }
    
}

?>