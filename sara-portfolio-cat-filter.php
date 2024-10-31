<?php
/**
 * Plugin Name:       Portfolio Cat Filter Gtb Block
 * Plugin URI:
 * Description:       Simple Portfolio Category Filter Gutenberg Block
 * Version:           1.0.0
 * Author:            Hasnat Masum
 * Author URI:        https://themespassion.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sara-portfolio-cat-filter
 * Domain Path:       /languages
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function spcf_sara_load_textdomain() {
    require_once 'carbon-fields/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
    load_plugin_textdomain( 'sara-portfolio-cat-filter', false, dirname( __FILE__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'spcf_sara_load_textdomain' );

// Enqueue assets
function spcf_sara_plugin_assets() {
    wp_enqueue_style( 'spcf_style', plugins_url( 'assets/css/style.css', __FILE__ ), null, time() );
    wp_enqueue_script( 'spcf_isotope', plugins_url( 'assets/js/isotope.pkgd.min.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'spcf_ptf_js', plugins_url( 'assets/js/sara_portfolio.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}
add_action( 'enqueue_block_assets', 'spcf_sara_plugin_assets' );
// Register gutenberg block
function spcf_sara_gtb_block() {
    Block::make( __( 'Sara Portfolio Block', 'sara-portfolio-cat-filter' ) )
        ->set_description( __( 'This block for portfolio category filtering.', 'sara-portfolio-cat-filter' ) )
        ->set_icon( 'schedule' )
        ->add_fields( array(
            Field::make( 'select', 'spcf_sara_col_layout', __( 'Column Layout', 'sara-portfolio-cat-filter' ) )
                ->add_options( array(
                    'p-item-col-4' => __( '4 Columns', 'sara-portfolio-cat-filter' ),
                    'p-item-col-3' => __( '3 Columns', 'sara-portfolio-cat-filter' ),
                    'p-item-col-2' => __( '2 Columns', 'sara-portfolio-cat-filter' ),

                ) ),
        ) )
        ->add_fields( array(
            Field::make( 'complex', 'spcf_sara_menu_item', __( 'Menu Item', 'sara-portfolio-cat-filter' ) )
                ->add_fields( array(
                    Field::make( 'text', 'spcf_sara_cat_name', __( 'Category name', 'sara-portfolio-cat-filter' ) )
                        ->set_attribute( 'placeholder', 'Write One Category name similar portfolio item category name' ),
                ) ),
        ) )
        ->add_fields( array(
            Field::make( 'complex', 'spcf_sara_portfolio_item', __( 'Portfolio Item', 'sara-portfolio-cat-filter' ) )
                ->add_fields( array(
                    Field::make( 'image', 'spcf_sara_image', __( 'Upload Image', 'sara-portfolio-cat-filter' ) ),
                    Field::make( 'text', 'spcf_sara_image_link', __( 'Image Link', 'sara-portfolio-cat-filter' ) )
                        ->set_attribute( 'placeholder', 'https://google.com' ),
                    Field::make( 'text', 'spcf_sara_item_cat_name', __( 'Category name', 'sara-portfolio-cat-filter' ) )
                        ->set_attribute( 'placeholder', 'Write Category name' ),
                ) ),
        ) )

        ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {

            ?>

<?php
if ( !empty( $fields['spcf_sara_menu_item'] ) ):
            ?>
<div class="sara-btn-wraper">
    <button type="button" class="active" data-filter="*"><?php _e( 'ALL', 'sara-portfolio-cat-filter' );?></button>

    <?php foreach ( $fields['spcf_sara_menu_item'] as $menu_item ): ?>
    <?php if ( !empty( $menu_item['spcf_sara_cat_name'] ) ): ?>
    <button type="button"
        data-filter=".<?php echo esc_attr( strtolower( $menu_item['spcf_sara_cat_name'] ) ); ?>"><?php echo esc_html( strtoupper( $menu_item['spcf_sara_cat_name'] ) ); ?></button>
    <?php endif;?>
    <?php endforeach;?>
</div>
<?php endif;?>

<?php if ( !empty( $fields['spcf_sara_portfolio_item'] ) ): ?>
<div class="sara-container">
    <?php foreach ( $fields['spcf_sara_portfolio_item'] as $portfolio_item ): ?>
    <div
        class="<?php echo $fields['spcf_sara_col_layout']; ?> mix <?php echo esc_attr( strtolower( $portfolio_item['spcf_sara_item_cat_name'] ) ); ?>">
        <a href="<?php echo ( !empty( $portfolio_item['spcf_sara_image_link'] ) ? esc_url( $portfolio_item['spcf_sara_image_link'] ) : '#' ); ?>"
            target="_blank">
            <div><span><?php _e( 'View Site', 'sara-portfolio-cat-filter' );?></span></div>
            <?php echo wp_get_attachment_image( $portfolio_item['spcf_sara_image'], 'large' ); ?>
        </a>
    </div>

    <?php endforeach;?>
</div>
<?php endif;?>
<?php
} );
}
add_action( 'carbon_fields_register_fields', 'spcf_sara_gtb_block' );
