<?php

namespace system\Core;

class Message
{

    private $texto;
    private $css;

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Método responsável pelas mensagens de success
     * @param string $message
     * @return Message
     */
    public function success(string $message): Message
    {
        $this->css = 'alert alert-success';
        $this->texto = $this->filter($message);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de error
     * @param string $message
     * @return Message
     */
    public function error(string $message): Message
    {
        $this->css = 'alert alert-danger';
        $this->texto = $this->filter($message);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de alert
     * @param string $message
     * @return Message
     */
    public function alert(string $message): Message
    {
        $this->css = 'alert alert-warning';
        $this->texto = $this->filter($message);
        return $this;
    }

    /**
     * Método responsável pelas mensagens de infoções
     * @param string $message
     * @return Message
     */
    public function info(string $message): Message
    {
        $this->css = 'alert alert-primary';
        $this->texto = $this->filter($message);
        return $this;
    }

    /**
     * Método responsável pela renderização das mensagens
     * @return string
     */
    public function render(): string
    {
        return "<div class='{$this->css} alert-dismissible fade show'>{$this->texto}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    /**
     * Método responsável por filter as mensagens
     * @param string $message
     * @return string
     */
    private function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Cria a sessão das mensagens flash
     * @return void
     */
    public function flash(): void
    {
        (new Session())->create('flash', $this);
    }

}
