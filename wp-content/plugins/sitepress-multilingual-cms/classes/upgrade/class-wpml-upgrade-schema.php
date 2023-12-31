<?php

class WPML_Upgrade_Schema {

	/** @var wpdb $wpdb */
	private $wpdb;

	public function __construct( wpdb $wpdb ) {
		$this->wpdb = $wpdb;
	}

	/**
	 * @param string $table_name
	 *
	 * @return bool
	 */
	public function does_table_exist( $table_name ) {
		return $this->has_results( $this->wpdb->get_results( "SHOW TABLES LIKE '{$this->wpdb->prefix}{$table_name}'" ) );
	}

	/**
	 * @param string $table_name
	 * @param string $column_name
	 *
	 * @return bool
	 */
	public function does_column_exist( $table_name, $column_name ) {
		return $this->has_results( $this->wpdb->get_results( "SHOW COLUMNS FROM {$this->wpdb->prefix}{$table_name} LIKE '{$column_name}'" ) );
	}

	/**
	 * @param string $table_name
	 * @param string $index_name
	 *
	 * @return bool
	 */
	public function does_index_exist( $table_name, $index_name ) {
		return $this->has_results( $this->wpdb->get_results( "SHOW INDEXES FROM {$this->wpdb->prefix}{$table_name} WHERE key_name = '{$index_name}'" ) );
	}

	/**
	 * @param string $table_name
	 * @param string $key_name
	 *
	 * @return bool
	 */
	public function does_key_exist( $table_name, $key_name ) {
		return $this->has_results( $this->wpdb->get_results( "SHOW KEYS FROM {$this->wpdb->prefix}{$table_name} WHERE key_name = '{$key_name}'" ) );
	}


	private function has_results( $results ) {
		return is_array( $results ) && count( $results );
	}

	/**
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $attribute_string
	 *
	 * @return false|int
	 */
	public function add_column( $table_name, $column_name, $attribute_string ) {
		return $this->wpdb->query( "ALTER TABLE {$this->wpdb->prefix}{$table_name} ADD `{$column_name}` {$attribute_string}" );
	}

	/**
	 * @param string $table_name
	 * @param string $column_name
	 * @param string $attribute_string
	 *
	 * @return false|int
	 */
	public function modify_column( $table_name, $column_name, $attribute_string ) {
		return $this->wpdb->query( "ALTER TABLE {$this->wpdb->prefix}{$table_name} MODIFY COLUMN `{$column_name}` {$attribute_string}" );
	}

	/**
	 * @param string $table_name
	 * @param string $index_name
	 * @param string $attribute_string
	 *
	 * @return false|int
	 */
	public function add_index( $table_name, $index_name, $attribute_string ) {
		return $this->wpdb->query( "ALTER TABLE {$this->wpdb->prefix}{$table_name} ADD INDEX `{$index_name}` {$attribute_string}" );
	}

	/**
	 * @param string $table_name
	 * @param array  $key_columns
	 *
	 * @return false|int
	 */
	public function add_primary_key( $table_name, $key_columns ) {
		return $this->wpdb->query( "ALTER TABLE {$this->wpdb->prefix}{$table_name} ADD PRIMARY KEY (`" . implode( '`, `', $key_columns ) . '`)' );
	}

	/**
	 * @param string $table_name
	 * @param string $index_name
	 *
	 * @return false|int
	 */
	public function drop_index( $table_name, $index_name ) {
		return $this->wpdb->query( "ALTER TABLE {$this->wpdb->prefix}{$table_name} DROP INDEX `{$index_name}`" );
	}

	/**
	 * @param string $table_name
	 * @param string $column_name
	 *
	 * @return null|string
	 */
	public function get_column_collation( $table_name, $column_name ) {
		return $this->wpdb->get_var(
			"SELECT COLLATION_NAME FROM INFORMATION_SCHEMA.COLUMNS
			 WHERE TABLE_SCHEMA = '{$this->wpdb->dbname}'
			 	AND TABLE_NAME = '{$this->wpdb->prefix}{$table_name}'
			 	AND COLUMN_NAME = '{$column_name}'"
		);
	}

	/**
	 * @param string $table_name
	 *
	 * @return string|null
	 */
	public function get_table_collation( $table_name ) {
		$table_data = $this->wpdb->get_row(
			$this->wpdb->prepare( 'SHOW TABLE status LIKE %s', $table_name )
		);

		if ( isset( $table_data->Collation ) ) {
			return $table_data->Collation;
		}

		return null;
	}

	/**
	 * We try to get the collation from the posts table first.
	 *
	 * @return string|null
	 */
	public function get_default_collate() {
		$posts_table_collate = $this->get_table_collation( $this->wpdb->posts );

		if ( $posts_table_collate ) {
			return $posts_table_collate;
		} elseif ( ! empty( $this->wpdb->collate ) ) {
			return $this->wpdb->collate;
		}

		return null;
	}

	/**
	 * @param string $table_name
	 *
	 * @return string|null
	 */
	public function get_table_charset( $table_name ) {
		try {
			return $this->wpdb->get_var(
				$this->wpdb->prepare(
					'SELECT CCSA.character_set_name
					FROM information_schema.`TABLES` T,
					information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
					WHERE CCSA.collation_name = T.table_collation
					AND T.table_schema = "%s"
					AND T.table_name = "%s";',
					$this->wpdb->dbname,
					$table_name
				)
			);
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * We try to get the charset from the posts table first.
	 *
	 * @return string|null
	 */
	public function get_default_charset() {
		$post_table_charset = $this->get_table_charset( $this->wpdb->posts );

		if ( $post_table_charset ) {
			return $post_table_charset;
		} elseif ( ! empty( $this->wpdb->charset ) ) {
			return $this->wpdb->charset;
		}

		return null;
	}

	/**
	 * @return wpdb
	 */
	public function get_wpdb() {
		return $this->wpdb;
	}
}
