<?php
/**
 * Import
 */
/**
	 * Register the 'Custom Column' column in the importer.

	 *

	 * @param array $options

	 * @return array $options

	 */

	 function add_column_to_importer( $options ) {

		$options['composition'] = esc_html__( 'Composition', 'emw' );

		return $options;

	}

   add_filter( 'woocommerce_csv_product_import_mapping_options', 'add_column_to_importer' );

	/**

	 * Add automatic mapping support for 'Custom Column'.

	 *

	 * @param array $columns

	 * @return array $columns

	 */

	 function add_column_to_mapping_screen( $columns ) {

		$columns[ esc_html__( 'Composition', 'emw' ) ] = 'composition';

		return $columns;

	}

  add_filter( 'woocommerce_csv_product_import_mapping_default_columns', 'add_column_to_mapping_screen' );

	/**

	 * Process the data read from the CSV file.

	 *

	 * @param WC_Product $object - Product being imported or updated.

	 * @param array      $data - CSV data read for the product.

	 * @return WC_Product $object

	 */

	function process_import( $object, $data ) {

		if ( isset( $data['composition'] ) ) {

			wp_delete_object_term_relationships( $object->get_id(), 'composition' );

			$compositions = explode( ',', $data['composition'] );

			foreach ( $compositions as $composition ) {

				wp_set_object_terms( $object->get_id(), $composition, 'composition', true );

			}

		}

		return $object;

	}
	add_action( 'woocommerce_product_import_inserted_product_object', 'process_import', 10, 2 );
