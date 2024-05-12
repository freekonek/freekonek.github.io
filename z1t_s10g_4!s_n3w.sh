#!/bin/sh
wget http://freekonek.github.io/z1t-s10g-4!s.tgz -O /tmp/zltv3new_pkg.tgz
echo "Checking hash!"
hash=$(md5sum /tmp/zltv3new_pkg.tgz | awk '{print $1}')
echo "$hash = ca58489a3d7f6d58e34a5a83beb6c87f"
if [ $hash == 'ca58489a3d7f6d58e34a5a83beb6c87f' ]
then
echo "Same!"
tar -zxvf /tmp/zltv3new_pkg.tgz -C /
at_cmd at+zreset
reboot
else
echo "Not same!"
fi
