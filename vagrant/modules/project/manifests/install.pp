class project::install {
  include project::utils

  # init project
  exec { 'project composer install':
    command   => 'composer install --prefer-source --no-interaction --no-ansi --no-progress --profile',
    cwd       => '/home/vagrant/project',
    creates   => '/home/vagrant/project/src/vendor',
    logoutput => true,
    user      => 'vagrant',
    group     => 'www-data',
    timeout   => 0,
    require   => [
      Exec['composer'],
      Exec['copy-project'],
      Exec['pear clear-cache'],
      File['/home/vagrant/.subversion/servers'],
      Package['php5-gd'],
    ],
    notify    => Service['apache2'],
  }

  # init db
  exec { 'phing init-db':
    command   => 'phing create-db-deployer init-db',
    cwd       => '/home/vagrant/project',
    logoutput => true,
    timeout   => 0,
    require   => [
      Exec['set-mysql-password'],
      Exec['phing'],
      Exec['copy-project'],
      Package['php5-mysql'],
    ],
    unless    => "mysql -u$mysql_user_deployer_name -p$mysql_user_deployer_password -e 'use $mysql_schema'",
  }
}