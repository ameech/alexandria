<?php

namespace Alexandria\Repository\Pdo;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\ValuesInterface;
use Aura\SqlQuery\QueryFactory;
use Aura\SqlQuery\QueryInterface;
use Alexandria\Paginator\AuraSqlQueryPaginator;

abstract class AbstractPdoRepository
{
    /**
     * @var ExtendedPdoInterface
     */
    protected $pdo;

    /**
     * @var QueryFactory
     */
    protected $query;

    /**
     * @var AuraSqlQueryPaginator
     */
    protected $paginator;

    /**
     * @return string
     */
    abstract protected function getTable();

    /**
     * @return Alexandria\Mapper\MapperInterface
     */
    abstract protected function getMapper();

    /**
     * @return string[]
     */
    protected function getColumns()
    {
        return [$this->getTable() . '.*'];
    }

    /**
     * @param ExtendedPdoInterface $pdo
     * @param QueryFactory $query
     * @param AuraSqlQueryPaginator $paginator
     */
    public function __construct(
        ExtendedPdoInterface $pdo,
        QueryFactory $query,
        AuraSqlQueryPaginator $paginator
    ) {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->paginator = $paginator;
    }

    /**
     * @param array $columns
     * @return SelectInterface
     */
    protected function select(array $columns = [])
    {
        if (empty($columns)) {
            $columns = $this->getColumns();
        }

        return $this->query
            ->newSelect()
            ->cols($columns)
            ->from($this->getTable());
    }

    /**
     * @param array $columns
     * @return ValuesInterface
     */
    protected function insert(array $columns)
    {
        return $this->query
            ->newInsert()
            ->into($this->getTable())
            ->cols($columns);
    }

    /**
     * @param array $columns
     * @param string|null $name
     * @return string
     */
    protected function insertAndGetId(array $columns, $name = null)
    {
        $this->execute($this->insert($columns));
        return $this->pdo->lastInsertId($name);
    }

    /**
     * @param QueryInterface $query
     * @return \PDOStatement
     */
    protected function execute(QueryInterface $query)
    {
        return $this->pdo
            ->perform(
                $query->getStatement(),
                $query->getBindValues()
            );
    }

    /**
     * @param SelectInterface $select
     * @return \Equip\Data\EntityInterface|null
     */
    protected function getOneResult(SelectInterface $select)
    {
        $statement = $this->execute($select);
        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->getMapper()->mapObject($row);
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return \Equip\Data\EntityInterface|null
     */
    protected function getOneResultByColumn($column, $value)
    {
        $select = $this->select()
            ->where($column . ' = ?', $value);

        return $this->getOneResult($select);
    }

    /**
     * @param SelectInterface $select
     * @param array $options
     * @return \Equip\Data\EntityInterface[]
     */
    protected function paginate(SelectInterface $select, array $options = [])
    {
        $result = $this->paginator->paginate($select, $options);

        return $this->getMapper()->mapObjects($result->getCurrentPageRows());
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getPaginationOptions($limit, $offset)
    {
        $options = [];

        if ($limit !== null) {
            $options['limit'] = $limit;
        }

        if ($offset !== null) {
            $options['page'] = floor($offset / $limit);
        }

        return $options;
    }
}
