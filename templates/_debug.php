<?php
/*
$query = $wpdb->prepare("
      SELECT
          i.quantity,
          i.unit,
          i.description
      FROM
          wp_ingredients i
      INNER JOIN
          wp_posts p
      ON
          i.post_id = p.ID
      WHERE
          p.post_name = '%s'
      ORDER BY i.description ASC
", 'beef-with-potatoes-2');

$recipe_posts = $wpdb->get_results( $query, OBJECT );

dump('RECIPE', $recipe_posts);  

if ( $recipe_posts )
{
     
    dump('REQUEST', $_REQUEST);       
      
    echo '<pre>';
      
    $c = 0;
    
    $metric_to_us3 = array(
	'gram' 		=> array('0.0352739619496'	=> 'ounce'),
	'teaspoon' 		=> array('5' 	   	    	=> 'gram'),
	'pound'		=> array('453.59' 	 	=> 'gram'),
	'ounce' 		=> array('28.349523125'  	=> 'gram'),
	'liter'		=> array('1000'   	 	=> 'gram'),
	'kilogram'		=> array('1000'   	 	=> 'gram'),
	'milligram'		=> array('0.001'  	 	=> 'gram'),
	'pint'		=> array('16' 	  	 	=> 'ounce'),
	'quart'		=> array('32' 	  	 	=> 'ounce'),
	'tablespoon' 	=> array('3' 	   	 	=> 'teaspoon'),
	'cup' 		=> array('15.772'  	 	=> 'tablespoon'),
	'millilitres' 	=> array('0.0676280451178'  	=> 'tablespoon'),
	'gallon'		=> array('293.65892' 	 	=> 'tablespoon'),
	'stick'		=> array('3048'   	 	=> 'millilitres'),
    );
      
    foreach ($recipe_posts as $k => $recipe_post) {
	

	if(isset($_REQUEST['serving']) && $_REQUEST['serving'] != '')
	{
	    $servingamt_req = $_REQUEST['serving'];
	    
	} else {
	    
	    $servingamt_req = @$recipe_post->servingamt;
	    
	}
	
	if(isset($_REQUEST['metric']) && $_REQUEST['metric'] != '')
	{
	    
	    $metric = $_REQUEST['metric'];
	    
	} else {
	    
	    $metric = 'us';
	    
	}
	
	$item[$k] = new usToMatric(
	    $recipe_post->quantity,
	    $recipe_post->unit,
	    $recipe_post->description,
	    1,
	    $servingamt_req,
	    $metric
	);
	
	$item[$k]->convert_matric();
	
	$debug[$k] = get_defined_vars();
	
//        
//        
//        echo '<br/>';
//        
//        print_r('unit : '.$item[$k]->get_unit());
//        
//        echo '<br/>';
//        
//        print_r('original_unit : '.$item[$k]->get_original_unit());
//        
//        echo '<br/>';
	
	
	//if($item[$k]->get_is_dev_done() == true && $item[$k]->get_is_countable()):
	
	if($item[$k]->get_is_dev_done() == true && $item[$k]->get_is_countable() == true):
	
	//if($item[$k]->get_is_dev_done() == false):
	
	$c++;
	
	print_r('No : '.$c);
	
	echo '<br/>';
	
	print_r('quantity : '.$item[$k]->get_quantity());
	
	echo '<br/>';
	
	print_r('original_quantity : '.$item[$k]->get_original_quantity());
	
	echo '<br/>';
	
	print_r('unit : '.$item[$k]->get_unit());
	
	echo '<br/>';
	
	print_r('original_unit : '.$item[$k]->get_original_unit());
	
	echo '<br/>';
	
	print_r('servingamt_ori : '.$item[$k]->get_servingamt_ori());
	
	echo '<br/>';
	
	print_r('servingamt_req : '.$item[$k]->get_servingamt_req());
	echo '<br/>';
	
	print_r('metric : '.$item[$k]->get_metric());
	
	echo '<br/>';
	
	print_r('quantity_by_servingamt : '.$item[$k]->get_quantity_by_servingamt());
	
	echo '<br/>';
	
	print_r('unit_by_servingamt : '.$item[$k]->get_unit_by_servingamt());
	
	echo '<br/>';
	
	print_r('quantity_by_servingamt_by_metric : '.$item[$k]->get_quantity_by_servingamt_by_metric());
	
	echo '<br/>';
	
	print_r('unit_by_servingamt_by_metric : '.$item[$k]->get_unit_by_servingamt_by_metric());
	
	echo '<br/>';
	
	print_r('description : '.$item[$k]->get_description());
	
	
	echo '<br/>';
	
	var_dump('is_countable : '.$item[$k]->get_is_countable());
	
	
	echo '<br/>';
	
	if ( array_key_exists( $item[$k]->get_original_unit(), $metric_to_us3 ) )
	{
	    $sorting[$k] = sortingMetricToUs( $metric_to_us3, $item[$k]->get_original_unit() );
	    $convert[$k] = convert_unit( $sorting[$k], $item[$k]->get_original_unit(), $item[$k]->get_unit_by_servingamt_by_metric(), $item[$k]->get_quantity_by_servingamt_by_metric() );
	    dump('convert', $convert[$k]);
	}   
	echo '<hr/>';
	    
	endif;
	
	
	
//            print_r($debug[$k]['item']);
//            print_r($recipe_post);

	
	unset($item);    
	
	unset($debug);
	
    }
  echo '</pre>';
    
}
*/

