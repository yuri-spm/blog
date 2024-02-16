<?php

namespace system\Core;

/**
 * Message
 */
class Message
{
    private $text;
    private $css;

    

    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * success
     *
     * @param  mixed $mensage
     * @return Message
     */
    public function success(string $mensage): Message
    {
        $this->css = 'alert alert-success';
        $this->text = $this->filter($mensage);
        return $this;
    }
    
    /**
     * error
     *
     * @param  mixed $mensage
     * @return Message
     */
    public function error(string $mensage): Message
    {
        $this->css = 'alert alert-danger';
        $this->text = $this->filter($mensage);
        return $this;
    }
    
    /**
     * alert
     *
     * @param  mixed $mensage
     * @return Message
     */
    public function alert(string $mensage): Message
    {
        $this->css = 'alert alert-warning';
        $this->text = $this->filter($mensage);
        return $this;
    }

    public function info(string $mensage): Message
    {
        $this->css = 'alert alert-primary';
        $this->text = $this->filter($mensage);
        return $this;
    }

    /**
     * render text
     *
     * @return string
     */
    public function render(): string
    {
       return "<div class='{$this->css}'>{$this->text}</div>";
    }
    
    /**
     * filter
     *
     * @param  mixed $message
     * @return string
     */
    private function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}