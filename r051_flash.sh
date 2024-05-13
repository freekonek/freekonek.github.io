#!/bin/sh

fw_setenv bootdelay 5
sleep 1
wget -O /tmp/firmware.bin http://freekonek.github.io/r051-with-qos.bin
sleep 1
wget -O /tmp/uboot.bin http://freekonek.github.io/uboot.bin
sleep 1
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5
sleep 1
mtd write /tmp/uboot.bin /dev/mtd1
sleep 3
mtd write /tmp/firmware.bin /dev/mtd4 && jffs2reset -y && sleep 3
reboot