$metric_to_us = array(
    'gram' 		=> array('0.0352739619496'	=> 'ounce'),
    'ounce' 		=> array('28.349523125'  	=> 'gram'),
    'pint'		=> array('16' 	  	 	=> 'ounce'),
    'quart'		=> array('32' 	  	 	=> 'ounce'),
    'teaspoon' 		=> array('5' 	   	    	=> 'gram'),
    'pound'		=> array('453.59' 	 	=> 'gram'),
    'liter'		=> array('1000'   	 	=> 'gram'),
    'kilogram'		=> array('1000'   	 	=> 'gram'),
    'milligram'		=> array('0.001'  	 	=> 'gram'),
    'tablespoon' 	=> array('3' 	   	 	=> 'teaspoon'),
    'cup' 		=> array('15.772'  	 	=> 'tablespoon'),
    'millilitres' 	=> array('0.0676280451178'  	=> 'tablespoon'),
    'gallon'		=> array('293.65892' 	 	=> 'tablespoon'),
    'stick'		=> array('3048'   	 	=> 'millilitres'),
);

$metric_to_us2 = array(
    'millilitres' 	=> array('0.0676280451178'  	=> 'tablespoon'),
    'teaspoon' 		=> array('5' 	   	    	=> 'gram'),
    'liter'		=> array('1000'   	 	=> 'gram'),
    'pint'		=> array('16' 	  	 	=> 'ounce'),
    'kilogram'		=> array('1000'   	 	=> 'gram'),
    'cup' 		=> array('15.772'  	 	=> 'tablespoon'),
    'pound'		=> array('453.59' 	 	=> 'gram'),
    'ounce' 		=> array('1.97156864583'  	=> 'tablespoon'),
    'gallon'		=> array('293.65892' 	 	=> 'tablespoon'),
    'gram' 		=> array('0.0352739619496'	=> 'ounce'),
    'tablespoon' 	=> array('3' 	   	 	=> 'teaspoon'),
    'quart'		=> array('32' 	  	 	=> 'ounce'),
    'milligram'		=> array('0.001'  	 	=> 'gram'),
);	



function convert_unit( $metric_to_us, $original_unit, $metric_unit, $metric_qty )
{
    $units = array();
    foreach( $metric_to_us as $unit => $metric )
    {
	
	// search metrics by metric unit
	if( $convert_by_metric = array_search( $metric_unit, $metric ) )
	{
	    // do convert unit by metric unit
	    $qty = floatval( $metric_qty ) / floatval( $convert_by_metric );
	    $units[$unit] = _number_format( $qty );
	    
	} else {
	    
	    // get convert unit by converter
	    $_unit = array_values( $metric_to_us[$unit] );
	    $_qty = $units[$_unit[0]];
	    // do convert
	    $qty = floatval( $_qty ) / floatval( key($metric_to_us[$unit]) );
	    $units[$unit] = _number_format( $qty );
	    
	}
    }

    // remove original n metric units
    //unset( $units[$original_unit] );
    //unset( $units[$metric_unit] );
    
    return $units;
}

/*$unit = convert_unit( $metric_to_us, $original_unit, $metric_unit, $metric_qty );
dump('CONVERT', $unit);*/

//$index = array_search('gram', array_keys($metric_to_us));
//$result1 = array_slice($metric_to_us, 0, $index);
//$result2 = array_slice($metric_to_us, $index);
//$c = array_merge($result2, $result1);
//uasort ( $metric_to_us2 , 'sorty');

function sorty($a, $b) {
    $_a = array_values( $a ); $unit1 = $_a[0];
    $_b = array_values( $b ); $unit2 = $_b[0];
    return strnatcmp($unit1, $unit2); 
}

function sortingMetricToUs( $metric_to_us, $original_unit )
{
    $index = array_search( $original_unit, array_keys($metric_to_us) );
    
    $m1 = array_slice( $metric_to_us, 0, $index );
    $m2 = array_slice( $metric_to_us, $index );
    $c = array_merge($m2, $m1);
    uasort ( $c , 'sorty');
    
    $_so[$original_unit] = $c[$original_unit];
    foreach( $c as $k => $v )
    {
	
	$_unit = array_values( $v ); $unit = $_unit[0];
	if( $unit == $original_unit ){
	    
	    $_so[$k] = $v;
	    
	} else if( array_key_exists( $unit, $_so ) ){
	    
	    $_so[$k] = $c[$k];
    
	} 
    }
    
    $d = array_diff_assoc($c, $_so);
    if( $d ){
	foreach( $d as $k => $v )
	{
	    $_unit = array_values( $v ); $unit = $_unit[0];
	    if( array_key_exists( $unit, $_so ) ){
		
		$_so[$k] = $c[$k];
	
	    } 
	}
    }
    
    return $_so;
}

$original_unit1 = 'gram';
$original_qty1 = '200';

$metric_unit1 = 'ounce';
$metric_qty1  = '7.05479238992';

$sorting1 = sortingMetricToUs( $metric_to_us2, $original_unit1 );
$unit1 = convert_unit( $sorting1, $original_unit1, $metric_unit1, $metric_qty1 );
dump('SORTING1', $sorting1);
dump('CONVERT1', $unit1);
echo '<hr/>';

$original_unit2 = 'tablespoon';
$original_qty2 = '4';

$metric_unit2 = 'teaspoon';
$metric_qty2  = '12';

$sorting2 = sortingMetricToUs( $metric_to_us2, $original_unit2 );
$unit2 = convert_unit( $sorting2, $original_unit2, $metric_unit2, $metric_qty2 );
dump('SORTING2', $sorting2);
dump('CONVERT2', $unit2);
