<?php
/**
 * Point the snapshot's content at this Playground session's URL.
 * The snapshot was taken from a local preview at http://127.0.0.1:9401.
 */
require_once '/wordpress/wp-load.php';

$old   = 'http://127.0.0.1:9401';
$new   = untrailingslashit( site_url() );
$pairs = array(
	$old                        => $new,
	str_replace( '/', '\/', $old ) => str_replace( '/', '\/', $new ),
	rawurlencode( $old )        => rawurlencode( $new ),
);

$replace = function ( $value ) use ( &$replace, $pairs ) {
	if ( is_string( $value ) ) {
		return strtr( $value, $pairs );
	}
	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) {
			$value[ $k ] = $replace( $v );
		}
	}
	return $value;
};

global $wpdb;
$like = '%' . $wpdb->esc_like( '127.0.0.1:9401' ) . '%';

foreach ( array( 'post_content', 'post_excerpt', 'guid' ) as $col ) {
	foreach ( $pairs as $from => $to ) {
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->posts} SET {$col} = REPLACE({$col}, %s, %s)", $from, $to ) );
	}
}

// Serialized and JSON values are decoded, replaced and re-encoded, so lengths stay valid.
$tables = array(
	array( $wpdb->postmeta, 'meta_id', 'meta_value' ),
	array( $wpdb->termmeta, 'meta_id', 'meta_value' ),
	array( $wpdb->usermeta, 'umeta_id', 'meta_value' ),
	array( $wpdb->options, 'option_id', 'option_value' ),
);
$changed = 0;
foreach ( $tables as $t ) {
	list( $table, $key, $col ) = $t;
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT {$key} AS k, {$col} AS v FROM {$table} WHERE {$col} LIKE %s OR {$col} LIKE %s", $like, '%' . $wpdb->esc_like( '127.0.0.1%3A9401' ) . '%' ) );
	foreach ( $rows as $row ) {
		$value = maybe_serialize( $replace( maybe_unserialize( $row->v ) ) );
		if ( $value !== $row->v ) {
			$wpdb->update( $table, array( $col => $value ), array( $key => $row->k ) );
			$changed++;
		}
	}
}

wp_cache_flush();
if ( class_exists( '\Elementor\Plugin' ) ) {
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
echo "Rewrote {$changed} values to {$new}\n";
