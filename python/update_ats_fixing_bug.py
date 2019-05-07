#!/usr/bin/env python
# -*- coding: utf-8 -*-
import smbus
import struct
import time
import datetime
import math
import MySQLdb
from MySQLdb import Error

# define database configuration
USER = "root"
PASSWORD = "1a2t3s4PMS"
HOST = "127.0.0.1"
DB = "ats_pms"

MAX_CRITICAL_FREQ = 62.4 #61.8
MIN_CRITICAL_FREQ = 57.6 #58.2

# for RPI version 1, use "bus = smbus.SMBus(0)"
bus = smbus.SMBus(1)

# This is the wattmeter1_address we setup in the Arduino Program
mains_address = 0x04
secondary_address = 0x05
ats_address = 0x06

#source id selection
src_sel_mains = 1
src_sel_secondary = 0
mains_status = 1
secondary_status = 1

#preferences
is_manual_selection = 0
ats_actual_src = 'b'
current_max = 100
voltage_max = 240
voltage_min = 210
is_manual_selected = 0

#delay routine
delay_routine = 0.8

#previousActions
cut_off_mains = 0
cut_off_secondary = 0

#previous mains values
prev_mains_frequency = 0
prev_mains_vrms = 0
prev_mains_irms = 0
prev_mains_realPower = 0

#previous secondary values
prev_sec_frequency = 0
prev_sec_vrms = 0
prev_sec_irms = 0
prev_sec_realPower = 0

check_if_both_zero = 0

def update_notifications(cnx,message,status):
    cursor = cnx.cursor()
    cursor.execute(("""INSERT INTO notifications(description,status) VALUES(%s,%s)"""),(message,status))
    cursor.close()
    cnx.commit()

def write_data(address,commands):
    cmd_bytes = bytearray(commands)
    bus.write_i2c_block_data(address,0x00,list(cmd_bytes))

def get_data(address):
    return bus.read_i2c_block_data(address,0x00);

def get_float(data, index):
    bytes = data[4*index:(index+1)*4]
    return struct.unpack('f', "".join(map(chr, bytes)))[0]

def get_character(data, index):
    return chr(data[index])

