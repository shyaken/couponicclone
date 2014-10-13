<?php

class MCmsBlockForm extends MCmsBlock {

	public $positionRel;
	public $plainText;
	public $wysiwyg;

	public function rules() {
		return array(
			array('title,editorType,content', 'required'),
			array('show,hide,space,position,positionRel', 'safe'),
			array('plainText,wysiwyg,getParams', 'safe'),
		);
	}

	public function attributeLabels() {
		return array(
			'title' => $this->t('Title'),
			'content' => $this->t('Content'),
			'space' => $this->t('Layout Space'),
			'position' => $this->t('Layout Order Position'),
			'show' => $this->t('Show on Page'),
			'hide' => $this->t('Hide on Page'),
			'positionRel' => $this->t('Worklet ID'),
			'editorType' => $this->t('Editor Type'),
			'getParams' => $this->t('Get Params'),
		);
	}

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function beforeValidate() {
		$this->content = $this->editorType ? $this->plainText : $this->wysiwyg;
		return parent::beforeValidate();
	}

	public function beforeSave() {
		if ($this->position == 'before' || $this->position == 'after')
			$this->position.= ':' . $this->positionRel;

		if (!$this->show)
			$this->show = null;
		if (!$this->hide)
			$this->hide = null;

		return parent::beforeSave();
	}

	public function afterSave() {
		parent::afterSave();
		wm()->get('customize.cms.helper')->saveContent('block.' . $this->id, $this->content);
	}

	public function afterFind() {
		$tmp = explode(':', $this->position);
		if (count($tmp) > 1)
			list($this->position, $this->positionRel) = $tmp;
	}

}