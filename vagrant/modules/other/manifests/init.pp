class other {
  $packages = ['wget', 'mc', 'git', 'subversion', 'default-jre', 'ntp', 'graphviz']
  package { $packages:
    ensure  => present,
    require => Exec['apt-get update'],
  }

  file { '/etc/localtime':
    source  => '/usr/share/zoneinfo/Europe/Prague',
    require => Package['ntp'],
  }

  exec { 'set-timezone':
    command => 'echo "Europe/Prague" > /etc/timezone',
    require => File['/etc/localtime'],
    unless  => 'cat /etc/timezone | grep "Europe/Prague"',
    notify  => [
      Service['apache2'],
      Service['mysql'],
    ],
  }
}
