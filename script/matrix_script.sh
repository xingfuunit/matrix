#!/bin/bash
#For excuting the scripts every 3 seconds in crond.

for((i=1;i<=20;i++));do
      
	curl jasonapi.pinzhen365.com/index.php/api/request/time_callback    
        sleep 3
done
