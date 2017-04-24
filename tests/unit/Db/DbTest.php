<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Tests\Db;

use Bluz;
use Bluz\Db;
use Bluz\Proxy;
use Bluz\Db\Query\Select;
use Bluz\Db\Query\Insert;
use Bluz\Db\Query\Update;
use Bluz\Db\Query\Delete;

/**
 * Test class for Db.
 * Generated by PHPUnit on 2011-07-27 at 13:52:01.
 */
class DbTest extends Bluz\Tests\TestCase
{
    /**
     * @var Db\Db
     */
    protected $db;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->db = new Db\Db();
        $this->db->setOptions(Proxy\Config::getData('db'));
    }

    /**
     * tearDown
     */
    public function tearDown()
    {
        $this->db->disconnect();
    }

    /**
     * Initial Db with configuration
     */
    public function testCheckConnect()
    {
        $this->db->connect();
        self::assertInstanceOf('\Pdo', $this->db->handler());
    }

    /**
     * Initial Db with empty configuration
     * @expectedException \Bluz\Common\Exception\ConfigurationException
     */
    public function testCheckConnectException()
    {
        $db = new Db\Db();
        $db->setConnect([]);
    }

    /**
     * fetchOne
     */
    public function testFetchOne()
    {
        $result = $this->db->fetchOne("SELECT id FROM test LIMIT 1");
        self::assertTrue((bool) $result);
    }

    /**
     * fetchRow
     */
    public function testFetchRow()
    {
        $result = $this->db->fetchRow("SELECT * FROM test LIMIT 1");
        self::assertCount(4, $result);
    }

    /**
     * fetchAll
     */
    public function testFetchAll()
    {
        $result = $this->db->fetchAll("SELECT * FROM test LIMIT 10");
        self::assertCount(10, $result);
    }

    /**
     * fetchColumn
     */
    public function testFetchColumn()
    {
        $result = $this->db->fetchColumn("SELECT id FROM test LIMIT 10");
        self::assertCount(10, $result);
    }

    /**
     * fetchGroup
     */
    public function testFetchGroup()
    {
        $result = $this->db->fetchGroup("SELECT status, id, name FROM test");

        self::assertArrayHasKey('active', $result);
        self::assertArrayHasKey('disable', $result);
        self::assertArrayHasKey('delete', $result);
    }

    /**
     * fetchColumnGroup
     */
    public function testFetchColumnGroup()
    {
        $result = $this->db->fetchColumnGroup("SELECT status, COUNT(id) FROM test GROUP BY status");

        self::assertArrayHasKey('active', $result);
        self::assertArrayHasKey('disable', $result);
        self::assertArrayHasKey('delete', $result);
    }

    /**
     * fetchPairs
     */
    public function testFetchPairs()
    {
        $result = $this->db->fetchPairs("SELECT email, name FROM test LIMIT 10");
        self::assertCount(10, $result);
    }

    /**
     * fetchObject to default class
     */
    public function testFetchObjectToStdClass()
    {
        $result = $this->db->fetchObject("SELECT * FROM test LIMIT 1");
        self::assertInstanceOf(\stdClass::class, $result);
    }

    /**
     * fetchObjects to declared class
     */
    public function testFetchObjectToDeclaredClass()
    {
        $result = $this->db->fetchObject("SELECT * FROM test LIMIT 10", [], 'stdClass');
        self::assertInstanceOf(\stdClass::class, $result);
    }

    /**
     * fetchObject to instance
     */
    public function testFetchObjectToInstance()
    {
        $result = $this->db->fetchObject("SELECT * FROM test LIMIT 1", [], new \stdClass());
        self::assertInstanceOf(\stdClass::class, $result);
    }

    /**
     * fetchObjects to default class
     */
    public function testFetchObjectsToStdClass()
    {
        $result = $this->db->fetchObjects("SELECT * FROM test LIMIT 10");
        self::assertCount(10, $result);
        self::assertInstanceOf(\stdClass::class, current($result));
    }

    /**
     * fetchObjects to declared class
     */
    public function testFetchObjectsToDeclaredClass()
    {
        $result = $this->db->fetchObjects("SELECT * FROM test LIMIT 10", [], 'stdClass');
        self::assertCount(10, $result);
        self::assertInstanceOf(\stdClass::class, current($result));
    }

    /**
     * Transaction
     */
    public function testTransactionTrue()
    {
        $result = $this->db->transaction(function () {
            $this->db->query('SELECT * FROM test LIMIT 10');
        });
        self::assertTrue($result);
    }

    /**
     * Transaction Fail
     */
    public function testTransactionFalse()
    {
        $result = $this->db->transaction(function () {
            $this->db->query('DELETE FROM test LIMIT 1');
            $this->db->query('DELETE FROM test LIMIT 1');
            $this->db->query('DELETE FROM test LIMIT 1');
            $this->db->query('DELETE FROM notexiststable LIMIT 1');
        });
        self::assertFalse($result);
    }

    /**
     * Transaction fail
     * @expectedException \Bluz\Db\Exception\DbException
     */
    public function testTransactionInvalidCallbackThrowException()
    {
        $this->db->transaction('foo');
    }

    /**
     * Select Query Builder
     */
    public function testSelect()
    {
        $query = $this->db->select('test');
        self::assertInstanceOf(Select::class, $query);
    }

    /**
     * Insert Query Builder
     */
    public function testInsert()
    {
        $query = $this->db->insert('test');
        self::assertInstanceOf(Insert::class, $query);
    }

    /**
     * Update Query Builder
     */
    public function testUpdate()
    {
        $query = $this->db->update('test');
        self::assertInstanceOf(Update::class, $query);
    }

    /**
     * Delete Query Builder
     */
    public function testDelete()
    {
        $query = $this->db->delete('test');
        self::assertInstanceOf(Delete::class, $query);
    }
}
