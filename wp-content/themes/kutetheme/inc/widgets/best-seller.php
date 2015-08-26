<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Pages widget class
 *
 * @since 1.0
 */
class Widget_KT_Best_Seller extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
                        'classname' => 'widget_kt_best_seller', 
                        'description' => __( 'Box best seller product on sidebar.', 'kutetheme' ) );
		parent::__construct( 'widget_kt_best_seller', __('KT Best Seller', 'kutetheme' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        $title   = isset( $instance[ 'title' ] )   ? esc_attr($instance[ 'title' ])   : __( 'Best Sellers' );
        $number = ( isset( $instance[ 'number' ] ) && intval( $instance[ 'number' ] ) ) ? $instance[ 'number' ] : 6;
        $perpage = ( isset( $instance[ 'perpage' ] ) && intval( $instance[ 'perpage' ] ) ) ? $instance[ 'perpage' ] : 3;
        
        $meta_query = WC()->query->get_meta_query();
        $params = array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $number,
			'meta_query' 			=> $meta_query,
            'suppress_filter'       => true,
            'orderby'               => 'meta_value_num',
            'meta_key'              => 'total_sales'
		);
        $product = new WP_Query( $params );
        if( $product->have_posts() ):
        ?>
        <!-- block best sellers -->
        <div class="block left-module">
        <?php
        if($title){
            echo $args['before_title'];
            echo $title;
            echo $args['after_title'];
        }
        $i = 1;
        $endtag = $perpage + 1;
        ?>
            <div class="block_content">
                <div class="owl-carousel owl-best-sell" data-loop="true" data-nav = "false" data-margin = "0" data-autoplayTimeout="1000" data-autoplay="true" data-autoplayHoverPause = "true" data-items="1">
                    <?php while($product->have_posts()): $product->the_post(); ?>
                        <?php if( $i==1 ): ?>
                        <ul class="products-block best-sell">
                        <?php endif; ?>
                            <?php wc_get_template_part( 'content', 'special-product-sidebar' ); ?>
                        <?php $i++; ?>
                        <?php if( $i == $endtag ): $i = 1; ?>
                        </ul>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <!-- ./block best sellers  -->
        <?php
        endif;
        wp_reset_query();
        wp_reset_postdata();
        echo $args[ 'after_widget' ];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
        $instance[ 'title' ] = isset( $new_instance[ 'title' ] ) ? $new_instance[ 'title' ] : __( 'Best Sellers' );
        $instance[ 'number' ] = ( isset( $new_instance[ 'number' ] ) && intval( $new_instance[ 'perpage' ] ) ) ? $new_instance[ 'number' ] :6;
        $instance[ 'perpage' ] = ( isset( $new_instance[ 'perpage' ] ) && intval( $new_instance[ 'perpage' ] ) ) ? $new_instance[ 'perpage' ] : 3;
        
		return $instance;
	}

	public function form( $instance ) {
		//Defaults
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Best Sellers' );
        $number = ( isset( $instance[ 'number' ] ) && intval( $instance[ 'number' ] ) ) ? $instance[ 'number' ] : 6;
        $perpage = ( isset( $instance[ 'perpage' ] ) && intval( $instance[ 'perpage' ] ) ) ? $instance[ 'perpage' ] : 3;
	?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'kutetheme'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number:', 'kutetheme'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'perpage' ); ?>"><?php _e( 'Perpage:', 'kutetheme'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name('perpage'); ?>" type="text" value="<?php echo esc_attr($perpage); ?>" />
        </p>
    <?php
	}

}
add_action( 'widgets_init', function(){
    register_widget( 'Widget_KT_Best_Seller' );
} );