<?php
/**
 * Zym Framework Demo
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_Controller_Action_Abstract
 */
require_once 'Zym/Controller/Action/Abstract.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Demo_ContextSwitchController extends Zym_Controller_Action_Abstract 
{
    /**
     * Ajax actions
     *
     * @var array
     */
    public $ajaxable = array(
        'view' => array('html', 'json')
    );
    
    /**
     * Contexts
     *
     * @var array
     */
    public $contexts = array(
        'view' => array('json', 'xml')
    );
    
    /**
     * Init
     * 
     * @return void
     */
    public function init()
    {
        // Init Contexts
        $this->getHelper('ContextSwitch')->initContext();
        
        // Init AjaxContexts
        $this->getHelper('AjaxContext')->initContext();
    }
    
    /**
     * Index
     *
     * @return void
     */
    public function indexAction()
    {}

    /**
     * View
     *
     * @return void
     */
    public function viewAction()
    {
        // Get articles
        $article = array(
            'title'     => 'Contexts are Cool',
            'subTitle'  => 'Demo brought to you by Zym',
            'published' => '5 April 2008',
        
            'content'   => 'Sample context data content'
        );
        
        // Set view
        $this->getView()->assign(array(
            'article' => $article
        ));
    }
}