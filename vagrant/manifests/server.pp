# default path
Exec {
  path => ['/usr/bin', '/bin', '/usr/sbin', '/sbin', '/usr/local/bin', '/usr/local/sbin', '/home/vagrant/.composer/vendor/bin']
}

$mysql_user_root_name = "root"
$mysql_user_root_password = "t00r"
$mysql_user_deployer_name = "deployer"
$mysql_user_deployer_password = "deployer"
$mysql_schema = "jo_myps"

include bootstrap
include other
include apache
include php
include pear
include mysql
include subversion
include project::install