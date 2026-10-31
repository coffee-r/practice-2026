<?php

namespace App\Packages\Shared\Usecase;

class PagenationData
{
    public readonly int $total;
    public readonly int $currentPage;
    public readonly int $lastPage;
    public readonly bool $hasPreviousPage;
    public readonly bool $hasNextPage;
    
    public function __construct(int $total, int $currentPage, int $perPage)
    {
        if($total < 1){
            throw new UsecaseException('総件数は1以上です');
        }

        $this->total = $total;

        if($currentPage < 1){
            throw new UsecaseException('現在のページ数は1以上です');
        }

        $this->currentPage = $currentPage;

        if ($total % $perPage === 0){
            $this->lastPage = intdiv($total, $perPage);
        }else{
            $this->lastPage = intdiv($total, $perPage) + 1;
        }

        if ($this->lastPage === 1 || $currentPage === 1){
            $this->hasPreviousPage = false;
        }else{
            $this->hasPreviousPage = true;
        }

        if ($this->lastPage === 1 || $currentPage === $this->lastPage){
            $this->hasNextPage = false;
        }else{
            $this->hasNextPage = true;
        }
    }
}
