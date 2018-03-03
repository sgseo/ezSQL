<?php

require_once('ez_sql_loader.php');

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
require_once dirname(__FILE__) . '/shared/ez_sql_recordset.php';

/**
 * Test class for ezSQL_recordset.
 * Generated by PHPUnit
 *
 * @author  Stefanie Janine Stoelting <mail@stefanie-stoelting.de>
 * @name    SQL_recordsetTest
 * @package ezSQL
 * @subpackage unitTests
 * @license FREE / Donation (LGPL - You may do what you like with ezSQL - no exceptions.)
 */
class ezSQL_recordsetTest2 extends PHPUnit_Framework_TestCase {

    /**
     * constant string user name
     */
    const TEST_DB_USER = 'ez_test';

    /**
     * constant string password
     */
    const TEST_DB_PASSWORD = 'ezTest';

    /**
     * constant database name
     */
    const TEST_DB_NAME = 'ez_test';

    /**
     * constant database host
     */
    const TEST_DB_HOST = 'localhost';

    /**
     * constant database connection charset
     */
    const TEST_DB_CHARSET = 'utf8';
    
    /**
     * @var ezSQL_recordset
     */
    protected $object;
    
    /**
     * ezSQL connection
     * @var ezSQL_mysql
     */
    protected $ezSQL = null;
    

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->ezSQL = new ezSQL_mysql;
        $this->ezSQL->quick_connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME);        
        
        $this->ezSQL->select(self::TEST_DB_NAME);

        $this->ezSQL->query('CREATE TABLE unit_test(id integer, test_key varchar(50), PRIMARY KEY (ID))');
        $this->ezSQL->query('INSERT INTO unit_test(id, test_key) VALUES(1, \'test 1\')');        
        $this->ezSQL->query('INSERT INTO unit_test(id, test_key) VALUES(2, \'test 2\')');        
        $this->ezSQL->query('INSERT INTO unit_test(id, test_key) VALUES(3, \'test 3\')');        
        $this->ezSQL->query('INSERT INTO unit_test(id, test_key) VALUES(4, \'test 4\')');        
        $this->ezSQL->query('INSERT INTO unit_test(id, test_key) VALUES(5, \'test 5\')'); 
        
        $this->ezSQL->query('SELECT * FROM unit_test WHERE id = 7');

        $this->object = new ezSQL_recordset($this->ezSQL->get_results());
    } // setUp

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->ezSQL->query('DROP TABLE unit_test');
        
        $this->object = null;
    } // tearDown

     /**
     * @covers ezSQL_recordset::rewind
     */
    public function testRewind() {
        for ($index = 0; $index < 3; $index++) {
            $result = $this->object->ezSQL_fetch_object();

            $this->assertEquals($index + 1, $result->id);
        }
        
        $this->object->rewind();
        $result = $this->object->ezSQL_fetch_object();
        $this->assertEquals(1, $result->id);
    } // testRewind

    /**
     * @covers ezSQL_recordset::current
     */
    public function testCurrent() {
        $result = $this->object->current(ezSQL_recordset::RESULT_AS_OBJECT);
        
        $this->assertTrue(is_a($result, 'stdClass'));
        
        $this->assertEquals(1, $result->id);
    } // testCurrent

    /**
     * @covers ezSQL_recordset::key
     */
    public function testKey() {
        $this->assertEquals(0, $this->object->key());
        
        $this->object->ezSQL_fetch_object();
        
        $this->assertEquals(1, $this->object->key());
    } // testKey

    /**
     * @covers ezSQL_recordset::next
     */
    public function testNext() {
        $this->object->current(ezSQL_recordset::RESULT_AS_OBJECT);
        $this->assertEquals(0, $this->object->key());
        
        $this->object->next();
        $this->assertEquals(1, $this->object->key());
    } // testNext

    /**
     * @covers ezSQL_recordset::previous
     */
    public function testPrevious() {
        $this->object->current(ezSQL_recordset::RESULT_AS_OBJECT);
        $this->object->next();
        $this->object->next();
        $this->assertEquals(2, $this->object->key());
        
        $this->object->previous();
        $this->assertEquals(1, $this->object->key());
    } // testPrevious

    /**
     * @covers ezSQL_recordset::valid
     */
    public function testValid() {
        $this->assertTrue($this->object->valid());
    } // testValid

    /**
     * @covers ezSQL_recordset::ezSQL_fetch_assoc
     */
    public function testEzSQL_fetch_assoc() {
        $result = $this->object->ezSQL_fetch_assoc();
        
        $this->assertTrue(is_array($result));
        
        $this->assertEquals(1, $result['id']);
    } // testEzSQL_fetch_assoc

    /**
     * @covers ezSQL_recordset::ezSQL_fetch_row
     */
    public function testEzSQL_fetch_row() {
        $result = $this->object->ezSQL_fetch_row();
        
        $this->assertTrue(is_array($result));
        
        $this->assertEquals(1, $result[0]);
    } // testEzSQL_fetch_row

    /**
     * @covers ezSQL_recordset::ezSQL_fetch_object
     */
    public function testEzSQL_fetch_object() {
        $result = $this->object->ezSQL_fetch_object();
        
        $this->assertTrue(is_a($result, 'stdClass'));
        
        $this->assertEquals(1, $result->id);
    } // testEzSQL_fetch_object

} // ezSQL_recordsetTest