class pear {
  package { 'php-pear':
     ensure  => present,
     require => Package['php5'],
  }

  # create pear temp directory
  file { ['/tmp/pear',
          '/tmp/pear/temp']:
    ensure  => 'directory',
    owner   => 'root',
    group   => 'root',
    mode    => 0777,
    require => Package['php-pear'],
  }

  # pear-upgrade.lock
  file { '/home/vagrant/pear-upgrade.lock':
    ensure  => present,
    require => File['/tmp/pear/temp'],
  }

  # set autodiscover
  exec { 'pear config-set auto_discover 1':
    subscribe   => File['/home/vagrant/pear-upgrade.lock'],
    refreshonly => true,
  }

  # update channels
  exec { 'pear update-channels':
    subscribe   => Exec['pear config-set auto_discover 1'],
    refreshonly => true,
  }

  # upgrade
  exec { 'pear upgrade':
    subscribe   => Exec['pear update-channels'],
    refreshonly => true,
  }

  # clear cache
  exec { 'pear clear-cache':
    subscribe   => Exec['pear upgrade'],
    refreshonly => true,
  }
}
