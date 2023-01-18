<?php
//index.php?module=test&component=test&action=test
class Framework{

    /**
     * Module : The main running script.
     *
     * @var string
     */
    private $module = '';


    /**
     * Index Module : Front-End Module.
     *
     * @var string
     */
    private $indexModule;


    /**
     * Component : called specific Controller.
     *
     * @var string
     */
    private $component = '';


    /**
     * Action : The called Action from URL it must be in $actions.
     *
     * @var string
     */
    private $action = '';


    /**
     * Parametres : it can be string if it's one condition or an array when there
     * are two or more conditions.
     *
     * @var array
     */
    private $params = array();


    /**
     * Actions : possible used actions throw GET request in specific Component.
     *
     * @var array
     */
    protected $actions = array(

        'Add',

        'Edit',

        'List',

        'Delete',

        'Search',

        'Status',

        'Login',

        'Signup'

    );

    /**
     * Scripts Names just to call them in need .
     *
     * @var array
     */
    public $scripts = array();


    /*
    |--------------------------------------------------------------------------
    | Application Constructor
    |--------------------------------------------------------------------------
    |
    | This construct determines the "environment" your application is currently
    | running in (Module, Component, Action,  Parametres).
    | This may determine how you prefer to configure various services your 
    | application utilizes.
    |
    */
    public function __construct(array $params){

        if(!empty($params)){
            //ignore index/index/???? for Index Modules
            $this->module =  (isset($params[0]) && ($this->isModule($params[0])) && $params[0] !== 'index') 
                ? $params[0] 
            : ((isset($params[0]) && $this->isIndexComponent($params[0]) && $params[0] !== 'index') 
                ? 'Index' 
            : '404');

            $this->indexModule =  ($this->module === 'Index') ? true : NULL;

            $this->component =  (isset($params[1]) && is_null($this->indexModule) && $this->component !== '404') 
                ? $params[1]
            : (($this->indexModule && $this->component !== '404')  
                ? $params[0] 
            : NULL);

            $this->action =  (isset($params[2]) && is_null($this->indexModule) && in_array( ucfirst($params[2]), $this->actions) && $this->component !== '404') 
                ? $params[2] 
            : ((isset($params[1]) && $this->indexModule && in_array(ucfirst($params[1]), $this->actions) && $this->component != '404') 
                ? $params[1] 
            : NULL);

            $this->params = (sizeof($params)>2 && is_null($this->action) && is_null($this->indexModule) && $this->component !== '404') 
                ? array_splice($params, 2) 
            : ((sizeof($params) > 3 && !is_null($this->action) && is_null($this->indexModule) && $this->component !== '404')
                ? array_splice($params, 3) 
            : ((sizeof($params) > 1 && is_null($this->action) && $this->indexModule && $this->component !== '404') 
                ? array_splice($params, 1) 
            : ((sizeof($params) > 2 && !is_null($this->action) && $this->indexModule && $this->component !== '404') 
                ? array_splice($params, 2) 
            : NULL)));

        }else{
            $this->module = 'Index';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Run The Auto Loader
    |--------------------------------------------------------------------------
    |
    | Composer provides a convenient, automatically generated class loader
    | for our application. We just need to utilize it! We'll require it
    | into the script here so that we do not have to worry about the
    | loading of any our classes "manually". Feels great to relax.
    |
    */
    public function run(){

        //--------------------------------------------------------------------------
        // Check if Module exist.
        //--------------------------------------------------------------------------
        if($this->getModule() !== '404'){


            //--------------------------------------------------------------------------
            // Check if Module exist or it's Index Module.
            //--------------------------------------------------------------------------
            if ( is_dir( PATH_MODULES . $this->getModule() ) ){
                

                //--------------------------------------------------------------------------
                // Check if it's Main or/and Index Module.
                //--------------------------------------------------------------------------
                if( empty($this->getComponent()) && empty($this->getAction()) ){


                    //--------------------------------------------------------------------------
                    // Run default Module file Index.php.
                    //--------------------------------------------------------------------------
                    if( $this->isModule(lcfirst($this->getModule())) ){

                        if (file_exists( PATH_MODULES . $this->getModule() . DS . 'Index.php' )){

                            require_once PATH_MODULES . $this->getModule() . DS . 'Index.php';

                        }else{
                            
                            Request::redirect( HTML_PATH_ROOT . '404' );
                        }

                    }

                }else{

                        //--------------------------------------------------------------------------
                        // Check if Backend Module & Run Users Login.
                        //--------------------------------------------------------------------------
                        if ($this->getModule() === 'Backend' &&  $this->isBackendModule( lcfirst($this->getComponent()) )){
                        
                            if (!file_exists( PATH_MODULES . $this->getModule() . DS . $this->getComponent() . DS . $this->getAction() . '.php'))
                            
                                Request::redirect( HTML_PATH_ROOT . '404' );
                                // require_once PATH_MODULES . 'Index/404.php';

                            else

                                require_once PATH_MODULES . $this->getModule() . DS . $this->getComponent() . DS . ucfirst($this->getAction()) . '.php';
                        
                        }else{

                            //--------------------------------------------------------------------------
                            // Run every Module Component.
                            //--------------------------------------------------------------------------
                            if (!file_exists(PATH_MODULES . $this->getModule() . DS . $this->getComponent() . '.php'))

                                Request::redirect( HTML_PATH_ROOT . '404' );
                                // require_once PATH_MODULES . 'Index/404.php';

                            else

                                require_once PATH_MODULES . $this->getModule() . DS . $this->getComponent() . '.php';

                        }
                        //--------------------------------------------------------------------------
                        // Handle Actions.
                        //--------------------------------------------------------------------------
                }
                
            }else{
                
                Request::redirect( HTML_PATH_ROOT . '404' );
                // require_once PATH_MODULES . 'Index/404.php';
            }
        }else{

            Request::redirect( HTML_PATH_ROOT . '404' );
            //require_once PATH_MODULES . 'Index/404.php';
        }
    }


    /**
     * getModule : return the current running Module.
     *
     * @return string
     */
    public function getModule(){
        return ucfirst($this->module);
    }


    /**
     * getIndexModule : return the name of Index (Front-End) Module
     *
     * @return string
     */
    public function getIndexModule(){
        return ucfirst($this->indexModule);
    }

    /**
     * isModule : check existance of an Module .
     *
     * @param string $module
     * @return boolean
     */
    public function isModule($module){
        return in_array($module, Handler::directoryList(PATH_MODULES));
    }

    /**
     * isBackendModule : check existance of an Backend Module .
     *
     * @param string $module
     * @return boolean
     */
    public function isBackendModule($module){
        return in_array($module, Handler::directoryList(PATH_BACKEND));
    }

    /**
     * getComponent : return the Component of current running Module.
     *
     * @return string
     */
    public function getComponent(){
        return ucfirst($this->component);
    }

    /**
     * isIndexComponent : check if the Component is an Index (Front-End) Component
     *
     * @param string $indexComp
     * @return boolean
     */
    public function isIndexComponent($indexComp){
        return in_array( lcfirst($indexComp), Handler::fileList(PATH_FRONTEND));
    }

    /**
     * getAction : return the called Action from Component.
     *
     * @return string
     */
    public function getAction(){
        return ucfirst($this->action);
    }

    /**
     * getParams : return array contain Parametres from URL.
     *
     * @return array
     */
    public function getParams(){
        return $this->params;
    }
    /**
     * getParams : return array contain Parametres from URL.
     *
     * @return array
     */
    public function checkParams($value, $indexOf = null){
        if(is_array($this->params)){
            if(!is_null($indexOf)){
                return (!empty($this->params[$indexOf]) && $this->params[$indexOf] == $value);
            }
        }else{
            return ($this->params == $value);
        }
    }
    /**
     * getParams : return array contain Parametres from URL.
     *
     * @return array
     */
    public function getParamsIndexOf($indexOf = 0){
        if(is_array($this->params)){
                return $this->params[$indexOf];
        }else{
            return $this->params;
        }
    }

    /**
     * appendJS : append an JS file to HTML.
     *
     * @param string $js_file
     * @return void
     */
    public function appendJS($js_file, $data=null){
        echo "<script $data src=\"$js_file\"></script>\n";
    }

    /**
     * appendCSS : append an CSS file to HTML.
     *
     * @param string $css_file
     * @return void
     */
    public function appendCSS($css_file, $data = NULL){
        echo "<link href=\"$css_file\" $data rel=\"stylesheet\" />\n";
    }

    /**
     * requireTPL : call template file php.
     *
     * @param string $_file
     * @return void
     */
    public function requireTPL($_file, $path = PATH_BACKEND_TPL){
        return require_once $path . $_file  . '.tpl.php';
    }
    /**
     * includeTPL : call template file php.
     *
     * @param string $_file
     * @return void
     */
    public function includeTPL($_file){
        return include_once PATH_BACKEND_TPL . $_file . '.tpl.php';
    }
    /**
     * includeTPL : call template file php.
     *
     * @param string $_file
     * @return void
     */
    public function getScriptArray(array $script = NULL){
        if(!is_null($script)){
            $this->scripts = $script;
        }else{
            return $this->scripts;
        }
    }
}
