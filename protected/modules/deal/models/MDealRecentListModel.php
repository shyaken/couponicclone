<?php
class MDealRecentListModel extends MDeal
{
	public $location;
	public $category;
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->with = array('imageBin','stats','locs','categories');
		$criteria->condition = 't.status IN (1,3)';
		if($this->getModule()->param('categories') >= 0 && $this->category)
		{
			$criteria->addCondition('categories.id=:category');
			$criteria->params[':category'] = $this->category;
		}
		
		if($this->getModule()->param('categories') <= 0)
		{
			$criteria->addCondition('locs.location = 0 OR locs.location = :loc');
			$criteria->params[':loc'] = $this->location;
		}
		
		$criteria->compare('end', '<'.time());
		$criteria->compare('active',1);
		
		return new CActiveDataProvider(__CLASS__, array(
			'criteria'=>$criteria,
			'sort' => array(
				'defaultOrder' => '`end` DESC',
			),
		));
	}
}