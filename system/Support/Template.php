<?php

namespace system\Support;

use Twig\Lexer;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use system\Core\Helpers;
use system\Core\Message;
use system\Controller\UserController;

/**
 * Classe Template
 */
class Template
{

    private \Twig\Environment $twig;
    
    /**
     * __construct
     *
     * @param  mixed $diretorio
     * @return void
     */
    public function __construct(string $diretorio)
    {
        $loader = new FilesystemLoader($diretorio);
        $this->twig = new Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }

    /**
     * Metodo responsavel por realizar a renderização das views
     * @param string $view
     * @param array $dados
     * @return string
     */
    public function render(string $view, array $dados): string
    {
        return $this->twig->render($view, $dados);
    }

    /**
     * Metodo responsavel por chamar funções da classe Helpers
     * @return void
     */
    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    new TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            })
            ),
            $this->twig->addFunction(
                    new TwigFunction('greetings', function () {
                                return Helpers::greetings();
                            })
            ),
            $this->twig->addFunction(
                    new TwigFunction('summarizeText', function (string $texto, int $limite) {
                                return Helpers::summarizeText($texto, $limite);
                            })
            ),
            $this->twig->addFunction(
                new TwigFunction('flash', function () {
                        return Helpers::flash();
                    })
            ),
            $this->twig->addFunction(
                new TwigFunction('user', function () {
                        return UserController::user();
                    })
            ),

        );
    }

}
