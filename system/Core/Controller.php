<?php

namespace system\Core;

use system\Support\Template;

class Controller
{
  protected Template $template;
  protected Message $message;

  
  /**
   * __construct
   *
   * @param  mixed $directory
   * @return void
   */
  public function __construct(string $directory)
  {
    $this->template = new Template($directory);

    $this->message = new Message();
  }
}