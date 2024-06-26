<?php
session_start();
unset($_SESSION ['adminer']);
header('Location: ./login.php');