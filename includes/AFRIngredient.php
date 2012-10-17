<?php
/**
 *
 * AFR Ingredient
 * 
 * The main class for the control of this plugin
 * Hooks WP API
 * - init register_post_type 
 * - init register_taxonomy
 * - template_redirect
 * - admin_print_scripts
 * - wp_enqueue_scripts
 * - add_meta_box
 * - add_submenu_page
 * - AJAX HANDLER
 * 
 */

/**
 * include substitution class
 * the class to control of recipe ingredient substitutions
 */
include AFR_PLUGIN_INC_DIR . '/SubtitutionIngredient.php';

class AFRIngredient
{
    public $substitution;
    
    // static variable for ingredients unit
    static $unit = array(
	'',
	'teaspoon',
	'tablespoon',
	'cup',
	'gram',
	'ounce',
	'pinch',
	'whole',
	'bunch',
	'can',
	'count',
	'clove',
	'dash',
	'drop',
	'gallon',
	'kilogram',
	'jar',
	'pound',
	'leaf',
	'liter',
	'loaf',
	'milligram',
	'millilitres',
	'packet',
	'piece',
	'pint',
	'quart',
	'scoop',
	'sheet',
	'slice',
	'small',
	'stalk',
	'stick',
	'strip'
    );
    
    // default posts per page is 10
    const POSTS_PER_PAGE = 10;
    
    /**
     * define nonce ( Creates a random, one time use token ) for
     * meta box field
     * AJAX Handler
     */
    const NONCE_META_BOX_FIELD = 'ingredient_meta_box';
    const NONCE_AJAX = 'ajax-ingredient-nonce';    
    
    // define prefix for ingredient meta key
    const PREFIX = 'ingredient-';
        
    public function __construct()
    {	
	// register post type
	add_action( 'init', array(&$this, 'register') );
	// add submenu
	add_action( 'admin_menu', array(&$this, 'sub_menu') );
	// add meta box
	add_action( 'add_meta_boxes', array(&$this, 'add_meta_box') );
	// save meta box
	add_action( 'save_post', array(&$this, 'save_meta_box') );
	
	// include template
	add_filter( 'template_redirect', array(&$this, 'template') );
		
	// admin print scripts
	add_action( 'admin_print_scripts-post.php', array(&$this, 'admin_print_scripts') );
	add_action( 'admin_print_scripts-post-new.php', array(&$this, 'admin_print_scripts') );
	// register scripts
	add_action( 'wp_enqueue_scripts', array(&$this, 'register_scripts') );
	
	/**
	 * setting AJAX in Plugins
	 *
	 * AJAX Handler for jQuery Autocomplete & Search
	 * 
	 * argument 1 :
	 * wp_ajax_nopriv_{ parameter action name in AJAX }
	 * example in javascript:
	 * $.ajax({
		type: 'POST',
		data: { action:'AFR-ajax-search', nonce:'....', // another data parameters },
		....
	    })
	 * 
	 * argument 2 :
	 * { action/method AJAX Handler in class/functions }
	 * in class array( &$this, {method name} )
	 * 
	 * see more documentation at http://codex.wordpress.org/AJAX_in_Plugins
	 *
	 */
	add_action( 'wp_ajax_nopriv_AFR-ajax-search', array(&$this, 'ajax_search') );
	add_action( 'wp_ajax_AFR-ajax-search', array(&$this, 'ajax_search') );
	add_action( 'wp_ajax_nopriv_AFR-ajax-autocomplete', array(&$this, 'ajax_autocomplete') );
	add_action( 'wp_ajax_AFR-ajax-autocomplete', array(&$this, 'ajax_autocomplete') );
	
	// define substitution object
	$this->substitution = new SubtitutionIngredient();	
    }
    
    /**
     * install plugin
     * no action for this method
     */
    public function install(){}
    
