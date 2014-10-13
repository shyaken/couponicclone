<?php

class MCmsPageForm extends MCmsPage {

	public $plainText;
	public $wysiwyg;

	public function rules() {
		return array(
			array('title,editorType,content', 'required'),
			array('url', 'unique', 'className' => 'MCmsPage', 'message' => $this->t('This URL is already taken.')),
			array('mainMenu,footerMenu', 'safe'),
			array('plainText,wysiwyg', 'safe'),
		);
	}

	public function attributeLabels() {
		return array(
			'title' => $this->t('Title'),
			'url' => $this->t('URL'),
			'content' => $this->t('Content'),
			'mainMenu' => $this->t('Add to the main menu'),
			'footerMenu' => $this->t('Add to the footer menu'),
			'editorType' => $this->t('Editor Type'),
		);
	}

	public function beforeValidate() {
		$this->content = $this->editorType ? $this->plainText : $this->wysiwyg;
		return parent::beforeValidate();
	}

	public function afterSave() {
		parent::afterSave();
		wm()->get('customize.cms.helper')->saveContent('page.' . $this->id, $this->content);
	}

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

}