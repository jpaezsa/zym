<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Paginate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Paginate
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Paginate_Abstract implements Iterator, Countable
{
    /**
     * Total number of pages
     *
     * @var int
     */
    protected $_pageCount = 0;

    /**
     * Total number of items
     *
     * @var int
     */
    protected $_rowCount = 0;

    /**
     * Amount of items per page
     *
     * @var int
     */
    protected $_rowLimit = 10;

    /**
     * The current page nr
     *
     * @var int
     */
    protected $_currentPage = 1;

    /**
     * Page counter for the iterator
     *
     * @var int
     */
    protected $_iteratorPage = 1;

    /**
     * Get the current page nr.
     *
     * @return int
     */
    public function current()
    {
        return (int) $this->_iteratorPage;
    }

    /**
     * Get the iterator key
     *
     * @return int
     */
    public function key()
    {
        return (int) $this->_iteratorPage;
    }

    /**
     * Get the next page nr
     */
    public function next()
    {
        $this->_iteratorPage += 1;
    }

    /**
     * Rewind to the first page
     */
    public function rewind()
    {
        $this->_iteratorPage = 1;
    }

    /**
     * Check if there's a next page
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_iteratorPage <= $this->getPageCount();
    }

    /**
     * Get the number of pages
     *
     * @return int
     */
    public function count()
    {
        return (int) $this->getPageCount();
    }

    /**
     * Check if the page number exists
     *
     * @param int $page
     * @return boolean
     */
    public function hasPageNumber($page)
    {
        return $page <= $this->getPageCount();
    }

    /**
     * Check if there are pages available
     *
     * @return boolean
     */
    public function hasPages()
    {
        return $this->getPageCount() > 0;
    }

    /**
     * Check if there is a next page
     *
     * @return boolean
     */
    public function hasNext()
    {
        return $this->getCurrentPageNumber() < $this->getPageCount();
    }

    /**
     * Check if there is a previous page
     *
     * @return boolean
     */
    public function hasPrevious()
    {
        return $this->getCurrentPageNumber() > 1;
    }

    /**
     * Get item nr
     *
     * @param int $itemNumber
     * @param int $pageNumber
     * @return int
     */
    public function getItemNumber($item, $page = null)
    {
        if (!$page) {
            $page = $this->getCurrentPageNumber();
        }

        return (((int) $page - 1) * $this->getRowLimit()) + (int) $item;
    }

    /**
     * Get the total amount of pages
     *
     * @return int
     */
    public function getPageCount()
    {
        return $this->_pageCount;
    }

    /**
     * Set the total amount of pages
     */
    protected function _setPageCount($count)
    {
        $this->_pageCount = (int) $count;
    }

    /**
     * Get the amount of rows
     *
     * @return int
     */
    public function getRowCount()
    {
        return $this->_rowCount;
    }

    /**
     * Set the amount of rows
     */
    protected function _setRowCount($count)
    {
        $this->_rowCount = (int) $count;
    }

    /**
     * Get amount of items per page
     *
     * @return int
     */
    public function getRowLimit()
    {
        return $this->_rowLimit;
    }

    /**
     * Set the amount of items per page
     *
     * @param int $limit
     * @return Zym_Paginate_Abstract
     */
    public function setRowLimit($limit)
    {
        $this->_rowLimit = (int) $limit;

        return $this;
    }

    /**
     * Set current page nr
     *
     * @param int $page
     * @return Zym_Paginate_Abstract
     */
    public function setCurrentPageNumber($page)
    {
        if (!$this->hasPageNumber($page)) {
            /**
             * @see Zym_Paginate_Exception_PageNotFound
             */
            require_once 'Zym/Paginate/Exception/PageNotFound.php';

            throw new Zym_Paginate_Exception_PageNotFound(sprintf('Page "%s" not found', $page));
        }

        $this->_currentPage = (int) $page;

        return $this;
    }

    /**
     * Get the current page nr
     *
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->_currentPage;
    }

    /**
     * Check if the given number is the current page nr
     *
     * @param int $number
     * @return boolean
     */
    public function isCurrentPageNumber($number)
    {
        return $this->getCurrentPageNumber() === (int) $number;
    }

    /**
     * Get the current page
     *
     * @return Zend_Db_Table_Rowset_Abstract|array
     */
    public function getCurrentPage()
    {
        return $this->getPage($this->getCurrentPageNumber());
    }

    /**
     * Get the next page
     *
     * @throws Zym_Paginate_Exception_NoNextPage
     * @return Zend_Db_Table_Rowset_Abstract|array
     */
    public function getNextPage()
    {
        if (!$this->hasNext()) {
            /**
             * @see Zym_Paginate_Exception_NoNextPage
             */
            require_once 'Zym/Paginate/Exception/NoNextPage.php';

            throw new Zym_Paginate_Exception_NoNextPage('No next page');
        }

        return $this->getPage($this->getNextPageNumber());
    }

    /**
     * Get the next page number
     *
     * @return int
     */
    public function getNextPageNumber()
    {
        if (!$this->hasNext()) {
            /**
             * @see Zym_Paginate_Exception_NoNextPage
             */
            require_once 'Zym/Paginate/Exception/NoNextPage.php';

            throw new Zym_Paginate_Exception_NoNextPage('No next page');
        }

        return $this->getCurrentPageNumber() + 1;
    }

    /**
     * Get the previous page
     *
     * @throws Zym_Paginate_Exception_NoPreviousPage
     * @return Zend_Db_Table_Rowset_Abstract|array
     */
    public function getPreviousPage()
    {
        if (!$this->hasPrevious()) {
            /**
             * @see Zym_Paginate_Exception_NoPreviousPage
             */
            require_once 'Zym/Paginate/Exception/NoPreviousPage.php';

            throw new Zym_Paginate_Exception_NoPreviousPage('No previous page');
        }

        return $this->getPage($this->getPreviousPageNumber());
    }

    /**
     * Get previous page nr
     *
     * @return int
     */
    public function getPreviousPageNumber()
    {
        if (!$this->hasPrevious()) {
            /**
             * @see Zym_Paginate_Exception_NoPreviousPage
             */
            require_once 'Zym/Paginate/Exception/NoPreviousPage.php';

            throw new Zym_Paginate_Exception_NoPreviousPage('No previous page');
        }

        return $this->getCurrentPageNumber() - 1;
    }

    /**
     * Get a page
     *
     * @var int $page
     */
    abstract public function getPage($page);
}