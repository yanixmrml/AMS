$(document).ready(function(){
    
    /****************************** Automatic Transfer Switch *******************************/
    var counter = 0;
    var delay_realtime = 1500;
    var num_tick = 5;
    var source_selected = 1;
 
    var powerOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
    
      // the value axis
      yAxis: {
        stops: [
          [0.5, '#55BF3B'], // green
          [parseFloat(($("#ats-power-max").val()/parseFloat($("#ats-power-max").val())).toFixed(2)), '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: -70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
 
    var currentOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
    
      // the value axis
      yAxis: {
        stops: [
          [parseFloat(($("#ats-current-min").val()/parseFloat($("#ats-current-max").val())).toFixed(2)) - 0.05, '#55BF3B'], // green
          [parseFloat(($("#ats-current-max").val()/parseFloat($("#ats-current-max").val())).toFixed(2)), '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: -70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
 
    var voltageOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
    
      // the value axis
      yAxis: {
        stops: [
          [parseFloat(($("#ats-voltage-min").val()/parseFloat($("#ats-voltage-max").val())).toFixed(2)) - 0.05, '#DF5353'], // red
          [parseFloat(($("#ats-nominal-voltage").val()/parseFloat($("#ats-voltage-max").val())).toFixed(2)), '#55BF3B'],// green
          [parseFloat(($("#ats-voltage-max").val()/parseFloat($("#ats-voltage-max").val())).toFixed(2)), '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: -70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
    
    //alert($("#ats-nominal-frequency").val());
    //alert($("#ats-nominal-frequency").val()/80.0);
    
    var frequencyOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
      // the value axis
      yAxis: {
        stops: [
          [$("#ats-frequency-min").val()/80.0, '#DF5353'], // red
          [$("#ats-nominal-frequency").val()/80.0, '#55BF3B'],// green
          [$("#ats-frequency-max").val()/80.0, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: -70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
    
    var powerFactorOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
    
      // the value axis
      yAxis: {
        stops: [
          [0.7, '#DF5353'], // red
          [0.8, '#55BF3B'] // green
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: -70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
    
    var chartMainsFrequency = Highcharts.chart('container-mains-frequency', Highcharts.merge(frequencyOptions, {
        yAxis: {
          min: 0,
          max: 80,
          title: {
            text: '<b>MAINS FREQUENCY</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Mains Frequency',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Hertz</span></div>'
          },
          tooltip: {
            valueSuffix: ' Hz'
          }
        }]
      
    }));
    
     var chartSecondaryFrequency = Highcharts.chart('container-secondary-frequency', Highcharts.merge(frequencyOptions, {
        yAxis: {
          min: 0,
          max: 80,
          title: {
            text: '<b>SECONDARY FREQUENCY</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Secondary Frequency',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Hertz</span></div>'
          },
          tooltip: {
            valueSuffix: ' Hz'
          }
        }]
      
    }));
    
    var chartMainsVoltage = Highcharts.chart('container-mains-voltage', Highcharts.merge(voltageOptions, {
        yAxis: {
          min: 0,
          max: parseFloat($("#ats-voltage-max").val()),
          title: {
            text: '<b>MAINS  VOLTAGE</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Mains Voltage',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Volts</span></div>'
          },
          tooltip: {
            valueSuffix: ' Volts'
          }
        }]
      
    }));	
    
    var chartSecondaryVoltage = Highcharts.chart('container-secondary-voltage', Highcharts.merge(voltageOptions, {
        yAxis: {
          min: 0,
          max: parseFloat($("#ats-voltage-max").val()),
          title: {
            text: '<b>SECONDARY  VOLTAGE</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Secondary Voltage',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Volts</span></div>'
          },
          tooltip: {
            valueSuffix: ' Volts'
          }
        }]
      
    }));

    var chartMainsCurrent = Highcharts.chart('container-mains-current', Highcharts.merge(currentOptions, {
        yAxis: {
          min: 0,
          max: 100,
          title: {
            text: '<b>MAINS  CURRENT</b>'
          }
        },
      
        credits: {
          enabled: false
        },
      
        series: [{
          name: 'Mains Current',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Amperes</span></div>'
          },
          tooltip: {
            valueSuffix: ' Amperes'
          }
        }]
      
    }));
    
    var chartSecondaryCurrent = Highcharts.chart('container-secondary-current', Highcharts.merge(currentOptions, {
        yAxis: {
          min: 0,
          max: 100,
          title: {
            text: '<b>SECONDARY  CURRENT</b>'
          }
        },
      
        credits: {
          enabled: false
        },
      
        series: [{
          name: 'Secondary Current',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">Amperes</span></div>'
          },
          tooltip: {
            valueSuffix: ' Amperes'
          }
        }]
      
    }));

    var chartMainsPower = Highcharts.chart('container-mains-power', Highcharts.merge(powerOptions, {
        yAxis: {
          min: 0,
          max: 100,
          title: {
            text: '<b>MAINS REAL POWER</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Mains Average Power',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">kW</span></div>'
          },
          tooltip: {
            valueSuffix: ' kW'
          }
        }]
      
    }));
    
    var chartSecondaryPower = Highcharts.chart('container-secondary-power', Highcharts.merge(powerOptions, {
        yAxis: {
          min: 0,
          max: 100,
          title: {
            text: '<b>SECONDARY REAL POWER</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Secondary Average Power',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">kW</span></div>'
          },
          tooltip: {
            valueSuffix: ' kW'
          }
        }]
      
    }));
    
    var chartMainsPowerFactor = Highcharts.chart('container-mains-power-factor', Highcharts.merge(powerFactorOptions, {
        yAxis: {
          min: 0,
          max: 1,
          title: {
            text: '<b>MAINS POWER FACTOR</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Mains Power Factor',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">-</span></div>'
          },
          tooltip: {
            valueSuffix: ''
          }
        }]
      
    }));
    
    var chartSecondaryPowerFactor = Highcharts.chart('container-secondary-power-factor', Highcharts.merge(powerFactorOptions, {
        yAxis: {
          min: 0,
          max: 1,
          title: {
            text: '<b>SECONDARY POWER FACTOR</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Secondary Power Factor',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">-</span></div>'
          },
          tooltip: {
            valueSuffix: ''
          }
        }]
      
    }));

    setInterval(function () {
        var point;
        var src = $("#get-ats-data").attr("action");
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
		    if(data["primary"]!=null && data["primary"]!=""){
			var voltage = parseFloat(data["primary"]["voltage"]);
			var current = parseFloat(data["primary"]["current"]);
			var frequency = parseFloat(data["primary"]["frequency"]);
			var ave_power = parseFloat(data["primary"]["power"]);
			var status = parseInt(data["primary"]["status"])==1?"Stable":"Unstable";
			var action = parseInt(data["primary"]["is_selected"])==1?"Source of Power (SUPPLYING)":"Standby";
			var power_factor = 0;
			if(ave_power>0 && (current > 0 && voltage > 0)){
			    power_factor = ((ave_power)/(current*voltage));
			}else{
			    power_factor = 0;
			}
			var apparent_power = ((voltage * current)/1000);
			var is_manual_selection = parseInt(data["primary"]["is_manual_selection"]);
			
			//if(parseInt(data["primary"]["is_selected"])){
			//    $("#source-type").val(1);
			//}
			//Information
			$("#mains-info-body").html("<b>" + action + "</b><br/>" +
                                    "Status : <b>" + status + "</b><br/>" +
				    "Frequency : <b>" + frequency.toFixed(2) + " Hz</b><br/>" + 
                                    "Voltage : <b>" + voltage.toFixed(2) + " V</b><br/>" + 
				    "Current : <b>" + current.toFixed(2) + " A</b><br/>" +
                                    "Apparent Power : <b>" + apparent_power.toFixed(3) + " kVA</b><br/>" + 
                                    "Average Power : <b>" + (ave_power/1000).toFixed(3) + " kW</b><br/>" +
                                    "Power Factor : <b>" + power_factor.toFixed(3) + "</b><br/>");
			
			if(is_manual_selection){
			    $("#update-ats-button").prop("disabled",true);
			    $("#source-type").prop("disabled",true);			    		    
			    $("#manual-selection-message").html('<br/>' +
				    '<p class="text-center alert alert-warning">' +
					'<b clas="alert-heading">Warning:</b>&nbsp;Automatic Transfer Switch is disabled,<br/>' +
					'Manual operation is selected.' +
				    '</p>');
			}else{
			    $("#update-ats-button").prop("disabled",false);
			    $("#source-type").prop("disabled",false);			    
			    $("#manual-selection-message").html("");
			}
			
			// Mains Frequency
                        if (chartMainsFrequency) {
                            point = chartMainsFrequency.series[0].points[0];
                            point = chartMainsFrequency.series[0].points[0];
                            point.update(parseFloat(frequency.toFixed(2)));
                        }
			
			 // Mains Voltage
                        if (chartMainsVoltage) {
                            point = chartMainsVoltage.series[0].points[0];
                            point = chartMainsVoltage.series[0].points[0];
                            point.update(parseFloat(voltage.toFixed(2)));
                        }
			
			// Mains Current
                        if (chartMainsCurrent) {
                            point = chartMainsCurrent.series[0].points[0];
                            point = chartMainsCurrent.series[0].points[0];
                            point.update(parseFloat(current.toFixed(2)));
                        }
                      
			 // Mains Power
                        if (chartMainsPower) {
                            point = chartMainsPower.series[0].points[0];
                            point = chartMainsPower.series[0].points[0];
                            point.update(parseFloat((ave_power/1000).toFixed(3)));
                        }
			
			// Mains Power Factor
			if (chartMainsPowerFactor) {
                            point = chartMainsPowerFactor.series[0].points[0];
                            point = chartMainsPowerFactor.series[0].points[0];
                            point.update(parseFloat(power_factor.toFixed(3)));
                        }
		    }
                    
		    if(data["secondary"]!=null && data["secondary"]!=""){
			var voltage = parseFloat(data["secondary"]["voltage"]);
			var current = parseFloat(data["secondary"]["current"]);
			var frequency = parseFloat(data["secondary"]["frequency"]);
			var ave_power = parseFloat(data["secondary"]["power"]);
			var status = parseInt(data["secondary"]["status"])==1?"Stable":"Unstable";
			var action = parseInt(data["secondary"]["is_selected"])==1?"Source of Power (SUPPLYING)":"Standby";
			var power_factor = 0;
			if(ave_power>0 && (current > 0 && voltage > 0)){
			    power_factor = ((ave_power)/(current*voltage));
			}else{
			    power_factor = 0;
			}
			var apparent_power = ((voltage * current)/1000);
			
			//if(parseInt(data["secondary"]["is_selected"])){
			//    $("#source-type").val(1);
			//}
			//Information
			$("#secondary-info-body").html("<b>" + action + "</b><br/>" +
				    "Status : <b>" + status + "</b><br/>" +
				    "Frequency : <b>" + frequency.toFixed(2) + " Hz</b><br/>" + 
				    "Voltage : <b>" + voltage.toFixed(2) + " V</b><br/>" + 
				    "Current : <b>" + current.toFixed(2) + " A</b><br/>" +
				    "Apparent Power : <b>" + apparent_power.toFixed(3) + " kVA</b><br/>" + 
				    "Average Power : <b>" + (ave_power/1000).toFixed(3) + " kW</b><br/>" +
				    "Power Factor : <b>" + power_factor.toFixed(3) + "</b><br/>");
			
			// secondary Frequency
			if (chartMainsFrequency) {
			    point = chartSecondaryFrequency.series[0].points[0];
			    point = chartSecondaryFrequency.series[0].points[0];
			    point.update(parseFloat(frequency.toFixed(2)));
			}
			
			 // secondary Voltage
			if (chartMainsVoltage) {
			    point = chartSecondaryVoltage.series[0].points[0];
			    point = chartSecondaryVoltage.series[0].points[0];
			    point.update(parseFloat(voltage.toFixed(2)));
			}
			
			// secondary Current
			if (chartMainsCurrent) {
			    point = chartSecondaryCurrent.series[0].points[0];
			    point = chartSecondaryCurrent.series[0].points[0];
			    point.update(parseFloat(current.toFixed(2)));
			}
		      
			 // secondary Power
			if (chartMainsPower) {
			    point = chartSecondaryPower.series[0].points[0];
			    point = chartSecondaryPower.series[0].points[0];
			    point.update(parseFloat((ave_power/1000).toFixed(3)));
			}
			
			// secondary Power Factor
			if (chartMainsPowerFactor) {
			    point = chartSecondaryPowerFactor.series[0].points[0];
			    point = chartSecondaryPowerFactor.series[0].points[0];
			    point.update(parseFloat(power_factor.toFixed(3)));
			}
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
    }, delay_realtime);
    
    
    $("#update-ats-button").on("click",function(){
	var source =  $("#source-type").val()==1?"MAIN SUPPLY":"SECONDARY SUPPLY";
	source_selected = $("#source-type").val();
	$("#confirmATSModal").find('.modal-body p').html('Do you want <b>'  + source
							 + '</b> as source of power? Please click "Yes" to save your changes, otherwise click "No".');
	$("#confirmATSModal").modal("show");
    });
    
    $("#yes-update-ats").on("click",function(){
	var src = $("#update-ats").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {source_id:source_selected},
	    dataType: 'html',
	    beforeSend: function(){
		$("#confirmATSModal").modal("hide");
		$("#atsMessageModal").find('.modal-body p').html("Please wait while saving your changes in ATS record.");
		$("#atsMessageModal").modal("show");
		//$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data!=""){
		    $("#atsMessageModal").find('.modal-body p').html(data);
		    $("#atsMessageModal").modal("show");
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
    });
    
});