    /**
     * Callback init
     * 
     * register
     * 	- post type : ingredient
     * register_post_type
     * @param String post_type
     * @param Array args
     * see more documentation at http://codex.wordpress.org/Function_Reference/register_post_type
     *
     * 	- taxonomy  : ingredient_taxonomy
     * 	register_taxonomy
     * 	@param String taxonomy
     * 	@param String/Array object_type
     *  @param Array args
     * 	see more documentation at http://codex.wordpress.org/Function_Reference/register_taxonomy
     */ 
    public function register()
    {
	// register post type
	register_post_type(
	    'ingredient', 
	    array(
		'labels' => array(
		    'name' => 'Ingredients',
		    'singular_name' => 'Ingredients',
		    'add_new' => 'Add New',
		    'add_new_item' => 'Add New Ingredient',
		    'edit_item' => 'Edit Ingredient',
		    'new_item' => 'New Ingredient',
		    'view_item' => 'View Ingredient',
		    'search_items' => 'Search Ingredients',
		    'not_found' => 'No ingredients found',
		    'not_found_in_trash' => 'No ingredients found in Trash'
		),
		'hierarchical'	=> false,
		'public'	=> true,
		'has_archive'	=> true,
		'rewrite'	=> true,
		'supports'	=> array('title'),
		'menu_icon'	=> AFR_PLUGIN_URL . '/images/icon_ingredient.png',
		//'menu_position'	=> 5
	    )
	);
	
	//flush_rewrite_rules( false );
	
	// register taxonomy
	register_taxonomy(
	    'ingredient_taxonomy',
	    'ingredient',
	    array(
		'hierarchical' 	=> true,
		'label' 	=> 'Categories',
		'query_var'	=> true
	    )
	);
	
    }
    
    /**
     * Callback template_redirect
     *
     * set page template for ingredient post type 
     * see more documentation at http://codex.wordpress.org/Plugin_API/Action_Reference/template_redirect
     */
    public function template( $template )
    {
		global $wp_query, $post, $ingredient_settings;
		
		// if post_type is 'ingredient'
		if ( $wp_query->query_vars["post_type"] == 'ingredient' )
		{
			if ( have_posts() )
			{
			// get ingredient setting for display
			//$settings = unserialize(get_option('ingredient_settings'));
			$display = $ingredient_settings['display'];
			
			// if display is 0, set default display
			$post_per_page = ( isset($display) && intval($display) > 0 ) ? $display : self::POSTS_PER_PAGE;
			// get count all post type ingredient
			$count_ingredients = wp_count_posts('ingredient');
			// get count page
			$count = round( $count_ingredients->publish / $post_per_page );
			
			// get template page -> templates/page-ingredient.php
			$template = $this->_get_template('page', 'ingredient');
			// include the template
			include( $template );
			
			exit();
			}
			else
			{
			// if dont have posts, set 404 error page
			$wp_query->is_404 = true;
			}    
		}
    }
    
    /**
     * Callback admin_print_scripts
     * 
     * admin print scripts
     * - javascript : custom-js (for meta box)
     * - css	    : jquery ui (for meta box)
     * see more documentation at http://codex.wordpress.org/Plugin_API/Action_Reference/admin_print_scripts-(hookname)
     */
    public function admin_print_scripts()
    {
		wp_enqueue_script('custom-js', AFR_PLUGIN_URL.'/js/custom-js.js');
		wp_enqueue_style('jquery-ui-custom', AFR_PLUGIN_URL.'/css/jquery-ui-custom.css');
    }
    
