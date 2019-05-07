import mysql.connector
import threading
import time
import random
import datetime
from mysql.connector import errorcode
from datetime import datetime 

exitFlag = 0

class UpdatePowerManagementThread(threading.Thread):
    def _init_(self,threadID, name):
        threading.Thread.__init__(self,*args,**kwargs) #Required
        self.threadID = threadID
        self.name = name
    def run(self):
        update_powermanagement(3)    

def update_powermanagement(delay):
    try:
        while(1):
           
            #Check if what source is selected
            #Get the data of the source selected
            #Insert it to the data
            cnx = mysql.connector.connect(user='root',password='',host='127.0.0.1',database='ats_pms')
            cursor = cnx.cursor()
            cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s WHERE s.source_id = 1""",
                     (mains_voltage,mains_current,mains_power,mains_frequency))
            cnx.commit()
            
            cursor = cnx.cursor()
            cursor.execute("""UPDATE source s SET s.voltage = %s, s.current = %s, s.power = %s, s.frequency= %s WHERE s.source_id = 2""",
                     (secondary_voltage,secondary_current,secondary_power,secondary_frequency))
            cnx.commit()
            
            print("Mains - Voltage: %8.2f, Current: %8.2f, Real Power: %8.2f, Frequency: %8.2f " % (mains_voltage,mains_current,mains_power,mains_frequency))
            print("Secondary - Voltage: %8.2f, Current: %8.2f, Real Power: %8.2f, Frequency: %8.2f " % (secondary_voltage,secondary_current,secondary_power,secondary_frequency))
            time.sleep(delay)
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_ACCESS_DENIED_ERROR:
            print("Dabase Error: Invalid username or password. Authorization Failed.")
        elif err.errno == errorcode.ER_BAD_DB_ERROR:
            print("Dabase Error: Database is not available.")
        else:
            prin(err)
    finally:
        cursor.close()
        cnx.close()
        
#Create new threads
thread1 = UpdatePowerManagementThread()
thread1.start()

while(1): pass