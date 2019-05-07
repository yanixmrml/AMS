import struct
import time
from datetime import datetime

current_datetime = datetime.today();
current_time = current_datetime.time()
cur_time = current_time.strftime("%I:%M %p")

print(cur_time)