<?php

namespace Alexandria\Paginator;

use Aura\Sql\PdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

class AuraSqlQueryPaginator
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'limit' => 20,
        'page' => 0,
    ];

    /**
     * @var PdoInterface
     */
    protected $pdo;

    /**
     * @param PdoInterface $pdo
     */
    public function __construct(PdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param SelectInterface $select
     * @param array $options
     * @return PaginatorResult
     */
    public function paginate(SelectInterface $select, array $options = [])
    {
        $options = $this->filterOptions($options);
        $select = $this->filterQuery($select, $options);
        $stmt = $this->executeQuery($select);
        $pageRows = $this->getPageRows($stmt);
        $totalRowCount = $this->getTotalRowCount();
        return new PaginatorResult(
            $totalRowCount,
            $options['limit'],
            $options['page'],
            $pageRows
        );
    }

    /**
     * @param array $options
     * @return array
     */
    protected function filterOptions(array $options)
    {
        $options += $this->defaultOptions;
        $options['limit'] = (int) $options['limit'];
        $options['page'] = (int) $options['page'];
        return $options;
    }

    /**
     * @param SelectInterface $select
     * @param array $options
     * @return SelectInterface
     */
    protected function filterQuery(SelectInterface $select, array $options)
    {
        if (!$select instanceof \Aura\SqlQuery\Mysql\Select) {
            throw new \RuntimeException('Pagination is currently supported only for MySQL');
        }
        $select = clone $select;
        $select
            ->calcFoundRows()
            ->setPaging($options['limit'])
            ->page($options['page']);
        return $select;
    }

    /**
     * @param SelectInterface $select
     * @return \PDOStatement
     */
    protected function executeQuery(SelectInterface $select)
    {
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());
        return $stmt;
    }

    /**
     * @param \PDOStatement $stmt
     * @return array
     */
    protected function getPageRows(\PDOStatement $stmt)
    {
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return int
     */
    protected function getTotalRowCount()
    {
        $stmt = $this->pdo->query('SELECT FOUND_ROWS()');
        return (int) $stmt->fetchColumn();
    }
}
