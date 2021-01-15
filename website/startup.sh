cd /
hostname=$(hostname)
line="127.0.0.1 localhost.localdomain localhost $hostname"
sed -e "1 s/^.*$/${line}/g" /etc/hosts > tmphosts
cp tmphosts /etc/hosts
service sendmail restart

/usr/sbin/apache2ctl -D FOREGROUND