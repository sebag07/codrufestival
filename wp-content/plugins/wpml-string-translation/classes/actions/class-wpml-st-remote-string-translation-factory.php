<?php

class WPML_ST_Remote_String_Translation_Factory implements IWPML_Backend_Action_Loader, IWPML_Action {

	public function create() {
		return $this;
	}

	public function add_hooks() {
		if ( did_action( 'wpml_tm_loaded' ) ) {
			$this->on_tm_loaded();
		} else {
			add_action( 'wpml_tm_loaded', array( $this, 'on_tm_loaded' ) );
		}
	}

	public function on_tm_loaded() {
		if ( current_user_can( \WPML\LIB\WP\User::CAP_MANAGE_TRANSLATIONS ) ) {
			add_action( 'wpml_st_below_menu', array( 'WPML_Remote_String_Translation', 'display_string_menu' ) );
		}
	}
}
