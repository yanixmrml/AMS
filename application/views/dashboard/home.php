<h1 class="text-center" style="float: none; display: block; "></h1>
<p class="text-justify text-muted"><span  style="font-size: 50px; font-weight: bold;">Hi! BOLOS KANO! <?php echo $userInfo['firstname'] ?>!</span>&nbsp;Welcome to your AMS (Attendance Monitoring System)
Dashboard Account. The AMS Dashboard is a control panel with privileges to access and print masterlist and attendance of your students.
</p>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 text-center">
            &nbsp;
            <div id="dashboard-home-panel" class="container-fluid text-center">
                <div class="row">
                <?php
                    $size = count($menus);
                    for($i=0;$i<$size;$i++){
                        echo "<div class='col-md-2 dashboard-atspms-dashboard-services'>"
                            . "<a href='" . $menus[$i]["URL"] . "' id='" . $menus[$i]["ID"]    ."'><img src='" . $menus[$i]["ICON"] . "'><br/>" . $menus[$i]["NAME"] . "</a></div>";
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userInfoLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="password-warning-message"></div>
        <?php
            echo form_open("dashboard/update_password",array("id"=>"update-password-form","method"=>"post"));
        ?>
        <div class="form-group" >
            <label for="old-password">Old Password</label>&nbsp;
            <input type="password" name="old-password" id="old-password" class="form-control" value="" placeholder="Enter old password" required>
        </div>
        <div class="form-group" >
            <label for="new-password">New Password</label>&nbsp;
            <input type="password" name="new-password" id="new-password" class="form-control" value="" placeholder="Enter new password" required>
        </div>
        <div class="form-group" >
            <label for="confirm-password">Confirm Password</label>&nbsp;
            <input type="password" name="confirm-password" id="confirm-password" class="form-control" value="" placeholder="Confirm the new password" required>
        </div>
        <input type="hidden" name="auth" value="<?php echo $auth; ?>" />
        <?php
            if(isset($userInfo["faculty_id"])){
        ?>
                <input type="hidden" name="u_id"  id="home-user-id" value="<?php echo $userInfo['faculty_id']; ?>" />
        <?php
            }else{
        ?>
                <input type="hidden" name="u_id"  id="home-user-id" value="<?php echo $userInfo['student_id']; ?>" />
        <?php
            }
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="save-pass-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-pass-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="homeMessageModal" tabindex="-1" role="dialog" aria-labelledby="homeMessageModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">`
      <div class="modal-header">
        <h5 class="modal-title" id="homeMessageModalTitle">Information Message</h5>
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