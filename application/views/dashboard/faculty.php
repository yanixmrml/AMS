<?php echo form_open("dashboard/get_course_list",array("id"=>"get-course-list","method"=>"post"   ));?>
<input type="hidden" id="faculty-id" name="faculty-id" value="<?php echo $userInfo["faculty_id"]; ?>">
<div class="container" id="faculty-options">
    <div class="row">
        <div class="col-lg-12 col-centered">
            <div class="input-group">
                <label for="select-semester" class="label">Semester&nbsp;</label>
                <select id="select-semester" name="select-semester" class="custom-select">
                    <?php
                        print_r($settings);
                        if(isset($settings) && $settings!=null){
                            foreach($semesters as $sem){
                                if($sem == $settings["current_semester"]){
                                    echo "<option value='" . $sem . "' selected>" . $sem . "</option>";
                                }else{
                                    echo "<option value='" . $sem . "'>" . $sem . "</option>";    
                                }
                            }
                        }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;
                <label for="select-academic-year" class="label">Academic Year&nbsp;</label>
                <select id="select-academic-year" name="select-academic-year" class="custom-select">
                    <?php
                        $year = intval($settings["current_academic_year"]);
                        if(isset($settings) && $settings!=null){
                            for($i=$year;$i>=($year-10);$i--){
                                echo "<option value='" . $i . "'>" . $i . "</option>";
                            }
                        }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="view-courses-button" name="view-courses-button" >Load Courses Assigned</button>
            </div>
        </div>
    </div>
</div>
<hr />
<?php echo form_close(); ?>
<p id="dynamicMessage-faculty" class='async-message'></p>
<div class="container-fluid" id="faculty-body-wrapper">
    <div class="row">
        <div class="col-md-3 alert">
            <div id="course-list">
                <label for="course-list"><b>Course Offerings Assigned</b></label>
            </div>
        </div>
        <div class="col-md-9">
            <?php echo form_open("dashboard/get_attendance_list",array("id"=>"get-attendance-list","method"=>"post"));?>
            <input type="hidden" id="selected-course-id" name="selected-course-id" value="">
            <input type="hidden" id="selected-section" name="selected-section" value="">
            <input type="hidden" id="selected-schedule" name="selected-schedule" value="">    
            <input type="hidden" id="selected-semester" name="selected-semester" value="">  
            <input type="hidden" id="selected-academic-year" name="selected-academic-year" value="">    
            <?php echo form_close(); ?>
            <div class="contiainer-fluid alert alert-warning">
                <div class="row label">
                    <div class="col">
                        <span id="subject-selected"><b>Subject :</b></span>
                    </div>
                    <div class="col">
                        <span id="section-selected"><b>Section :</b></span>
                    </div>
                </div>
                <div class="row label">
                    <div class="col">
                        <span id="schedule-selected"><b>Schedule :</b></span>
                    </div>
                    <div class="col">
                        <span id="semester-selected"><b>Semester :</b></span>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="input-group">
                <label for="attendance-type" class="label">Type&nbsp;</label>
                <select id="attendance-type" class="custom-select">
                    <option value="1">Today</option>
                    <option value="2">Range</option>
                </select>
                &nbsp;
                <label for="date-from" class="label">From&nbsp;</label>
                <input id="date-from" data-format="mm/dd/yyyy" data-date-format="mm/dd/yyyy"  type="text" class="form-control" value="" placeholder="From" disabled="disabled"/>
                &nbsp;
                <label for="date-to" class="label">To&nbsp;</label>
                <input id="date-to" data-format="mm/dd/yyyy" data-date-format="mm/dd/yyyy" type="text" class="form-control" value="" placeholder="To" disabled="disabled"/>
                &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="view-attendance-button" name="view-attendance-button" >View Attendance</button>
                &nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="print-attendance-button" name="print-attendance-button" >Print Attendance</button>
            </div>
            <hr/>
            <div class="table-responsive">
                <table class="table table-hover" id="attendance-table">
                    <caption>
                        List of attendance of the selected course offering<br/>
                        Note: <br/>
                        &nbsp;<b style="color: #F1B0B7;">Red</b> means student is "Absent". <br/>
                        &nbsp;Click 'View Attendance' button to view the attendance of the selected course offerings.
                    </caption>
                    <thead>
                        <tr class="table-secondary">
                            <th>No.</th>
                            <th>University ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ><td colspan='10'><p class='information-message'>Select a course offering assigned to populate the attendance list . </p></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="viewPictureModal" tabindex="-1" role="dialog" aria-labelledby="viewPictureLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewPictureLabel">View Picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="view-picture-warning"></div>
        <?php
            echo form_open_multipart("dashboard/update_picture",array("id"=>"update-picture","method"=>"post","enctype"=>"multipart/form-data"));
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col">
                    <label for="update-id-number">ID Number</label>&nbsp;
                    <input type="text" name="update-id-number" id="update-id-number" value="" class="form-control" disabled="disabled"/>
                </div>
                <div class="col picture-selection">
                    <img src="<?php echo base_url() . "assets/css/images/no-pic.jpg"; ?>" class="img-user img-fluid rounded mx-auto d-block" id="selected-update-img-thumbnail"/><br/>
                    <div class="custom-file">
                        <input type="file" name="updateStudentImage" id="updateStudentImage" value="" class="custom-file-input" aria-describedby="fileHelp"/>
                        <label for="updateStudentImage" class="custom-file-label"></label>
                        <span class="custom-file-control form-control-file"></span>
                    </div>
                    <input type="hidden" id="previousPicture" value="">
                    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-firstname">Fullname</label>&nbsp;
                    <input type="text" name="update-firstname" id="update-firstname" class="form-control" placeholder="Fullname" disabled>
                </div>
            </div>
        </div>
         <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-degree">Degree</label>&nbsp;
                    <input type="text" name="update-degree" id="update-degree" class="form-control" placeholder="Degree" disabled>
                </div>
            </div>
        </div>
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="submit"  id="save-update-picture-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-update-picture-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="facultyMessageModal" tabindex="-1" role="dialog" aria-labelledby="facultyMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="facultyMessageModalTitle">Information Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead center"></p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printMessageModalTitle">Print Dialog</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead center" id="print-options">
            <button type='button' name="print-form" id="print-form" class="btn btn-primary" onclick="window.print();">Print</button>&nbsp;
            <button type='button' name="home" class="btn btn-primary" id="cancel-print-button">Cancel</button>
        </p>
        <div id="printable-wrapper">
            <p class="text-center">
                Mindanao State University<br/>
                Marawi City<br/>
                
                <b>Attendance Sheet Log</b>
            </p>
            <p class="text-center">
                Faculty : <?php echo $userInfo["lastname"] . ", " . $userInfo["firstname"]; ?>
            </p>
            <br/>
            <div class="table-responsive">
                <table class="table table-hover" id="print-attendance-table">
                    <thead>
                        <tr class="table-secondary">
                            <th>No.</th>
                            <th>University ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ><td colspan='10'><p class='information-message'>Select a course offering assigned to populate the attendance list . </p></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>