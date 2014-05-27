CREATE USER ':user_application_name:'@'%' IDENTIFIED BY ':user_application_password:';
-- GRANT Create routine, Create temporary tables, Lock tables, Alter, Create, Create view, Delete, Drop, Index, Insert, Select, Show view, Trigger, Update, Select, Insert, Update, Alter routine, Execute ON `:prefix:connect`.* TO ':user_application_name:'@'%';
GRANT Create routine, Create temporary tables, Lock tables, Alter, Create, Create view, Delete, Drop, Index, Insert, Select, Show view, Trigger, Update, Select, Insert, Update, Alter routine, Execute ON `:prefix:myps`.* TO ':user_application_name:'@'%';

FLUSH PRIVILEGES;