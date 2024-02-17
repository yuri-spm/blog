<?php

namespace system\Support;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Template
{
    private Environment $twig;
    
    /**
     * __construct
     *
     * @param  mixed $directory
     * @return void
     */
    public function __construct(string $directory)
    {
        $loader = new FilesystemLoader($directory);
        $this->twig = new Environment($loader);
    }
    
    /**
     * render
     *
     * @param  mixed $view
     * @param  mixed $dados
     * @return void
     */
    public function render(string $view, array $dados)
    {
        return $this->twig->render($view, $dados);
    }


}