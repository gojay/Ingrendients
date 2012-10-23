<?php
/**
 *
 * AFR Subtitution of ingredients
 * the class to control of recipe ingredient substitutions
 *
 */

// include ustomatric & fraction class
include_once( AFR_PLUGIN_INC_DIR . '/ustomatric.php');
include_once( AFR_PLUGIN_INC_DIR . '/FractionOp.php');

/*if( file_exists( ABSPATH . 'resources_conversion/ustomatric.php' ) &&
    file_exists( ABSPATH . 'resources_conversion/FractionOp.php' ) ){    
    
    include_once( ABSPATH . 'resources_conversion/ustomatric.php' );   
    include_once( ABSPATH . 'resources_conversion/FractionOp.php');
    
} else {
    
    include_once( AFR_PLUGIN_INC_DIR . '/ustomatric.php');
    include_once( AFR_PLUGIN_INC_DIR . '/FractionOp.php');
    
}*/

class SubtitutionIngredient
{    
    const POST_TYPE     = 'recipe';
    const META_SERVING  = '_recipe_servingamt';
    
    public $ingredients;
    public $slug;
    
    // define default arguments
    public $args = array(
        'taxonomy' 		=> 'ingredient_taxonomy',
        'post_type'		=> 'ingredient',
        'post_status'	=> 'publish',
        'order'			=> 'ASC',
        'orderby'		=> 'title'
    );
    
    // array properties for setter n getter
    public $_properties = array();
    
    // define substitution type
    static $subs_type = array(
        'amount' 	    => 'ingredient-amount',
        'alternative' 	=> 'ingredient-alternative',
        'healthy' 	    => 'ingredient-healthy',
        'vegan' 	    => 'ingredient-vegan'
    );
    
    // private $_servingAmount;
    
    public function __construct() { }
    
    /**
     * Setter
     */ 
    public function __set( $name, $value )
    {		    
        if( empty($value) )
            return;
          
        $value = trim( $value );
        
        if( filter_var( $value, FILTER_VALIDATE_URL ) ){
                
            $path = parse_url( $value, PHP_URL_PATH );
            
            list( $post_type, $slug ) = explode('/', trim($path,'/'));
            
            if( $post_type == self::POST_TYPE ) { 
                $key = 'url';
                $this->slug = $slug;
            } else
                $key = 'error';
            
            $this->_properties[$key] = $slug;
                
        } else {
            $this->_properties[$name] = $value;
        }
    }		
    
    /**
     * Getter
     */ 
    public function __get( $name ){				
        return $this->_properties[$name];
    }
    
    /**
     * check if has slug
     */
    public function hasSlug()
    {
      return ( !empty($this->slug ) ||
             ( isset($this->_properties['url'] ) &&
             !isset($this->_properties['search_title'])) );
    }
    
    /**
     * Get arguments for query posts
     */
    public function getArguments()
    {		    
        $not_in_type = array('url', 'error');
        
        $args = $this->args;
        foreach( $this->_properties as $key => $value )
        {
            // if key is not url or error
            if( !in_array($key, $not_in_type) )
              $args = array_merge($args, array($key => $value));
        }				
        return $args;		    
    }	
    
