import struct
import time
import array
from datetime import datetime
import MySQLdb
from MySQLdb import Error

# define database configuration
USER = "root"
PASSWORD = "1a2t3s4PMS"
HOST = "127.0.0.1"
DB = "ats_pms"

#days = [""]
days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]

def update_load_scheduling():
    #Connect to DB
    cnx = MySQLdb.connect(user=USER,passwd=PASSWORD,host=HOST,db=DB)
    while True:
        try:
            
            #Retrieve connected loads schedules...
            cursor = cnx.cursor()
            query = ("""SELECT con_load_id, schedule_day, schedule_on, schedule_off, status, NOW() as today FROM connected_load""")
            cursor.execute(query)
            con_loads = []
            for (con_load_id, schedule_on, schedule_off, status, today) in cursor:
                if schedule_day is not None and schedule_day.find(days[today.weekday()]):
                    if schedule_on is not None and schedule_off is not None:
                        if schedule_on < schedule_off:
                            if(schedule_off < today):
                                con_loads.append([con_load_id, 0])
                            else:
                                con_loads.append([con_load_id, 1])
                        else:
                            if(schedule_on < today):
                                con_loads.append([con_load_id, 1])
                            else:
                                con_loads.append([con_load_id, 0])
                    else:
                        if(schedule_off is not None and schedule_off < today):
                            con_loads.append([con_load_id, 0])
                        elif(schedule_on is not None and schedule_on < today):
                            con_loads.append([con_load_id, 1])
            cursor.close()
            cnx.commit()
            
            for i in range(0,len(con_loads)):
                cursor = cnx.cursor()
                cursor.execute("""UPDATE connected_load SET status = %s WHERE con_load_id = %s""",(con_loads[i][1],con_loads[i][0]))
                cursor.close()
                cnx.commit()
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

update_load_scheduling()
