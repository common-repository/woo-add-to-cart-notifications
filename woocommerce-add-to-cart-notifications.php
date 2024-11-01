<?php

/*

	Plugin name: Woocommerce Add To Cart Notifications

	Description: Notify the customer when a new item was added to cart.

	Author: <a href="https://penguin-arts.com" target="_blank">PenguinArts</a>

    Version: 1.0.0

*/



class Woocommerce_add_to_cart_notifications{

	/**

	 * Plugin version, used for cache-busting of style and script file references.

	 *

	 * @since   1.0.0

	 *

	 * @var     string

	 */

	 

	const VERSION = '1.0.0';

	

	/**

	 * Unique identifier for the plugin.

	 *

	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should

	 * match the Text Domain file header in the main plugin file.

	 *

	 * @since    1.0.0

	 *

	 * @var      string

	 */

	protected static $plugin_slug = 'woocommerce-notifications-arts';

	

	public function __construct(){

		/*---- Add Page To Wordpress Menu ----*/

		add_action('admin_menu', array($this, 'create_plugin_settings_page' ));

		

		/*---- Register Sections ----*/

		add_action('admin_init', array($this, 'setup_sections'));

		

		/*---- Register Fields ----*/

		add_action('admin_init', array($this, 'setup_fields'));

		

		/*-- Register Scripts --*/

		add_action('wp_footer', array($this, 'register_js_scripts'));

		

		/*-- Register CSS --*/

		add_action('wp_head', array($this, 'register_css'));

		

		/*-- Register CSS To Admin Panel -- */

		add_action('admin_head', array($this, 'register_admin_style'));

		/*-- Ajax Functionality For Products and Template --*/

		add_action('wp_ajax_return_product_details', array($this, 'return_product_details'));

		add_action('wp_ajax_nopriv_return_product_details', array($this, 'return_product_details'));

	} 

