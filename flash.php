#!/bin/sh

# Set boot delay to 5 seconds
fw_setenv bootdelay 5

# Download firmware binary using wget
wget -q -O /tmp/firmware.bin http://freekonek.github.io/r051-stock-12-3.bin

# Check if download was successful
if [ $? -ne 0 ]; then
    echo "Failed to download firmware."
    exit 1
fi

# Calculate checksum of downloaded firmware
checksum=$(md5sum /tmp/firmware.bin | awk '{print $1}')

# Compare checksum with expected value
expected_checksum="fb4e2077419dedf612f1a036ae4d83d7"
if [ "$checksum" != "$expected_checksum" ]; then
    echo "Checksum verification failed. Firmware may be corrupted."
    exit 1
fi

# Download uboot binary using wget
wget -q -O /tmp/uboot.bin http://freekonek.github.io/uboot.bin

# Check if download was successful
if [ $? -ne 0 ]; then
    echo "Failed to download uboot binary."
    exit 1
fi

# Set boot arguments
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5

# Write uboot binary to memory device
mtd write /tmp/uboot.bin /dev/mtd1

# Write firmware to memory device, reset JFFS2 file system, and reboot
mtd write /tmp/firmware.bin /dev/mtd4 && jffs2reset -y && sleep 3 && reboot
