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
class Dashboard extends CI_Controller{
    //put your code here
    private $template = "dashboard/template.php";
    
    function _construct(){
        parent::_construct();
    }

    /**** Viewing *****/

    function index(){
        $this->home();
    }

    function home(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account");
        $data["page"] = "Home";
        $data["page_include"] = "dashboard/home.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["settings"] = $this->load_model->getCurrentSettings();
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }

    function faculty(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Faculty";
        $data["page_include"] = "dashboard/faculty.php"; 
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["settings"] = $this->load_model->getCurrentSettings();
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data);
    }
    
    function error_404(){
        $this->checkUser();
        $data["userInfo"] = $this->session->userdata("account"); 
        $data["page"] = "Home";
        $data["heading"] = "Page Not Found";
        $data["message"] = "This page is either removed or not existed";
        $data["page_include"] = "errors/cli/error_404.php";
        $data["page_javascript"] = "assets/js/dashboard.js";
        $data["settings"] = $this->load_model->getCurrentSettings();
        $data["auth"] = $this->tools->mc_encrypt($_SESSION['a_id'],ENCRYPTION_KEY);
        $this->load->view($this->template,$data); 
    }
    
    function print_attendance(){
        $this->checkUser();
        $data["university_id"] = intval($this->input->get("university_id"));
        $data["type"] = intval($this->input->post("a_type"));
        $data["course_id"] = $this->input->post("course_id");
        $data["faculty_id"] = $this->input->post("faculty_id");
        $data["section"] = $this->input->post("section");
        $data["schedule"] = "-" . $this->input->post("schedule");
        $data["semester"] = $this->input->post("semester");
        $data["academic_year"] = $this->input->post("academic_year");
        $data["date_from"] = $this->input->post("date_from");
        $data["date_to"] = $this->input->post("date_to");
        $data["webTitle"] = "AMS Dashboard";
        $this->load->view("dashboard/print_attendance.php",$data); 
    }
    
    /********* JSON / AJAX **********/

    function update_password(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $user_id = intval($this->input->post("user_id"));
            $old_password = $this->input->post("old_password");
            $new_password = $this->input->post("new_password");
            $status = $this->system_model->changePassword($user_id,$old_password,$new_password);
            if($status!=null && !empty($status)){
                if(intval($status["faculty_id"])>0){
                    echo "You have successfully updated your password.";
                }else{
                    echo "Authentication failed due to invalid old password. New password was <b>NOT</b> successfully updated.";
                }
            }else{
               echo "Problem occured. New password was <b>NOT</b> successfully updated.";
            }   
        }
    }

    function update_picture(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size']    = '7000';
            //$config['allowed_types']        = 'gif|jpg|png';
            //$config['max_size']             = 100;
            //$config['max_width']            = 200;
            //$config['max_height']           = 200;

            $this->load->library('upload', $config);
            $image = null;
            
            if($_FILES["updateStudentImage"]!="" && $_FILES["updateStudentImage"]!=null){
                if ( ! $this->upload->do_upload('updateStudentImage'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        echo "Error Occured. Image was <b>NOT</b> successfully uploaded.<br/>";
                        //$this->load->view('upload_form', $error);
                        echo $this->upload->display_errors();
                }else{
                        $data = $this->upload->data();
                        if($this->input->post("previousPicture")!=""){
                            $file = fopen("uploads/" . $this->input->post("previousPicture"),"w");
                            fclose($file);
                            unlink("uploads/" . $this->input->post("previousPicture"));
                            //$this->load->view('upload_success', $data);
                        }
                        $image = $data["file_name"];
                }
                
                $student = array(  "university_id"=>   $this->input->post("university_id"),
                                "picture"=>$image);     
             
                if(isset($student["university_id"]) && $student["university_id"]!=null){
                    if($this->load_model->updateStudent($student)){
                        echo "The selected student's picture was successfully updated.";
                    }else{
                        echo "Error Occured. User account was <b>NOT</b> successfully updated.";
                    }
                }else{
                    echo "Error Occured. User account was <b>NOT</b> successfully updated.";
                }
            }
        }
    }
    
    function update_user_picture(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            
            $config['upload_path']          = './uploads/';
            $config['allowed_types'] = 'png|gif|jpg|jpeg';
            $config['max_size']    = '7000';
            //$config['allowed_types']        = 'gif|jpg|png';
            //$config['max_size']             = 100;
            //$config['max_width']            = 200;
            //$config['max_height']           = 200;

            $this->load->library('upload', $config);
            $image = null;
            
            if($_FILES["updateUserImage"]!="" && $_FILES["updateUserImage"]!=null){
                if ( ! $this->upload->do_upload('updateUserImage'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        echo "Error Occured. Image was <b>NOT</b> successfully uploaded.<br/>";
                        //$this->load->view('upload_form', $error);
                        echo $this->upload->display_errors();
                }else{
                        $data = $this->upload->data();
                        if($this->input->post("previousUserPicture")!=""){
                            $file = fopen("uploads/" . $this->input->post("previousUserPicture"),"w");
                            fclose($file);
                            unlink("uploads/" . $this->input->post("previousUserPicture"));
                            //$this->load->view('upload_success', $data);
                        }
                        $image = $data["file_name"];
                }
                
                $user = array(  "university_id"=>   $this->input->post("university_id"),
                                "picture"=>$image);     
                $u_id = intval($_SESSION['a_id']);
                if(isset($user["university_id"]) && $user["university_id"]!=null){
                    if($this->load_model->updateFaculty($user)){
                        $data = $this->load_model->getFaculty($u_id);
                        $this->setUserSession($data);
                        echo "Your picture was successfully updated.";
                    }else{
                        echo "Error Occured. Your picture was <b>NOT</b> successfully updated.";
                    }
                }else{
                    echo "Error Occured. Your picture was <b>NOT</b> successfully updated.";
                }
            }
        }
    }
    
    function get_course_list(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $faculty_id = $this->input->post("faculty_id");
            $semester = $this->input->post("semester");
            $academic_year = $this->input->post("academic_year");
            
            echo "<div class='text-center'><b>Course Offerings Assigned</b></div><div class='text-center'>" . $semester . ", " . $academic_year . "</div>";
            echo "<ul id='course-body'>";
            $courseList = $this->load_model->getCourses($faculty_id,$semester,$academic_year);
            if(!empty($courseList)){
                foreach($courseList as $course){
                    echo "<li class='select-course'>
                            <input type='hidden' class='course-id' value='" . $course["course_id"] . "'>
                            <input type='hidden' class='course-description' value='" . $course["course_description"] . "'>
                            <input type='hidden' class='course-section' value='" . $course["section"] . "'>
                            <input type='hidden' class='course-schedule' value='" . $course["schedule_day"] . ", " . $this->tools->formatDateTime($course["time_start"],"g:i:s A") . " - " . $this->tools->formatDateTime($course["time_end"],"g:i:s A") . "'>
                            <span class='course-name'>" . $course["course_name"] . "</span>
                            <span> - " . $course["section"] . "</span> 
                        </li>";                        
                }
            }else{
                echo "<li>No courses assigned at selected semester</li>";
            }
            echo "</ul>";
        }
    }
    
    function get_attendance_list(){
        if($_POST && $this->input->is_ajax_request()){
            $this->checkUser();
            $type = intval($this->input->post("a_type"));
            $course_id = $this->input->post("course_id");
            $faculty_id = $this->input->post("faculty_id");
            $section = $this->input->post("section");
            $schedule = "-" . $this->input->post("schedule");
            $semester = $this->input->post("semester");
            $academic_year = $this->input->post("academic_year");
            $date_from = $this->input->post("date_from");
            $date_to = $this->input->post("date_to");
            
            echo    '<caption>
                        List of attendance of the selected course offering<br/>
                        Note: <br/>
                        &nbsp;<b style="color: #F1B0B7;">Red</b> means student is "Absent". <br/>
                        &nbsp;Click "View Attendance" button to view the attendance of the selected course offerings.
                    </caption>';
            //Generate Master List
            $masterList = $this->load_model->getMasterList($faculty_id,$course_id,$section,$semester,$academic_year);
            print_r($masterList);
            
            if(!empty($masterList)){
                if($type==1){
                     $st = strtotime("now");
                     $date_selected = date('Y-m-d',$st);
                     echo   "<thead>
                                 <tr class='table-secondary'>
                                     <th>No.</th>
                                     <th>University ID</th>
                                     <th>Name</th>
                                     <th>".$this->tools->formatDateTime($date_selected,'m/d/Y') . ", " . date('D',$st) . "</th>
                                 </tr>
                             </thead><tbody>";
                     $i = 1;
                     foreach($masterList as $student){
                        echo "<tr>
                             <td class='text-center'>" .  $i . "</td>
                             <td class='text-center student-id'>" .  $student["university_id"]  . "</td>" . 
                             "<td class='text-left student-action'><input type='hidden' class='view-picture' value='" . ($student["picture"]!=null && $student["picture"]!=""?$student["picture"]:"") . "'><input type='hidden' class='student-id' value='" .  $student["university_id"]  . "'>
                                    <span class='student-name'>" . $student["lastname"] . ", " . $student["firstname"] . " " . $student["middlename"] . "</span></td>";
                        $attendance = $this->load_model->getAttendance($student["student_course_id"],$date_selected);    
                        if(!empty($attendance)){
                            foreach($attendance as $a){
                                echo "<td class='text-center'>" . $a["tardiness"] . "</td>";
                            }
                        }else{
                            $day = date('D',$st);
                            if(strripos($schedule,$day)>0){
                                echo "<td class='text-center'><b class='red'>- A -</b></td>";
                            }else{
                                echo "<td class='text-center'>-&nbsp;-</td>";
                            }
                        }
                        echo "</tr>";                        
                         $i++;     
                     }
                     echo "</tbody>";
                }else{
                    $date_from = $this->tools->formatDateTime($date_from,'Y-m-d');
                    $date_to = $this->tools->formatDateTime($date_to,'Y-m-d');
                    $totalDays = intval($this->tools->days_diff($date_from,$date_to));
                    echo   "<thead>
                                 <tr class='table-secondary'>
                                     <th>No.</th>
                                     <th>University ID</th>
                                     <th>Name</th>";
                    $date_selected = null;
                    for($i = 0;$i<=$totalDays;$i++){
                        $st = strtotime("+" . $i . " day",strtotime($date_from));
                        $date_cur = date('Y-m-d',$st);
                        $date_selected[] = $date_cur;
                        echo "<th>" . $this->tools->formatDateTime($date_cur,'m/d/Y') . ", " . date('D',$st) . "</th>";
                    }                                 
                    echo    "</tr>
                                </thead><tbody>";
                    $i=1;
                    foreach($masterList as $student){
                        echo "<tr>
                             <td class='text-center'>" .  $i . "</td>
                             <td class='text-center'>" .  $student["university_id"]  . "</td>" . 
                             "<td class='text-left student-action'><input type='hidden' class='view-picture' value='" . ($student["picture"]!=null && $student["picture"]!=""?$student["picture"]:"") . "'><input type='hidden' class='student-id' value='" .  $student["university_id"]  . "'>
                                    <span class='student-name'>" . $student["lastname"] . ", " . $student["firstname"] . " " . $student["middlename"] . "</span></td>";
                        $a["schedule_day"] = "---" . $a["schedule_day"];                                
                        foreach($date_selected as $date_sel){
                            $attendance = $this->load_model->getAttendance($student["student_course_id"],$date_sel);    
                            
                            if(!empty($attendance)){
                                foreach($attendance as $a){
                                    echo "<td>" . $a["tardiness"] . "</td>";
                                }
                            }else{
                                $tstamp = strtotime($date_sel);
                                $day = date('D',$tstamp);
                                if(strripos($schedule,$day)>0){
                                    echo "<td class='text-center'><b class='red'>- A -</b></td>";
                                }else{
                                    echo "<td class='text-center'>-&nbsp;-</td>";
                                }
                            }
                        }
                        echo "</tr>";                        
                        $i++;     
                     }
                     echo "</tbody>";
                }
            }else{
                echo   "<thead>
                            <tr class='table-secondary'>
                                <th>No.</th>
                                <th>University ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody><tr><td colspan='3'>No enrolled students in this course offerings.</td></tr></tbody>";
            }
        }
    }
    
    /************** Security / Login ******************/

    function logout(){
        $this->system_model->logout();
        //javascript remove history
        redirect("login");
    }
    
    private function checkUser(){
        if(!$this->settings->offline){    
            if(!$this->system_model->hasLoggedIn()){
                redirect("login");
            }    
        }else{
            $this->logout();
        }
    }
    
    function setUserSession($dashboardAccount){
        $this->system_model->setSession('account', array("faculty_id"=>$dashboardAccount['faculty_id'],"lastname"=>$dashboardAccount['lastname'],
                                            "firstname"=>$dashboardAccount['firstname'],"university_id"=>$dashboardAccount['university_id'],
                                            "username"=>$dashboardAccount['username'],
                                            "last_login"=>$dashboardAccount['last_login'],"picture"=>$dashboardAccount['picture']));
        $_SESSION['a_id'] = $dashboardAccount["faculty_id"];
    }

}

