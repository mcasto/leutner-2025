<?php

namespace Castoware;

class Request
{
  public $headers, $body, $post, $files, $params, $auth;

  function __construct()
  {
    $this->headers = function_exists('getallheaders') ? (object) getallheaders() : false;
    $this->auth = $this->headers->authorization ?? $this->headers->Authorization ?? false;
    $this->body = json_decode(file_get_contents("php://input"));
    $this->post = (object) $_POST;
    $this->files = (object) $_FILES;
  }
}
