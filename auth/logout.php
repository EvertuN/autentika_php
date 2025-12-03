<?php
require '../config/session.php';
require '../config/logger.php';

log_event('LOGOUT');
session_destroy();
header("Location: login.php?msg=logout");
exit;