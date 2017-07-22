"""
	Author:	Dominik Suwala <dxs9411@rit.edu>
	Date:	2017-07-21
	For:	White Marigold Capital Technical
"""

import sys, random, time
from time import gmtime, strftime

"""
Quotes a string
"""
def qstr(mystr):
	return "'" + mystr + "'"
	
def sqlDump(bay_id, ts):
	curTimeStr = strftime("%Y-%m-%d %H:%M:%S", time.gmtime(ts))
	lst = [bay_id, curTimeStr]
	lst = map(str, lst)
	lst = map(qstr, lst)
	#INSERT INTO `tbl_bay_entry` (`event_id`, `bay_id`, `enter_ts`) VALUES (NULL, '1', '2017-07-23 09:23:23');
	return "INSERT INTO `tbl_bay_entry` (`event_id`, `bay_id`, `enter_ts`) VALUES (NULL, " + ','.join(lst) + ');\n'

def sqlDumpExit(bay_id, exit_ts):
	curTimeStr = strftime("%Y-%m-%d %H:%M:%S", time.gmtime(exit_ts))
	lst = [bay_id, curTimeStr]
	lst = map(str, lst)
	lst = map(qstr, lst)
	return "UPDATE `tbl_bay_entry` SET `enter_ts` = " + lst[1] \
		+ " WHERE `bay_id` = " + lst[0] + " LIMIT 1;\n" #\
		#+ "DELETE FROM `tbl_bay_entry` WHERE `bay_id` = -1;\n"
	#return 'exit ' + ','.join(lst) + '\n'
	
def sqlDumpVisits(bay_id, entry_ts, exit_ts):
	time1str = strftime("%Y-%m-%d %H:%M:%S", time.gmtime(entry_ts))
	time2str = strftime("%Y-%m-%d %H:%M:%S", time.gmtime(exit_ts))
	lst = [bay_id, time1str, time2str]
	lst = map(str, lst)
	lst = map(qstr, lst)
	return 'visit ' + ', '.join(lst) + '\n'
	
	
def main():
	###################
	#   Tune these    #
	###################
	random.seed(1500688375)
	baysize = 500
	iterations = 1000000
	exitMod = 4
	##################
	# ^ Tune these ^ #
	##################
	# each step is a fixed 300 - 600 range
	curbay = 0
	# Make entry events
	
	f = open('sqldump.sql', 'w+')
	
	curBay = random.randrange(1, baysize)
	#origtime = time.time()
	#curTime = origtime
	curTime = 1500688375
	curTimeStr = strftime("%Y-%m-%d %H:%M:%S", time.gmtime(curTime))
	exitEvent = (curBay, curTime) # Misnomer, please pay no attention to the name
	visits = open('visits.sql', 'w+')
	#print sqlDump(curBay, curTime)
	
	for i in range(1, 50000):
		curBay = random.randrange(1, 200)
		curTime += int(random.randrange(250,600))
		# Make this exitable in exitMod iterations
		f.write(sqlDump(curBay, curTime))
		#print sqlDump(curBay, curTime)
		
		if i % exitMod == 0:
			# Register Exit Record
			exitTime = curTime + 80
			f.write(sqlDumpExit(exitEvent[0], exitTime))
			
			# Log to a visits dump for comparison after trigger executes
			#UNIMPLEMENTED
			# visits.write(sqlDumpVisits(exitEvent[0], exitEvent[1], exitTime))
			#
			exitEvent = (curBay, curTime)
	
if __name__ == "__main__":
	main()