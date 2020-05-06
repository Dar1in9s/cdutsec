#!/bin/sh
chown -R root:ctf /home/ctf
chmod -R 750 /home/ctf
chmod 740 /home/ctf/flag

/etc/init.d/xinetd start;
sleep infinity;
