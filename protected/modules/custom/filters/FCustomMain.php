<?php
class FCustomMain extends UWorkletFilter
{
	/**
	 * Returns filter configuration.
	 * Ex.:
	 * <pre>
	 * return array(
	 *     'base.index' => array(
	 *         'replace' => 'custom.test'
	 *     ),
	 *     'base.contact' => array(
	 *         'behaviors' => array('custom.test'),
	 *     ),
	 * );
	 * </pre>
	 * 
	 * Above configuration means that:
	 * 'base.index' worklet needs to be replaced with 'custom.test';
	 * 'custom.test' behavior needs to be attached to 'base.contact' worklet.
	 * @return array filter configuration
	 */
	public function filters()
	{
		return array(
		);
	}
}