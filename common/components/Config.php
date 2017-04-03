<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;
use Yii;
use yii\base\Component;
use Exception;
use common\models\Config as ModelConfig;
use yii\helpers\ArrayHelper;

/**
 * This component get the configs from db and return ir
 *
 * @author fabioferreira
 */
class Config extends Component{
    
    private $params;


    //put your code here
    
    public function init(){
        parent::init();
        
        $this->params = ArrayHelper::map(ModelConfig::getConfigs(), 'attribute', 'value');
    }
    
   /**
    * return a parameter from the config array;
    * @param string $param
    * @param boolean $isArray is is set to true it converts the string to an array
    * @return element from array or array
    */
    public function getParam($param, $isArray = false)
    {
        if($isArray){
            return  isset($this->params[$param]) ? (array) json_decode($this->params[$param], true) : [];
        }
        return isset($this->params[$param]) ? $this->params[$param] : null;
    }


}
