<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category  Zym_Tests
 * @package   Zym_Filter
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license   http://www.zym-project.com/license New BSD License
 */

/**
 * @see PHPUnit_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @see Zym_Filter_Dummy
 */
require_once 'Zym/Filter/Dummy.php';

/**
 * Fake filter that does not do anything
 *
 * @author    Geoffrey Tran
 * @license   http://www.zym-project.com/license New BSD License
 * @category  Zym_Tests
 * @package   Zym_Filter
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Filter_DummyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Zym Filter
     *
     * @var Zym_Filter_Interface
     */
    private $_filter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->_filter = new Zym_Filter_Dummy();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->_filter = null;
    }

    /**
     * Test filter
     *
     */
    public function testFilter()
    {
        $value = 'test';
        $this->assertSame($value, $this->_filter->filter($value));
    }
}