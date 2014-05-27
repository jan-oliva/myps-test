class mysql {
  package { 'mysql-server':
    ensure  => present,
    require => Exec['apt-get update'],
  }

  service { 'mysql':
    ensure    => running,
    require   => Package['mysql-server'],
  }

  exec { 'set-mysql-password':
    command => "mysqladmin -u$mysql_user_root_name password $mysql_user_root_password",
    require => Service['mysql'],
    unless  => "mysqladmin -u$mysql_user_root_name -p$mysql_user_root_password status",
  }
  
  file { '/etc/mysql/my.cnf':
    ensure  => present,
    source  => 'puppet:///modules/mysql/my.cnf',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => Package['mysql-server'],
    notify  => Service['mysql'],
  }
}
