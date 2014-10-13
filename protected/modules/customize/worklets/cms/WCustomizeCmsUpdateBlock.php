<?php

class WCustomizeCmsUpdateBlock extends UFormWorklet {

	public $modelClassName = 'MCmsBlockForm';
	public $primaryKey = 'id';

	public function title() {
		return $this->isNewRecord ? $this->t('Create New Block') : $this->t('Edit Block');
	}

	public function accessRules() {
		return array(
			array('allow', 'roles' => array('administrator')),
			array('deny', 'users' => array('*'))
		);
	}

	public function afterConfig() {
		if ($this->model->editorType)
			$this->model->plainText = $this->model->content;
		else
		{
			$this->model->editorType = 0;
			$this->model->wysiwyg = $this->model->content;
		}
	}

	public function properties() {
		return array(
			'elements' => array(
				'show' => array('type' => 'text', 'hint' =>
					$this->t('Specify the "route" of the page, where the block should appear. Supports regular expressions: {link}', array(
						'{link}' => CHtml::link('http://dev.mysql.com/doc/refman/5.5/en/regexp.html', 'http://dev.mysql.com/doc/refman/5.5/en/regexp.html', array(
							'target' => '_blank',
							'style' => 'display: block',
						)),
					)) . '<br />' .
					$this->t('Example 1 (homepage): base/index') . '<br />' .
					$this->t('Example 2 (recent deals): deal/recent') . '<br />' .
					$this->t('Example 3 (all pages): .*') . '<br />' .
					$this->t('Example 4 (all pages that start with "deal/"): ^deal.*') . '<br />'
				),
				'getParams' => array('type' => 'text', 'class' => 'large', 'hint' => $this->t('Here you can input specific GET parameters which will also be checked in order to show or hide this block.')),
				'hide' => array('type' => 'text', 'hint' =>
					$this->t('Specify the "route" of the page, where the block should NOT appear. Supports regular expressions: {link}', array(
						'{link}' => CHtml::link('http://dev.mysql.com/doc/refman/5.5/en/regexp.html', 'http://dev.mysql.com/doc/refman/5.5/en/regexp.html', array(
							'target' => '_blank',
							'style' => 'display: block',
						)),
					)) . '<br />'
				),
				'title' => array('type' => 'text', 'class' => 'large'),
				'editorType' => array(
					'type' => 'radiolist',
					'items' => array(0 => $this->t('WYSIWYG Editor'), 1 => $this->t('Plain Text')),
					'layout' => "{label}\n<fieldset>{input}</fieldset>\n{hint}",
					'label' => $this->t('Editor Type'),
					'hint' => $this->t('Plain Text editor can accept PHP code.'),
				),
				'<div id="wysiwyg">',
				'wysiwyg' => array('type' => 'UCKEditor', 'layout' => "<div class='clearfix'>{label}</div>{input}\n{hint}",
					'label' => $this->t('WYSIWYG Editor')),
				'</div><div id="plainText">',
				'plainText' => array('type' => 'textarea',),
				'</div>',
				'space' => array('type' => 'dropdownlist', 'items' => array('content' => $this->t('Main Content Area'), 'sidebar' => $this->t('Sidebar')),
					'hint' => $this->t('Each layout has some fixed separated areas, where content appears. Please select such area for your content.')),
				'position' => array('type' => 'dropdownlist', 'items' => array(
						'top' => $this->t('Top'),
						'bottom' => $this->t('Bottom'),
						'before' => $this->t('Before...'),
						'after' => $this->t('After...'),
					), 'hint' => $this->t('The "space" where you want the block to appear may contain several other blocks. You can specify the position of your block within that "space".')),
				'<div id="positionRelLayer">',
				'positionRel' => array('type' => 'text', 'hint' => $this->t('You must specify a worklet ID of some existing block. Your block will appear before/after that block.')),
				'</div>',
				'<hr />',
			),
			'buttons' => array(
				'submit' => array('type' => 'submit',
					'label' => $this->isNewRecord ? $this->t('Create') : $this->t('Save')),
			),
			'model' => $this->model
		);
	}

	public function ajaxSuccess() {
		$message = $this->isNewRecord ? $this->t('Block has been successfully created.') : $this->t('Block has been successfully saved.');
		$json = array(
			'info' => array(
				'replace' => $message,
				'fade' => 'target',
				'focus' => true,
			),
		);
		if ($this->isNewRecord)
			$json['redirect'] = url('/customize/cms/updateBlock', array('ajax' => 1, 'id' => $this->model->id));

		wm()->get('base.init')->addToJson($json);
	}

	public function taskBreadCrumbs() {
		$bC = array();
		$bC[$this->t('Customize')] = url('/customize/list');
		$bC[$this->t('CMS')] = url('/customize/cms/list');
		$bC[] = $this->title;
		return $bC;
	}

	public function taskRenderOutput() {
		$att = 'position';
		$name = CHtml::resolveName($this->model, $att);
		cs()->registerScript(__CLASS__.'.position', 'jQuery("#' . $this->getDOMId() . ' select[name=\'' . $name . '\']").change(function(){
			if($(this).val() == "before" || $(this).val() == "after")
				$("#positionRelLayer").show();
			else
				$("#positionRelLayer").hide();
		});jQuery("#' . $this->getDOMId() . ' select[name=\'' . $name . '\']").change();');

		$att = 'editorType';
		$name = CHtml::resolveName($this->model, $att);
		cs()->registerScript(__CLASS__.'.editorType', 'jQuery("#' . $this->getDOMId() . ' input[name=\'' . $name . '\']:radio").change(function(){
			if($(this).is(":checked"))
			{
				$("#wysiwyg").hide();
				$("#plainText").hide();
				if($(this).val()==0)
					$("#wysiwyg").show();
				else
					$("#plainText").show();
			}
		});jQuery("#' . $this->getDOMId() . ' input[name=\'' . $name . '\']:radio").change();');
		
		parent::taskRenderOutput();
	}

}