    /**
     * get results subtitutions of ingredients
     *
     * @param String $slug
     * @return Array. example :
     * [unsubstituted] => Array
        (
            [2] => Array
            (
                [qty] => 0.5
                [unit] => tablespoon
                [desc] => Soy Sauce 
            ),
            ...
        )
     * [substitutions] => Array
        (
            [1] => Array
            (
                [ori] => Array
                (
                    [qty] => 0.5
                    [unit] => cup
                    [desc] => Milk
                )
                [subs] => Array
                (
                    [0] => Array
                    (
                        [ingredient] => Buttermilk
                        [amount] => Array
                        (
                            [amount] => 1/2
                            [size] => cup
                        )
                        [alternative] => Array
                        (
                            [0] => Array
                            (
                                [qty] => 1/2
                                [size] => cup
                                [val] => yogurt OR
                            )
                            [1] => Array
                            (
                                [qty] => 1/2
                                [size] => tablespoon
                                [val] => lemon juice OR
                            )
                            [2] => Array
                            (
                                [qty] => 1/2
                                [size] => cup
                                [val] => vinegar plus enough milk 
                            )
                        )
                        [healthy] => Array
                        (
                            [0] => Array
                            (
                                [qty] => 1/2
                                [size] => cup
                                [val] => yogurt 
                            )
                        )
                        [vegan] => Array
                        (
                            [0] => Array
                            (
                                [qty] => 1/2
                                [size] => cup
                                [val] => soy milk plus
                            )
                            [1] => Array
                            (
                                [qty] => 1.00
                                [size] => tablespoon
                                [val] => lemon juice allowed to sit for 5minutes OR
                            )
                            [2] => Array
                            (
                                [qty] => 1/4
                                [size] => cup
                                [val] => vegan sour cream plus
                            )
                            [3] => Array
                            (
                                [qty] => 1/4
                                [size] => cup
                                [val] => water OR 
                            )
                            [4] => Array
                            (
                                [qty] => 1/2
                                [size] => cup
                                [val] => vegan butter OR
                            )
                            [5] => Array
                            (
                                [qty] => 3/8
                                [size] => cup
                                [val] => vegetable oil
                            )
                        )
                        [ratio] => 0.5
                    )
                )
            )
        )
     */
    public function getSubstitution( $slug = '' )
    {		
        global $ingredient_settings;
        
        // original settings
        $show_original = ( $ingredient_settings['original'] == 'y' );
        
        // if paramter slug is empty, getter slug
        if( empty($slug) )
           $slug = $this->slug;
        
        // get serving amount by slug/post name
        // $this->_servingAmount = $this->getServingRecipe( $slug );
        
        // get recipe ingredients by post_name/slug	
        $ingredients = $this->getIngredientBySlug( $slug );
        
        // create array
        if( !is_array($ingredients) )
            $ingredients = array($ingredients);			
        
        // get substitution list
        $substitutions = $this->getSubstitutionList();
        
        /**
         * get all the existing substitution
         * looping recipe ingredients
         * - matching the recipes by ingredient substitution
         * - If found
         * 	- Add recipe ingredients found into the array 'founds'
         * 	- matching the for ingredient unit recipes by ingredient substitutions unit
         * 	- If the same material unit
         * 		Create a ratio for calculating
         * 		ratio = number of recipe ingredients : the amount of substitution
         * 	-  If the unit is not the same ingredients
         * 		Ratio is -1 / null
         * 	- calculate the quantity of ingredient substitute by the ratio and serving amount
         * 	- add the calculation into the array
         * 	- if ingredient seetings original (display original ingredient) is yes
         * 		add the original ingredient into the array
         * end loop
         *
         * get the recipe ingredients  that not be found, then do the comparison with the array 'founds'
         * - if ingredient seetings original (display original ingredient) is yes
         * 	add recipe ingredients that are not be found in the array 'results'
         *
         * return results
         * 
         */
        $data = array();
        // loop recipe ingredients
        foreach( $ingredients as $key => $ingredient )
        {
            // loop substitution list
            foreach( $substitutions as $sub )
            {
                /**
                 * get substitution
                 * 
                 * matching substitution like ingredient name
                 * Find the position of the first occurrence of a case-insensitive substring in a string
                 * see more documentation at http://php.net/manual/en/function.stripos.php
                 */
                   if ( stripos($sub['ingredient'], trim($ingredient['desc'])) !== false )
                   {	    
                        // add founded ingredient
                        $founds[$key] = $ingredient;
                        
                        // amount
                        $amount = $sub['amount'];
                        
                        // convert quantity substution by ratio
                        $convert = $this->_doConvert( $sub, $ingredient );
                        
                        // $key = $ingredient['desc'];
                        
                        // merging array sub with convert
                        $substitute[$key][] = array_merge( $sub, $convert );
                        
                        if( $show_original ){ // add/set original
                            $data[$key]['ori'] = $ingredient; 
                        }
                        
                        $data[$key]['subs'] = $substitute[$key];
                   
                   } // end if 
               
            } // end loop substitution  
         
        } // end loop recipe
        
        /**
         * get unsubstituted ingredient
         *
         * Compares the keys from array1 against the keys from array2 and returns the difference
         * see more documentation at http://php.net/manual/en/function.array-diff-key.php
         */
        $unsubstituted = array_diff_key( $ingredients, $founds );
        
        // results
        // check ingredient settings, display the original ingredient ?
        if( $ingredient_settings['original'] == 'y' ){		    
            $results['unsubstituted'] = $unsubstituted;
        }
        
        uasort ( $data , array( &$this, 'sortByIngredient' ) ); //sorting by ori description
        $results['substitutions'] = $data;
        
        # note : if you want to see the results, look at the comments in the example array
        
        return $results;
    }
    