    /**
     * Callback wp_enqueue_scripts
     * 
     * register scripts ( front end )
     */ 
    public function register_scripts()
    {
		// styles
		wp_enqueue_style( 'styles-ingredient', AFR_PLUGIN_URL . '/css/ingredient-styles.css');
		wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.8.20/themes/smoothness/jquery-ui.css'); // CDN
		
		// jquery core
		wp_enqueue_script('jquery');	
		// jquery ui
		wp_deregister_script('jquery-ui');
		wp_register_script('jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js',array('jquery')); // CDN
		wp_enqueue_script('jquery-ui');
		// js ajax functions
		wp_enqueue_script(
			'ajax-ingredient',
			AFR_PLUGIN_URL . '/js/ajax-ingredient.js',
			array('jquery')
		);
		
		/**
		 * Localizes a script, but only if script has already been added
		 * see more documentation at http://codex.wordpress.org/Function_Reference/wp_localize_script
		 */
		wp_localize_script('ajax-ingredient', 'Ajax', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajaxIngredientNonce' => wp_create_nonce( self::NONCE_AJAX ) // create nonce for AJAX
		));
    }
    
    /**
     * Callback add_meta_boxes
     * @param String id
     * @param String title
     * @param callback
     * @param post_type
     * @param context
     * @param priority
     * see more documentation at http://codex.wordpress.org/Function_Reference/add_meta_box
     */ 
    public function add_meta_box()
    {
	add_meta_box(
	    'ingredient-meta',
	    'Ingredients',
	    array( &$this, 'form_meta_box' ),
	    'ingredient',
	    'normal',
	    'low');
    }
    
    /**
     * define meta fields
     *
     * ingredient-amount
     * ingredient-alternative
     * ingredient-healthy
     * ingredient-vegan
     *
     * @return Array
     */
    private function _meta_fields()
    {
	return array(
	    array(
		'id'		=> self::PREFIX . 'amount',
		'label'		=> 'Amount',
		'description'	=> 'Amount of indgredient',
		'type'		=> 'text'
	    ),
	    array(
		'id'		=> self::PREFIX . 'alternative',
		'label'		=> 'Alternative',
		'description'	=> 'Specification of alternative ingredients',
		'type'		=> 'repeatable'
	    ),
	    array(
		'id'		=> self::PREFIX . 'healthy',
		'label'		=> 'Healthy',
		'description'	=> 'Specification of healthy ingredients',
		'type'		=> 'repeatable'
	    ),
	    array(
		'id'		=> self::PREFIX . 'vegan',
		'label'		=> 'Vegan',
		'description'	=> 'Specification of vegan ingredients',
		'type'		=> 'repeatable'
	    )
	);
    }
    
    /**
     * create select unit field
     */
    private function _get_unit_field( $name, $selected = null )
    {
	$options = self::$unit; 
	
	asort( $options ); ?>
	
	<select name="<?php echo $name ?>">
	<?php foreach( $options as $unit ) : $checked = ( $unit == $selected ) ? 'selected' : '' ; ?>
	    <option value="<?php echo $unit ?>" <?php echo $checked ?>><?php echo $unit ?></option>
	<?php endforeach; ?>
	</select>
	
	<?php	
    }
    
    /**
     * Callback meta box
     *
     * create form meta box
     */
    public function form_meta_box()
    {
	global $post;
	
	// create nonce field
	// see more documentaion at http://codex.wordpress.org/Function_Reference/wp_nonce_field
	wp_nonce_field( basename( __FILE__ ), self::NONCE_META_BOX_FIELD );
	?>
	<!-- table -->
	<table class="form-table">
	    <?php 
	    foreach( $this->_meta_fields() as $field ) :
		// get post meta
		$meta = get_post_meta($post->ID, $field['id'], true);
		// unserialize meta (@return Array)
		$meta = unserialize($meta); ?>
		<tr>
		    <th><label for="<?php echo $field['id'] ?>"><?php echo $field['label'] ?></label></th>
		    <td>
		    <?php
		    // create field
		    switch($field['type']) : 
			case 'text' : // input text ?>
			    
			    <input type="text" name="<?php echo $field['id'].'[amount]' ?>" id="<?php echo $field['id'] ?>" value="<?php echo $meta['amount'] ?>" size="1" placeholder="Qty" />
			    <?php echo $this->_get_unit_field($field['id'].'[size]', $meta['size']) ?>
			    <br/>
			    <span class="description"><?php echo $field['description'] ?></span>
			    
			<?php break; 
			
			case 'repeatable' : // repeatable/multiple input text ?>
			    
			    <a class="repeatable-add button" href="#">+</a>
			    <ul id="<?php echo $field['id'] ?>'-repeatable" class="custom_repeatable" style="margin:5px 0;">
			   
			    <?php
			    $i = 0;
			    if ( $meta ) :
				// loop meta
				foreach( $meta as $value ) : ?>
				    <li>
					<span class="sort hndle"><img src="<?php echo AFR_PLUGIN_URL ?>/images/sort-handle.png"/></span>
					<input type="text" name="<?php echo $field['id'].'['.$i.'][qty]' ?>"
						id="<?php echo $field['id'].'['.$i.'][qty]' ?>" size="1"
						placeholder="Qty" value="<?php echo $value['qty'] ?>" value="<?php echo $value['qty'] ?>" />
					
					<?php echo $this->_get_unit_field($field['id'].'['.$i.'][size]', $value['size']) ?>
					
					<input type="text" name="<?php echo $field['id'].'['.$i.'][val]' ?>" id="<?php echo $field['id'].'['.$i.'][val]' ?>" size="50" placeholder="ingredient" value="<?php echo $value['val'] ?>" />
					<a class="repeatable-remove button" href="#">-</a>
				    </li>
				    <?php
				    $i++;
				endforeach; // end loop meta
			    else : ?>
				<li>
				    <span class="sort hndle"><img src="<?php echo AFR_PLUGIN_URL ?>/images/sort-handle.png"/></span>.
				    <input type="text" name="<?php echo $field['id'].'['.$i.'][qty]' ?>" size="1" placeholder="Qty" />
				    
				    <?php echo $this->_get_unit_field($field['id'].'['.$i.'][size]') ?>
				    
				    <input type="text" name="<?php echo $field['id'].'['.$i.'][val]' ?>" size="30" placeholder="ingredient" />
				    <a class="repeatable-remove button" href="#">-</a>
				</li>
			    <?php endif; ?>
			    
			    </ul>
			    <span class="description"><?php echo $field['description'] ?></span>
			    
			    <?php break;
		    
			endswitch; ?>
		    </td>
		</tr>
	    <?php endforeach; // end loop meta_fields ?>
	</table> <!-- end table -->	
	<?php
    }
    
    /**
     * Callback save_posts
     *
     * action save post meta box
     */
    public function save_meta_box()
    {
	global $post;
	
	// if doing autosave, return
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	    return;
	// if don't have nonce, return
	if ( !wp_verify_nonce( $_POST[self::NONCE_META_BOX_FIELD], basename( __FILE__ ) ) )
	    return;
	
	// looping meta fields
	foreach( $this->_meta_fields() as $field )
	{
	    $data = $_POST[trim($field['id'])];
	    // data is array, so serialize to create a string
	    $new = serialize($data);
	    if ($new && $new != $old) {
		update_post_meta($post->ID, $field['id'], $new);
	    } elseif ('' == $new && $old) {
		delete_post_meta($post->ID, $field['id'], $old);
	    }
	}
    }
    
    /**
     * AJAX Search Handler
     *
     * get substituion ingredient by recipe url, name, or keyword ingredient
     *
     * @param String term
     * @param Int show
     * @param String search
     * @param String slug
     * @param String nonce
     * @return HTML
     */
    public function ajax_search()
    {
	global $wpdb, $post, $global_where, $global_order, $global_limit;
	
	// term/alphabet
	$term 	= $_POST['term'];
	// show display
	$show 	= (int) $_POST['show'];
	// search
	$search = $_POST['search'];
	
	// slug from autocomplete
	$slug = $_POST['slug'];
	// nonce
	$nonce = $_POST['nonce'];
     
	// if don't have nonce, set error
	if ( !wp_verify_nonce( $nonce, self::NONCE_AJAX ) )
	    die ( -1 );
	
	// if term is all, set default args
	if( strtolower($term) == 'all' ){
	    $term = '';
	    //$search = '';
	} 
	
	// setter
	$this->substitution->term = $term ;
	$this->substitution->posts_per_page = $show ;
	$this->substitution->search_title = $search;
	
	// set slug from response autocomplete
	if( isset($slug) && !empty($slug) )
	    $this->substitution->slug = $slug;
	
	// check if has slug / search form value is start with http://
	if( $this->substitution->hasSlug() ){
			
	    /**
	     * search susbtitutions by URL
	     */
	    
	    // search subtitute ingredient by slug	    
	    $ingredients = $this->substitution->getSubstitution();
	    
	    //dump('', $ingredients);
	    //exit;
	    
	    // if have substitutions of ingredient
	    if( $ingredients ){
		// prepare sql
		// get post id by post name
		$sql = $wpdb->prepare( "
		    SELECT ID
		    FROM $wpdb->posts
		    WHERE post_name = %s",
		    $this->substitution->slug );
		// get var ID
		$ID = $wpdb->get_var( $sql );
		    
		// set template for substitutions
		// templates/calculate-ingredient.php
		$template = $this->_get_template('loop', 'calculate-ingredient');		    
		include( $template );
		
	    } else {
		// if empty, send message HTML
		echo '<tr><td colspan="4" class="coloumn-center">No ingredients found</td></tr>';
		
	    }
			
	} else {
	    
	    /**
	     * search susbtitutions by
	     * term / alphabhet OR
	     * keyword OR
	     * displaying by page
	     */
	    
	    $args = $this->substitution->getArguments();
	    
	    // if has term / search by alphabet
	    if( isset($args['term']) && !empty($args['term']) )
	    {		
		// run/forward method search by term
		call_user_func( array(&$this, 'search_by_term'),  $args );
		
	    } else {
		
		/* search by keyword OR displaying by page */
		
		/* no-conflict wp-smart-sort plugin filter hook */
		$global_where = ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $args['search_title'] ) ) . '%\'';
		$global_order = $wpdb->posts . '.post_title ASC';
		$global_limit = $args['posts_per_page'];
		/* end no-conflict */
		
		query_posts($args);
		
		if( have_posts() ){
		
		   while(have_posts()){
			
			the_post();
			    
			// set template for substitutions
			// templates/loop-ingredient.php
			$template = $this->_get_template('loop', 'ingredient');			    
			include( $template );
			    
		    } // end while
			 
		    wp_reset_query();		 
		     
		} else {
		    
		    echo '<tr><td colspan="4" class="coloumn-center">No ingredients found</td></tr>';
		    
		} // end if have_posts
		
	    } // end if has term    
			
	} // end if has slug
 
	exit;
    }
    
    /**
     * manually search substituion by term/alphabet
     * 
     * @param Array args
     * @return HTML
     */
    private function search_by_term( $args )
    {
	global $wpdb;
	
	/**
	 * prepare sql
	 * get post id and post title by
	 * post_type AND
	 * taxonomy AND
	 * term
	 */
	$SQL = $wpdb->prepare("
	    SELECT p.ID, p.post_title
	    FROM {$wpdb->posts} p
	    INNER JOIN
		{$wpdb->term_relationships} relationship ON
		p.ID = relationship.object_id
	    INNER JOIN
		{$wpdb->term_taxonomy} tax ON
		relationship.term_taxonomy_id = tax.term_taxonomy_id
	    INNER JOIN
		{$wpdb->terms} term ON
		term.term_id = tax.term_id
	    WHERE
		p.post_type = '%s' AND
		p.post_status = 'publish' AND
		tax.taxonomy = '%s' AND  
		term.slug = '%s'  
	    ORDER BY p.post_title ASC
	    LIMIT 0, 10",
	    $args['post_type'], $args['taxonomy'], $args['term'] );
	
	// if have results
	if( $results = $wpdb->get_results( $SQL ) ) {	    
		
		foreach( $results as $post ) {
		    
		    // set template
		    // templates/loop-ingredient.php
		    $template = $this->_get_template('loop', 'ingredient');		
		    include( $template );	    
		
		}
	     
	} else {
	    
	    // empty results, send message HTML
	    echo '<tr><td colspan="4" class="coloumn-center">No ingredients found</td></tr>';
	    
	}
    }
    
    /**
     * AJAX Handler source autocomplete
     *
     * get recipes by keyword type
     *
     * @param term
     * @param nonce
     * @return JSON
     */
    public function ajax_autocomplete()
    {
	global $wpdb, $post, $global_where, $global_order, $global_limit;
	
	// get search keyword
	$term = $_POST['term'];
	// get nonce
	$nonce = $_POST['nonce'];
	
	// if don't have nonce, send error
	if ( !wp_verify_nonce( $nonce, self::NONCE_AJAX ) )
	    die ( -1 );
		
	// create arguments for query	
	$args = array(
	    'post_type' => 'recipe',
	    'post_status' => 'publish',
	    'posts_per_page' => 5,
	    'orderby' => 'title',
	    'order' => 'ASC' 
	);
		
	// no-conflict wp-smart-sort plugin hook filter
	$global_where = ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( like_escape( $term ) ) . '%\'';
	$global_order = $wpdb->posts . '.post_title ASC';
	$global_limit = 5;
	// end no-conflict
	
	// query arguments
	$recipes = new WP_Query( $args );
	
	$response = array();
	if( $recipes->have_posts() )
	{
	    while( $recipes->have_posts() )
	    {
		$recipes->the_post();
		// set to array
		$response[] = array(
		    'id' => $post->ID,
		    'label' => $post->post_title,
		    'slug' => $post->post_name,
		    'permalink' => get_bloginfo('url') . '/recipe/' . $post->post_name,
		    'args' => $args
		);
	    }
	}
	
	// send to JSON
	echo json_encode( $response );
	
	exit;
    }
    
    /**
     * get file template
     *
     * @param String prefix file
     * @param String file name
     * @param String separator <optional>
     *
     * @return String path file php
     */
    private function _get_template( $prefix, $name, $sep = '-' )
    {
	$file = $prefix . $sep . $name . '.php';
	return AFR_PLUGIN_TPL_DIR . '/' . $file;
    }
    
    /**
     * add_submenu_page
     * 
     * @param parent slug
     * @param page title
     * @param menu title
     * @param capability
     * @param menu slug
     * @param callback
     *
     * see more documentation at http://codex.wordpress.org/Function_Reference/add_submenu_page
     */
    public function sub_menu()
    {
	add_submenu_page(
	    'edit.php?post_type=ingredient',
	    'Ingredient Settings',
	    'Settings',
	    'manage_options',
	    'ingredient-settings',
	    array( &$this, 'settings_page' )
	);
    }
    
    /**
     * display and save settings ingredient page
     */
    public function settings_page()
    {
	// action save
	if( 'save' == $_REQUEST['action'] ){
	    if( $_POST['ingredient'] ){
		// data is array, so serialize to create a string
		$value = serialize($_POST['ingredient']);
		// update option
		update_option('ingredient_settings', $value);
		?>
		<p><div id="message" class="updated" >Settings saved successfully</div></p>
		<?php
	    }
	    // set value form
	    $settings = $_POST['ingredient'];
	} else {
	     // set value form
	     // unserialize option (@return Array)
	    $settings = unserialize(get_option('ingredient_settings'));
	}	
	// dump('',$settings);
	?>
	<div class="wrap">
	    <?php screen_icon( 'options-general' ); ?>
	    <h2>Ingredient Settings</h2>
	    <form method="POST" action="">
		<table class="form-table">
		    <tr valign="top">
			<th scope="row">
			    <label for="show_page">Number of display results</label>
			</th>
			<td>
			    <input type="text" name="ingredient[display]" size="25" value="<?php echo $settings['display'] ?>" />
			</td>
		    </tr>
		    <tr valign="top">
			<th scope="row"><label for="show_page">Show original for substitution</label></th>
			<td>
			    <input type="radio" name="ingredient[original]" value="y" <?php if( $settings['original'] == 'y' ) echo 'checked' ?> /> Yes
			    <input type="radio" name="ingredient[original]" value="n" <?php if( $settings['original'] == 'n' ) echo 'checked' ?> /> No
			</td>
		    </tr>
		</table>
		<p class="submit">
		    <input type="hidden" name="action" value="save" />
		    <input class="button-primary" name="Submit" type="submit" value="Save Options" />		    
		</p>
	    </form>
	</div>
	<?php
    }
}