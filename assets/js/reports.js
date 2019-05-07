$(document).ready(function(){
    
    /****************************** Power Management *******************************/
    var load_info_delay = 3000;
    
    Highcharts.setOptions({
      global: {
            useUTC: false
      }
    });

    Highcharts.chart('realtime-reports-container', {
      chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
              load: function () {
                    // set up the updating of the chart each second
                    var realtimeSeries = this.series[0];
                    var powerGoalSeries = this.series[1];
                    var src = $("#update-realtime-summary").attr("action");
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
                                        if(data!=null && data!=""){
                                                ave_power = data["total_power_so_far"]
                                                /*var voltage = parseFloat(data["voltage"]);
                                                var current = parseFloat(data["current"]);
                                                var frequency = parseFloat(data["frequency"]);
                                                ave_power = parseFloat(data["power"]);
                                                var status = parseInt(data["status"])==1?"Stable":"Unstable";
                                                var source = parseInt(data["source_id"])==1?"MAINS":"SECONDARY";
                                                var power_factor = ((ave_power)/(current*voltage));
                                                var apparent_power = ((voltage * current)/1000);
                                                var is_auto_load_shedding = parseInt(data["is_auto_load_shedding"]);
                                                var mode_selection = parseInt(data["is_manual_selection"])==0?"ATS is enabled":"Manual Transfer is enabled";
                                                */
                                                
                                                var x = (new Date()).getTime();//, // current time
                                                //     y = Math.random(); 
                                                
                                                realtimeSeries.addPoint([x, parseFloat((ave_power/1000).toFixed(3))], true, true);
                                                powerGoalSeries.addPoint([x, 1], true, true);
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
                                $("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                                $("#reportsMessageModal").modal("show");
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
            name: 'Commulative Real Power Since <Month>(kW)',
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
        },
            {
            name: 'Real Power Goal (kW)',
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