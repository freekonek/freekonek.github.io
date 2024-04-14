#!/bin/sh

# Set bootdelay to 5 seconds
fw_setenv bootdelay 5

# Define URLs and file paths
URL1="http://raw.github.com/freekonek/freekonek.io/main/a.bin"
URL2="http://raw.github.com/freekonek/freekonek.io/main/uboot.bin"
FILE1="/tmp/a.bin"
FILE2="/tmp/uboot.bin"

# Function to download file with checksum verification
download_file() {
    local url="$1"
    local file="$2"
    wget -q "$url" -O "$file"  # Download file quietly
    if [ $? -ne 0 ]; then
        echo "Failed to download $url"
        exit 1
    fi
    # Calculate checksum and compare with expected value
    expected_checksum=$(wget -qO- "$url.md5")
    actual_checksum=$(md5sum "$file" | awk '{print $1}')
    if [ "$actual_checksum" != "$expected_checksum" ]; then
        echo "Checksum mismatch for $file"
        exit 1
    fi
}

# Download firmware files with checksum verification
download_file "$URL1" "$FILE1"
download_file "$URL2" "$FILE2"

# Set boot arguments
fw_setenv bootargs "console=ttyS1,57600n8 root=/dev/mtdblock5"

# Write firmware to memory devices, reset, and reboot
mtd write "$FILE1" /dev/mtd4 && jffs2reset -y && sleep 3 && reboot
