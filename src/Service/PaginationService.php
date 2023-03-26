<?php

namespace App\Service;

class PaginationService
{
    private $currentPage;
    private $totalPages;
    private $itemsPerPage;

    public function __construct($currentPage, $totalItems, $itemsPerPage)
    {
        $this->currentPage = $currentPage;
        $this->totalPages = ceil($totalItems / $itemsPerPage);
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
}
