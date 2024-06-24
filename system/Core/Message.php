<?php

namespace system\Core;

/**
 * Classe Message – responsável por exibir as mensagens do sistema.
 * @author Ronaldo Aires <ceo@unset.com.br>
 * @copyright Copyright (c) 2022, UnSet
 */
class Message
{

    private $text;
    private $css;
    
    /**
     * __toString
     *
     * @return void
     */
    public function __toString()
    {
        return $this->render();
    }

      
    /**
     * success
     *
     * @param  mixed $mensagem
     * @return Message
     */
    public function success(string $mensagem): Message
    {
        $this->css = 'alert alert-success';
        $this->text = $this->filter($mensagem);
        return $this;
    }

      
    /**
     * error
     *
     * @param  mixed $mensagem
     * @return Message
     */
    public function error(string $mensagem): Message
    {
        $this->css = 'alert alert-danger';
        $this->text = $this->filter($mensagem);
        return $this;
    }

     
    /**
     * alert
     *
     * @param  mixed $mensagem
     * @return Message
     */
    public function alert(string $mensagem): Message
    {
        $this->css = 'alert alert-warning';
        $this->text = $this->filter($mensagem);
        return $this;
    }

    
    /**
     * inform
     *
     * @param  mixed $mensagem
     * @return Message
     */
    public function inform(string $mensagem): Message
    {
        $this->css = 'alert alert-primary';
        $this->text = $this->filter($mensagem);
        return $this;
    }

       
    /**
     * render
     *
     * @return string
     */
    public function render(): string
    {
        return "<div class='{$this->css} alert-dismissible show'>{$this->text}<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }

    
    /**
     * filter
     *
     * @param  mixed $mensagem
     * @return string
     */
    private function filter(string $mensagem): string
    {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    
    /**
     * flash
     *
     * @return void
     */
    public function flash(): void
    {
        (new Session)->create('flash', $this);
    }

}
