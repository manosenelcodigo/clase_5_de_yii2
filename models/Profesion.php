<?php

namespace app\models;

use yii\db\ActiveRecord;

class Profesion extends ActiveRecord{
    
    //public $primaryKey = 'cod';
    
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'profesion';
    }
}