    /**
     * get ingredients by post_name/slug recipe
     *
     * @param String $slug
     * @return Array. example:
     * Array
	(
	    [0] => Array
	    (
            [qty] => 50
            [unit] => gram
            [desc] => Beef 
	    ),
	    ...
	)
     *
     */ 	
    public function getIngredientBySlug( $slug )
    {
        global $wpdb;
        
        // prepare sql
        // get ingredients and post name, where post name is "slug" and post status is publish
        $sql = $wpdb->prepare("
            SELECT
                wp_ingredients.quantity,
                wp_ingredients.unit,
                wp_ingredients.description,
                wp_posts.post_name AS title
            FROM
                wp_ingredients
            INNER JOIN
                wp_posts
            ON
                wp_ingredients.post_id = wp_posts.ID
            WHERE
                wp_posts.post_name = '%s' AND
                wp_posts.post_status = 'publish'", $slug);
        // query 
        $posts = $wpdb->get_results( $sql );
        
        $results = array();
        // if have posts
        if( count($posts) > 0 )
        {                
            // get serving amount (int) by slug/post name
            foreach ( $posts as $k => $ingredient )
            {                
                // usToMatric    
                $item = new usToMatric(
                    $ingredient->quantity,	    // qty
                    $ingredient->unit,		    // unit
                    $ingredient->description	    // name/description
                );
                    
                // convert matric
                $item->convert_matric();
                
                // get quantity 
                $quantity = $item->get_quantity_by_servingamt();
                // set number format, if quantity is decimal
                $quantity = ( $this->is_decimal($quantity) ) ? number_format( $quantity, 2 ) : $quantity ;
                
                // set to array
                $results[] = array(
                   'qty'  => ( $quantity == '' ) ? '' : (float) $quantity,
                   'unit' => $item->get_original_unit(),
                   'desc' => $item->get_description(),
                   'converts' => $item->get_convert_by_metric()
                );
            }
        }
        
        # note : if you want to see the results, look at the comments in the example array
        
        return $results;
    }	 		
    
    /**
     * get all substitution list on DB
     *
     * @return Array. example :
     * [601280] => Array
        (
            [ingredient] => Allspice
            [amount] => Array
            (
                [amount] => 1
                [size] => teaspoon
            )
            [alternative] => Array
            (
                [0] => Array
                (
                    [qty] => 1/2
                    [size] => teaspoon
                    [val] => cinnamon
                )
                [1] => Array
                (
                    [qty] => 1/4
                    [size] => teaspoon
                    [val] => ginger AND
                )
                [2] => Array
                (
                    [qty] => 1/4 
                    [size] => teaspoon
                    [val] => cloves
                )
            )
            [healthy] => Array
            (
                [0] => Array
                (
                    [qty] => 
                    [size] => 
                    [val] => 
                )
            )
            [vegan] => Array
            (
                [0] => Array
                (
                    [qty] => 
                    [size] => 
                    [val] => 
                )
            )
        ),
        ...
     *
     */
    private function getSubstitutionList()
    {
        global $post, $global_limit;
        
        $subtitutions = array();
        
        /**
         * get substitution type
         * - amount
         * - alternative
         * - healthy
         * - vegan
         */ 
        $subs_type = self::$subs_type;
        
        /**
         * merging arguments
         * argument as follows :
         * array(
            'taxonomy'  => 'ingredient_taxonomy',
            'post_type' => 'ingredient',
            'order'	    => 'ASC',
            'orderby'   => 'title'
            'posts_per_page' => -1 // get all
         * );
         */
        $args = array_merge( $this->args, array('posts_per_page' => -1) );
        
        /* no-conflict wp-smart-sort plugin hook filter */
            $global_limit = -1;
        /* end no-conflict */
        
        // query the arguments
        $subs = new WP_Query( $args );	
        
        if( $subs->have_posts() )
        {            
            // looping subs
            while( $subs->have_posts() )
            {            
                $subs->the_post();
                
                // looping substitutions type
                foreach( $subs_type as $key => $ingredient )
                {
                    // unserialize post meta to create array
                    $meta = unserialize( get_post_meta($post->ID, $ingredient, true) );
                    // important: the key is post ID
                    $subtitutions[$post->ID]['ingredient'] = $post->post_title;
                    
                    if( $key == 'amount' ){
                        
                        $subtitutions[$post->ID]['amount'] = $meta;
                        
                    } else {
                        if( $meta )
                        {
                            // looping post meta
                            foreach($meta as $item)
                            {
                                $subtitutions[$post->ID][$key][] = $item;
                            }
                        }
                    }
                } // end looping subs
           
            } // end looping posts
        }
        
        # note : if you want to see the results, look at the comments in the example array
        
        return $subtitutions;
    }
    
    /**
     * converting subtitutions
     *
     * @param String $ingredient
     * @param float $ratio
     * @return Array
     */
    private function _doConvert( $ingredient, $ori )
    {		    
        $subs_type = self::$subs_type;
        
        foreach( $subs_type as $key => $_ingredient )
        {
             // if substitution key is amount
            if( $key == 'amount' )
            {
                // create/convert ratio unit
                $ratio = $this->_createUnitRatio( $ingredient['amount']['size'], $ingredient['amount']['amount'], $ori );
                // if have ratio, make the calculation in _calculate method
                $amount = ( $ratio !== -1 )
                        ? call_user_func(array(&$this, '_calculate'), $ingredient[$key]['amount'], $ratio)
                        : '' ;
                // replace amount with the calculation amount
                $results[$key] = array_replace(
                    $ingredient[$key],
                    array( 'amount' => $amount )
                );
                
            } else {
            
                foreach( $ingredient[$key] as $k => $alternate )
                {
                    // create/convert ratio unit
                    $ratio = $this->_createUnitRatio( $alternate['size'], $alternate['qty'], $ori );
                    // if have ratio, make the calculation in calculation method, send null otherwise
                    $qty = ( $ratio !== -1 )
                        ? call_user_func(array(&$this, '_calculate'), $alternate['qty'], $ratio)
                        : '';		
                    // replace amount with the calculation amount
                    $results[$key][$k] = array_replace_recursive(
                        $alternate,
                        array( 'qty' => $qty  )
                    );
                }
            
            }
             // if have ratio, merging results array ratio
            if( $ratio != -1 )
                $results = array_merge($results, array('ratio' => $ratio));
        }
        
        return $results;
    }
    
    /** =================================================================================================== *
     ********************************************** Calculation *********************************************
     ** =================================================================================================== */
    
    /**
     * create ratio between ingredient n subtitute
     * 
     * original qty / substitute unit
     * 
     * @param array amount
     * @param array ingredient
     * return float/-1;
     */
    private function _createUnitRatio( $unit, $qty, $ingredient )
    {	
        if( stripos( $unit, trim( $ingredient['unit'] )) !== false ) { // ingredient unit == substitute unit
            
            $ratio = floatval( $ingredient['qty'] / $qty );
            
        } elseif( count($ingredient['converts']) > 0 && array_key_exists( $unit, $ingredient['converts'] ) ) { // unit is converted ?
            
            $ratio = floatval( $ingredient['converts'][$unit] / $qty );
            
        } else {
            
            $ratio = -1; // null/false
            
        }
        
        return $ratio;
    }
    
    /**
     * calculation ingredient
     *
     * ( quantity * ratio * serving amount )
     *
     * @param int
     * @param float
     * @return number
     */
    private function _calculate( $qty, $ratio )
    {		    
        if( empty($qty) ) return;
        
        // convert to float
        $qty = $this->_format($qty);
        
        // calculate
        $val = $qty * $ratio;
        
        // convert to string( fraction )			
        return $this->_format($val, false);
    }
    
    
    /**
     * set format value 
     * float for the calculation and
     * fraction for the result
     */
    private function _format( $val, $toFloat = true )
    {
        // create object fraction
        $obj = Math_FractionOp::simplify(new Math_Fraction($val));
        // check is not fraction, return val
        if( !$this->_isFraction( $obj ) )
            return $val ; 
        
        return ( $toFloat ) ? $obj->toFloat() : $this->_fractionOP( $obj );
    }
    
    /**
     * check is fraction ?
     * @param OBJECT
     * @return boolean
     */
    private function _isFraction( $obj )
    {
        $num = $obj->getNum();
        $den = $obj->getDen();
        
        $len = ( strlen($num) > 2 || strlen($den) > 2 );
        
        return ( $num < $den ) && $num != 0 || $len ;
    }
    
    /**
     * set output fraction
     * display float number if fraction is high value (ex: 3333/55555)
     * send fraction otherwise
     */
    private function _fractionOP( $obj )
    {        
        $num = $obj->getNum();
        $den = $obj->getDen();
        
        $len = ( strlen($num) > 1 || strlen($den) > 1 );
        if( $len )
            return _number_format( $obj->toFloat(), 2 );
            
        return $obj->toString();
    }
    
    /**
     * is decimal ?
     *
     * @param Int
     * @return boolean
     */
    private function is_decimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }
    
    public function sortByIngredient( $a, $b )
    {
        $ingredient1 = $a['ori']['desc'];
        $ingredient2 = $b['ori']['desc'];
        return strnatcmp( $ingredient1 , $ingredient2 ); 
    }
}