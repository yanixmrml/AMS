<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of studentModel
 *
 * @author YANIX-MRML
 */
class System_model extends CI_Model{
    //put your code here
    private $password_min_length = 6;
    private $username_min_length = 3;
    
    public function _construct(){
        parent::_construct();
    }
    
    /********************* Time Functions ************************/
    
    public function getCurrentDate(){
        $dateNow = new DateTime();
        return date_format($dateNow,"Y-m-d");
    }
    
    public function getTimeNow(){
        $query = $this->db->query("SELECT NOW()");
        if($query->num_rows() > 0){
                return $query->row_array();
        }
        return FALSE;
    }
    
        /******************* Update/Change Password ***************/
    
    public function changePassword($user_id,$old_password,$new_password){
        if(is_numeric($user_id) && strlen($new_password)>=$this->password_min_length
           && strlen($old_password)>=$this->password_min_length){
            $sql1 = "UPDATE faculty f SET f.password = ? WHERE f.password = ? AND f.faculty_id = ?;";
            $query1 = $this->db->query($sql1,array($new_password,$old_password,$user_id));
            $sql2 = "SELECT * faculty f WHERE f.password = ? AND f.faculty_id = ? LIMIT 1";
            $query2 = $this->db->query($sql2,array($new_password,$user_id));
            if($query2->num_rows() > 0){
                return $query2->row_array();
            }
        }
        return FALSE;
    }
    
    /****************** Login / Session ******************/
    
    public function validateUser($username, $password,$type){
        if(!empty($password) && !empty($username) && strlen($username) >=$this->username_min_length && strlen($password)>=$this->password_min_length){
            if($type == 1){
                $sql = "SELECT DISTINCT f.* FROM faculty  f WHERE f.username = ? AND f.password = ? LIMIT 1";
            }else{
                $sql = "SELECT DISTINCT s.* FROM student  s WHERE s.username = ? AND s.password = ? LIMIT 1";            
            }
            $query = $this->db->query($sql,array($username,$password));
            if($query->num_rows() > 0){
                $r =  $query->row_array();
                if(count($r)>0){
                    if($type==1){
                        $sql = "UPDATE faculty f SET f.last_login = NOW() WHERE f.faculty_id = ?";
                        $query = $this->db->query($sql,intval($r["faculty_id"]));
                    }else{
                        $sql = "UPDATE student s SET s.last_login = NOW() WHERE s.student_id = ?";
                        $query = $this->db->query($sql,intval($r["student_id"]));
                    }
                    return $r;
                }
            }
        }
        return FALSE;
    }
    
    public function setSession($id,$value){
        $CI =& get_instance();
        $CI->session->set_userdata($id, $value);
    }
    
    public function hasLoggedIn(){
        $CI =& get_instance();
        return ($CI->session->userdata('account') &&
                isset($_SESSION['a_id']));
    }

    public function logout(){
        $CI =& get_instance();
        $CI->session->sess_destroy();
        unset($_SESSION['a_id']);
        session_destroy();
    }
    
}
?>
