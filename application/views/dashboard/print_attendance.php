<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Automatic Transfer Switch - Power Management System Dashboard">
        <meta name="author" content="M.R.M. Longhas, R.K. Pame, J.B. Damao">
        <link rel="icon" href="<?php echo base_url(); ?>/assets/css/images/msulogo.ico">
    
        <!-- Bootstrap core CSS -->
        
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">        
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="screen">
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-toggle.min.css" rel="stylesheet" type="text/css" media="screen">
        <link href="<?php echo base_url(); ?>assets/css/theme.css" rel="stylesheet" type="text/css" media="screen">
        <!-- Bootstrap theme -->
        <!--<link href="<?php echo base_url(); ?>assets/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">--->  
        <title>
            <?php echo isset($page)? $page . " | " . $webTitle: $webTitle; ?>
        </title>
    </head>
    <body role="document">
        <?php
            echo "<h1>" . $university_id . "</h1>";
            $data["webTitle"] = "AMS Dashboard";
            $this->load->view("include/print_header.php",$data);
        ?>
                    <p>
                        <input type='button' name="print-form" id="print-form" value="Print" onclick="window.print();">&nbsp;
                        <input type='button' name="home" id="home" value="Back" onclick="window.location.href='<?php echo base_url(); ?>'">
                    </p>
                </div>
                <div id="main-wrapper">
        <?php
                
            echo    '<caption>
                        List of attendance of the selected course offering<br/>
                        Note: <br/>
                        &nbsp;<b style="color: #F1B0B7;">Red</b> means student is "Absent". <br/>
                        &nbsp;Click "View Attendance" button to view the attendance of the selected course offerings.
                    </caption>';
            //Generate Master List
            $masterList = $this->load_model->getMasterList($faculty_id,$course_id,$section,$semester,$academic_year);
           
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
        
        ?>
           
                </div>
        <?php
            $this->load->view("include/print_footer.php",$data);
        ?>
    </body>
</html>