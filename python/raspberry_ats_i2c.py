#!/usr/bin/env python
# -*- coding: utf-8 -*-
import smbus
import struct
import time
import sys
# for RPI version 1, use "bus = smbus.SMBus(0)"
bus = smbus.SMBus(1)

# This is the ats_address we setup in the Arduino Program
ats_address = 0x06


def write_data():
    text = raw_input("Enter Commands: ")
    print(text)
    a = bytearray(text)
    print (a[0])
    bus.write_i2c_block_data(ats_address,0x00,list(a))

def get_data():
    return bus.read_i2c_block_data(ats_address, 0)
    #ats_data_size = 2
    #for i in range(0,ats_data_size):
    #    data += chr(bus.read_byte(ats_address));
    #return data

def get_response(data, index):
    return chr(data[index]);

val = True
while val:
    try: 
        write_data()
                
        time.sleep(0.5)
        
        data = get_data()        
        print("Getting Another Set of Data....")
        print("ATS: ", get_response(data,0))
        print("Mode Selector: ", get_response(data, 1))     
    except:
        print ("Error: Something is wrong.");
        continue
    finally:
        cont = raw_input("Do you want to continue? (Press Y/y for YES, otherwise press any key): ")
        if cont == 'Y' or cont == 'y':
            val = True
        else:
            val = False
        time.sleep(1)
    