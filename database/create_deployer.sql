GRANT USAGE ON *.* TO ''@'localhost';
DROP USER ''@'localhost';

CREATE USER ':user_deployer_name:'@'%' IDENTIFIED BY ':user_deployer_password:';
GRANT GRANT OPTION, Create user, Event, Process, Reload, Show databases, Super, File, Create routine, Create temporary tables, Lock tables, Alter, Create, Create view, Delete, Drop, Index, Insert, References, Select, Show view, Trigger, Update, Select, Insert, Update, References, Alter routine, Execute ON *.* TO ':user_deployer_name:'@'%';
FLUSH PRIVILEGES;