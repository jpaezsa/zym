<?php
class MyClass
{
    protected $_notification;
    
    public function __construct()
    {
        $this->_notification = Zym_Notification::get();
        $this->_notification->attach($this, 'testEvent');
    }
    
    public function __destruct()
    {
        $this->_notification->detach($this);
    }
    
    public function notify(Zym_Notification $notification)
    {
        if ('testEvent' == $notification->getName()) {
            // Assume Zend_Log instance
            $log = Zend_Registry::get('log');
            $log->log('testEvent was triggered and received by MyClass!');
        }
    }
}