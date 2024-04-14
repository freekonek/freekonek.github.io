#!/bin/sh

EXPECTED_HASH_FIRMWARE="fb4e2077419dedf612f1a036ae4d83d7"  # Expected hash value for firmware.bin
EXPECTED_HASH_UBOOT="d1e62ee1b49e7c7567a967db819ac531"  # Expected hash value for uboot.bin

check_hash() {
    FILE="$1"
    EXPECTED_HASH="$2"
    ACTUAL_HASH=$(sha1sum "$FILE" | awk '{print $1}')
    if [ "$EXPECTED_HASH" != "$ACTUAL_HASH" ]; then
        echo "Error: Hash mismatch for $FILE"
        exit 1
    fi
}

fw_setenv bootdelay 5

wget http://raw.github.com/freekonek/freekonek.io/main/a.bin -O /tmp/firmware.bin
sleep 1
wget http://raw.github.com/freekonek/freekonek.io/main/uboot.bin -O /tmp/uboot.bin
sleep 1

check_hash "/tmp/firmware.bin" "$EXPECTED_HASH_FIRMWARE"
check_hash "/tmp/uboot.bin" "$EXPECTED_HASH_UBOOT"

fw_setenv bootargs console=ttyS1,57600n8 root=/dev/mtdblock5
sleep 1

mtd write /tmp/firmware.bin /dev/mtd4
mtd write /tmp/uboot.bin /dev/mtd1
jffs2reset -y && sleep 3 && reboot
