<?php

namespace Sokil\Mongo;

class ResultSetTest extends \PHPUnit_Framework_TestCase
{
    public function testMap()
    {
        $resultSet = new ResultSet(array(
            1 => array('_id' => 1, 'field' => 'value1'),
            2 => array('_id' => 2, 'field' => 'value2'),
            3 => array('_id' => 3, 'field' => 'value3'),
        ));

        $newSet = $resultSet->map(function($item) {
            $item['newField'] = 'newValue' . $item['_id'];
            return $item;
        });

        $this->assertNotEmpty(count($newSet));

        foreach($newSet as $id => $item) {
            $this->assertArrayHasKey('newField', $item);
            $this->assertEquals('newValue' . $id, $item['newField']);
        }
    }

    public function testFilter()
    {
        $resultSet = new ResultSet(array(
            1 => array('_id' => 1, 'field' => 'value1'),
            2 => array('_id' => 2, 'field' => 'value2'),
            3 => array('_id' => 3, 'field' => 'value3'),
        ));

        // skip even ids
        $newSet = $resultSet->filter(function($item) {
            return ($item['_id'] % 2 !== 0);
        });

        $this->assertEquals(
            array(
                1 => array('_id' => 1, 'field' => 'value1'),
                3 => array('_id' => 3, 'field' => 'value3'),
            ),
            iterator_to_array($newSet)
        );
    }

    public function testEach()
    {
        $resultSet = new ResultSet(array(
            1 => array('_id' => 1, 'field' => 'value1'),
            2 => array('_id' => 2, 'field' => 'value2'),
            3 => array('_id' => 3, 'field' => 'value3'),
        ));

        // skip even ids
        $resultSet->each(function($item, $id, $resultSet) {
            if($item['_id'] % 2 === 0) {
                unset($resultSet[$id]);
            }
        });

        $this->assertEquals(
            array(
                1 => array('_id' => 1, 'field' => 'value1'),
                3 => array('_id' => 3, 'field' => 'value3'),
            ),
            iterator_to_array($resultSet)
        );
    }
}