<?php

   
// ~/app/controllers/seo_controller.php  
class SeoController extends AppController  
{  
      public $name = 'Seo';
    var $uses = array();  
    var $components = array('RequestHandler');  
  
    function robots()  
    {  
        if (Configure::read('debug'))  
        {  
            Configure::write('debug', 0);  
        }  
  
        $urls = array('/groupsview','groupview','view','/view');  
  
        // ...snip...  
        // fill the $urls array with those you don't  
        // want to be indexed/crawled  
        // for example  
       
  
        $this->set(compact('urls'));  
        $this->RequestHandler->respondAs('text');  
        $this->viewPath .= '/text';  
        $this->layout = 'ajax';  
    }  
}
?>  