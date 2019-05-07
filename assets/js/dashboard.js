$(document).ready(function(){
    
    /********* Faculty *************************/
    
    function populateCourseList(f_id,sem,acad_year){
	var src = $("#get-course-list").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {faculty_id:f_id,semester:sem,academic_year:acad_year},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-faculty").html("Loading the course list...");
	    },
	    success: function(data,textStatus,jqXHR){
		var courseBody = $("#course-list");
		if(data !=''&& data!=null){   
		    courseBody.html(data);    
		}
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#facultyMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#facultyMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-faculty").html("");
	    }
	});
    }
    
    $("#view-courses-button").on("click",function(){
	$("#selected-semester").val($("#select-semester").val());
	$("#selected-academic-year").val($("#select-academic-year").val());
	populateCourseList($("#faculty-id").val(),$("#select-semester").val(),$("#select-academic-year").val());
    });
    
    $(document).on("click",".select-course",function(){
	$("#selected-course-id").val($(this).find(".course-id").val());
	$("#selected-section").val($(this).find(".course-section").val());
	$("#selected-schedule").val($(this).find(".course-schedule").val());
	$("#subject-selected").html("<b>Subject : </b> " + $(this).find(".course-name").html() + " - " +  $(this).find(".course-description").val())
	$("#schedule-selected").html("<b>Schedule : </b>" + $(this).find(".course-schedule").val());
	$("#section-selected").html("<b>Section : </b>" + $(this).find(".course-section").val());
	$("#semester-selected").html("<b>Semester: </b>" + $("#selected-semester").val() + ", " + $("#selected-academic-year").val());
    });

    function populateAttendanceTable(type,c_id,f_id,sec,sched,sem,acad_year,d_from,d_to){
	var src = $("#get-attendance-list").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {a_type:type,course_id:c_id,faculty_id:f_id,section:sec,schedule:sched,semester:sem,academic_year:acad_year,date_from:d_from,date_to:d_to},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-faculty").html("Loading the course list...");
	    },
	    success: function(data,textStatus,jqXHR){
		tableName = $("#attendance-table");
		//alert("SDSDSD");
                if(data !=''&& data!=null){                    
                    tableName.html(data);
                }else{
                    var row = "<tr ><td colspan='10'><p class='information-message'>Attendance result is empty. No records match </p></td></tr>";
                    tableName.html(row);
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#facultyMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#facultyMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-faculty").html("");
	    }
	});
    }
    
    $("#view-attendance-button").on("click",function(){
	populateAttendanceTable($("#attendance-type").val(),$("#selected-course-id").val(),$("#faculty-id").val(),$("#selected-section").val(),$("#selected-schedule").val(),
				$("#selected-semester").val(),$("#selected-academic-year").val(),$("#date-from").val(),$("#date-to").val());
    });
    
    $('#date-from').datetimepicker({
        language:  'en',
	minView:'month'	
    });
     
    //#add-effectivity-date
    $('#date-to').datetimepicker({
        language:  'en',
	minView:'month'
    });
    
    $("#attendance-type").on("click",function(){
	if($("#attendance-type").val()==1){
	    $("#date-from").prop("disabled",true);
	    $("#date-to").prop("disabled",true);
	}else{
	    $("#date-from").prop("disabled",false)
	    $("#date-to").prop("disabled",false);
	}
    });
    
    /*function updatePicture(){
	var src = $("#view-picture").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {a_type:type,course_id:c_id,faculty_id:f_id,section:sec,schedule:sched,semester:sem,academic_year:acad_year,date_from:d_from,date_to:d_to},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-faculty").html("viewing picture of selected student...");
	    },
	    success: function(data,textStatus,jqXHR){
		//alert("SDSDSD");
            
		//$("#viewPictureModal").modal("show");
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#facultyMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#facultyMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-faculty").html("");
	    }
	});
    }*/
    
    $(document).on('click',".student-action",function(){
	if($(this).find(".view-picture").val()!=""){
	    //$("#updateStudentImage").val($(this).find(".view-picture").val());
	    $("#previousPicture").val($(this).find(".view-picture").val());
	    $("#selected-update-img-thumbnail").attr("src",$("#base_url").val() + "/uploads/" + $(this).find(".view-picture").val());
	    //alert($(this).find(".view-picture").val());
	}else{
	     $("#selected-update-img-thumbnail").attr("src",$("#base_url").val() + "assets/css/images/no-pic.jpg");
	}
	//alert($(this).find(".student-name").html());
	$("#update-firstname").val($(this).find(".student-name").html());
	$("#update-id-number").val($(this).find(".student-id").val())
	$("#viewPictureModal").modal("show");
    });
    
    $(document).on('click',"#save-update-picture-button",function(){
	var src = $("#update-picture").attr("action");
	var form = $("#update-picture").get(0);
	var formData = new FormData(form);
	formData.append("university_id",$("#update-id-number").val());
	formData.append("picture",$("#updateStudentImage").val());
	formData.append("previousPicture",$("#previousPicture").val());	
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: formData,
	    processData: false,
	    contentType: false,
	    dataType: 'html',
	    beforeSend: function(){
		$("#viewPictureModal").modal("hide");
		$("#facultyMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
		$("#facultyMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		$("#facultyMessageModal").find('.modal-body p').html(data);
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#facultyMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#facultyMessageModal").modal("show");
	    },
	    complete: function(){
		
	    }
	});
    });
    
    function printAttendanceTable(type,c_id,f_id,sec,sched,sem,acad_year,d_from,d_to){
	var src = $("#get-attendance-list").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {a_type:type,course_id:c_id,faculty_id:f_id,section:sec,schedule:sched,semester:sem,academic_year:acad_year,date_from:d_from,date_to:d_to},
	    dataType: 'html',
	    beforeSend: function(){
		$("#dynamicMessage-faculty").html("Loading the course list...");
	    },
	    success: function(data,textStatus,jqXHR){
		tableName = $("#print-attendance-table");
		//alert("SDSDSD");
                if(data !=''&& data!=null){                    
                    tableName.html(data);
                }else{
                    var row = "<tr ><td colspan='10'><p class='information-message'>Attendance result is empty. No records match </p></td></tr>";
                    tableName.html(row);
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#facultyMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#facultyMessageModal").modal("show");
	    },
	    complete: function(){
		$("#dynamicMessage-faculty").html("");
		$("#printModal").modal("show");
	    }
	});
    }
    
    $("#print-attendance-button").on("click",function(e){
	printAttendanceTable($("#attendance-type").val(),$("#selected-course-id").val(),$("#faculty-id").val(),$("#selected-section").val(),$("#selected-schedule").val(),
				$("#selected-semester").val(),$("#selected-academic-year").val(),$("#date-from").val(),$("#date-to").val());
    });
    
    $(document).on("click","#cancel-print-button",function(){
	$('#printModal').modal('hide');
    });
    
    $(document).on("click",".update-user-picture",function(){
	$("#viewUserPictureModal").modal("show");
    });
    
    $(document).on("click","#save-user-picture-button",function(){
	var src = $("#update-user-picture").attr("action");
	var form = $("#update-user-picture").get(0);
	var formData = new FormData(form);
	formData.append("university_id",$("#update-user-idnumber").val());
	formData.append("updateUserImage",$("#updateUserImage").val());
	formData.append("previousUserPicture",$("#previousUserPicture").val());	
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: formData,
	    processData: false,
	    contentType: false,
	    dataType: 'html',
	    beforeSend: function(){
		$("#viewUserPictureModal").modal("hide");
		$("#userMessageModal").find('.modal-body p').html("Please wait while saving the changes of this user account...");
		$("#userMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		$("#userMessageModal").find('.modal-body p').html(data);
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#userMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#userMessageModal").modal("show");
	    },
	    complete: function(){
		
	    }
	});
    });
    
    
    /*//Testing
    var counter = 1;
    setInterval(function(){
        
        $("#counter-id").html("Counter : " + counter);
	counter++;
        },1500); */
   
    /******************** Home ********************/
    
    $(document).on('click',"#home-change-password",function(){
        $("#passwordModal").modal("show");
	$("#old-password").val("");
	$("#new-password").val("");
	$("#confirm-password").val("");
    });
    
    $(document).on('click','#save-pass-button',function(){
        if($("#old-password").val()=="" ||
	    $("#old-password").val().length <6){
            $("#old-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter your old password correctly.</div>");
            $(document).on('alert','.alert');
        }else if($("#new-password").val()==""
                 || $("#new-password").val().length <6
                 || $("#new-password").val().length >16){
            $("#new-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Password must be at least 6 characters and at most 16 characters.</div>");
            $(document).on('alert','.alert');
        }else if($("#confirm-password").val()==""){
            $("#confirm-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please confirm your new password.</div>");
            $(document).on('alert','.alert');
        }else if($("#new-password").val()!=$("#confirm-password").val()){
            $("#confirm-password").focus();
            $("#password-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please verify your password correctly, confirm password doesn't match with new password.</div>");
            $(document).on('alert','.alert');
        }else{
            var src = $("#update-password-form").attr("action");
	    $.ajax({
		url: src,
		type: 'POST',
		data: {user_id:$("#home-user-id").val(),old_password:$("#old-password").val(),
		    new_password:$("#new-password").val()},
		dataType: 'html',
		beforeSend: function(){
		    $("#passwordModal").modal("hide");
		    $("#homeMessageModal").find('.modal-body p').html("Please wait while saving your new password.");
		    $("#homeMessageModal").modal("show");
		},
		success: function(data,textStatus,jqXHR){
		    $("#homeMessageModal").find('.modal-body p').html(data);
		    $("#homeMessageModal").modal("show");
		},
		error: function(jqXHR, textStatus, errorThrown){
		    $("#settingsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		    $("#settingsMessageModal").modal("show");
		},
		complete: function(){
		    $("#dynamicMessage-settings").html("");
		}
	    });
        }
    });
    
    $("#passwordModal").on("hide.bs.modal",function(e){
        $("#password-warning-message").text("");
        $("#old-password").val("");
        $("#new-password").val("");
        $("#confirm-password").val("");
    });
    
    $("#old-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#new-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#confirm-password").keypress(function(event){
        $("#password-warning-message").text("");
    });
    
    $("#update-other-button").click(function(){
        $("#otherInfoModal").modal("toggle");
        $("#profile-message").alert("close");
    });
    
    $(document).on('click','#save-other-info-button',function(){
        $("#update-other-info-frm").submit();
    });
    
});
