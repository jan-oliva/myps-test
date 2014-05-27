CREATE USER ':user_manager_name:'@'%' IDENTIFIED BY ':user_manager_password:';
-- GRANT Create routine, Create temporary tables, Lock tables, Alter, Create, Create view, Delete, Drop, Index, Insert, Select, Show view, Trigger, Update, Select, Insert, Update, Alter routine, Execute ON `:prefix:connect`.* TO ':user_manager_name:'@'%';

FLUSH PRIVILEGES;