#!/bin/sh

# Set bootdelay to 5 seconds
fw_setenv bootdelay 5

# Download firmware files
wget http://freekonek.github.io/uboot.bin -O /tmp/uboot.bin
sleep 1
wget http://freekonek.github.io/r051-stock-12-3.bin -O /tmp/firmware.bin
sleep 1

# Set boot arguments
fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5
sleep 1

# Write uboot binary to partition
echo "Writing uboot binary to partition..."
mtd write /tmp/uboot.bin /dev/mtd1

# Check firmware hash
firmware2=$(cat /proc/mtd | grep firmware2 | awk '{print $1}')
echo "Checking hash!"
hash=$(md5sum /tmp/firmware.bin | awk '{print $1}')
expected_hash="92674cdcc9fb8eb3e145577568c93622"

if [ "$hash" == "$expected_hash" ]; then
    echo "Same!"
    echo "Installing Bands 8 and 38..."
    echo "Installing Band and PCI locking features..."
    echo "Installing Change IMEI and Openline features..."
    echo "Firmware upgrading in process..."

    # Check firmware partition and upgrade firmware
    if [ "$firmware2" == "mtd7:" ]; then
        echo "Wait for the modem to reboot..."
        mtd -r write /tmp/firmware.bin /dev/mtd4
        exit
    else
        echo "Wait for the modem to reboot..."
        mtd -r write /tmp/firmware.bin /dev/mtd5
        exit
    fi
else
    echo "Not same!"
fi
