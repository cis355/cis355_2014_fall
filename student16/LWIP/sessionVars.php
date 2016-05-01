<?php
ini_set('session.cookie_domain', '.cis355.com' );
session_start();
echo 'session vars: <pre>' . print_r($_SESSION, TRUE) . '</pre>';