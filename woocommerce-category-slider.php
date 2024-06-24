<?php
/*
Plugin Name: WooCommerce Category Slider
Description: [wcs_slider] WooCommerce category slider.
Version: 1.0
Author: alpx.com.tr
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function wcs_enqueue_scripts() {
    wp_enqueue_style( 'owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css' );
    wp_enqueue_style( 'owl-theme', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css' );
    wp_enqueue_script( 'owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'wcs_enqueue_scripts' );

function wcs_category_slider_shortcode() {
    ob_start();

    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
    ));

    if ( ! empty($terms) && ! is_wp_error($terms)) : ?>

        <div class="owl-carousel woocommerce-category-slider owl-theme">
            <?php foreach ( $terms as $term ) : $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ); ?>
                <div class="item">
                    <a href="<?php echo get_term_link( $term ); ?>">
                        <?php if ( $thumbnail_id ) : ?>
                            <?php echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail' ); ?>
                        <?php else: ?>
                            <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr( $term->name ); ?>" />
                        <?php endif; ?>
                        <h2><?php echo esc_html( $term->name ); ?></h2>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif;

    return ob_get_clean();
}
add_shortcode( 'wcs_slider', 'wcs_category_slider_shortcode' );

function wcs_init_owl_carousel() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.owl-carousel').owlCarousel({
                items: 7,
                loop: true,
                margin: 10,
                nav: true,
                responsive: {
                    0: {
                        items: 3
                    },
                    600: {
                        items: 5
                    },
                    1000: {
                        items: 7
                    }
                }
            });
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'wcs_init_owl_carousel', 30 );

?>
