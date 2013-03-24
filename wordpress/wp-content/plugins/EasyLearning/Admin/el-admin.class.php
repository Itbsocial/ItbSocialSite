<?php
class ELAdmin {

	static function install () {
		add_menu_page("administrator",  array('ELAdmin', 'index'));
	}


}
?>