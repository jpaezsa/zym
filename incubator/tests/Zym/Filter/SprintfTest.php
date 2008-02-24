<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category Zym
 * @package Zym_Filter
 * @copyright Copyright (c) 2008 Zym. (http://www.assembla.com/wiki/show/zym)
 * @license http://www.assembla.com/wiki/show/zym/License New BSD License
 */

/**
 * @see PHPUnite_Framework_TestCase
 */
require_once('PHPUnit/Framework/TestCase.php');

/**
 * @see Zym_Filter_Sprintf
 */
require_once('Zym/Filter/Sprintf.php');

/**
 * Fake filter that does not do anything
 *
 * @author Geoffrey Tran
 * @license http://www.assembla.com/wiki/show/zym/License New BSD License
 * @category Zym
 * @package Zym_Filter
 * @copyright Copyright (c) 2008 Zym. (http://www.assembla.com/wiki/show/zym)
 */
class Zym_Filter_SprintfTest extends PHPUnit_Framework_TestCase
{
    /**
     * Zym Filter
     *
     * @var Zym_Filter_Interface
     */
    protected $_filter;	
    
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
	    $this->_filter = new Zym_Filter_Sprintf();
		parent::setUp();	
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		$this->_filter = null;	
		parent::tearDown();
	}
	
	/**
	 * Test filter
	 *
	 */
	public function testFilter()
	{
	    $filter = $this->_filter;
	    $filter->setArgs('placeholder1', 'placeholder2');
	    
	    $this->assertSame('placeholder1 placeholder2', $filter->filter('%s %s'));
	}
}