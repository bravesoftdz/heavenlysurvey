<?php
/**
 * This utility file will clear all of the questions and answers but leave all the user statistics
 */
require_once __DIR__ . '/../autoloader.inc.php';

Earthling\Survey\QuestionsUtil::AdminDeleteAll();
