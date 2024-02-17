<?php

namespace system\Support;

use Twig\Lexer;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use system\Core\Helpers;

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
        $laxer = new Lexer($this->twig, array(
            $this->helpers()
        ));

        $this->twig->setLexer($laxer);
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

    private function helpers()
    {
        array(
            $this->twig->addFunction(
                new TwigFunction('url', function (string $url = null){
                    return Helpers::url($url);
                })
            )
        );
    }
}
