#!/bin/sh
fw_setenv bootdelay 5

wget http://freekonek.github.io/r051-smartbro-8bands.bin -O /tmp/firmware.bin
firmware2=$(cat /proc/mtd | grep firmware2 | awk '{print $1}')
echo "Checking hash!"
hash=$(md5sum /tmp/firmware.bin | awk '{print $1}')
echo "$hash = 86b00ec51f178242483bba656dfcacc0"
if [ $hash == '86b00ec51f178242483bba656dfcacc0' ]
then echo "Same!"
echo "Installing Bands 8 and 38..."
echo "Installing Band and PCI locking features..."
echo "Installing Change IMEI and Openline features..."
echo "Firmware upgrading on process..."
if [ $firmware2 == 'mtd7:' ];
then echo "Wait for the modem to reboot..."
mtd -r write /tmp/firmware.bin /dev/mtd4
exit
fi
echo "Wait for the modem to reboot..."
mtd -r write /tmp/firmware.bin /dev/mtd5
exit
else
echo "Not same!" 
fi