	public function create_plugin_settings_page(){ 

		$page_title = 'Woocommerce Notifications';

		$menu_title = 'Notifications';

		$capability = 'manage_options';

		$slug = self::$plugin_slug;

		$callback = array( $this, 'notifications_settings_page' );

		$icon = 'dashicons-admin-plugins';

    	$position = 100;

		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );

	}

	public function notifications_settings_page(){ ?>

		<div class="wrap">

			<h2>Woocommerce Add To Cart Notifications</h2>

			<form method="POST" action="options.php" class="cart_notifications_form">

				<?php 

					settings_fields(self::$plugin_slug);

					do_settings_sections(self::$plugin_slug);

					submit_button();

				?>

			</form>

		</div>

	<?php }

	public function setup_sections() {

	    add_settings_section( 'plugin_section', 'Available Templates', array( $this, 'section_callback' ), self::$plugin_slug );

	}

	public function section_callback( $arguments ) {

	    switch( $arguments['id'] ){

	        case 'plugin_section':

				$template = '<div class="notification_structure">';

				$template .= '<div class="row">';

				$template .= '<div class="column">';

				$template .= '<div class="column_inner">';

				$template .= '<div class="image"><img src="'.plugin_dir_url(__FILE__) . 'assets/images/template_1.jpg"></div>';

				$template .= '<div class="title"><h3>Template 1</h3></div>'; 

				$template .= '<div class="description"><p>Simple template. Notice user + product name</p></div>';

				$template .= '</div>';

				$template .= '</div>';

				

				$template .= '<div class="column">';

				$template .= '<div class="column_inner">';

				$template .= '<div class="image"><img src="'.plugin_dir_url(__FILE__) . 'assets/images/template_2.jpg"></div>';

				$template .= '<div class="title"><h3>Template 2</h3></div>'; 

				$template .= '<div class="description"><p>This template contains the following: Product Image + Product title + Product Price (regular price and sale price) + 2 buttons for cart and continue shopping</p></div>';

				$template .= '</div>';

				$template .= '</div>';

				

				$template .= '<div class="column">';

				$template .= '<div class="column_inner">';

				$template .= '<div class="image"><img src="'.plugin_dir_url(__FILE__) . 'assets/images/template_3.jpg"></div>';

				$template .= '<div class="title"><h3>Template 3</h3></div>'; 

				$template .= '<div class="description"><p>All From Template 2 + Cross Sale Functionality</p></div>';

				$template .= '</div>';

				$template .= '</div>';

				

				$template .= '<div class="column">';

				$template .= '<div class="column_inner">';

				$template .= '<div class="image"><img src="'.plugin_dir_url(__FILE__) . 'assets/images/template_4.jpg"></div>';

				$template .= '<div class="title"><h3>Template 4</h3></div>'; 

				$template .= '<div class="description"><p>All From Template 2 + Up Sale Functionality</p></div>';

				$template .= '</div>';

				$template .= '</div>';

				

				$template .= '</div>';

				$template .= '</div>';

	            echo $template;

	            break;

	    }

	}

	public function setup_fields() {

	   $fields = array(

		    array(

		        'uid' => 'select_popup_template',

		        'label' => 'Select Your Favourite Template',

		        'section' => 'plugin_section',

		        'type' => 'select',

		        'options' => array(

		            'template1' => 'Templete 1',

		            'template2' => 'Templete 2',

		            'template3' => 'Templete 3',

		            'template4' => 'Templete 4',

		        ),

		        'placeholder' => 'Text goes here',

		        'helper' => '',

		        'supplemental' => '',

		        'default' => 'maybe'

		    )

	    );

	    foreach( $fields as $field ){ 

	        add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), self::$plugin_slug, $field['section'], $field );

	        register_setting( self::$plugin_slug, $field['uid'] );

	    }

	}

	public function field_callback( $arguments ) {

	    $value = get_option( $arguments['uid'] ); // Get the current value, if there is one

	    if( ! $value ) { // If no value exists

	        $value = $arguments['default']; // Set to our default

	    }

	

	    // Check which type of field we want

	    switch( $arguments['type'] ){

		    case 'text': // If it is a text field

		        printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );

		        break;

		    case 'textarea': // If it is a textarea

		        printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );

		        break;

		    case 'select': // If it is a select dropdown

		        if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){

		            $options_markup = '';

		            foreach( $arguments['options'] as $key => $label ){

		                $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );

		            }

		            printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );

		        }

		        break;

		}

	

	    // If there is help text

	    if( $helper = $arguments['helper'] ){

	        printf( '<span class="helper"> %s</span>', $helper ); // Show it

	    }

	

	    // If there is supplemental text

	    if( $supplimental = $arguments['supplemental'] ){

	        printf( '<p class="description">%s</p>', $supplimental ); // Show it

	    }

	}

	public function register_js_scripts(){

		wp_enqueue_script('magnificPopup', plugin_dir_url(__FILE__) . 'assets/js/jquery.magnific-popup.min.js', array('jquery'));

		wp_enqueue_script('main', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'));

	}

	public function register_css(){

		wp_enqueue_style('font', plugin_dir_url(__FILE__) . 'assets/css/font-awesome.min.css');

		wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'assets/css/magnific-popup.css');

		wp_enqueue_style('main', plugin_dir_url(__FILE__) . 'assets/css/style.css');

		

	}

	public function return_product_details(){

		$product_id = $_POST['product_id'];

		global $woocommerce;

		$product = wc_get_product( $product_id );

		$cart_url = $woocommerce->cart->get_cart_url();

		if($product){

			$product_name = $product->get_name();

			$product_price = $product->get_price();

			$product_image =  wp_get_attachment_image_src( get_post_thumbnail_id($product->get_id()));

			$product_sale_price = $product->get_sale_price() ;

			$cross_sale_products = $product->get_cross_sell_ids();

			$up_sale_products = $product->get_upsell_ids();

			$data = array( 

				'product_name'  => $product_name,

				'product_image' => $product_image[0],

				'product_price' => $product_price,

				'cart_url'      => $cart_url,

				'template'      => get_option('select_popup_template'),

				'sale_price'    => $product_sale_price,

				'currency'      => get_woocommerce_currency_symbol(),

				'more_products' => ''

			);

			if(get_option('select_popup_template') == 'template3' ){

				if(!empty($cross_sale_products)){

					$cross_sale_template = '<div class="main_container_products">';

					$cross_sale_template .= '<h2>Other products:</h2>';

					$cross_sale_template .= '<div class="row">';

					$i = 1;

					foreach($cross_sale_products as $k => $v){

						if($i <= 3){

							$cross_product = wc_get_product( $v );

							$cross_image = wp_get_attachment_image_src( get_post_thumbnail_id($v));

							$cross_name = $cross_product->get_name();

							$cross_price = $cross_product->get_price();

							$cross_link_details = get_permalink($v);

							

							$cross_sale_template .= '<div class="column">';

							$cross_sale_template .= '<div class="column_inner">';

							$cross_sale_template .= '<div class="background" style="background-image:url('.$cross_image[0].')"><a href="'.$cross_link_details.'"></a></div>';

							$cross_sale_template .= '<div class="product_name"><a href="'.$cross_link_details.'">'.$cross_name.'</a></div>';

							$cross_sale_template .= '<div class="view_details"><a href="'.$cross_link_details.'" class="blue_button">More Details</a></div>';

							$cross_sale_template .= '</div>';

							$cross_sale_template .= '</div>';

						}

						$i++;

					}

					$cross_sale_template .= '</div>';

					$cross_sale_template .= '</div>';

					$data['more_products'] = $cross_sale_template;	

				}

				

			}

			if(get_option('select_popup_template') == 'template4' ){

				if(!empty($up_sale_products)){

					$up_sale_template = '<div class="main_container_products">';

					$up_sale_template .= '<h2>Other products:</h2>';

					$up_sale_template .= '<div class="row">';

					$i = 1;

					foreach($up_sale_products as $k => $v){

						if($i <= 3){

							$up_product = wc_get_product( $v );

							$up_image = wp_get_attachment_image_src( get_post_thumbnail_id($v));

							$up_name = $up_product->get_name();

							$up_price = $up_product->get_price();

							$up_link_details = get_permalink($v);

							

							$up_sale_template .= '<div class="column">';

							$up_sale_template .= '<div class="column_inner">';

							$up_sale_template .= '<div class="background" style="background-image:url('.$up_image[0].')"><a href="'.$up_link_details.'"></a></div>';

							$up_sale_template .= '<div class="product_name"><a href="'.$up_link_details.'">'.$up_name.'</a></div>';

							$up_sale_template .= '<div class="view_details"><a href="'.$up_link_details.'" class="blue_button">More Details</a></div>';

							$up_sale_template .= '</div>';

							$up_sale_template .= '</div>';

						}

						$i++;

					}

					$cross_sale_template .= '</div>';

					$cross_sale_template .= '</div>'; 

					$data['more_products'] = $up_sale_template;	

				}

			}

			echo json_encode($data);

			exit();

		}

	}

	public function register_admin_style(){

		wp_enqueue_style('notifications-plugin', plugin_dir_url(__FILE__) . 'assets/css/admin_style.css');

	}

	/*public function return_popup_template(){

		$template = get_option('select_popup_template');

		return $template;

	}*/

}





new Woocommerce_add_to_cart_notifications();



