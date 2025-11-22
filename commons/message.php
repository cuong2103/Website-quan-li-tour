<?php
class Message
{
  public static function set($key, $message)
  {
    $_SESSION['messages'][$key] = $message;
  }

  public static function get($key)
  {
    if (!empty($_SESSION['messages'][$key])) {
      $msg = $_SESSION['messages'][$key];
      unset($_SESSION['messages'][$key]); // chỉ hiển thị 1 lần
      return $msg;
    }
    return null;
  }
}
