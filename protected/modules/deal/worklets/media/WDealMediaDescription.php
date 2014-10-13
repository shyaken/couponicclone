<?php
class WDealMediaDescription extends UFormWorklet
{
    public $modelClassName='MDealMediaDescriptionForm';
    public $primaryKey='id';
	public $space = 'inside';
	
	public function title()
	{
		return $this->t('Slide Description');
	}
	
    public function ajaxSuccess()
	{
        wm()->get('base.init')->addToJson(array(
			'content' => array(
				'append' => CHtml::script('jQuery("#MediaDescription_'
					. $this->model->id.'").html("'.CJavaScript::quote($this->model->description).'");
					$.uniprogy.dialogClose();'),
			),
        ));
    }
	
    public function properties()
    {
		return array(
			'elements'=>array(
				'description'=>array('type' => 'text', 'class' => 'large', 'layout' => '<div class="txt-center">{input}</div>'),
			),
			'buttons'=>array(
				'save'=>array(
					'type'=>'submit',
					'label'=>'Save',
				),
			),
			'model' => $this->model,
		);
    }
    
}

?>
