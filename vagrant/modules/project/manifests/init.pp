class project {
  file { '/home/vagrant/project':
    ensure => directory,
    owner  => 'vagrant',
    group  => 'www-data',
    mode   => 777,
  }

  exec { 'copy-project':
    command => 'cp -r -u --preserve=ownership,mode /vagrant/* /home/vagrant/project && rm -rf /home/vagrant/project/src/vendor && rm -rf /home/vagrant/project/utils/silex/vendor',
    unless  => 'find "/home/vagrant/project" -type f | grep Vagrantfile',
    require => File['/home/vagrant/project'],
  }

  # map project to /var/www/project
  file { '/var/www/project':
    ensure  => '/home/vagrant/project/src',
    require => [
      Package['apache2'],
      Exec['copy-project'],
    ],
  }

  # setup project vhost
  file { '/etc/apache2/sites-available/project':
    ensure  => present,
    source  => 'puppet:///modules/project/project.sample',
    require => Package['apache2'],
  }

  file { '/etc/apache2/sites-enabled/project':
    ensure  => '/etc/apache2/sites-available/project',
    require => [
      File['/var/www/project'],
      File['/etc/apache2/sites-available/project'],
    ],
    notify  => Service['apache2'],
  }

  # setup utils vhost
  file { '/var/www/utils':
    ensure  => '/home/vagrant/project/utils',
    require => [
      Package['apache2'],
      Exec['copy-project'],
    ]
  }

  file { '/etc/apache2/sites-available/utils':
    ensure  => present,
    source  => 'puppet:///modules/project/utils.sample',
    require => Package['apache2'],
  }

  file { '/etc/apache2/sites-enabled/utils':
    ensure  => '/etc/apache2/sites-available/utils',
    require => [
      File['/var/www/utils'],
      File['/etc/apache2/sites-available/utils'],
    ],
    notify  => Service['apache2'],
  }
}