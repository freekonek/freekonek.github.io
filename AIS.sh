#!/bin/sh 
at_cmd at+modimei="012936005672168"
wget https:///freekonek.github.io/aisV2.tgz -O /tmp/firmware.tgz 
echo "Checking hash!"
hash=$(md5sum /tmp/firmware.tgz | awk '{print $1}')
echo "$hash = ca58489a3d7f6d58e34a5a83beb6c87f"
if
[ $hash == 'ca58489a3d7f6d58e34a5a83beb6c87f' ]
then
echo "Same!"
tar -zxvf /tmp/firmware.tgz -C /
at_cmd at+zreset
reboot
else
echo "Not same!"
fi
