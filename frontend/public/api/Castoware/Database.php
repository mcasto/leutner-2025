<?php

namespace Castoware;

use Dibi\Connection;

class Database
{
  public $db;

  function __construct()
  {

    $util = new Util;
    $config = json_decode(file_get_contents($util->configFile));
    $username = $config->username;
    $password = $config->password;
    $dbName = $config->dbName;

    $databaseConnection = $this->connectMysql($username, $password, $dbName);

    $this->db = isset($databaseConnection) ? new Connection($databaseConnection) : false;
  }

  function connectSqlite($dbFile)
  {
    return [
      'driver' => 'sqlite',
      'database' => $dbFile
    ];
  }

  function connectMysql($username, $password, $dbName)
  {
    return [
      'driver'   => 'mysqli',
      'host'     => '127.0.0.1',
      'username' => $username,
      'password' => $password,
      'database' => $dbName,
    ];
  }
}
