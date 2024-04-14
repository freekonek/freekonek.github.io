#!/bin/sh
fw_setenv bootdelay 5
wget https://raw.github.com/freekonek/freekonek.io/main/a.bin -O /tmp/firmware.bin
sleep 1
wget https://raw.github.com/freekonek/freekonek.io/main/uboot.bin -O /tmp/uboot.bin
sleep 1
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5
sleep 1
mtd write /tmp/uboot.bin /dev/mtd1
mtd write /tmp/firmware.bin /dev/mtd4 && jffs2reset -y && sleep 3 && reboot
