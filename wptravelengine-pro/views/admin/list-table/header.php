<?php
/**
 * Plugin List Table header.
 *
 * @since 1.0.0
 */

/**
 * @var string $search
 * @var array $categories
 * @var string $category
 */

// Ensure $categories is a valid array to prevent fatal errors.
if ( ! is_array( $categories ) ) {
	$categories = array();
}

array_unshift( $categories, (object) array(
	'slug'  => 'all',
	'name'  => __( 'All' ),
	'count' => array_sum( array_column( $categories, 'count' ) ),
) );
?>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="arrow" viewBox="0 0 20 20" fill="none">
        <path d="M3.33337 10H16.6667M16.6667 10L11.6667 5M16.6667 10L11.6667 15" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
    </symbol>
</svg>

<div class="wpte-addons__header">
    <div class="wpte-addons__header-top">
        <h3><?php echo __( "Extensions" ); ?></h3>
        <button class="button" data-refresher>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.12218 16.4729C9.12146 17.3599 11.5027 17.3117 13.5418 16.1344C16.9297 14.1784 18.0905 9.84627 16.1345 6.45837L15.9262 6.09752M3.86543 13.5418C1.90942 10.1539 3.07021 5.8218 6.45811 3.86579C8.49727 2.68848 10.8785 2.64031 12.8778 3.52728M2.07785 13.6115L4.35456 14.2216L4.9646 11.9448M15.0355 8.05488L15.6456 5.77818L17.9223 6.38822"
                    stroke="#3E4B50" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
			<?php echo __( 'Refresh' ); ?>
        </button>
        <div action="" class="wpte-addons__search-wrap">
            <input class="wpte-addons__search-input" placeholder="Search" type="search"
                   value="<?php echo esc_attr( $search ); ?>">
        </div>
    </div>
    <ul class="wpte-addons__filter-list">
		<?php
		foreach ( $categories as $_category ) :
			?>
            <li class="<?php echo esc_attr( $category === $_category->slug ? 'active' : '' ); ?>">
                <a href="<?php echo esc_url( add_query_arg( [
					'tab'      => 'wptravelengine',
					'category' => $_category->slug,
				], is_multisite() ? network_admin_url( 'plugin-install.php' ) : admin_url( 'plugin-install.php' ) ) ); ?>">
					<?php echo $_category->name; ?>
                    <span><?php echo esc_html( $_category->count ); ?></span>
                </a>
            </li>
		<?php endforeach; ?>
    </ul>
</div>
