<?php
namespace AlexandriaTests\Data\Paginator;

use Alexandria\Paginator\PaginatorResult;

class PaginatorResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $rows;

    /**
     * @var PaginatorResult
     */
    protected $result;

    protected function setUp()
    {
        $this->rows = array_map(function ($id) {
            return ['id' => $id];
        }, range(1, 5));
        $this->result = new PaginatorResult(6, 5, 1, $this->rows);
    }

    public function testAccessors()
    {
        $this->assertSame(6, $this->result->getTotalRowCount());
        $this->assertSame(5, $this->result->getRowsPerPage());
        $this->assertSame(1, $this->result->getCurrentPage());
        $this->assertSame($this->rows, $this->result->getCurrentPageRows());
    }
}
