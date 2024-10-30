<?php

/**
 * Error handling class
 * 
 * @package RDPlugin
 * @copyright (c) 2014, Chris Cox
 * @author Chris Cox <chris@renaissance-design.net>
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @version 0.1
 */
if (!class_exists('RDPlugin_Error')) :

	class RDPlugin_Error extends WP_Error {
		public function do_notices() {
			
		}
	}

endif;