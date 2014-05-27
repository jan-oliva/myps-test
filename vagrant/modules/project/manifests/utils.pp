class project::utils {
  include project

  # install php-cs-fixer
  exec { 'php-cs-fixer':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require fabpot/php-cs-fixer:dev-master',
    creates     => '/home/vagrant/.composer/vendor/bin/php-cs-fixer',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install phing
  exec { 'phing':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require phing/phing:2.6.1',
    creates     => '/home/vagrant/.composer/vendor/bin/phing',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install apigen
  exec { 'apigen':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require apigen/apigen:2.8.0',
    creates     => '/home/vagrant/.composer/vendor/bin/apigen.php',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # fix apigen link
  file { '/home/vagrant/.composer/vendor/bin/apigen':
    ensure  => '/home/vagrant/.composer/vendor/bin/apigen.php',
    require => Exec['apigen'],
  }

  # install phpcs
  exec { 'phpcs':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require squizlabs/php_codesniffer:1.4.7',
    creates     => '/home/vagrant/.composer/vendor/bin/phpcs',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install phpmd
  exec { 'phpmd':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require phpmd/phpmd:1.5.0',
    creates     => '/home/vagrant/.composer/vendor/bin/phpmd',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install phpcpd
  exec { 'phpcpd':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require sebastian/phpcpd:1.4.3',
    creates     => '/home/vagrant/.composer/vendor/bin/phpcpd',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install phpunit
  exec { 'phpunit':
    command     => 'composer --prefer-source --no-interaction --no-ansi --no-progress --profile global require phpunit/phpunit:3.7.27',
    creates     => '/home/vagrant/.composer/vendor/bin/phpunit',
    environment => 'HOME=/home/vagrant',
    logoutput   => true,
    timeout     => 0,
    user        => 'vagrant',
    require     => Exec['composer'],
  }

  # install composer
  exec { 'composer-download':
    command => 'wget --no-check-certificate -O - https://getcomposer.org/installer | php -- --install-dir=/usr/bin',
    creates => '/usr/bin/composer',
    require => [
      Package['wget'],
      Package['php5'],
      Package['git'],
    ],
    notify  => Exec['composer'],
  }

  exec { 'composer':
    command     => 'mv /usr/bin/composer.phar /usr/bin/composer',
    refreshonly => true,
  }

  # init silex to utils
  exec { 'silex composer install':
    command   => 'composer install --prefer-source --no-interaction --no-ansi --no-progress --profile',
    cwd       => '/home/vagrant/project/utils/silex/',
    creates   => '/home/vagrant/project/utils/silex/vendor/',
    timeout   => 0,
    logoutput => true,
    user      => 'vagrant',
    group     => 'www-data',
    require   => [
      Exec['composer'],
      Exec['copy-project'],
    ],
  }

  # xhprof dirs in utils
  file { ['/home/vagrant/project/utils/xhprof', '/home/vagrant/project/utils/xhprof/data']:
    ensure  => directory,
    mode    => 777,
    owner   => 'vagrant',
    group   => 'www-data',
    require => Exec['copy-project'],
  }

  # install xhprof 0.9.3
  exec { 'xhprof':
    command => 'wget http://pecl.php.net/get/xhprof-0.9.3.tgz -O - | tar xz && cd xhprof-0.9.3/extension && phpize && ./configure && make && make install',
    cwd     => '/home/vagrant/project/utils/xhprof',
    creates => '/home/vagrant/project/utils/xhprof/xhprof-0.9.3',
    require => [
      Package['php5-dev'],
      Package['wget'],
      File['/home/vagrant/project/utils/xhprof'],
    ],
  }

  # ensures that xhprof is configured properly
  file { '/etc/php5/apache2/conf.d/xhprof.ini':
    ensure  => present,
    source  => 'puppet:///modules/php/xhprof.ini',
    mode    => 0644,
    owner   => 'root',
    group   => 'root',
    require => [
      Package['apache2'],
      Package['php5'],
      Exec['xhprof'],
    ],
    notify  => Service['apache2'],
  }

  file { '/home/vagrant/project/utils/xhprof/xhprof_lib':
    ensure  => '/home/vagrant/project/utils/xhprof/xhprof-0.9.3/xhprof_lib',
    require => Exec['xhprof'],
  }

  file { '/home/vagrant/project/utils/xhprof/xhprof_html':
    ensure  => '/home/vagrant/project/utils/xhprof/xhprof-0.9.3/xhprof_html',
    require => Exec['xhprof'],
  }

  exec { 'browscap':
    command => 'wget http://tempdownloads.browserscap.com/stream.asp?PHP_BrowsCapINI -O /home/vagrant/browscap.ini',
    creates => '/home/vagrant/browscap.ini',
    require => Package['wget'],
    notify  => Service['apache2'],
  }
}