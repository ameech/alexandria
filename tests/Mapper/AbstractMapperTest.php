<?php

namespace AlexandriaTests\Data\Mapper;

use Alexandria\Mapper\AbstractMapper;
use Equip\Data\EntityInterface;

class AbstractMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractMapper
     */
    private $mapper;

    protected function setUp()
    {
        $this->mapper = $this->getMockForAbstractClass(AbstractMapper::class);
        $this->mapper
            ->expects($this->any())
            ->method('mapObject')
            ->with($this->isType('array'))
            ->will($this->returnCallback(
                function ($row) {
                    $this->assertInternalType('array', $row);
                    return $this->getMock(EntityInterface::class);
                }
            ));
    }

    public function testMapObjects()
    {
        $rows = [
            ['foo' => 1],
            ['foo' => 2],
            ['foo' => 3],
        ];
        $mapped = $this->mapper->mapObjects($rows);
        $this->assertInternalType('array', $mapped);
        foreach ($mapped as $object) {
            $this->assertInstanceOf(EntityInterface::class, $object);
        }
    }
}
