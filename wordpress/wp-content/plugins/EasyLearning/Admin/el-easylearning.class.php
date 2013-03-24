<?php
class EasyLearning {

	static function createPostTypes () {
		global $postTypes;
		foreach ($postTypes as $key => $postType) {
			register_post_type($key, $postType);
		}
		flush_rewrite_rules();

	}

	static function setupCoursesViewColumns ($columns) {

		$columns = array(
			'cb' 		=> '<input type="checkbox" />',
			'title' 	=> __('Title'),
		);

		return $columns;
	}

	static function setupCoursesViewColumnCategories ($column, $post_id) {

		switch ($column) {

			case 'dueDate':

			break;

		}

	}


}
?>