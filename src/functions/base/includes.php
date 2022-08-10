<?php
/*
 ################################################################################################################
 ##
 ##     SMART INCLUDES WITH VARIABLES
 ##     https://github.com/Smartik89/SMK-Theme-View/blob/master/functions.php
 ##
 ################################################################################################################
 */

// Usage:
// get_template_obj('filename.php', array(
//   'variable_1' => 'Content',
//   'other_variable' => 'Other content'
// ));

if (!class_exists('get_theme_part')) {
	class get_theme_part
	{
		private $args;
		private $file;
		public function __get($name)
		{
			return $this->args[$name];
		}
		public function __construct($file, $args = array())
		{
			$this->file = $file;
			$this->args = $args;
		}
		public function __isset($name)
		{
			return isset($this->args[$name]);
		}
		public function render()
		{
			if (locate_template($this->file)) {
				include(locate_template($this->file)); //Theme Check free. Child themes support.
			}
		}
	}
}
if (!function_exists('get_template_obj')) {
	function get_template_obj($file, $args = array())
	{
		$template = new get_theme_part($file, $args);
		$template->render();
	}
}
