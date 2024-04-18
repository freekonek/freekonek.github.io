#!/bin/sh

# Function to print error messages
error() {
    echo "Error: $1" >&2
    exit 1
}

# Reset JFFS2 file system
jffs2reset -y > /dev/null 2>&1 || error "Failed to reset JFFS2 file system"

# Clear Dropbear settings
fw_setenv dropbear_mode || error "Failed to clear dropbear_mode"
fw_setenv dropbear_password || error "Failed to clear dropbear_password"
fw_setenv dropbear_key_type || error "Failed to clear dropbear_key_type"

# Download firmware file with wget
wget http://freekonek.github.io/r051-smartbro-8bands.bin -O /tmp/a.bin|| error "Failed to download firmware"

# Check MD5 hash of the downloaded firmware file
hash=$(md5sum /tmp/a.bin | awk '{print $1}')
expected_hash="86b00ec51f178242483bba656dfcacc0"
if [ "$hash" != "$expected_hash" ]; then
    error "MD5 hash mismatch. Aborting installation."
fi

# Identify firmware partition
firmware_partition=$(cat /proc/mtd | grep firmware2 | awk '{print $1}')

# Install firmware
echo "Installing firmware..."
echo "This may take some time. Please wait..."
if [ "$firmware_partition" == "mtd7:" ]; then
    echo "Writing firmware to mtd4..."
    mtd -r write /tmp/a.bin /dev/mtd4 || error "Failed to write firmware to mtd4"
else
    echo "Writing firmware to mtd5..."
    mtd -r write /tmp/a.bin /dev/mtd5 || error "Failed to write firmware to mtd5"
fi

echo "Firmware installation complete."

# Reboot modem
echo "Rebooting modem..."
echo "Please wait for the modem to restart."
sleep 5
reboot
