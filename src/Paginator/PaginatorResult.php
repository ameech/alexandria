<?php

namespace Alexandria\Paginator;

class PaginatorResult
{
    /**
     * @var integer
     */
    protected $totalRowCount;

    /**
     * @var integer
     */
    protected $rowsPerPage;

    /**
     * @var integer
     */
    protected $currentPage;

    /**
     * @var array
     */
    protected $currentPageRows;

    /**
     * @param integer $totalRowCount
     * @param integer $rowsPerPage
     * @param integer $currentPage
     * @param array $currentPageRows
     */
    public function __construct(
        $totalRowCount,
        $rowsPerPage,
        $currentPage,
        array $currentPageRows
    ) {
        $this->totalRowCount = (int) $totalRowCount;
        $this->rowsPerPage = (int) $rowsPerPage;
        $this->currentPage = (int) $currentPage;
        $this->currentPageRows = $currentPageRows;
    }

    /**
     * @return integer
     */
    public function getTotalRowCount()
    {
        return $this->totalRowCount;
    }

    /**
     * @return integer
     */
    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    /**
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return array
     */
    public function getCurrentPageRows()
    {
        return $this->currentPageRows;
    }
}
