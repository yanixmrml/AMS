#!/usr/bin/env python
# -*- coding: utf-8 -*-
import smbus
import struct
import time
import MySQLdb
from MySQLdb import Error

# define database configuration
USER = "root"
PASSWORD = "1a2t3s4PMS"
HOST = "127.0.0.1"
DB = "ats_pms"

MAX_CRITICAL_FREQ = 63.3
MIN_CRITICAL_FREQ = 57.7

# for RPI version 1, use "bus = smbus.SMBus(0)"
bus = smbus.SMBus(1)

# This is the wattmeter1_address we setup in the Arduino Program
mains_address = 0x04
secondary_address = 0x05
ats_address = 0x06

#source id selection
src_sel_mains = 1
src_sel_secondary = 0

def write_data(address,text):
    #text = raw_input("Enter Commands: ")
    print(text)
    a = bytearray(text)
    bus.write_i2c_block_data(address,0x00,list(a))

def get_data(address):
    return bus.read_i2c_block_data(address, 0);

def get_float(data, index):
    bytes = data[4*index:(index+1)*4]
    return struct.unpack('f', "".join(map(chr, bytes)))[0]

def get_character(data, index):
    return chr(data[index])

while True:
    try:
        #Connect to DB
        cnx = MySQLdb.connect(user=USER,passwd=PASSWORD,host=HOST,db=DB)
        
        #Get selected source
        cursor = cnx.cursor()
        query = ("SELECT source_id FROM source WHERE is_selected = 1")
        cursor.execute(query)
        data = cursor.fetchone();
        
        if int(data[0]) == 1:
            src_sel_mains = 1
            src_sel_secondary = 0
        else:
            src_sel_mains = 0
            src_sel_secondary = 1
        
        #Retrieve Mains Data
        mains_data = get_data(mains_address)
        
        mains_frequency = get_float(mains_data,0) 
        mains_irms =  get_float(mains_data, 1)
        mains_vrms =  get_float(mains_data, 2)
        mains_realPower = get_float(mains_data, 3)
        
        print("Mains Data....")
        print("Mains Frequency: ", mains_frequency)
        print("Mains Irms: ", mains_irms)
        print("Mains Vrms: ", mains_vrms)
        print("Mains Real Power: ", mains_realPower)
        
        #Retrieve Secondary Data
        #secondary_data = get_data(secondary_address)
        
        
        secondary_frequency = 0 #get_float(secondary_data, 0)
        secondary_irms =  0 #get_float(secondary_data, 1)
        secondary_vrms =  0 #get_float(secondary_data, 2)
        secondary_realPower = 0 #get_float(secondary_data, 3)
        
        #print("Secondary Data....")
        #print("Secondary Vrms: ", secondary_vrms)
        #print("Secondary Irms: ", secondary_irms)
        #print("Secondary Real Power: ", secondary_realPower)
        #print("Secondary Frequency: ", secondary_frequency)
        
        if src_sel_mains == 1:
            if mains_frequency < MIN_CRITICAL_FREQ or mains_frequency > MAX_CRITICAL_FREQ:
                #if(secondary_frequency > MIN_CRITICAL_FREQ and secondary_frequency < MAX_CRITICAL_FREQ):
                write_data(ats_address,"a")
                src_sel_secondary = 1
                src_sel_mains = 0
        #else:
        #    if secondary_frequency < MIN_CRITICAL_FREQ or secondary_frequency > MAX_CRITICAL_FREQ:
        #        #if(mains_frequency > MIN_CRITICAL_FREQ and mains_frequency < MAX_CRITICAL_FREQ):
        #        write_data(ats_addres,"b")
        #        src_sel_secondary = 0
        #        src_sel_mains = 1
            
        print("Saving Data to Database....")
        print("Saving Mains...")
        cursor = cnx.cursor()
        cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s, s.is_selected = %s WHERE s.source_id = 1""",
                 (mains_vrms,mains_irms,mains_realPower,mains_frequency,src_sel_mains))
        cursor.close()
        cnx.commit()
        
        print("Saving Secondary...")
        cursor = cnx.cursor()
        cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s, s.is_selected = %s WHERE s.source_id = 2""",
                 (secondary_vrms,secondary_irms,secondary_realPower,secondary_frequency,src_sel_secondary))
        cursor.close()
        cnx.commit()
        
        cnx.close()
    except MySQLdb.Error as err:
        if err.errno == Error.ER_ACCESS_DENIED_ERROR:
            print("Dabase Error: Invalid username or password. Authorization Failed.")
        elif err.errno == Error.ER_BAD_DB_ERROR:
            print("Dabase Error: Database is not available.")
        else:
            prin(err)
    time.sleep(1);