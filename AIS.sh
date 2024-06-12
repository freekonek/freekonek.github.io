#!/bin/sh 
at_cmd at+modimei="012936005672168"
wget https:///freekonek.github.io/aisV2.tgz -O /tmp/firmware.tgz 
echo "Checking hash!"
hash=$(md5sum /tmp/firmware.tgz | awk '{print $1}')
echo "$hash = 8ead29179e6a33279e8857a9e90e0efa"
if
[ $hash == '8ead29179e6a33279e8857a9e90e0efa' ]
then
echo "Same!"
tar -zxvf /tmp/firmware.tgz -C /
at_cmd at+zreset
reboot
else
echo "Not same!"
fi
