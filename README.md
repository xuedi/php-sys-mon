[![Actions Status](https://github.com/xuedi/php-sys-mon/workflows/PHP-Unit/badge.svg)](https://github.com/xuedi/php-sys-mon/actions)
[![Code Coverage](https://raw.githubusercontent.com/xuedi/php-sys-mon/main/tests/badge/coverage.svg?sanitize=true)](https://github.com/xuedi/php-sys-mon/blob/main/tests/badge_generator.php)

# php-sys-mon

PHP based cli tool to display basic linux information

## installing

### linux requirements

Some basic cli tools that the app is using for data collection. 
Reducing redundancies of linux tools is a work in progress.  

| Distro   | Command                                              |
|----------|------------------------------------------------------|
| arch     | sudo pacman -S lm_sensors hddtemp nvme-cli smartctl  |
| ubuntu   | sudo apt install lm-sensors hddtemp                  |
| fedora   | sudo dfn install lm_sensors hddtemp                  |
| openSuse | sudo zypper install lm_sensors hddtemp               |

### php requirements

Since this is a PHP practise app, it is using version 8.1 of PHP

### app settings

Clone the repo to any location of your liking

``` shell
git clone git@github.com:xuedi/php-sys-mon.git 
cd php-sys-mon
make install
```

### local binary

Where ever your bin path is `~/Bin` or `~/.bin` just add a script 
if you like the typical command style for example: `php-sys-mon.bash` 
with this content and make it executable with: `chmod +x php-sys-mon.bash`

``` shell
#!/bin/bash

php ~/Projects/php-sys-mon/php-sys-mon dashboard
```

## configuration

The basic configuration file is in `config/config.yaml` there 
are so far two section for `storage` and `sensors` the example 
configuration should explain the basics

### storage

The main node `root` is the name of the mount point, the mount 
point itself is under `mount` the partition is under `partition` 
there are a few basic filesystems under `fsType` for now: 
btrfs, ext4. 

There can be multiple `disks` for one partition, depending on 
if there is a system or not. The types of disk are: 
hdd, ssd, nvme

``` yaml
root:
  mount: "/"
  partition: "/dev/sdb2"
  fsType: ext4
  disks:
    "/dev/sdb": ssd
```

### sensors

Multiple lmSensors reading compos are possible, create a main 
entry for each type for example `asus` for the provider 
`asuswmisensors-isa-0000` you can find out about the option 
by the comman `sensors` in your commandline.

A list of one or multiple items can be added under each 
section for example here named `CPU` the value is the 
actual reading point you get from the sensors command. 
The extra can be another sensor reading related to the 
sensor, for example a fan speed. 

``` yaml
asus:
  provider: "asuswmisensors-isa-0000"
  items:
    CPU:
      value: "CPU Temperature"
      extra: "CPU Fan"
```

There can also be multiple extra sensors, just add them as a 
list like:

``` yaml
extra:
  - "Chassis Fan 2"
  - "Chassis Fan 3"
```

## example output

```
Filesystem
+--------+-------------------+--------+-----------+-----------------+------+--------------------------------------------------+
| Name   | Mount             | FsType | Size      | Used            | Temp | Devices                                          |
+--------+-------------------+--------+-----------+-----------------+------+--------------------------------------------------+
| root   | /                 | ext4   | 315.93 GB | 19% -  59.03 GB | 25°  | sdb: 25°                                         |
| boot   | /boot             | ext4   |   1.07 GB | 34% - 369.67 MB | 25°  | sdb: 25°                                         |
| home   | /home/xuedi       | ext4   |   2.95 TB | 16% - 464.36 GB | 31°  | nvme1: 31°, nvme2: 31°, nvme3: 31°, nvme4: 31°   |
| games  | /home/xuedi/Games | ext4   |   1.65 TB | 56% - 930.23 GB | 25°  | sdb: 25°                                         |
| ubuntu | /run/media/ubuntu | ext4   | 491.11 GB | 28% - 139.80 GB | 37°  | nvme0: 37°                                       |
| data   | /run/media/Data   | btrfs  |  32.01 TB | 21% -   6.80 TB | 37°  | sda: 37°, sdc: 37°, sdd: 39°, sde: 36°, sdf: 37° |
+--------+-------------------+--------+-----------+-----------------+------+--------------------------------------------------+

Sensors
+-------------+---------+--------------------+
| CPU         | +37.0°C | 785 RPM            |
| Motherboard | +30.0°C | 1073 RPM           |
| Chipset     | +48.0°C |                    |
| VRM         | +37.0°C |                    |
| Harddrives  | +38.0°C | 1203 RPM, 1182 RPM |
| Power       | +40.5°C | 0 RPM, 118.00 W    |
| WifiCard    | +32.0°C |                    |
+-------------+---------+--------------------+
```

## roadmap

There are a few thing that are in progress:
- refactoring to build a best practise php codebase
- a self refreshing web interface
- filesystem health
- raid health check infos
- writing tests
