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
 * @package    Zym_Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_Controller_Action_Abstract
 */
require_once 'Zym/Controller/Action/Abstract.php';

/**
 * @see Zend_Data_Paginator
 */
require_once 'Zend/Data/Paginator.php';

/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * @TODO Make this compatible with MultiPageForm
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
abstract class Zym_Controller_Action_CrudAbstract extends Zym_Controller_Action_Abstract
{
    /**
     * Table instance
     *
     * @var Zend_Db_Table_Abstract
     */
    protected $_table = null;

    /**
     * Form instance
     *
     * @var Zend_Form
     */
    protected $_form = null;

    /**
     * The add-edit action
     *
     * @var string
     */
    protected $_addEditAction = 'addEdit';

    /**
     * The browse action
     *
     * @var string
     */
    protected $_browseAction = 'browse';

    /**
     * Default page limit for pagination
     *
     * @var int
     */
    protected $_defaultPageRange = null;

    /**
     * Default page number for pagination
     *
     * @var int
     */
    protected $_defaultPageNumber = 1;

    /**
     * Primary ID key
     *
     * @var string
     */
    protected $_primaryIdKey = null;

    /**
     * Range parameter
     * 
     * @var string
     */
    protected $_rangeParam = 'range';
    
    /**
     * Page parameter
     * 
     * @var string
     */
    protected $_pageParam = 'page';
    
    /**
     * Browse query
     * 
     * @var Zend_Db_Select
     */
    protected $_browseQuery = null;
    
    /**
     * Allows this to work when setDefaultAction() has been set to something other than 'index'
     *
     * @return void
     */
    public function init()
    {
        $front = Zend_Controller_Front::getInstance();
        if ($this->getRequest()->getActionName() == $front->getDefaultAction() && $this->getRequest()->getActionName() != 'index') {
            $this->_forward($this->_getBrowseAction());
        }
    }
    
    /**
     * Get the table for this model
     *
     * @return Zym_Db_Table_Abstract
     */
    protected function _getTable()
    {
        if (!$this->_table) {
            $this->_throwException('No table instance set.');
        }

        return $this->_table;
    }

    /**
     * Set the table
     *
     * @param Zym_Db_Table_Abstract $table
     * @return Zym_Controller_Action_Crud_Abstract
     */
    protected function _setTable(Zym_Db_Table_Abstract $table)
    {
        $this->_table = $table;

        return $this;
    }

    /**
     * Get the form for this model
     *
     * @return Zend_Form
     */
    protected function _getForm()
    {
        if (!$this->_form) {
            $this->_throwException('No form instance set.');
        }

        return $this->_form;
    }

    /**
     * Set a form instance
     *
     * @param Zend_Form $form
     * @return Zym_Controller_Action_Crud_Abstract
     */
    protected function _setForm(Zend_Form $form)
    {
        $this->_form = $form;

        return $this;
    }

    /**
     * Get the primary id from the request
     *
     * @return int|null
     */
    protected function _getPrimaryId()
    {
        return $this->_getParam($this->_getPrimaryIdKey());
    }

    /**
     * Get the location to where the form needs to submit for a new entry
     *
     * @return array
     */
    protected function _getNewSubmitLocation()
    {
        return array('module'     => $this->getRequest()->getModuleName(),
                     'controller' => $this->getRequest()->getControllerName(),
                     'action'     => $this->_getAddEditAction());
    }

    /**
     * Get the location to where the form needs to submit for an edited entry
     *
     * @return array
     */
    protected function _getEditSubmitLocation()
    {
        $location = $this->_getNewSubmitLocation();
        $location[$this->_getPrimaryIdKey()] = $this->_getPrimaryId();

        return $location;
    }

    /**
     * Get the column name of the primary id
     *
     * @return string
     */
    protected function _getPrimaryIdKey()
    {
        if ($this->_primaryIdKey == null) {
            $info = $this->_getTable()->info();

            $this->_primaryIdKey = (string) array_shift($info[Zend_Db_Table_Abstract::PRIMARY]);
        }

        return $this->_primaryIdKey;
    }

    /**
     * Get the model from the table
     *
     * @param int $id
     * @return Zend_Db_Table_Row_Abstract|null
     */
    protected function _getRow($id)
    {
        $table = $this->_getTable();

        $row = $table->find((int) $id)
                     ->current();

        if (!$row) {
            $this->_throwException('The requested row could not be loaded.');
        }

        return $row;
    }

    /**
     * Get the name of the action that takes care of the add/edit stuff
     *
     * @return string
     */
    protected function _getAddEditAction()
    {
        if (!$this->_addEditAction) {
            $this->_throwException('You must set an add-edit action.');
        }

        return $this->_addEditAction;
    }

