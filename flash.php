#!/bin/sh

# Set boot delay
fw_setenv bootdelay 5 || { echo "Failed to set bootdelay"; exit 1; }

# Download firmware and calculate checksum
wget https://raw.github.com/freekonek/freekonek.io/main/a.bin -O /tmp/firmware.bin || { echo "Failed to download firmware"; exit 1; }
firmware_checksum=$(md5sum /tmp/firmware.bin | awk '{print $1}')

# Verify firmware checksum
expected_checksum="fb4e2077419dedf612f1a036ae4d83d7" # Replace this placeholder with the expected checksum
if [ "$firmware_checksum" != "$expected_checksum" ]; then
    echo "Firmware checksum verification failed"
    rm /tmp/firmware.bin
    exit 1
fi

sleep 1

# Download bootloader and calculate checksum
wget https://raw.github.com/freekonek/freekonek.io/main/uboot.bin -O /tmp/uboot.bin || { echo "Failed to download bootloader"; exit 1; }
bootloader_checksum=$(md5sum /tmp/uboot.bin | awk '{print $1}')

# Verify bootloader checksum
expected_checksum="d1e62ee1b49e7c7567a967db819ac531" # Replace this placeholder with the expected checksum
if [ "$bootloader_checksum" != "$expected_checksum" ]; then
    echo "Bootloader checksum verification failed"
    rm /tmp/uboot.bin
    exit 1
fi

sleep 1

# Set boot arguments
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5 || { echo "Failed to set boot arguments"; exit 1; }
sleep 1

# Write bootloader to memory device
mtd write /tmp/uboot.bin /dev/mtd1 || { echo "Failed to write bootloader"; exit 1; }

# Write firmware to memory device and reset filesystem
mtd write /tmp/firmware.bin /dev/mtd4 || { echo "Failed to write firmware"; exit 1; }
jffs2reset -y || { echo "Failed to reset filesystem"; exit 1; }
sleep 3

# Reboot device
reboot || { echo "Failed to reboot device"; exit 1; }
