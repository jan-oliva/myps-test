class bootstrap {
  # this makes puppet and vagrant shut up about the puppet group
  group { 'puppet':
    ensure => 'present'
  }

  # sets group for vagrant user
  exec { 'vagrant group www-data':
    command => 'usermod -g 33 vagrant && usermod -aG vagrant vagrant',
    unless  => 'id -g vagrant | grep 33',
  }

  # sets custom profile for vagrant user
  file { '/home/vagrant/.profile':
    ensure => present,
    mode   => 644,
    owner  => 'vagrant',
    group  => 'www-data',
    source => 'puppet:///modules/bootstrap/.profile',
  }

  # custom source for packages
  exec { 'deb dotdeb':
    command => 'echo "deb http://debian-dotdeb.mirror.web4u.cz/ squeeze all" | tee -a /etc/apt/sources.list',
    unless  => 'cat /etc/apt/sources.list | grep "deb http://debian-dotdeb.mirror.web4u.cz"',
  }

  exec { 'deb-src dotdeb':
    command => 'echo "deb-src http://debian-dotdeb.mirror.web4u.cz/ squeeze all" | tee -a /etc/apt/sources.list',
    require => Exec['deb dotdeb'],
    unless  => 'cat /etc/apt/sources.list | grep "deb-src http://debian-dotdeb.mirror.web4u.cz"',
  }

  exec { 'wget dotdeb.gpg':
    command => 'wget -qO - http://www.dotdeb.org/dotdeb.gpg | apt-key add -',
    unless  => 'apt-key list | grep dotdeb',
    require => Exec['deb-src dotdeb'],
    notify  => Exec['apt-get update'],
  }

  # custom source for svn 1.7
  exec { 'deb wandisco':
    command => 'echo "deb http://opensource.wandisco.com/debian/ squeeze svn17" | tee -a /etc/apt/sources.list',
    unless  => 'cat /etc/apt/sources.list | grep "deb http://opensource.wandisco.com"',
  }

  exec { 'wget wandisco-debian.gpg':
    command => 'wget -qO - http://opensource.wandisco.com/wandisco-debian.gpg | apt-key add -',
    unless  => 'apt-key list | grep wandisco',
    require => Exec['deb wandisco'],
    notify  => Exec['apt-get update'],
  }

  # make sure the packages are up to date before beginning
  exec { 'apt-get update':
    command     => 'apt-get update',
    require     => Exec['vagrant group www-data'],
    refreshonly => true,
  }

  # ensures that sudoers are overwritten
  file { '/etc/sudoers':
    ensure  => present,
    source  => 'puppet:///modules/bootstrap/sudoers',
    mode    => 0440,
    owner   => 'root',
    group   => 'root',
  }
}