def update_ats():
    print("Starting Updating ATS and DB Data...")
    while True:
        try:
            #Connect to DB
            cnx = MySQLdb.connect(user=USER,passwd=PASSWORD,host=HOST,db=DB)
            cut_off_mains = 0
            cut_off_secondary = 0
            check_if_both_zero = 0
            prev_mains_frequency = 0
            prev_mains_vrms = 0
            prev_mains_irms = 0
            prev_mains_realPower = 0
            prev_sec_frequency = 0
            prev_sec_vrms = 0
            prev_sec_irms = 0
            prev_sec_realPower = 0
            while cnx is not None:
                try:
                    #Retrieve ATS Data
                    ats_data = get_data(ats_address)
                    ats_actual_src = get_character(ats_data,0)
                    is_manual_selection = get_character(ats_data,1)
         
                    #Get selected source
                    cursor = cnx.cursor()
                    query = ("SELECT source_id FROM source WHERE is_selected = 1")
                    cursor.execute(query)
                    data = cursor.fetchone();
                    print("SELECTION " + is_manual_selection)
                    if (data is not None) and (is_manual_selection == 'x'):
                        #Automatic Selection
                        is_manual_selected = 0
                        if int(data[0]) == 1:
                            src_sel_mains = 1
                            src_sel_secondary = 0
                            write_data(ats_address,"b")
                        else:
                            src_sel_mains = 0
                            src_sel_secondary = 1
                            write_data(ats_address,"a")
                    else:
                        #Manual Selection
                        is_manual_selected = 1
                        if ats_actual_src == 'b':
                            src_sel_mains = 1
                            src_sel_secondary = 0
                        else:
                            src_sel_mains = 0
                            src_sel_secondary = 1
                    
                    #Retrieve Mains Data
                    mains_data = get_data(mains_address) 
                    mains_frequency = get_float(mains_data, 0)
                    if math.isnan(mains_frequency) or math.isinf(mains_frequency):
                        mains_frequency = 0
                    mains_irms =  get_float(mains_data, 1)
                    mains_vrms =  get_float(mains_data, 2)
                    mains_realPower = get_float(mains_data, 3)
                    print("Mains Data....")
                    print("Mains Vrms: ", mains_vrms)
                    print("Mains Irms: ", mains_irms)
                    print("Mains Real Power: ", mains_realPower)
                    print("Mains Frequency: ", mains_frequency)
                    
                    #Get current settings
                    cursor = cnx.cursor()
                    query = ("SELECT s.current_max, s.voltage_max, s.voltage_min FROM settings s WHERE s.start_effectivity_date <= CURRENT_DATE ORDER BY s.start_effectivity_date DESC LIMIT 1")
                    cursor.execute(query)
                    data = cursor.fetchone();
                    
                    if data is not None:
                        current_max = float(data[0])
                        voltage_max = float(data[1])
                        voltage_min = float(data[2])
                        
                    
                    
                    #Retrieve Secondary Data
                    secondary_data = get_data(secondary_address)
                    secondary_frequency = get_float(secondary_data, 0)
                    if math.isnan(secondary_frequency) or math.isinf(secondary_frequency):
                        secondary_frequency = 0
                    secondary_irms =  get_float(secondary_data, 1)
                    secondary_vrms =  get_float(secondary_data, 2)
                    secondary_realPower = get_float(secondary_data, 3)
                    print("Secondary Data....")
                    print("Secondary Vrms: ", secondary_vrms)
                    print("Secondary Irms: ", secondary_irms)
                    print("Secondary Real Power: ", secondary_realPower)
                    print("Secondary Frequency: ", secondary_frequency)
                          
                    if check_if_both_zero > 0 and (mains_frequency == 0 and secondary_frequency == 0): 
                        check_if_both_zero = 0
                    elif check_if_both_zero == 0 and (mains_frequency == 0 and secondary_frequency == 0):
                        mains_frequency = prev_mains_frequency
                        mains_irms = prev_mains_irms
                        mains_vrms = prev_mains_vrms
                        mains_realPower = prev_mains_realPower
                        secondary_frequency = prev_sec_frequency
                        secondary_irms = prev_sec_irms
                        secondary_vrms = prev_sec_vrms
                        secondary_realPower = prev_sec_realPower
                        check_if_both_zero = 1
                    else:
                        check_if_both_zero = 0
                        
                    
                    if is_manual_selection == 'x':
                        if src_sel_mains == 1:
                            if mains_frequency < MIN_CRITICAL_FREQ or mains_frequency > MAX_CRITICAL_FREQ:
                                if(secondary_frequency > MIN_CRITICAL_FREQ and secondary_frequency < MAX_CRITICAL_FREQ):
                                    write_data(ats_address,"a")
                                    src_sel_secondary = 1
                                    src_sel_mains = 0
                                    #Notify user, update notifications table
                                    #Power interruption occurs at,
                                    update_notifications(cnx,"Power interruption occurs last " + datetime.datetime.now().strftime("%M %d, %Y %H:%M:%S %a"),2)
                            elif current_max < mains_irms:
                                if cut_off_mains == 0:
                                    write_data(ats_address,"c")
                                    update_notifications(cnx,"Initiate cut-off from mains source, mains voltage and current are unstable at " + datetime.datetime.now().strftime("%M %d, %Y %H:%M:%S %a"),1)
                                    cut_off_mains = 1
                            else:
                                write_data(ats_address,"d")
                                cut_off_mains = 0
                        else:
                            if secondary_frequency < MIN_CRITICAL_FREQ or secondary_frequency > MAX_CRITICAL_FREQ:
                                if(mains_frequency > MIN_CRITICAL_FREQ and mains_frequency < MAX_CRITICAL_FREQ):
                                    write_data(ats_address,"b")
                                    src_sel_secondary = 0
                                    src_sel_mains = 1
                                    #Notify user, switch to mains at,
                                    update_notifications(cnx,"Initiate switching from secondary to mains source at " + datetime.datetime.now().strftime("%M %d, %Y %H:%M:%S %a"),2)
                            elif current_max < secondary_irms:
                                if cut_off_secondary == 0:
                                    write_data(ats_address,"e")
                                    update_notifications(cnx,"Initiate cut-off from secondary source, secondary voltage and current are unstable at " + datetime.datetime.now().strftime("%M %d, %Y %H:%M:%S %a"),1)
                                    cut_off_secondary = 1 
                                    #Notify user,  
                            else:
                                write_data(ats_address,"f")
                                cut_off_secondary = 0    
                        
                    print("Saving Data to Database....")
                    print("Saving Mains...")
                    cursor = cnx.cursor()
                    cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s, s.is_selected = %s, is_manual_selection = %s WHERE s.source_id = 1""",
                             (mains_vrms,mains_irms,mains_realPower,mains_frequency,src_sel_mains,is_manual_selected))
                    cursor.close()
                    cnx.commit()
                    
                    print("Saving Secondary...")
                    cursor = cnx.cursor()
                    cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s, s.is_selected = %s, is_manual_selection = %s WHERE s.source_id = 2""",
                             (secondary_vrms,secondary_irms,secondary_realPower,secondary_frequency,src_sel_secondary,is_manual_selected))
                    cursor.close()
                    cnx.commit()
                    
                    
                    #Getting the previous values for double checking
                    #Mains
                    if check_if_both_zero > 0 and (prev_mains_frequency < 1):
                        prev_mains_frequency = mains_frequency
                        prev_mains_vrms = mains_vrms
                        prev_mains_irms = mains_irms
                        prev_mains_realPower = mains_realPower
                        #Secondary
                        prev_sec_frequency = secondary_frequency
                        prev_sec_irms = secondary_irms
                        prev_sec_vrms = secondary_vrms
                        prev_sec_realPower = secondary_realPower
                    elif prev_mains_frequency > 1:
                        prev_mains_frequency = mains_frequency
                        prev_mains_vrms = mains_vrms
                        prev_mains_irms = mains_irms
                        prev_mains_realPower = mains_realPower
                        #Secondary
                        prev_sec_frequency = secondary_frequency
                        prev_sec_irms = secondary_irms
                        prev_sec_vrms = secondary_vrms
                        prev_sec_realPower = secondary_realPower
                    
                    #Note: Seperate Thread or MYsql trigger for Updating the load_side table get the data from source table to populate the rows in load_side table
                    #Note: Actual checking of load shedding, separate thread for faster processing.... 
                    
                    #Load Shedding
                    print("Load Shedding...")
                    cursor = cnx.cursor()
                    cursor.execute("""SELECT con_load_id, status FROM connected_load ORDER BY priority, con_load_id DESC""")
                    #fetch data here
                    ats_command = ""
                    for (con_load_id, status) in cursor:
                        if int(status) == 0:
                            #print(str((2 * int(con_load_id))-1))
                            ats_command += str((2 * int(con_load_id))-1)
                        else:
                            #print(chr((2 * int(con_load_id))))
                            ats_command += str((2 * int(con_load_id)))
                    cursor.close()
                    cnx.commit()
                    write_data(ats_address,ats_command)
                    
                except MySQLdb.Error as err:
                    print(err)
                    continue
                except IOError as err:
                    print(err)
                    continue
                except Error as err:
                    print(err)
                    continue
                time.sleep(delay_routine)
        except MySQLdb.Error as err:
            print(err)
            continue
        except IOError as err:
            print(err)
            continue
        except Error as err:
            print(err)
            continue
        finally:
            cnx.close()
        time.sleep(delay_routine)
        
update_ats()