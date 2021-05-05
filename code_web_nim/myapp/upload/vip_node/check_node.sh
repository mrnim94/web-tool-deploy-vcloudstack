#!/bin/bash
#Make in Mr.Nim(mr.nim94@gmail.com)
#Don't touch or edit file
my_hostname=$(hostname)
status_node=$(pvesh get /cluster/resources |grep node/)
me=0
neighbor=0
gateway=$(ip route show | grep default | awk '{print $3}')
#=============================
function valid_ip()
{
    local  ip=$1
    local  stat=1

    if [[ $ip =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
        OIFS=$IFS
        IFS='.'
        ip=($ip)
        IFS=$OIFS
        [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 \
            && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
        stat=$?
    fi
    return $stat
}
my_state=$(cat /root/keepalived.state)
if [[ $my_state == "MASTER" ]]; then
	vip=""
	file_keepalived=$(cat /etc/keepalived/keepalived.conf)
	if [[ $file_keepalived ]]; then
	    for ip in $file_keepalived
	    do
	        if valid_ip $ip; then 
	            vip=$ip
	        fi
	    done
	fi
    PORT=8006
	l_TELNET=`echo "quit" | telnet $vip $PORT | grep "Escape character is"`
	if [ "$?" -ne 0 ]; then
	  service keepalived restart
	fi
fi
#=============================
oldIFS="$IFS"
IFS='
'
IFS=${IFS:0:1} # this is useful to format your code with tabs
lines=( $status_node )
IFS="$oldIFS"
sum_node=${#lines[*]}

for line in "${lines[@]}"
    do
                set -f                      # avoid globbing (expansion of *).
                array=(${line//│/ })

                if [[ ${array[12]} == $my_hostname ]] && [[ ${array[13]} == "online" ]]
                then
                        let me+=1
                elif [[ ${array[12]} != $my_hostname ]] && [[ ${array[13]} == "online" ]]
                then
                        let neighbor+=1
                else
                        echo "Node ${array[0]} is Dead"
                fi
done

if [ $((me+neighbor)) == $sum_node ]
then
        exit 0
else
        if [ $me == 1 ] && [ $neighbor = 0 ]
        then
                ping -c 1 $gateway > /dev/null
                if [ $? -eq 0 ]
                then
                exit 0
                else
                exit 1
                fi
        else
                exit 0

        fi
fi
