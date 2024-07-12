<?php

namespace system\Support;


class Pager
{

    private string $url;
    private int $limit;
    private int $offset;
    private int $pager;
    private int $countPages;
    private int $surroundings;
    private int $countRecords;

    /**
     * Construtor com os valuees iniciais 
     * @param string $url
     * @param int $pager
     * @param int $limit
     * @param int $surroundings
     * @param int $count
     */
    public function __construct(
            string $url,
            int $pager = 1,
            int $limit = 10,
            int $surroundings = 3,
            int $count = 0
    )
    {
        $this->url = $url;
        $this->pager = $pager;
        $this->limit = $limit;
        $this->offset = ($this->pager - 1) * $this->limit;
        $this->countPages = ceil($count / $this->limit);
        $this->surroundings = $surroundings;
        $this->countRecords = $count;
    }

    /**
     * Retorna o limit de itens por página
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * Retorna o índice do item de início da página atual
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

//        public function pager(): int
//    {
//        return $this->pager;
//    }

    /**
     * Gera a renderização da pagerção
     * @return string
     */
    public function render(): string
    {
        $pageraction = '<ul class="pagertion justify-content-center">';
        $pageraction .= '<li class="page-item">' . $this->firstPage() . '</li>';
        $pageraction .= '<li class="page-item">' . $this->previous() . '</li>';
        $pageraction .= '<li class="page-item">' . $this->paginationBar() . '</li>';
        $pageraction .= '<li class="page-item">' . $this->next() . '</li>';
        $pageraction .= '<li class="page-item">' . $this->lastPage() . '</li>';
        $pageraction .= '</ul>';

        return $pageraction;
    }

    /**
     * Gera link para a primeira página
     * @return string|null
     */
    private function firstPage(): ?string
    {
        if ($this->pager > 2) {
            return ' <a class="page-link" href=" ' . $this->url . '/1 " tooltip="tooltip" title="Primeira Página"><i class="fa-solid fa-angles-left"></i></a>';
        }
        return null;
    }

    /**
     * Gera link para a página anterior
     * @return string|null
     */
    private function previous(): ?string
    {
        if ($this->pager > 1) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . ($this->pager - 1) . ' " tooltip="tooltip" title="Página Anterior"><i class="fa-solid fa-angle-left"></i></a>';
        } elseif ($this->pager < 2) {
            return ' <a class="page-link disabled" href=" ' . $this->url . '/' . ($this->pager - 1) . ' "><i class="fa-solid fa-angle-left"></i></a>';
        }
        return null;
    }

    /**
     * Gera links de pagerção para as páginas intermediárias com o value de arredondamento para determinar quantas páginas devem ser exibidas ao redor da página atual 
     * @return string|null
     */
    private function paginationBar(): ?string
    {
        $start = max(1, $this->pager - $this->surroundings);
        $end =min($this->countPages, $this->pager + $this->surroundings);

        $navegacao = null;

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->pager) {
                $navegacao .= '<span class="page-link active">' . $i . '</span>';
            } else {
                $navegacao .= '<li class="page-item fw-bold"><a class="page-link" href=" ' . $this->url . '/' . $i . ' " tooltip="tooltip" title="Página ' . $i . ' ">' . $i . '</a></li>';
            }
        }
        return $navegacao;
    }

    /**
     * Gera link para a próxima página
     * @return string|null
     */
    private function next(): ?string
    {
        if ($this->pager < $this->countPages) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . ($this->pager + 1) . ' " " tooltip="tooltip" title="Próxima Página"><i class="fa-solid fa-angle-right"></i></a>';
        }
        return null;
    }

    /**
     * Gera link para a última página
     * @return string|null
     */
    private function lastPage(): ?string
    {
        if ($this->pager < $this->countPages) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . $this->countPages . ' " tooltip="tooltip" title="Última Página"><i class="fa-solid fa-angles-right"></i></a>';
        }
        return null;
    }

    /**
     * Retorna o count inicial e final da página atual e o count de registros 
     * @return string
     */
    public function info(): string
    {
        $countInitial = $this->offset + 1;
        $countFinal = min($this->countRecords, $this->pager * $this->limit);
        $countRecords = number_format($this->countRecords, 0, '.', '.');

        return "Mostrando {$countInitial} até {$countFinal} de {$countRecords} registros";
    }

}
