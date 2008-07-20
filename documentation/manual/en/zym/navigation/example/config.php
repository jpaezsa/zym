<?php
/* CONTENTS OF /path/to/navigation.xml:
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <nav>
    
        <zym>
            <label>Zym</label>
            <uri>http://www.zym-project.com/</uri>
            <position>100</position>
        </zym>
        
        <page1>
            <label>Page 1</label>
            <uri>page1</uri>
            <pages>
            
                <page1_1>
                    <label>Page 1.1</label>
                    <uri>page1/page1_1</uri>
                </page1_1>
                
            </pages>
        </page1>
        
        <page2>
            <label>Page 2</label>
            <uri>page2</uri>
            <pages>
            
                <page2_1>
                    <label>Page 2.1</label>
                    <uri>page2/page2_1</uri>
                </page2_1>
                
                <page2_2>
                    <label>Page 2.2</label>
                    <uri>page2/page2_2</uri>
                    <pages>
                    
                        <page2_2_1>
                            <label>Page 2.2.1</label>
                            <uri>page2/page2_2/page2_2_1</uri>
                        </page2_2_1>
                        
                        <page2_2_2>
                            <label>Page 2.2.2</label>
                            <uri>page2/page2_2/page2_2_2</uri>
                            <active>1</active>
                        </page2_2_2>
                        
                    </pages>
                </page2_2>
                
                <page2_3>
                    <label>Page 2.3</label>
                    <uri>page2/page2_3</uri>
                    <pages>
                    
                        <page2_3_1>
                            <label>Page 2.3.1</label>
                            <uri>page2/page2_3/page2_3_1</uri>
                        </page2_3_1>
                        
                        <page2_3_2>
                            <label>Page 2.3.2</label>
                            <uri>page2/page2_3/page2_3_2</uri>
                            <visible>0</visible>
                            <pages>
                                    
                                    <page2_3_2_1>
                                        <label>Page 2.3.2.1</label>
                                        <uri>page2/page2_3/page2_3_2/1</uri>
                                        <active>1</active>
                                    </page2_3_2_1>
                                    
                                    <page2_3_2_2>
                                        <label>Page 2.3.2.2</label>
                                        <uri>page2/page2_3/page2_3_2/2</uri>
                                        <active>1</active>
                                        
                                        <pages>
                                            <page_2_3_2_2_1>
                                                <label>Ignore</label>
                                                <uri>#</uri>
                                                <active>1</active>
                                            </page_2_3_2_2_1>
                                        </pages>
                                    </page2_3_2_2>
                            
                            </pages>
                        </page2_3_2>
                        
                        <page2_3_3>
                            <label>Page 2.3.3</label>
                            <uri>page2/page2_3/page2_3_3</uri>
                            <role>admin</role>
                            <pages>
                                    
                                    <page2_3_3_1>
                                        <label>Page 2.3.3.1</label>
                                        <uri>page2/page2_3/page2_3_3/1</uri>
                                        <active>1</active>
                                    </page2_3_3_1>
                                    
                                    <page2_3_3_2>
                                        <label>Page 2.3.3.2</label>
                                        <uri>page2/page2_3/page2_3_3/2</uri>
                                        <role>guest</role>
                                        <active>1</active>
                                    </page2_3_3_2>
                            
                            </pages>
                        </page2_3_3>
                        
                    </pages>
                </page2_3>
                
            </pages>
        </page2>
        
        <page3>
            <label>Page 3</label>
            <uri>page3</uri>
            <pages>
            
                <page3_1>
                    <label>Page 3.1</label>
                    <uri>page3/page3_1</uri>
                    <role>guest</role>
                </page3_1>
                
                <page3_2>
                    <label>Page 3.2</label>
                    <uri>page3/page3_2</uri>
                    <role>member</role>
                    <pages>
                    
                        <page3_2_1>
                            <label>Page 3.2.1</label>
                            <uri>page3/page3_2/page3_2_1</uri>
                        </page3_2_1>
                        
                        <page3_2_2>
                            <label>Page 3.2.2</label>
                            <uri>page3/page3_2/page3_2_2</uri>
                            <role>admin</role>
                        </page3_2_2>
                        
                    </pages>
                </page3_2>
                
                <page3_3>
                    <label>Page 3.3</label>
                    <uri>page3/page3_3</uri>
                    <role>special</role>
                    <pages>
                    
                        <page3_3_1>
                            <label>Page 3.3.1</label>
                            <uri>page3/page3_3/page3_3_1</uri>
                            <visible>0</visible>
                        </page3_3_1>
                        
                        <page3_3_2>
                            <label>Page 3.3.2</label>
                            <uri>page3/page3_3/page3_3_2</uri>
                            <role>admin</role>
                        </page3_3_2>
                        
                    </pages>
                </page3_3>
                
            </pages>
        </page3>
        
        <home>
            <label>Home</label>
            <position>-100</position>
        </home>
        
    </nav>
</config>
 */

$config = new Zend_Config_Xml('/path/to/navigation.xml', 'nav');

$nav = new Zym_Navigation($config);