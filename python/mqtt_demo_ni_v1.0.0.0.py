import paho.mqtt.client as mqtt
#import paho.mqtt.publish as publish

topic_1 = "room_0"
topic_2 = "room_01/return"

# The callback for when the client receive a CONNACK from the server.
def on_connect(client, userdata, flag, rc):
	print("Connect with result code " + str(rc))
	client.subscribe(topic_1)

# The callback for when a PUBLISH message is receive from the server.
def on_message(client, userdata, msg):
	print(str(msg.payload))
	id_num = int(msg.payload)
	
	if id_num == 201134204:
		print ("Si Kent Lloyd na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201123223:
		print ("Si Donnell na!")
		if(client.publish(topic_2, "Not class.")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201105817:
		print ("Si Rey Kevin na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201108408:
		print ("Si Jahid na!")
		if(client.publish(topic_2, "Not class.")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 200742898:
		print ("Si Mark Ryan na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201243739:
		print ("Si Eryl Kean na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201270048:
		print ("Si John Ryan na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	elif id_num == 201245887:
		print ("Si Sittie Ainah na!")
		if(client.publish(topic_2, "Welcome!!")):
			print("Send")
		else:
			print("Not send")
	else:
		print ("Invalid ID!")
		if(client.publish(topic_2, "Invalid ID!")):
			print("Send")
		else:
			print("Not send")

# Create an MQTT Client and atach our routines to it.
client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message

client.username_pw_set("AMS_SERVER", "thesis")
client.connect("192.168.8.102", 1883, 60)

client.loop_forever()
