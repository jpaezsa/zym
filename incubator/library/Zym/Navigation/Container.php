<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';

/**
 * Zym_Navigation_Container
 * 
 * Container class for Zym_Navigation_Page classes.  
 * 
 * @author     Robin Skoglund
 * @category   Zym
 * @package    Zym_Navigation
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Navigation_Container
    implements RecursiveIterator, Countable
{
    /**
     * Contains sub pages
     *
     * @var array
     */
    protected $_pages = array();

    /**
     * Order in which to display and iterate pages
     * 
     * @var array
     */
    protected $_order = array();

    /**
     * Whether internal order has been updated
     * 
     * @var bool
     */
    protected $_orderUpdated = false;
    
    /**
     * Parent container
     *
     * @var Zym_Navigation_Container
     */
    protected $_parent = null;
    
    // Internal methods:

    /**
     * Sort pages according to their given positions
     * 
     * @return void
     */
    protected function _sort()
    {
        if ($this->_orderUpdated) {
            $newOrder = array();
            $index = 0;
            
            foreach ($this->_pages as $hash => $page) {
                $pos = $page->getPosition();
                if ($pos === null) {
                    $newOrder[$hash] = $index;
                    $index++;
                } else {
                    $newOrder[$hash] = $pos;
                }
            }

            asort($newOrder);
            $this->_order = $newOrder;
            $this->_orderUpdated = false;
        }
    }
    
    // Public methods:
    
    /**
     * Notifies container that the order of pages are updated
     *
     * @return void
     */
    public function notifyOrderUpdated()
    {
        $this->_orderUpdated = true;
    }
    
    /**
     * Adds a page to the container
     * 
     * @param  Zym_Navigation_Page|array|Zend_Config $page  page to add
     * @return Zym_Navigation_Container
     * @throws InvalidArgumentException  if invalid page is given
     */
    public function addPage($page)
    {
        if (is_array($page) || $page instanceof Zend_Config) {
            $page = Zym_Navigation_Page::factory($page);
        } elseif (!$page instanceof Zym_Navigation_Page) {
            $msg = '$page must be Zym_Navigation_Page|array|Zend_Config';
            throw new InvalidArgumentException($msg);
        }
        
        $id = spl_object_hash($page);
        
        $this->_pages[$id] = $page;
        $this->_order[$id] = $page->getPosition();
        $this->_orderUpdated = true;
        
        $page->setParent($this);
        
        return $this;
    }
    
    /**
     * Adds several pages at once
     *
     * @param  array|Zend_Config $pages  pages to add
     * @return Zym_Navigation_Container
     */
    public function addPages($pages)
    {
        if ($pages instanceof Zend_Config) {
            $pages = $pages->toArray();
        }
        
        foreach ($pages as $page) {
            if ($page instanceof Zym_Navigation_Page) {
                $this->addPage($page);
            } elseif (is_array($page) || $page instanceof Zend_Config) {
                $this->addPage(Zym_Navigation_Page::factory($page));
            }
        }
        
        return $this;
    }
    
    /**
     * Sets pages this container should have, clearing existing ones
     *
     * @param array $pages  pages to set
     * @return Zym_Navigation_Container
     */
    public function setPages(array $pages)
    {
        $this->removePages();
        return $this->addPages($pages);
    }
    
    /**
     * Removes the given page from the container
     *
     * @param  int|Zym_Navigation_Page $page  page to remove, either
     *                                                 position or instance
     * @return bool  indicating whether the removal was successful
     */
    public function removePage($page)
    {
        if (is_int($page)) {
            $this->_sort();
            if ($hash = array_search($page, $this->_order)) {
                unset($this->_order[$hash]);
                unset($this->_pages[$hash]);
                $this->_orderUpdated = true;
                return true;
            }
        } elseif ($page instanceof Zym_Navigation_Page) {
            $hash = spl_object_hash($page);
            if (isset($this->_order[$hash])) {
                unset($this->_order[$hash]);
                unset($this->_pages[$hash]);
                $this->_orderUpdated = true;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Removes all pages in container
     *
     * @return Zym_Navigation_Container_Abstract
     */
    public function removePages()
    {
        $this->_pages = array();
        $this->_order = array();
        return $this;
    }
    
    /**
     * Returns true if container contains any pages
     *
     * @return bool
     */
    public function hasPages()
    {
        return count($this->_order) > 0;
    }
    
    /**
     * Sets parent container
     *
     * @param  Zym_Navigation_Container $parent  [optional] new parent to set,
     *                                           defaults to null which will set
     *                                           no parent
     * @return Zym_Navigation_Page
     */
    public function setParent(Zym_Navigation_Container $parent = null)
    {
        // remove from old parent
        if (null !== $this->_parent && $this instanceof Zym_Navigation_Page) {
             $this->_parent->removePage($this);
        }
        
        $this->_parent = $parent;
        return $this;
    }
    
    /**
     * Returns parent container
     *
     * @return Zym_Navigation_Container|null
     */
    public function getParent()
    {
        return $this->_parent;
    }
 
    // RecursiveIterator interface:

    /**
     * RecursiveIterator: Returns current page
     * 
     * @return Zym_Navigation_Page
     * @throws OutOfBoundsException  if the index is invalid
     */
    public function current()
    {
        $this->_sort();
        current($this->_order);
        $key = key($this->_order);
        
        if (isset($this->_pages[$key])) {
            return $this->_pages[$key];
        } else {
            $msg = 'Corruption detected in container; '
                 . 'invalid key found in internal iterator';
            throw new OutOfBoundsException($msg);
        }
    }

    /**
     * RecursiveIterator: Returns current page id
     * 
     * @return string
     */
    public function key()
    {
        $this->_sort();
        return key($this->_order);
    }

    /**
     * RecursiveIterator: Move pointer to next page in container
     * 
     * @return void
     */
    public function next()
    {
        $this->_sort();
        next($this->_order);
    }

    /**
     * RecursiveIterator: Moves pointer to beginning of container
     * 
     * @return void
     */
    public function rewind()
    {
        $this->_sort();
        reset($this->_order);
    }

    /**
     * RecursiveIterator: Determines if container is valid
     * 
     * @return bool
     */
    public function valid()
    {
        $this->_sort();
        return (current($this->_order) !== false);
    }
    
    /**
     * RecursiveIterator: Proxy to hasPages()
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->hasPages();
    }
    
    /**
     * RecursiveIterator: Returns pages
     *
     * @return Zym_Navigation_Page|null
     */
    public function getChildren()
    {
        $key = key($this->_order);
        
        if (isset($this->_pages[$key])) {
            return $this->_pages[$key];
        }
        return null;
    }
    
    // Countable interface:

    /**
     * Countable: Count of pages that are iterable
     * 
     * @return int
     */
    public function count()
    {
        return count($this->_order);
    }
}

/**
 * @see Zym_Navigation_Page
 */
require_once 'Zym/Navigation/Page.php';