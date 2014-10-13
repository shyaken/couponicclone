<?php
class MProfileForm extends MProfile {
    public function rules() {
        return array(
            array('name, title, type', 'required'),
            array('enabled', 'safe'),
            array('name, title, type, settings', 'length', 'max'=>250),
        );
    }
    
    public function attributeLabels() {
        return array(
            'name' => $this->t('Name Field'),
            'title' => $this->t('Title'),
            'type' => $this->t('Type'),
            'settings' => $this->t('Settings'),
            'enabled' => $this->t('Enabled'),
        );
    }
    
    public function beforeSave() {
        if($this->settings) {
            $this->settings = serialize($this->settings);
        }
        return parent::beforeSave();
    }


    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}