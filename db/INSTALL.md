1) INSTALL THE DATABASE importing structure.sql

On debian
apt-get install sqlite3
$ sqlite3
sqlite> .open survey.sqlite3
sqlite> .separator ';'
sqlite> .read structure.sql

Open webpage -- no questions added yet


2) ADD A QUESTION WITH ANSWERS

Then modify ../lib/Tests/question_test.php with the question and answers that you want.

and run php ../lib/Tests/question_test.php


3) MAKE SURE PERMISSIONS ARE GOOD ON survey.sqlite3 DATABASE

//now make sure that the survey.sqlite3 file is writable by apache group:
chgrp www-data survey.sqlite3

//make sure the /db/ folder is ug=rwx and chown www-data
permissions on db folder should be
drwxrwxr-x 2 root www-data 4096 Apr  6 19:12 .

permissions on survey.sqlite3 should be:
-rw-rw-r-- 1 root www-data 5120 Apr  6 19:12 survey.sqlite3

