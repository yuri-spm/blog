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

    public function __construct(
        string $url,
        int $pager = 1,
        int $limit = 10,
        int $surroundings = 3,
        int $count = 0
    ) {
        $this->url = $url;
        $this->pager = $pager;
        $this->limit = $limit;
        $this->offset = ($this->pager - 1) * $this->limit;
        $this->countPages = ceil($count / $this->limit);
        $this->surroundings = $surroundings;
        $this->countRecords = $count;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function render(): string
    {
        $pageraction = '<ul class="pagination justify-content-center">';
        $pageraction .= $this->firstPage();
        $pageraction .= $this->previous();
        $pageraction .= $this->paginationBar();
        $pageraction .= $this->next();
        $pageraction .= $this->lastPage();
        $pageraction .= '</ul>';

        return $pageraction;
    }

    private function firstPage(): ?string
    {
        if ($this->pager > 2) {
            return '<li class="page-item"><a class="page-link" href="' . $this->url . '/1" tooltip="tooltip" title="Primeira Página"><i class="fa-solid fa-angles-left"></i></a></li>';
        }
        return null;
    }

    private function previous(): ?string
    {
        if ($this->pager > 1) {
            return '<li class="page-item"><a class="page-link" href="' . $this->url . '/' . ($this->pager - 1) . '" tooltip="tooltip" title="Página Anterior"><i class="fa-solid fa-angle-left"></i></a></li>';
        } else {
            return '<li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-angle-left"></i></a></li>';
        }
    }

    private function paginationBar(): ?string
    {
        $start = max(1, $this->pager - $this->surroundings);
        $end = min($this->countPages, $this->pager + $this->surroundings);

        $navegacao = '';

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->pager) {
                $navegacao .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $navegacao .= '<li class="page-item"><a class="page-link" href="' . $this->url . '/' . $i . '" tooltip="tooltip" title="Página ' . $i . '">' . $i . '</a></li>';
            }
        }
        return $navegacao;
    }

    private function next(): ?string
    {
        if ($this->pager < $this->countPages) {
            return '<li class="page-item"><a class="page-link" href="' . $this->url . '/' . ($this->pager + 1) . '" tooltip="tooltip" title="Próxima Página"><i class="fa-solid fa-angle-right"></i></a></li>';
        } else {
            return '<li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-angle-right"></i></a></li>';
        }
    }

    private function lastPage(): ?string
    {
        if ($this->pager < $this->countPages) {
            return '<li class="page-item"><a class="page-link" href="' . $this->url . '/' . $this->countPages . '" tooltip="tooltip" title="Última Página"><i class="fa-solid fa-angles-right"></i></a></li>';
        }
        return null;
    }

    public function info(): string
    {
        $countInitial = $this->offset + 1;
        $countFinal = min($this->countRecords, $this->pager * $this->limit);
        $countRecords = number_format($this->countRecords, 0, '.', '.');

        return "Mostrando {$countInitial} até {$countFinal} de {$countRecords} registros";
    }
}
