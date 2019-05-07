$(document).ready(function(){
    
    /****************************** Power Management *******************************/
    var load_info_delay = 3000;
    var auto_load_setting;
    var days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
    var selected_days = [0,0,0,0,0,0,0];
    
    function clearTimeDays(){
        selected_days = [0,0,0,0,0,0,0];
        $("#update-sched-time-on").val("");
        $("#update-sched-time-off").val("");
        displayDays();
    }
    
    function checkDays(schedDay){
        if(schedDay!="" && schedDay != null){
            var sel_days = schedDay.split(' ');
            if(sel_days.length>0){
                for(var i=0;i<sel_days.length;i++){
                    for(var j=0;j<7;j++){
                        if(sel_days[i]==days[j]){
                            selected_days[j] = 1;
                        }
                    }
                }
            }
        }
    }
    
    function displayDays(){
        var text = "";
        for(var i=0;i<7;i++){
            if(selected_days[i]){
                text += days[i] + " ";
                $("#" + days[i] + "-button").attr("class","btn btn-warning");
            }else{
                $("#" + days[i] + "-button").attr("class","btn btn-primary");
            }
        }
        $("#update-load-sched-day").val(text);
        $("#powermanagement-warning-message").html("");
    }
    
    function setDay(index){
        if(selected_days[index]){
            selected_days[index] = 0;
        }else{
            selected_days[index] = 1;            
        }
        displayDays();
    }
    
    $(".add-on i").on("click",function(){
        $("#powermanagement-warning-message").html("");
    });
    
    $("#Mon-button").on("click",function(){
        setDay(0);
    });
    
    $("#Tue-button").on("click",function(){
        setDay(1);
    });
    
    $("#Wed-button").on("click",function(){
        setDay(2);
    });
    
    $("#Thu-button").on("click",function(){
        setDay(3);
    });
    
    $("#Fri-button").on("click",function(){
        setDay(4);
    });
    
    $("#Sat-button").on("click",function(){
        setDay(5);
    });
    
    $("#Sun-button").on("click",function(){
        setDay(6);
    });
    
    $("#clear-button").on("click",function(){
        clearTimeDays();
    });
    
    $("#save-update-powermanagement").on("click",function(){
        if($("#update-load-description").val()==""){
            $("#powermanagement-warning-message").trigger("focus");
            $("#powermanagement-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "Please enter the appropriate description for this load.</div>");
            $(document).on('alert','.alert');
        }else if(($("#update-sched-time-on").val()!="" || $("#update-sched-time-off").val()!="")
                 && ($("#update-load-sched-day").val()=="")){
            $("#powermanagement-warning-message").trigger("focus");
            $("#powermanagement-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "You have updated the schedule time on/off. Please enter the appropriate schedule day(s).</div>");
            $(document).on('alert','.alert');
        }else if(($("#update-sched-time-on").val()=="" && $("#update-sched-time-off").val()=="")
                 && ($("#update-load-sched-day").val()!="")){
            $("#powermanagement-warning-message").trigger("focus");
            $("#powermanagement-warning-message").html("<div class='alert alert-danger'><a href='#' class='close' data-dismiss='alert'>&times;</a>" +
                             "You have updated the schedule day(s). Please enter the appropriate schedule time on/off.</div>");
            $(document).on('alert','.alert');
        }else{
            var stat = $("#update-load-status").prop("checked")?1:0;
            var src = $("#update-powermanagement").attr("action");
            var load_id = $("#update-load-id").val();
            $.ajax({
                url: src,
                type: 'POST',
                data: {con_load_id:load_id,description:$("#update-load-description").val(),priority:$("#update-load-priority").val(),
                    status:stat,schedule_day:$("#update-load-sched-day").val(),schedule_on:$("#update-sched-time-on").val(),schedule_off:$("#update-sched-time-off").val(),
                    user_id:$("#load-user-id").val()},
                dataType: 'html',
                beforeSend: function(){
                    $("#powermanagementMessageModal").find('.modal-body p').html("Please wait while saving your changes for Load " + load_id + "...");
                    $("#powermanagementMessageModal").modal("show");
                    $("#powermanagementModal").modal("hide");
                },
                success: function(data,textStatus,jqXHR){
                    if(data!=null && data.responseText!='' && data.length!=0){
                        $("#powermanagementMessageModal").find('.modal-body p').html(data);
                    }else{                    
                        $("#powermanagementMessageModal").find('.modal-body p').html("Problem occured, transaction failed. Load " + load_id +
                                                                          " cannot be updated. Kindly contact the developer.");
                    }
                    $("#powermanagementMessageModal").modal("show");
                },
                error: function(jqXHR, textStatus, errorThrown){
                    $("#powermanagementMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                    $("#powermanagementMessageModal").modal("show");
                },
                complete: function(){
                    $("#powermanagement-warning-message").html("");
                }
            });
        }
    });
    
    
    $(".update-con-load-button").on("click",function(){
        clearTimeDays();
        var body_info = "#load-body-info-" + $(this).attr("id");
        $("#update-load-id").val($(this).attr("id"));
        $("#update-load-description").val($(body_info + " .con-load-description").html());
        $("#update-load-priority").val($(body_info + " .con-load-priority").html())
        if(parseInt($(body_info + " .load-status").val())==1){
            $("#update-load-status").bootstrapToggle("on"); 
        }else{
            $("#update-load-status").bootstrapToggle("off");  
        }
        $("#update-sched-time-on").val(($(body_info + " .sched-on").val()=="N / A"?"":$(body_info + " .sched-on").val()));
        $("#update-sched-time-off").val(($(body_info + " .sched-off").val()=="N / A"?"":$(body_info + " .sched-off").val()));
        $("#sched-time-on").datetimepicker("update");
        $("#sched-time-off").datetimepicker("update");
        checkDays($(body_info + " .sched-day").val());
        displayDays();
        $("#powermanagement-warning-message").html("");
        $("#powermanagementModal").modal("show");
    });
    
    $("#sched-time-on").datetimepicker({
        //language:  'fr',
        pickDate: false,
        pick12HourFormat: true
    });
    
    $("#sched-time-off").datetimepicker({
        //language:  'fr',
        pickDate: false,
        pick12HourFormat: true
    });
    
    
    $("#auto-load-shedding-on").click(function(){
        auto_load_setting = 1;
        $("#confirmPowerManagementModal").find('.modal-body p').html("Do you want to <b>Activate</b> Auto Load Shedding?");
	$("#confirmPowerManagementModal").modal("show");       
    });
    
    $("#auto-load-shedding-off").click(function(){
        auto_load_setting = 0;
        $("#confirmPowerManagementModal").find('.modal-body p').html("Do you want to <b>Deactivate</b> Auto Load Shedding?");
	$("#confirmPowerManagementModal").modal("show");
    });
    
    $("#yes-update-powermanagement").click(function(){
        var src = $("#update-load-shedding").attr("action");
        $.ajax({
            url: src,
	    type: 'POST',
	    data: {status:auto_load_setting},
	    dataType: 'html',
	    beforeSend: function(){
                $("#confirmPowerManagementModal").modal("hide");
		$("#powermanagementMessageModal").find('.modal-body p').html("Please wait while updating the auto load shedding settings...");
		$("#powermanagementMessageModal").modal("show");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data.length!=0){
                    $("#powermanagementMessageModal").find('.modal-body p').html(data);
                    $("#powermanagementMessageModal").modal("show");
                    if(auto_load_setting){
                        $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>ON</b>");
                    }else{
                        $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>OFF</b>");
                    }
                }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#powermanagementMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#powermanagementMessageModal").modal("show");
	    },
	    complete: function(){		
	    }
        }); 
    });

    setInterval(function(){
        //Update connected loads information
        var src = $("#get-connected-loads").attr("action");
        $.ajax({
            url: src,
	    type: 'POST',
	    data: {},
	    dataType: 'json',
	    beforeSend: function(){
		//$("#powermanagementMessageModal").modal("hide");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data.length!=0){
		    for(var i=1;i<=data.length;i++){
                        var body_info = "#load-body-info-" + i;
                        $(body_info + " .con-load-description").html(data[(i-1)]["description"]);
                        $(body_info + " .con-load-priority").html(data[(i-1)]["priority"]);
                        if(parseInt(data[(i-1)]["status"])==1){
                            $(body_info + " .load-status").val("1");
                            $(body_info + " .con-load-status").html("On");
                            $("#load-image-status-" + i).html('<img alt="ON IMAGE" src="' + $("#powermanagement-base-url").val() +
                                                              'assets/css/images/load-on.gif" class="load-image-status img-fluid">');
                        }else{
                            $(body_info + " .load-status").val("0");
                            $(body_info + " .con-load-status").html("Off");
                            $("#load-image-status-" + i).html('<img alt="OFF IMAGE" src="' + $("#powermanagement-base-url").val() +
                                                              'assets/css/images/load-off.gif" class="load-image-status img-fluid">');
                        }
                        var sc_on = data[(i-1)]["schedule_on"]=="N / A"?"N / A":moment(data[(i-1)]["schedule_on"]).format("");
                        $(body_info + " .con-load-sched-day").html(data[(i-1)]["schedule_day"]);
                        $(body_info + " .con-load-sched-on").html(data[(i-1)]["schedule_on"]);
                        $(body_info + " .con-load-sched-off").html(data[(i-1)]["schedule_off"]);
                        $(body_info + " .sched-day").val(data[(i-1)]["sched_day"]);
                        $(body_info + " .sched-on").val(data[(i-1)]["sched_on"]);
                        $(body_info + " .sched-off").val(data[(i-1)]["sched_off"]);
                        $(body_info + " .con-load-updated-on").html(data[(i-1)]["last_updated"]);
                        $(body_info + " .con-load-updated-by").html(data[(i-1)]["user_fullname"]);
                    }
		}
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#powermanagementMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#powermanagementMessageModal").modal("show");
	    },
	    complete: function(){		
	    }
        });
        
    },load_info_delay);
    
    
    Highcharts.setOptions({
      global: {
            useUTC: false
      }
    });

    Highcharts.chart('realtime-powermanagement-container', {
      chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
              load: function () {
                    // set up the updating of the chart each second
                    var series = this.series[0];
                    var src = $("#update-load-summary").attr("action");
                    setInterval(function () {
                        var ave_power = 0;
                        $.ajax({
                            url: src,
                            type: 'POST',
                            data: {},
                            dataType: 'json',
                            beforeSend: function(){
                                //$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
                            },
                            success: function(data,textStatus,jqXHR){
                                        if(data!=null && data!=""){;
                                                var voltage = parseFloat(data["voltage"]);
                                                var current = parseFloat(data["current"]);
                                                var frequency = parseFloat(data["frequency"]);
                                                ave_power = parseFloat(data["power"]);
                                                var status = parseInt(data["status"])==1?"Stable":"Unstable";
                                                var source = parseInt(data["source_id"])==1?"MAINS":"SECONDARY";
                                                var power_factor = 0;
                                                if(current>0 && voltage>0){
                                                    power_factor = ((ave_power)/(current*voltage));   
                                                }
                                                var apparent_power = ((voltage * current)/1000);
                                                var is_auto_load_shedding = parseInt(data["is_auto_load_shedding"]);
                                                var mode_selection = parseInt(data["is_manual_selection"])==0?"ATS is enabled":"Manual Transfer is enabled";
                                                
                                                
                                                var x = (new Date()).getTime();//, // current time
                                                //     y = Math.random();
                                                series.addPoint([x, parseFloat((ave_power/1000).toFixed(3))], true, true);
                                                
                                                //if(parseInt(data["primary"]["is_selected"])){
                                                //    $("#source-type").val(1);
                                                //}
                                                //Information
                                                $("#load-summary-info-body").html("Mode: <b>" + mode_selection + "</b><br/>" +
                                                                                  "Source: <b>" + source + "</b><br/>" +
                                                                                                "Status : <b>" + status + "</b><br/>" +
                                                                "Frequency : <b>" + frequency.toFixed(2) + " Hz</b><br/>" + 
                                                                                                "Voltage : <b>" + voltage.toFixed(2) + " V</b><br/>" + 
                                                                "Current : <b>" + current.toFixed(2) + " A</b><br/>" +
                                                                                                "Apparent Power : <b>" + apparent_power.toFixed(3) + " kVA</b><br/>" + 
                                                                                                "Average Power : <b>" + (ave_power/1000).toFixed(3) + " kW</b><br/>" +
                                                                                                "Power Factor : <b>" + power_factor.toFixed(3) + "</b><br/>");
                                                if(is_auto_load_shedding){
                                                    $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>ON</b>");
                                                }else{
                                                    $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>OFF</b>");
                                                }
                                                
                                        }
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                $("#atsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                                $("#atsMessageModal").modal("show");
                            },
                            complete: function(){
                                //$("#dynamicMessage-settings").html("");
                            }
                        });                     
                        
                    }, 1500);
              }
            }
      },
      title: {
            text: 'Realtime Real Power Consumption'
      },
      credits: {
          enabled: false
      },
      xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
      },
      yAxis: {
            title: {
              text: 'Power (kW)'
            },
            plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
            }]
      },
      tooltip: {
            formatter: function () {
              return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                    Highcharts.numberFormat(this.y, 2);
            }
      },
      legend: {
            enabled: true
      },
      exporting: {
            enabled: true
      },
      series: [{
            name: 'Real Power (kW)',
            data: (function () {
              // generate an array of random data
              var data = [],
                    time = (new Date()).getTime(),
                    i;
              var y_data;  
              for (i = -19; i <= 0; i += 1) {
                    data.push({
                      x: time + i * 1000,
                      y: y_data
                    });
              }
              return data;
            }())
      }]
    });
    
});