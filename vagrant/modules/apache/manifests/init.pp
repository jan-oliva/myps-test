class apache {
  package { 'apache2':
    ensure  => present,
    require => Exec['apt-get update'],
  }
  
  # delete unused configuraion and data
  file { '/etc/apache2/sites-enabled/000-default':
    ensure  => absent,
    target  => '/etc/apache2/sites-enabled/000-default',
    require => Package['apache2'],
    notify  => Service['apache2'],
  }
  
  file { '/var/www/index.html':
    ensure  => absent,
    target  => '/var/www/index.html',
    require => Package['apache2'],
  }

  # ensures that mod_rewrite is loaded
  file { '/etc/apache2/mods-enabled/rewrite.load':
    ensure  => '/etc/apache2/mods-available/rewrite.load',
    require => Package['apache2'],
    notify  => Service['apache2'],
  }
  
  # ensures that apache listens on port 8080
  file { '/etc/apache2/ports.conf':
    source  => 'puppet:///modules/apache/ports.conf',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => Package['apache2'],
    notify  => Service['apache2'],
  }

  # starts the apache2 service once the packages installed
  service { 'apache2':
    ensure    => running,
    require   => Package['apache2'],
  }
}
