<?php

namespace system\Support;

class Pagination
{

    private string $url;
    private int $limit;
    private int $offset;
    private int $page;
    private int $count;
    private int $surroundings;


    public function __construct(
        string $url,
        int $page = 1,
        int $limit = 10,
        int $surroundings = 3,
        int $count = 0
    ) {
        $this->url = $url;
        $this->page = $page;
        $this->limit = $limit;
        $this->offset = ($this->page - 1) * $this->limit;
        $this->count = ceil($count / $this->limit);
        $this->surroundings = $surroundings;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function render(): string
    {
        $pagecao = '<ul class="pagetion">';
        $pagecao .= '<li class="page-item">' . $this->lastPage() . '</li>';
        $pagecao .= '<li class="page-item">' . $this->firstPage() . '</li>';
        $pagecao .= '<li class="page-item">' . $this->barPagination() . '</li>';
        $pagecao .= '<li class="page-item">' . $this->lastPage() . '</li>';
        $pagecao .= '<li class="page-item">' . $this->nextPage() . '</li>';
        $pagecao .= '</ul>';

        return $pagecao;
    }

    private function previous(): ?string
    {
        if ($this->page > 1) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . ($this->page - 1) . ' ">anterior</a>';
        } elseif ($this->page > 2) {
            return ' <a class="page-link disabled" href=" ' . $this->url . '/' . ($this->page - 1) . ' ">anterior</a>';
        }
        return null;
    }

    private function firstPage(): ?string
    {
        if ($this->page > 1) {
            return ' <a class="page-link" href=" ' . $this->url . '/1 ">primeira</a>';
        }
        return null;
    }

    private function barPagination(): ?string
    {
        $inicio = max(2, $this->page - $this->surroundings);
        $fim = min($this->count, $this->page + $this->surroundings);

        $navegacao = null;

        for ($i = $inicio; $i <= $fim; $i++) {
            if ($i == $this->page) {
                $navegacao .= '<span class="page-link active">' . $i . '</span>';
            } else {
                $navegacao .= '<li class="page-item"><a class="page-link" href=" ' . $this->url . '/' . $i . ' ">' . $i . '</a></li>';
            }
        }
        return $navegacao;
    }

    private function lastPage(): ?string
    {
        if ($this->page < $this->count) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . $this->count . ' ">ultima</a>';
        }
        return null;
    }

    private function nextPage(): ?string
    {
        if ($this->page < $this->count) {
            return ' <a class="page-link" href=" ' . $this->url . '/' . ($this->page + 1) . ' ">Proxima</a>';
        }
        return null;
    }
}
