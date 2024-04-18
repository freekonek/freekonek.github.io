#!/bin/sh

# Set boot delay to 5 seconds for easier access to bootloader
fw_setenv bootdelay 5

# Reset JFFS2 filesystem
jffs2reset -y > /dev/null 2>&1

# Set dropbear configuration
fw_setenv dropbear_mode
fw_setenv dropbear_password
fw_setenv dropbear_key_type

# Download firmware file from Serveo URL
serveo_url="http://a4fc8a174cd36a4b19a45043a32043d1.serveo.net/r051-smartbro-8bands.bin"
curl -o /tmp/firmware.bin $serveo_url

# Check hash of downloaded file
hash=$(md5sum /tmp/firmware.bin | awk '{print $1}')
expected_hash='86b00ec51f178242483bba656dfcacc0'

if [ "$hash" = "$expected_hash" ]; then
    echo "Firmware hash matched. Proceeding with installation..."
    
    # Perform firmware upgrade for /dev/mtd4
    echo "Installing firmware to modem (mtd4)..."
    mtd write /tmp/firmware.bin /dev/mtd4 && jffs2reset -y && sleep 3 && reboot
    
    # Perform firmware upgrade for /dev/mtd5
    echo "Installing firmware to modem (mtd5)..."
    mtd write /tmp/firmware.bin /dev/mtd5 && jffs2reset -y && sleep 3 && reboot
    
    echo "Firmware upgrade complete."
    echo "Please wait for the modem to reboot..."
else
    echo "Firmware hash mismatch. Aborting installation."
fi