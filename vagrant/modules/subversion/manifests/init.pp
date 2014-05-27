class subversion {
  file { '/home/vagrant/.subversion':
    ensure  => directory,
    mode    => 0777,
    owner   => 'vagrant',
    group   => 'www-data',
  }

  file { '/home/vagrant/.subversion/servers':
    ensure  => present,
    source  => 'puppet:///modules/subversion/servers',
    mode    => 0644,
    owner   => 'vagrant',
    group   => 'vagrant',
    require => [
      Package['subversion'],
      File['/home/vagrant/.subversion'],
    ],
  }
}