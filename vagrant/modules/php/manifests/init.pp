class php {
  $packages = ['php5', 'php5-cli', 'php5-gd', 'php5-mysql','php5-intl', 'php5-xdebug', 'php5-xsl', 'php5-dev', 'php5-curl']

  package { $packages:
    ensure  => present,
    require => Package['apache2'],
    notify  => Service['apache2'],
  }

  # ensures that suhosin is configured properly
  file { '/etc/php5/apache2/conf.d/suhosin.ini':
    ensure  => present,
    source  => 'puppet:///modules/php/suhosin.ini',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => Package['php5'],
    notify  => Service['apache2'],
  }

  # ensures that xdebug is configured properly
  file { '/etc/php5/apache2/conf.d/xdebug_configuration.ini':
    ensure  => present,
    source  => 'puppet:///modules/php/xdebug_configuration.ini',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => [
      Package['php5'],
      Package['php5-xdebug'],
    ],
    notify  => Service['apache2'],
  }

  # ensures that apache-php is configured properly
  file { '/etc/php5/apache2/php.ini':
    ensure  => present,
    source  => 'puppet:///modules/php/apache_php.ini',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => Package['php5'],
    notify  => Service['apache2'],
  }

  # ensures that cli-php is configured properly
  file { '/etc/php5/cli/php.ini':
    ensure  => present,
    source  => 'puppet:///modules/php/cli_php.ini',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => Package['php5'],
  }
}
