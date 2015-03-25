<?php

$test = new Installer('test_ins');
$test->addRepo('https://github.com/moodle/moodle.git');
$test->addRepo('https://github.com/mgage/wwlink.git', 'blocks/wwlink');
$test->addRepo('https://github.com/mgage/wwassignment.git', 'mod/wwassignment');
$test->debug();
$test->exec();
