<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
/**
 * Description of studentModel
 *
 * @author YANIX-MRML
 */
class Load_model extends CI_Model{
    //put your code here
    public $semester_str_length = 16; //16 characters long
    public $password_max_length = 16;
    
    public function _construct(){
        parent::_construct();
    }
    
    public function getFaculty($faculty_id){
        if($faculty_id!=null && is_numeric($faculty_id)){                               
            $sql = "SELECT * FROM faculty f WHERE f.faculty_id = ? LIMIT 1";
            $query = $this->db->query($sql,$faculty_id); 
            if($query->num_rows() > 0){
                return $query->row_array();
            }
        }
        return FALSE;
    }
    
    public function getCourses($faculty_id,$semester, $academic_year){
        if($academic_year != null && is_numeric($academic_year) && $semester!=null){
            $sql = "SELECT DISTINCT c.*, sc.section,sc.schedule_day,sc.time_start, sc.time_end
                    FROM student_courses sc INNER JOIN course c ON sc.course_id = c.course_id WHERE
                    sc.faculty_id = ? AND sc.semester = ? AND sc.academic_year = ?";
            $query = $this->db->query($sql,array($faculty_id,$semester,$academic_year));
            if($query->num_rows() > 0){
                return $query->result_array();
            }
        }
        return FALSE;
    }
    
    public function getMasterList($faculty_id,$course_id,$section,$semester, $academic_year){
         if($academic_year != null && is_numeric($academic_year) && $semester!=null){
            $sql = "SELECT DISTINCT s.*,sc.student_course_id FROM student s INNER JOIN student_courses sc ON s.student_id = sc.student_id WHERE
                sc.faculty_id = ? AND sc.course_id = ? AND sc.section = ? AND sc.semester = ? AND sc.academic_year = ? ORDER BY s.lastname, s.firstname, s.middlename";
            $query = $this->db->query($sql,array($faculty_id,$course_id,$section,$semester,$academic_year));
            if($query->num_rows() > 0){
                return $query->result_array();
            }    
         }
         return FALSE;
    }
    
    public function getAttendance($student_course_id,$date){
        if($student_course_id != null && is_numeric($student_course_id)){
            $sql = "SELECT DISTINCT a.*, TIMEDIFF(TIME(a.date_attended),CAST(sc.time_start AS TIME)) AS tardiness, sc.schedule_day FROM attendance a
                RIGHT JOIN student_courses sc ON a.student_course_id = sc.student_course_id WHERE a.student_course_id = ? AND DATE(a.date_attended) =  ?";
            $query = $this->db->query($sql,array($student_course_id,$date));
            if($query->num_rows() > 0){
                return $query->result_array();
            }   
        }
    }
    
    public function getCurrentSettings(){
        $sql = "SELECT * FROM settings s LIMIT 1";
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            return $query->row_array();    
        }
        return FALSE;
    }
    
    /****************** Update **************************/
    
    public function updateStudent($student){
        if(isset($student)&&!empty($student) && $student!=null){
            $sql = "UPDATE student s SET s.picture = ? WHERE s.university_id = ?";
            $query = $this->db->query($sql,array($student['picture'],$student['university_id']));     
            
            return TRUE;
        }
        return FALSE;
    }
    
    public function updateFaculty($faculty){
        if(isset($faculty)&&!empty($faculty) && $faculty!=null){
            $sql = "UPDATE faculty f SET f.picture = ? WHERE f.university_id = ?";
            $query = $this->db->query($sql,array($faculty['picture'],$faculty['university_id']));     
            
            return TRUE;
        }
        return FALSE;
    }
    
    /*************************************************/
}

/**** Procedures to be added....
 *  AKAN_SEL_OFFR_SCTN
 *  AKAN_SEL_OFFR_SUBJCT
 *
 **/
