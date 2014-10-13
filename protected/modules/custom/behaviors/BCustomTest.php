<?php
class BCustomTest extends UWorkletBehavior
{
	public function beforeAnything()
	{
		// you can execute any code before master method
	}
	
	public function afterAnything($currentResult)
	{
		// you can execute any code after master method
		// and even modify current result
	}
	
	public function taskNew()
	{
		// you can even create new tasks and they will be automatically
		// added to the original worklet
	}
}