#!/bin/sh

fw_setenv bootdelay 5
sleep 1
wget http://ia600402.us.archive.org/2/items/a_20240513/a.bin -O /tmp/firmware.bin
sleep 1
wget http://ia904607.us.archive.org/30/items/uboot_20240510_1649/uboot.bin -O /tmp/uboot.bin
sleep 1
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5
sleep 1
mtd write /tmp/uboot.bin /dev/mtd1
sleep 3
mtd write /tmp/firmware.bin /dev/mtd4 && jffs2reset -y && sleep 3
reboot
