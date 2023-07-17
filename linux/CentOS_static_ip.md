![alt](/img_blog/linux/centos_static_ip/20210112215110105.png)

```bash
ifconfig

vim /etc/sysconfig/network-scripts/ifcfg-ens33
vim /etc/sysconfig/network-scripts/ifcfg-enp0s3
```


```conf
TYPE="Ethernet"
PROXY_METHOD="none"
BROWSER_ONLY="no"
BOOTPROTO="dhcp"			# static
DEFROUTE="yes"
IPV4_FAILURE_FATAL="no"
IPV6INIT="yes"
IPV6_AUTOCONF="yes"
IPV6_DEFROUTE="yes"
IPV6_FAILURE_FATAL="no"
IPV6_ADDR_GEN_MODE="stable-privacy"
NAME="enp0s3"
UUID="dd08fe69-4c7c-43e3-82ae-ebbb416a8528"
DEVICE="enp0s3"
ONBOOT="yes"				# yes
IPADDR="192.168.."			# add
NETMASK="255.255.255.0"		# add
GATEWAY="192.168..1"		# add
DNS1="119.29.29.29"			# add
```


```bash
nmcli c reload
# systemctl restart network.service

ifconfig
ping
```