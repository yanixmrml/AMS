<?php
$picture = "";
$fullname = "";

if(isset($userInfo)){
    //if($this->session->userdata("picture")){                    
    //    $userPic = $this->session->userdata("picture");
    //}else{
    $picture = "";
    $fullname = $userInfo['lastname'] . ", " . $userInfo['firstname'] ;
    if(isset($userInfo['picture']) && $userInfo['picture']!=null){
        $userPic = "uploads/" . $userInfo['picture'];
        $picture =  $userInfo['picture'];
    }else{
        $userPic = "assets/css/images/no-pic.jpg";    
    }   
    //}
    echo "<div class='user-info-panel'>";
    echo "<p><img class='img-user img-thumbnail' src='" . base_url() .  $userPic . "'></p>";
    echo "<p><span class='update-user-picture'><b>" . $fullname . "</b></span><br/>";  
    echo "Univesity ID: " . $userInfo['university_id'] . "<br/>";
    echo "Last Logged In: " . $this->tools->formatDateTime($userInfo['last_login'],"D, F j, Y");
    echo "</p>";
    echo "<hr/></div>";
}
?>
<div class="modal" id="viewUserPictureModal" tabindex="-1" role="dialog" aria-labelledby="viewUserPictureLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewUserPictureLabel">View Picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="view-user-picture-warning"></div>
        <?php
            echo form_open_multipart("dashboard/update_user_picture",array("id"=>"update-user-picture","method"=>"post","enctype"=>"multipart/form-data"));
        ?>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-user-idnumber">ID Number</label>&nbsp;
                    <input type="text" name="update-user-idnumber" id="update-user-idnumber" value="<?php echo $userInfo["university_id"]; ?>" class="form-control" disabled="disabled"/>
                </div>
                <div class="col picture-selection">
                    <img src="<?php echo base_url() . ($picture==""?"assets/css/images/no-pic.jpg":"uploads/".$picture); ?>" class="img-user img-fluid rounded mx-auto d-block" id="selected-update-img-thumbnail"/><br/>
                    <div class="custom-file">
                        <input type="file" name="updateUserImage" id="updateUserImage" value="" class="custom-file-input" aria-describedby="fileHelp"/>
                        <label for="updateUserImage" class="custom-file-label"></label>
                        <span class="custom-file-control form-control-file"></span>
                    </div>
                    <input type="hidden" id="previousUserPicture" value="<?php echo $picture; ?>">
                    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-group" >
            <div class="form-row">
                <div class="col">
                    <label for="update-firstname">Fullname</label>&nbsp;
                    <input type="text" name="update-user-firstname" id="update-user-firstname" class="form-control" value="<?php echo $fullname; ?>" placeholder="Fullname" disabled>
                </div>
            </div>
        </div>
        <?php
            echo form_close();
        ?>
      </div>
      <div class="modal-footer">
        <button type="submit"  id="save-user-picture-button" class="btn btn-primary">Save changes</button>
        <button type="button" id="cancel-user-picture-button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="userMessageModal" tabindex="-1" role="dialog" aria-labelledby="userMessageModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userMessageModalTitle">Information Message</h5>
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