import paho.mqtt.client as mqtt
import struct
import time
from datetime import datetime
import MySQLdb
from MySQLdb import Error

#import paho.mqtt.publish as publish

topic_1 = "room_0"
topic_2 = "room_01/return"

# define database configuration
USER = "root"
PASSWORD = "1a2m3s4"
HOST = "127.0.0.1"
DB = "ams_server"

#days
days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]

#Connect to DB
cnx = MySQLdb.connect(user=USER,passwd=PASSWORD,host=HOST,db=DB)


# The callback for when the client receive a CONNACK from the server.
def on_connect(client, userdata, flag, rc):
	print("Connect with result code " + str(rc))
	client.subscribe(topic_1)

# The callback for when a PUBLISH message is receive from the server.
def on_message(client, userdata, msg):
	print(str(msg.payload))
	id_num = int(msg.payload)
	#Check if faculty_id
	current_datetime = datetime.today();
	current_time = current_datetime.time()
	
	if(id_num<100000):
		print("Check Faculty ID");
		cursor = cnx.cursor()
		cursor.execute("""SELECT f.faculty_id FROM faculty f WHERE f.university_id = %s""",(id_num))
		data = cursor.fetchone()
		
		if(data is not None):
			faculty_id = data[0]
			cursor = cnx.cursor()
			cursor.execute("""SELECT a.faculty_id FROM attendance a WHERE a.faculty_id = %s AND a.date_attended BETWEEN  %s AND %s""",(faculty_id,time_start,time_end))
			data = cursor.fetchone()
			cursor.close()
			
			if(data is not None):
				client.publish(topic_2, "Has logged in.")	
			else:					
				cursor = cnx.cursor()
				cursor.execute("""SELECT s.schedule_day FROM student_courses s WHERE s.faculty_id = %s AND %s BETWEEN s.time_start AND s.time_end""",(id_num,current_time))
				data = cursor.fetchone()
				if(data is not None):
					schedule_day = data[0]
					if(schedule_day.find(days[current_datetime.weekday()])>=0):
						#Insert this faculty in attendance log
						cursor = cnx.cursor()
						client.publish(topic_2, "Welcome!")
						cursor.execute(("""INSERT INTO attendance(faculty_id,date_attended,status) VALUES(%s,NOW(),%s)"""),(faculty_id,1))
						cursor.close()
					else:
						client.publish(topic_2, "Not your sched.")
				else:
					client.publish(topic_2, "Not your sched.")	
		else:
			client.publish(topic_2, "ID not found")
		cursor.close()
	else:	
		print("Check Student ID");
		cursor = cnx.cursor()
		cursor.execute("""SELECT s.student_id FROM student s WHERE s.university_id = %s""",(id_num))
		data = cursor.fetchone()
		cursor.close()
		
		if(data is not None):
			student_id = data[0]
			cursor = cnx.cursor()
			print (current_time)
			cursor.execute("""SELECT s.student_course_id, s.faculty_id, s.schedule_day, s.time_start, s.time_end FROM student_courses s WHERE s.student_id = %s AND %s BETWEEN s.time_start AND s.time_end""",(student_id,current_time.strftime("%H:%M")))
			data = cursor.fetchone()
			cursor.close();
			if(data is not None):
				student_course_id = data[0]
				faculty_id = data[1]
				schedule_day = data[2]
				t_start = data[3]
				t_end = data[4]
				
				time_start = (datetime.min + t_start).time()
				time_end = (datetime.min + t_end).time()
				
				print(days[current_datetime.weekday()])
				print(time_start)
				print(time_end)
				
				
				if(schedule_day.find(days[current_datetime.weekday()])>=0):
					#Check first if the student has logged in the attendance
					cursor = cnx.cursor()
					cursor.execute("""SELECT a.student_course_id FROM attendance a WHERE a.student_course_id = %s AND TIME(a.date_attended) BETWEEN  %s AND %s""",(student_course_id,time_start,time_end))
					data = cursor.fetchone()
					cursor.close()
					
					if(data is not None):
						client.publish(topic_2, "Has logged in.")	
					else:					
						#Insert this student in attendance log
						cursor = cnx.cursor()
						cursor.execute("""SELECT a.faculty_id FROM attendance a WHERE a.faculty_id = %s AND a.date_attended BETWEEN  %s AND %s""",(faculty_id,time_start,time_end))
						data = cursor.fetchone()
						cursor.close()
						
						client.publish(topic_2, "Welcome!")
						if(data is not None):		
							cursor = cnx.cursor()
							cursor.execute(("""INSERT INTO attendance(student_course_id,date_attended,status) VALUES(%s,NOW(),%s)"""),(student_course_id,1))
							cursor.close()
						else:
							cursor = cnx.cursor()
							cursor.execute(("""INSERT INTO attendance(student_course_id,date_attended,status) VALUES(%s,NOW(),%s)"""),(student_course_id,0))
							cursor.close()
				else:
					client.publish(topic_2, "Not your sched.")
			else:
				client.publish(topic_2, "Not your sched.")
		else:
			client.publish(topic_2, "ID not found.")
		
	

# Create an MQTT Client and atach our routines to it.
client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message

client.username_pw_set("AMS_SERVER", "thesis")
client.connect("192.168.8.102", 1883, 60)
client.loop_forever()