    /**
     * Set the add-edit action
     *
     * @param string $action
     * @return Zym_Controller_Action_Crud_Abstract
     */
    protected function _setAddEditAction($action)
    {
        $this->_addEditAction = $action;

        return $this;
    }

    /**
     * Get the name of the action that takes care of the browsing
     *
     * @return string
     */
    protected function _getBrowseAction()
    {
        if (!$this->_browseAction) {
            $this->_throwException('You must set a browse action.');
        }

        return $this->_browseAction;
    }

    /**
     * Set the browse action
     *
     * @param string $action
     * @return Zym_Controller_Action_Crud_Abstract
     */
    protected function _setBrowseAction($action)
    {
        $this->_browseAction = $action;

        return $this;
    }

    /**
     * Throw an exception
     *
     * @param string $message
     * @throws Zym_Controller_Action_Exception
     */
    protected function _throwException($message)
    {
        /**
         * @see Zym_Controller_Action_Exception
         */
        require_once 'Zym/Controller/Action/Exception.php';

        throw new Zym_Controller_Action_Exception($message);
    }

    /**
     * Index action. Forward to the browse action
     */
    public function indexAction()
    {
        $this->_forward($this->_getBrowseAction());
    }

    /**
     * Browse through your models
     */
    public function browseAction()
    {
        $range = (int) $this->_getParam($this->_rangeParam, $this->_defaultPageRange);
        $page  = (int) $this->_getParam($this->_pageParam, $this->_defaultPageNumber);

        $paginator = Zend_Data_Paginator::factory($this->_getBrowseQuery());

        if ($range > 0) {
            $paginator->setPageRange($range);
        }

        if ($page > 0) {
            $paginator->setCurrentPageNumber($page);
        }

        $this->view->paginator = $paginator;
    }

    /**
     * Get the select object for the browse action
     *
     * @return Zend_Db_Table_Select
     */
    protected function _getBrowseQuery()
    {
        if (!$this->_browseQuery) {
            $this->_browseQuery = $this->_getTable()->select();
        }
        
        return $this->_browseQuery; 
    }
    
    /**
     * Set the select object for the browse action
     *
     * @param Zend_Db_Select $query
     * @return Zym_Controller_Action_CrudAbstract
     */
    protected function _setBrowseQuery(Zend_Db_Select $query)
    {
        $this->_browseQuery = $query;
        
        return $this; 
    }

    /**
     * View a model if it exists
     */
    public function viewAction()
    {
        $id = $this->_getPrimaryId();
        $row = $this->_getRow($id);

        $this->view->row = $row;
    }

    /**
     * Add or edit a model
     */
    public function addEditAction()
    {
        $form = $this->_getForm();

        $id = $this->_getPrimaryId();

        if ($this->getRequest()->isPost()) {
            $this->_handlePostAction();

            if ($form->isValid($this->getRequest()->getPost())) {
                $this->_processValidForm();
            }
        } else {
            if ($id) {
                $row = $this->_getRow($id);

                $form->setDefaults($row->toArray());
            }
        }

        if (!$id) {
            $url = $this->_getNewSubmitLocation();
        } else {
            $url = $this->_getEditSubmitLocation();
        }

        $form->setAction($this->view->url($url, null, true));

        $this->view->form = $form;
    }

    /**
     * Check if a special submit button is used and act accordingly.
     * @TODO make this nicer...
     */
    protected function _handlePostAction()
    {
        $postData = $this->getRequest()->getPost();

        switch (true) {
            case array_key_exists('_cancel', $postData);
                $this->_goto($this->_getBrowseAction());
                break;
        }
    }

    /**
     * Process the form after it's been succesfully validated
     *
     */
    protected function _processValidForm()
    {
        $table = $this->_getTable();

        $formValues = $this->_getForm()->getValues();

        if (!empty($formValues[$this->_getPrimaryIdKey()])) {
            $row = $this->_getRow($this->_getPrimaryId());
        } else {
            $row = $table->createRow();
        }

        foreach ($formValues as $key => $value) {
            if (isset($row->$key) && !$table->isIdentity($key)) {
                $row->$key = $value;
            }
        }

        $row->save();

        $this->_goto($this->_getBrowseAction());
    }

    /**
     * Delete a model
     */
    public function deleteAction()
    {
        $id = $this->_getPrimaryId();

        if ($id) {
            $table = $this->_getTable();

            $where = $table->getAdapter()
                           ->quoteInto(sprintf('%s=?', $this->_getPrimaryIdKey()), $id);

            $table->delete($where);
        }

        $this->_goto($this->_getBrowseAction());
    }
}