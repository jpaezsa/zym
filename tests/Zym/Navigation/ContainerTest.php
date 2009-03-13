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
 * @package   Zym_Navigation
 * @license   http://www.zym-project.com/license    New BSD License
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */

/**
 * @see PHPUnite_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @see Zym_Navigation
 */
require_once 'Zym/Navigation.php';

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * Tests the class Zym_Navigation_Container
 *
 * @author    Robin Skoglund
 * @category  Zym_Tests
 * @package   Zym_Navigation
 * @license   http://www.zym-project.com/license    New BSD License
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Navigation_ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     *
     */
    protected function setUp()
    {

    }

    /**
     * Tear down the environment after running a test
     *
     */
    protected function tearDown()
    {

    }

    public function testIteratorShouldBeOrderAware()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#',
                'order' => -1
            ),
            array(
                'label' => 'Page 3',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 4',
                'uri' => '#',
                'order' => 100
            ),
            array(
                'label' => 'Page 5',
                'uri' => '#'
            )
        ));

        $expected = array('Page 2', 'Page 1', 'Page 3', 'Page 5', 'Page 4');
        $actual = array();
        foreach ($nav as $page) {
            $actual[] = $page->getLabel();
        }
        $this->assertEquals($expected, $actual);
    }

    public function testRecursiveIteration()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Page 1.1',
                        'uri' => '#',
                        'pages' => array(
                            array(
                                'label' => 'Page 1.1.1',
                                'uri' => '#'
                            ),
                            array(
                                'label' => 'Page 1.1.2',
                                'uri' => '#'
                            )
                        )
                    ),
                    array(
                        'label' => 'Page 1.2',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Page 2.1',
                        'uri' => '#'
                    )
                )
            ),
            array(
                'label' => 'Page 3',
                'uri' => '#'
            )
        ));

        $actual = array();
        $expected = array(
            'Page 1',
            'Page 1.1',
            'Page 1.1.1',
            'Page 1.1.2',
            'Page 1.2',
            'Page 2',
            'Page 2.1',
            'Page 3'
        );

        $iterator = new RecursiveIteratorIterator($nav,
            RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $page) {
            $actual[] = $page->getLabel();
        }
        $this->assertEquals($expected, $actual);
    }

    public function testSettingPageOrderShouldUpdateContainerOrder()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#'
            )
        ));

        $page3 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 3',
            'uri' => '#'
        ));

        $nav->addPage($page3);


        $expected = array(
            'before' => array('Page 1', 'Page 2', 'Page 3'),
            'after'  => array('Page 3', 'Page 1', 'Page 2')
        );

        $actual = array(
            'before' => array(),
            'after'  => array()
        );

        foreach ($nav as $page) {
            $actual['before'][] = $page->getLabel();
        }

        $page3->setOrder(-1);

        foreach ($nav as $page) {
            $actual['after'][] = $page->getLabel();
        }

        $this->assertEquals($expected, $actual);
    }

    public function testAddPageShouldWorkWithArray()
    {
        $pageOptions = array(
            'label' => 'From array',
            'uri' => '#array'
        );

        $nav = new Zym_Navigation();
        $nav->addPage($pageOptions);

        $this->assertEquals(1, count($nav));
    }

    public function testAddPageShouldWorkWithConfig()
    {
        $pageOptions = array(
            'label' => 'From config',
            'uri' => '#config'
        );

        $pageOptions = new Zend_Config($pageOptions);

        $nav = new Zym_Navigation();
        $nav->addPage($pageOptions);

        $this->assertEquals(1, count($nav));
    }

    public function testAddPageShouldWorkWithPageInstance()
    {
        $pageOptions = array(
            'label' => 'From array 1',
            'uri' => '#array'
        );

        $nav = new Zym_Navigation(array($pageOptions));

        $page = Zym_Navigation_Page::factory($pageOptions);
        $nav->addPage($page);

        $this->assertEquals(2, count($nav));
    }

    public function testAddPagesShouldWorkWithArray()
    {
        $nav = new Zym_Navigation();
        $nav->addPages(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'action' => 'index',
                'controller' => 'index'
            )
        ));

        $this->assertEquals(2, count($nav),
                            'Expected 2 pages, found ' . count($nav));
    }

    public function testAddPagesShouldWorkWithConfig()
    {
        $nav = new Zym_Navigation();
        $nav->addPages(new Zend_Config(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'action' => 'index',
                'controller' => 'index'
            )
        )));

        $this->assertEquals(2, count($nav),
                            'Expected 2 pages, found ' . count($nav));
    }

    public function testAddPagesShouldWorkWithMixedArray()
    {
        $nav = new Zym_Navigation();
        $nav->addPages(new Zend_Config(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            new Zend_Config(array(
                'label' => 'Page 2',
                'action' => 'index',
                'controller' => 'index'
            )),
            Zym_Navigation_Page::factory(array(
                'label' => 'Page 3',
                'uri' => '#'
            ))
        )));

        $this->assertEquals(3, count($nav),
                            'Expected 3 pages, found ' . count($nav));
    }

    public function testRemovingAllPages()
    {
        $nav = new Zym_Navigation();
        $nav->addPages(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#'
            )
        ));

        $nav->removePages();

        $this->assertEquals(0, count($nav),
                            'Expected 0 pages, found ' . count($nav));
    }

    public function testSettingPages()
    {
        $nav = new Zym_Navigation();
        $nav->addPages(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#'
            )
        ));

        $nav->setPages(array(
            array(
                'label' => 'Page 3',
                'uri' => '#'
            )
        ));

        $this->assertEquals(1, count($nav),
                            'Expected 1 page, found ' . count($nav));
    }

    public function testRemovingPageByOrder()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#',
                'order' => 32
            ),
            array(
                'label' => 'Page 3',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 4',
                'uri' => '#'
            )
        ));

        $expected = array(
            'remove0'      => true,
            'remove32'     => true,
            'remove0again' => true,
            'remove1000'   => false,
            'count'        => 1,
            'current'      => 'Page 4'
        );

        $actual = array(
            'remove0'      => $nav->removePage(0),
            'remove32'     => $nav->removePage(32),
            'remove0again' => $nav->removePage(0),
            'remove1000'   => $nav->removePage(1000),
            'count'        => $nav->count(),
            'current'      => $nav->current()->getLabel()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testRemovingPageByInstance()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#'
            )
        ));

        $page3 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 3',
            'uri' => '#'
        ));

        $nav->addPage($page3);

        $this->assertEquals(true, $nav->removePage($page3));
    }

    public function testRemovingPageByInstanceShouldReturnFalseIfPageIsNotInContainer()
    {
        $nav = new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri' => '#'
            ),
            array(
                'label' => 'Page 2',
                'uri' => '#'
            )
        ));

        $page = Zym_Navigation_Page::factory(array(
            'label' => 'Page lol',
            'uri' => '#'
        ));

        $this->assertEquals(false, $nav->removePage($page));
    }

    public function testHasPage()
    {
        $page0 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 0',
            'uri' => '#'
        ));

        $page1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1',
            'uri' => '#'
        ));

        $page1_1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1.1',
            'uri' => '#'
        ));

        $page1_2 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1.2',
            'uri' => '#'
        ));

        $page1_2_1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1.2.1',
            'uri' => '#'
        ));

        $page1_3 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1.3',
            'uri' => '#'
        ));

        $page2 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 2',
            'uri' => '#'
        ));

        $page3 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 3',
            'uri' => '#'
        ));

        $nav = new Zym_Navigation(array($page1, $page2, $page3));

        $page1->addPage($page1_1);
        $page1->addPage($page1_2);
        $page1_2->addPage($page1_2_1);
        $page1->addPage($page1_3);

        $expected = array(
            'haspage0'            => false,
            'haspage2'            => true,
            'haspage1_1'          => false,
            'haspage1_1recursive' => true
        );

        $actual = array(
            'haspage0'            => $nav->hasPage($page0),
            'haspage2'            => $nav->hasPage($page2),
            'haspage1_1'          => $nav->hasPage($page1_1),
            'haspage1_1recursive' => $nav->hasPage($page1_1, true)
        );

        $this->assertEquals($expected, $actual);
    }

    public function testHasPages()
    {
        $nav1 = new Zym_Navigation();
        $nav2 = new Zym_Navigation();
        $nav2->addPage(array(
            'label' => 'Page 1',
            'uri' => '#'
        ));

        $expected = array(
            'empty' => false,
            'notempty' => true
        );

        $actual = array(
            'empty' => $nav1->hasPages(),
            'notempty' => $nav2->hasPages()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testSetParentShouldWorkWithPage()
    {
        $page1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1',
            'uri' => '#'
        ));

        $page2 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 2',
            'uri' => '#'
        ));

        $page2->setParent($page1);

        $expected = array(
            'parent' => 'Page 1',
            'hasPages' => true
        );

        $actual = array(
            'parent' => $page2->getParent()->getLabel(),
            'hasPages' => $page1->hasPages()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testSetParentShouldWorkWithNull()
    {
        $page1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1',
            'uri' => '#'
        ));

        $page2 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 2',
            'uri' => '#'
        ));

        $page2->setParent($page1);
        $page2->setParent(null);

        $this->assertEquals(null, $page2->getParent());
    }

    public function testSetParentShouldRemoveFromOldParentPage()
    {
        $page1 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 1',
            'uri' => '#'
        ));

        $page2 = Zym_Navigation_Page::factory(array(
            'label' => 'Page 2',
            'uri' => '#'
        ));

        $page2->setParent($page1);
        $page2->setParent(null);

        $expected = array(
            'parent' => null,
            'haspages' => false
        );

        $actual = array(
            'parent' => $page2->getParent(),
            'haspages' => $page2->hasPages()
        );

        $this->assertEquals($expected, $actual);
    }

    public function testFinderMethodsShouldWorkWithCustomProperties()
    {
        $nav = $this->_getFindByNavigation();

        $found = $nav->findOneBy('page2', 'page2');
        $this->assertEquals('Page 2', $found->getLabel());
    }

    public function testFindOneByShouldReturnOnlyOnePage()
    {
        $nav = $this->_getFindByNavigation();

        $found = $nav->findOneBy('id', 'page_2_and_3');
        $this->assertType('Zym_Navigation_Page', $found);
    }

    public function testFindOneByShouldReturnNullIfNotFound()
    {
        $nav = $this->_getFindByNavigation();

        $found = $nav->findOneBy('id', 'non-existant');
        $this->assertNull($found);
    }

    public function testFindAllByShouldReturnAllMatchingPages()
    {
        $nav = $this->_getFindByNavigation();

        $found = $nav->findAllBy('id', 'page_2_and_3');

        $expected = array(
            'type' => 'array',
            'count' => 2
        );

        $actual = array(
            'type' => gettype($found),
            'count' => count($found)
        );

        $this->assertEquals($expected, $actual);
    }

    public function testFindAllByShouldReturnEmptyArrayifNotFound()
    {
        $nav = $this->_getFindByNavigation();
        $found = $nav->findAllBy('id', 'non-existant');

        $expected = array('type' => 'array', 'count' => 0);
        $actual = array('type' => gettype($found), 'count' => count($found));
        $this->assertEquals($expected, $actual);
    }

    public function testFindByShouldDefaultToFindOneBy()
    {
        $nav = $this->_getFindByNavigation();

        $found = $nav->findBy('id', 'page_2_and_3');
        $this->assertType('Zym_Navigation_Page', $found);
    }

    protected function _getFindByNavigation()
    {
        // findAllByFoo('bar')         // Page 1, Page 1.1
        // findById('page_2_and_3')    // Page 2
        // findOneById('page_2_and_3') // Page 2
        // findAllById('page_2_and_3') // Page 2, Page 3
        // findAllByAction('about')    // Page 1.3, Page 3
        return new Zym_Navigation(array(
            array(
                'label' => 'Page 1',
                'uri'   => 'page-1',
                'foo'   => 'bar',
                'pages' => array(
                    array(
                        'label' => 'Page 1.1',
                        'uri'   => 'page-1.1',
                        'foo'   => 'bar',
                        'title' => 'The given title'
                    ),
                    array(
                        'label' => 'Page 1.2',
                        'uri'   => 'page-1.2',
                        'title' => 'The given title'
                    ),
                    array(
                        'type'   => 'uri',
                        'label'  => 'Page 1.3',
                        'uri'    => 'page-1.3',
                        'title'  => 'The given title',
                        'action' => 'about'
                    )
                )
            ),
            array(
                'id'         => 'page_2_and_3',
                'label'      => 'Page 2',
                'module'     => 'page2',
                'controller' => 'index',
                'action'     => 'page1',
                'page2'      => 'page2'
            ),
            array(
                'id'         => 'page_2_and_3',
                'label'      => 'Page 3',
                'module'     => 'page3',
                'controller' => 'index',
                'action'     => 'about'
            )
        ));
    }
}