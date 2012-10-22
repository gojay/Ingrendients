<?php

include_once('evalmath.class.php');

include_once ('Fraction.php');

class usToMatric {
    
    private $is_debug = false;
    private $is_dev_done = false;
    private $format_pattern = false;
    private $quantity = '';
    private $unit = '';
    private $description = '';
    private $servingamt_ori = '';
    private $servingamt_req = '';
    private $metric = '';
    private $is_have_original_unit = false;
    private $is_countable = false;
    private $original_unit = '';
    private $original_quantity = '';
    private $quantity_by_servingamt = '';
    private $unit_by_servingamt = '';
    private $quantity_by_servingamt_by_metric = '';
    private $unit_by_servingamt_by_metric = '';
    public $m = null;
    
    public $metric_to_us = array(
				'millilitres' 	=> array('0.0676280451178'  	=> 'tablespoon'),
				'teaspoon' 	=> array('5' 	   	    	=> 'gram'),
				'liter'		=> array('1000'   	 	=> 'gram'),
				'pint'		=> array('16' 	  	 	=> 'ounce'),
				'kilogram'	=> array('1000'   	 	=> 'gram'),
				'cup' 		=> array('15.772'  	 	=> 'tablespoon'),
				'pound'		=> array('453.59' 	 	=> 'gram'),
				'ounce' 	=> array('28.349523125'  	=> 'gram'),
				'gallon'	=> array('293.65892' 	 	=> 'tablespoon'),
				'gram' 		=> array('0.0352739619496'	=> 'ounce'),
				'tablespoon' 	=> array('3' 	   	 	=> 'teaspoon'),
				'quart'		=> array('32' 	  	 	=> 'ounce'),
				'stick'		=> array('3048'   	 	=> 'millilitres'),
				'milligram'	=> array('0.001'  	 	=> 'gram'),
			    );
	
    public $us_to_metric = array(
				'teaspoon' => array('4.929' => 'millilitres'),
				'tablespoon' => array('14.787' => 'millilitres'),
				'ounce' => array('29.574' => 'millilitres'),
				'cup' => array('236.588' => 'millilitres'),
				'pint' => array('473.176' => 'millilitres'),
				'quart' => array('946.353' => 'millilitres'),
				'gallon' => array('3.785' => 'liter'),
				'pound' => array('453.592' => 'gram'),
				'drop' => array('0.0649' => 'millilitres'),
				'dash' => array('0.308' => 'millilitres'),
				'pinch' => array('0.616' => 'millilitres')
                            );
                                    
                                    
    public $metrics_alias = array(
                                    'teaspoon'	=> array(
						    'tsp',
						    'generous tsp',
						    'heap tsp',
						    'heaped tsp',
						    'heaping tsp',
						    'level tsp',
						    'scant teaspoon',
						    'scant tsp',
						    'teaspoon',
						    'teaspoons',
						    'treaspoon',
						    'tsp hot pepper'
                                                ),
                                    'tablespoon'=> array(
						    'tbsp',
						    'Tbsp',
						    'tablespoons',
						    'heaped tbsp',
						    'heaping tbsp',
						    'scant tbsp',
						    'tbsp sesame',
                                                ),
                                    'ounce' 	=> array(
                                                    'oz',
                                                    'loz',
                                                    'fl oz',
                                                    'fl ozs',
                                                    'lb',
                                                    'lb of',
                                                    'One  lb',
                                                    'lbs',
                                                    'ounces',
                                                    'ounce',
                                                    'ounce packages',
                                                    'oz can',
                                                    'oz each',
                                                    'ozs',
                                                ),
                                    'cup' 	=> array(
						    'cup',
						    'cup of',
						    'cups',
						    'cups of',
						    'heaping cup',
						    'scant cup',
						),
                                    'pint' 	=> array(
						    'pint',
						),
                                    'quart' 	=> array(
						    'quart',
						    'quartered',
						    'quarts',
						),
                                    'gallon' 	=> array(
						    'gallon',
						),
                                    'pound' 	=> array(
						    'pound',
						    'pounds',
						),
                                    'drop' 	=> array(
						    'drop',
						),
                                    'dash'	 => array(
						    'dash',
						    'dashes',
						    'heavy dashes',
						),
                                    'pinch' 	=> array(
						    'pinch',
						    'pinch of',
						    'pinches of',
						),
                                    );
    
    /*
    
    public $us_to_metric = array(
                                    'teaspoon' => array('4.929' => 'mL'),
                                    'tablespoon' => array('14.787' => 'mL'),
                                    'ounce' => array('29.574' => 'mL'),
                                    'cup' => array('236.588' => 'mL'),
                                    'pint' => array('473.176' => 'mL'),
                                    'quart' => array('946.353' => 'mL'),
                                    'gallon' => array('3.785' => 'L'),
                                    'pound' => array('453.592' => 'gram'),
                                    'drop' => array('0.0649' => 'mL'),
                                    'dash' => array('0.308' => 'mL'),
                                    'pinch' => array('0.616' => 'mL'),
                                    );
    
    public $immeasurables_alias = array(
                                    'Whole' => array(
                                                        'whole',
                                                        ),
                                    'Bunch' => array(
                                                        'bunch',
                                                        'bunches',
                                                        ),
                                    'Can' => array(
                                                        'can',
                                                        ),
                                    'Count' => array(
                                                        'count',
                                                        ),
                                    'Clove' => array(
                                                        'cl',
                                                        'clove',
                                                        ),
                                    'Jar' => array(
                                                        'jar',
                                                        ),
                                    'Leaf' => array(
                                                        'leaf',
                                                        ),
                                    'Packet' => array(
                                                        'packet',
                                                        ),
                                    'Piece' => array(
                                                        'piece',
                                                        ),
                                    'Scoop' => array(
                                                        'scoop',
                                                        ),
                                    'Sheet' => array(
                                                        'sheet',
                                                        ),
                                    'Slice' => array(
                                                        'slice',
                                                        ),
                                    'Small' => array(
                                                        'small',
                                                        ),
                                    'Stalk' => array(
                                                        'stalk',
                                                        ),
                                    'Stick' => array(
                                                        'stick',
                                                        'sticks',
                                                        ),
                                    'Strip' => array(
                                                        'strip',
                                                        ),
                                    'Bowl' => array(
                                                        'bowl',
                                                        'bowls',
                                                        ),
                                    'Cup' => array(
                                                        'C',
                                                        'cup',
                                                        'cups',
                                                        'cup of',
                                                        'cup or more',
                                                        ),
                                    'Bag' => array(
                                                        'bag',
                                                        'bag of',
                                                        'cups',
                                                        ),
                                    'Big' => array(
                                                        'big',
                                                        ),
                                    'Block' => array(
                                                        'block',
                                                        ),
                                    'Bottle' => array(
                                                        'bottle',
                                                        ),
                                    'Box' => array(
                                                        'box',
                                                        ),
                                    'Brick' => array(
                                                        'brick',
                                                        'brick of',
                                                        ),
                                    'Bundle' => array(
                                                        'bundle',
                                                        ),
                                    'Chopped' => array(
                                                        'chopped',
                                                        ),
                                    'Dash' => array(
                                                        'dash',
                                                        ),
                                                        
                                    );
    
    public $us_alias = array(
                                    'teaspoon' => array(
                                                        'tsp',
                                                        ),
                                    'tablespoon' => array(
                                                        'tbsp',
                                                        'tablespoons',
                                                        ),
                                    'ounce' => array(
                                                    'oz',
                                                    'lb',
                                                    'lbs',
                                                    'ounces',
                                                    ),
                                    'cup' => array(
                                                  '',
                                                  ),
                                    'pint' => array(
                                                  '',
                                                  ),
                                    'quart' => array(
                                                  '',
                                                  ),
                                    'gallon' => array(
                                                  '',
                                                  ),
                                    'pound' => array(
                                                    'pounds',
                                                    ),
                                    'drop' => array(
                                                  '',
                                                  ),
                                    'dash' => array(
                                                  '',
                                                  ),
                                    'pinch' => array(
                                                  '',
                                                  ),
                                    'inch'  => array(
                                                    '',
                                                    ),
                                    'cc' => array(
                                                  '',
                                                  ),
                                    );
    
    public $metric_alias = array(
                                    'grams' => array(
                                                    'g',
                                                    ),
                                    'mL' => array(
                                                    'ml',
                                                    'mls',
                                                    ),
                                    'grams' => array(
                                                    'g',
                                                    ),
                                    'grams' => array(
                                                    'g',
                                                    ),
                                    'cm' => array(
                                                    '',
                                                    ),
                                    );
    */
    
    function usToMatric( $quantity = '', $unit = '', $description = '', $servingamt_ori = 1, $servingamt_req = 1, $metric = 'original')
    {
        $quantity = iconv('UTF-8', 'ASCII//TRANSLIT', $quantity); // Since we have this kind of contents : 13½inch
        
        $quantity = str_replace('^A','',$quantity); // Since we have this kind of contents : 13½inch
        
        $this->m = new EvalMath;
        
        $this->set_original_unit($unit);
        
        $this->set_original_quantity($quantity);
        
        $this->filter_quantity_and_unit();
        
        $this->set_description($description);
        
        if($servingamt_ori == '')
        {
            $servingamt_ori = 1;
        }
        
        $this->set_servingamt_ori($servingamt_ori);
        
        if($servingamt_req == '')
        {
        
            $this->set_servingamt_req($this->get_servingamt_ori());
            
        } else {
        
            $this->set_servingamt_req($servingamt_req);
            
        }
        
        if($metric == '')
        {
            
            $this->set_metric('original');
            
        } else {
            
            $this->set_metric($metric);
            
        }
        
        
    }
    
    function __construct($quantity = '', $unit = '', $description = '', $servingamt_ori = 1, $servingamt_req = 1, $metric = 'us')
    {
        $this->usToMatric($quantity, $unit, $description, $servingamt_ori, $servingamt_req, $metric);
    }
    
    
    public function convert_matric()
    {
        if($this->is_have_original_unit === true)
        {
            
            $this->convert_matric_when_unit_available();
            
        } else {
            
            $this->convert_matric_when_unit_not_available();
            
        }
    }
    
    public function convert_matric_when_unit_not_available()
    {
        
        $original_quantity = trim($this->get_original_quantity());
        
        /**
         * Get possible UNIT from quantity content
         **/
         
        /**
         * Check if original quantity is empty
         **/
         
        if($original_quantity == '' || is_null($original_quantity))
        {
            
            return false;  
            
        }
        /**
         * Check if original quantity don't have numeric value, sampel : "Few"
         **/
         
        $check_if_all_is_have_number = preg_replace("/[^0-9]/", "", $original_quantity);
        
         if(!$check_if_all_is_have_number || $check_if_all_is_have_number == '' || is_null($check_if_all_is_have_number) || count($check_if_all_is_have_number) == 0)
         {
              return false;      
                 
         }
         
                
        /**
         * Check if original quantity only have round bracket value, sample : (10)
         **/
                 
         $check_if_all_is_have_round_bracket = preg_replace('#\(.+\)\s*#','',$original_quantity);
         
         
         // $check_if_all_is_have_round_bracket = preg_replace('#\(.*\)#','',$quantity);
       
         if(!$check_if_all_is_have_round_bracket || $check_if_all_is_have_round_bracket == '' || is_null($check_if_all_is_have_round_bracket) || count($check_if_all_is_have_round_bracket) == 0)
         {
            
              return false;      
                 
         }
         
                
        /**
         * Do calculation
         **/
         
        $serving_count = $this->get_servingamt_req() / $this->get_servingamt_ori() ;
        
        $serving_count = number_format($serving_count, 2);
        
        $is_convertable = false;
        
        
        
        $quantity = $this->get_quantity();
        
        $original_unit = $this->get_original_unit();
        
        $unit = $this->get_unit();
        
        
        if(is_array($quantity) || is_array($unit))
        {
                    
            /**
             * If quantity or unit is array
             **/
             
             
                        
                /**
                 * Check data format
                 **/
                 
                 $original_quantity = $this->get_original_quantity();
                 
                 if($this->format_pattern == 'n (n x) x')
                 {
                    
                    $last_quantity_array = false;
                    
                    $last_unit_array = false;
                    
                    $is_convertable_array = false;
                    
                    $current_unit_array = false;
                    
                    $comparison_value_array = false;
                    
                    $last_quantity_by_servingamt_array = false;
                    
                    $last_quantity_by_matric_array = false;
                    
                    $last_quantity_by_servingamt_by_metric_array = false;
                    
                    
                    foreach($quantity as $kq => $vq)
                    {
                        
                        if(!is_array($vq))
                        {
                            $is_convertable_array[$kq] = false;
                            
                            if($unit[$kq] != '' && $this->is_convertable_by_unit($unit[$kq]) !== false && $this->get_metric() == 'metric')
                            {
                                $is_convertable_array[$kq] = true;
                            }
                
                
                            $last_quantity_array[$kq] = @$this->m->evaluate($vq);
                            
                            $last_quantity_array[$kq] = trim($last_quantity_array[$kq]);
                            
                            
                            if($is_convertable_array[$kq] == true)
                            {
                                $unit_convert = $this->is_convertable_by_unit($unit[$kq]);
                                
                                $current_unit_array[$kq] = $unit_convert['unit_converted'];
                                
                                $comparison_value_array[$kq] = (float)key($this->us_to_metric[$unit_convert['unit']]);
                        
                                $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq] * $comparison_value_array[$kq];                     
                                
                                $last_quantity_by_servingamt_by_metric_array[$kq] = round($last_quantity_by_matric_array[$kq] * $serving_count);
                                
                                unset($unit_convert);
                                
                            } else {
                                
                                $current_unit_array[$kq] = $unit[$kq];
                                
                                $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq];
                                
                                $last_quantity_by_servingamt_by_metric_array[$kq] = $last_quantity_by_matric_array[$kq] * $serving_count;
                                
                            }
                            
                            
                            $last_quantity_by_servingamt_array[$kq] = $last_quantity_array[$kq] * $serving_count;
                            
                            
                        } else {
                            
                            $is_convertable_array[$kq] = false;
                            
                            
                            foreach($vq as $kqq => $vqq)
                            {
                            
                                $last_quantity_array[$kq][$kqq] = @$this->m->evaluate($vqq);
                                
                                $last_quantity_array[$kq][$kqq] = trim($last_quantity_array[$kq][$kqq]);
                                
                            
                                if($unit[$kq][$kqq] != '' && $this->is_convertable_by_unit($unit[$kq][$kqq]) !== false && $this->get_metric() == 'metric')
                                {
                                    $unit_convert = $this->is_convertable_by_unit($unit[$kq][$kqq]);
                                    
                                    $current_unit_array[$kq][$kqq] = $unit_convert['unit_converted'];
                                
                                    $comparison_value_array[$kq][$kqq] = (float)key($this->us_to_metric[$unit_convert['unit']]);
                            
                                    $last_quantity_by_matric_array[$kq][$kqq] = $last_quantity_array[$kq][$kqq] * $comparison_value_array[$kq][$kqq];                     
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq][$kqq] = round($last_quantity_by_matric_array[$kq][$kqq] * $serving_count);
                                    
                                    
                                    unset($unit_convert);
                                    
                                } else {
                                    
                                    $current_unit_array[$kq][$kqq] = $unit[$kq][$kqq];
                                
                                    $last_quantity_by_matric_array[$kq][$kqq] = $last_quantity_array[$kq][$kqq];
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq][$kqq] = $last_quantity_by_matric_array[$kq][$kqq] * $serving_count;
                                    
                                }
                                
                                
                                $last_quantity_by_servingamt_array[$kq][$kqq] = $last_quantity_array[$kq][$kqq] * $serving_count;
                                
                            }
                            
                        }                      
                    }
                    
                    
                    
                    /**
                     * Combine all
                     **/
                     
                     if(!is_array($last_quantity_array[1]))
                     {
                         $this->set_quantity_by_servingamt(
                            trim(
                            $last_quantity_by_servingamt_array[0].' ('.$last_quantity_by_servingamt_array[1].' '.$unit[1].') '.$unit[0]
                            )
                         );
                         
                         $this->set_unit_by_servingamt($this->get_original_unit());
                         
                         $this->set_quantity_by_servingamt_by_metric(
                             trim(
                                $last_quantity_by_servingamt_by_metric_array[0].' ('.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1].') '.$current_unit_array[0]
                                )
                         );
                        
                     } else {
                         $this->set_quantity_by_servingamt(
                            trim(
                            $last_quantity_by_servingamt_array[0].' ('.$last_quantity_by_servingamt_array[1][0].' '.$unit[1][0].' or '.$last_quantity_by_servingamt_array[1][1].' '.$unit[1][1].') '.$unit[0]
                            )
                         );
                         
                         $this->set_unit_by_servingamt($this->get_original_unit());
                         
                         $this->set_quantity_by_servingamt_by_metric(
                             trim(
                                $last_quantity_by_servingamt_by_metric_array[0].' ('.$last_quantity_by_servingamt_by_metric_array[1][0].' '.$current_unit_array[1][0].' or '.$last_quantity_by_servingamt_by_metric_array[1][1].' '.$current_unit_array[1][1].') '.$current_unit_array[0]
                                )
                         );
                     }
                     
                     
                     $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                     
                     return false;
                  
                    
                 }
                 
                 
                 
                 
                 if($this->format_pattern == 'n - n x' || $this->format_pattern == 'n-nx' || $this->format_pattern == 'n x X n x')
                 {
                    
                    $last_quantity_array = false;
                    
                    $last_unit_array = false;
                    
                    $is_convertable_array = false;
                    
                    $current_unit_array = false;
                    
                    $comparison_value_array = false;
                    
                    $last_quantity_by_servingamt_array = false;
                    
                    $last_quantity_by_matric_array = false;
                    
                    $last_quantity_by_servingamt_by_metric_array = false;
                    
                    if(is_array($unit))
                    {
                    
                        foreach($quantity as $kq => $vq)
                        {
                                $is_convertable_array[$kq] = false;
                                
                                if($unit[$kq] != '' && $this->is_convertable_by_unit($unit[$kq]) !== false && $this->get_metric() == 'metric')
                                {
                                    $is_convertable_array[$kq] = true;
                                }
                    
                    
                                $last_quantity_array[$kq] = @$this->m->evaluate($vq);
                                
                                $last_quantity_array[$kq] = trim($last_quantity_array[$kq]);
                                
                                
                                if($is_convertable_array[$kq] == true)
                                {
                                    $unit_convert = $this->is_convertable_by_unit($unit[$kq]);
                                    
                                    $current_unit_array[$kq] = $unit_convert['unit_converted'];
                                    
                                    $comparison_value_array[$kq] = (float)key($this->us_to_metric[$unit_convert['unit']]);
                            
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq] * $comparison_value_array[$kq];                     
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = round($last_quantity_by_matric_array[$kq] * $serving_count);
                                    
                                    unset($unit_convert);
                                    
                                } else {
                                    
                                    $current_unit_array[$kq] = $unit[$kq];
                                    
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq];
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = $last_quantity_by_matric_array[$kq] * $serving_count;
                                    
                                }
                                
                                
                                $last_quantity_by_servingamt_array[$kq] = $last_quantity_array[$kq] * $serving_count;
                                
                            
                            
                        }
                        
                        
                        
                        /**
                         * Combine all
                         **/
                         
                         if($this->format_pattern == 'n - n x' || $this->format_pattern == 'n-nx')
                         {
                             $this->set_quantity_by_servingamt(
                                trim(
                                $last_quantity_by_servingamt_array[0].' '.$unit[0].' - '.$last_quantity_by_servingamt_array[1].' '.$unit[1]
                                )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' '.$current_unit_array[0].' - '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                            
                        } elseif($this->format_pattern == 'n x X n x')
                        {
                             $this->set_quantity_by_servingamt(
                                trim(
                                $last_quantity_by_servingamt_array[0].' '.$unit[0].' X '.$last_quantity_by_servingamt_array[1].' '.$unit[1]
                                )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' '.$current_unit_array[0].' X '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                            
                        }
                       
                       
                    } else {
                        
			foreach($quantity as $kq => $vq)
                        {
                                $is_convertable_array[$kq] = false;
                                
                                if($unit != '' && $this->is_convertable_by_unit($unit) !== false && $this->get_metric() == 'metric')
                                {
                                    $is_convertable_array[$kq] = true;
                                }
                    
                    
                                $last_quantity_array[$kq] = @$this->m->evaluate($vq);
                                
                                $last_quantity_array[$kq] = trim($last_quantity_array[$kq]);
                                
                                
                                if($is_convertable_array[$kq] == true)
                                {
                                    $unit_convert = $this->is_convertable_by_unit($unit);
                                    
                                    $current_unit_array[$kq] = $unit_convert['unit_converted'];
                                    
                                    $comparison_value_array[$kq] = (float)key($this->us_to_metric[$unit_convert['unit']]);
                            
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq] * $comparison_value_array[$kq];                     
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = round($last_quantity_by_matric_array[$kq] * $serving_count);
                                    
                                    unset($unit_convert);
                                    
                                } else {
                                    
                                    $current_unit_array[$kq] = $unit;
                                    
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq];
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = $last_quantity_by_matric_array[$kq] * $serving_count;
                                    
                                }
                                
                                
                                $last_quantity_by_servingamt_array[$kq] = $last_quantity_array[$kq] * $serving_count;
                                
                            
                            
                        }
                        
                        
                        
                        /**
                         * Combine all
                         **/
                         
                         if($this->format_pattern == 'n - n x' || $this->format_pattern == 'n-nx')
                         {
                         
                             $this->set_quantity_by_servingamt(
                                trim(
                                $last_quantity_by_servingamt_array[0].' - '.$last_quantity_by_servingamt_array[1].' '.$unit
                                )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' - '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                        
                        } elseif($this->format_pattern == 'n x X n x') {
                         
                             $this->set_quantity_by_servingamt(
                                trim(
                                $last_quantity_by_servingamt_array[0].' X '.$last_quantity_by_servingamt_array[1].' '.$unit
                                )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' X '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                            
                        }
                    }
                 }
                 
                 
                 if($this->format_pattern == 'n x + n x' || $this->format_pattern == 'n x / n x')
                 {
                    
                    $last_quantity_array = false;
                    
                    $last_unit_array = false;
                    
                    $is_convertable_array = false;
                    
                    $current_unit_array = false;
                    
                    $comparison_value_array = false;
                    
                    $last_quantity_by_servingamt_array = false;
                    
                    $last_quantity_by_matric_array = false;
                    
                    $last_quantity_by_servingamt_by_metric_array = false;
                    
                    
                    if(is_array($unit))
                    {
                    
                        foreach($quantity as $kq => $vq)
                        {
                                $is_convertable_array[$kq] = false;
                                
                                if($unit[$kq] != '' && $this->is_convertable_by_unit($unit[$kq]) !== false && $this->get_metric() == 'metric')
                                {
                                    $is_convertable_array[$kq] = true;
                                }
                    
                    
                                $last_quantity_array[$kq] = @$this->m->evaluate($vq);
                                
                                $last_quantity_array[$kq] = trim($last_quantity_array[$kq]);
                                
                                
                                if($is_convertable_array[$kq] == true)
                                {
                                    $unit_convert = $this->is_convertable_by_unit($unit[$kq]);
                                    
                                    $current_unit_array[$kq] = $unit_convert['unit_converted'];
                                    
                                    $comparison_value_array[$kq] = (float)key($this->us_to_metric[$unit_convert['unit']]);
                            
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq] * $comparison_value_array[$kq];                     
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = round($last_quantity_by_matric_array[$kq] * $serving_count);
                                    
                                    unset($unit_convert);
                                    
                                } else {
                                    
                                    $current_unit_array[$kq] = $unit[$kq];
                                    
                                    $last_quantity_by_matric_array[$kq] = $last_quantity_array[$kq];
                                    
                                    $last_quantity_by_servingamt_by_metric_array[$kq] = $last_quantity_by_matric_array[$kq] * $serving_count;
                                    
                                }
                                
                                
                                $last_quantity_by_servingamt_array[$kq] = $last_quantity_array[$kq] * $serving_count;
                                
                            
                            
                        }
                        
                        
                        
                        /**
                         * Combine all
                         **/
                         
                         if($this->format_pattern == 'n x + n x')
                         {
                         
                             $this->set_quantity_by_servingamt(
                                trim(
                                $last_quantity_by_servingamt_array[0].' '.$unit[0].' + '.$last_quantity_by_servingamt_array[1].' '.$unit[1]
                                )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' '.$current_unit_array[0].' + '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                            
                         } elseif($this->format_pattern == 'n x / n x') {
                            
                         
                             $this->set_quantity_by_servingamt(
				    trim(
					$last_quantity_by_servingamt_array[0].' '.$unit[0].' / '.$last_quantity_by_servingamt_array[1].' '.$unit[1]
				    )
                             );
                             
                             $this->set_unit_by_servingamt($this->get_original_unit());
                             
                             $this->set_quantity_by_servingamt_by_metric(
                                 trim(
                                    $last_quantity_by_servingamt_by_metric_array[0].' '.$current_unit_array[0].' / '.$last_quantity_by_servingamt_by_metric_array[1].' '.$current_unit_array[1]
                                    )
                             );
                             
                             $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                            
                            
                            return false;
                            
                         }
                       
                       
                    }
                 }
                 
                        
                /**
                 * Check data format
                 **/
             
                    
            /**
             * If quantity or unit is array
             **/
             
        } else {
            
            /**
             * If quantity or unit not array
             **/
             
            
            if(@$this->m->evaluate($quantity))
            {
                $this->set_is_countable(true);
                
                $is_convertable = false;
                
                if($this->get_unit() != '' && $this->is_convertable() == true && $this->get_metric() == 'metric')
                {
                    
                    $is_convertable = true;
                }
                
                
                $last_quantity = @$this->m->evaluate($quantity);
                
                $last_quantity = trim($last_quantity);
                
                
                
                if($is_convertable == true)
                {
                    $current_unit = $this->get_unit();
                    
                    $comparison_value = (float)key($this->us_to_metric[$current_unit]);
                    
                    $last_quantity_by_matric = $last_quantity * $comparison_value;
                    
                } else {
                    
                    $last_quantity_by_matric = $last_quantity;
                    
                }
                
                
            
                $this->set_quantity($last_quantity);
                
                $this->set_quantity_by_servingamt($last_quantity * $serving_count);
                
                if($is_convertable == true)
                {
                    $temp_round = round($last_quantity_by_matric * $serving_count);
                    
                    if($temp_round == 0)
                    {
                        
                        $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count,2));
                        
                    } else {
                    
                        $this->set_quantity_by_servingamt_by_metric($temp_round);
                        
                    }
                    
                } else {
                
                    $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                    
                }
         
         
                if($is_convertable == true)
                {
                    if(!isset($current_unit))
                    {
                        $current_unit = $this->get_unit();
                    }
                    
                    foreach($this->us_to_metric[$current_unit] as $x => $y)
                    {
                        
                        $this->set_unit_by_servingamt_by_metric($y);
                        
                    }
                    
                } else {
                    $this->set_unit_by_servingamt_by_metric($this->get_unit());
                }
                    
                $this->set_unit_by_servingamt($this->get_unit());
                
                
                return false;
            
            
            /**
             * If quantity or unit not array
             **/
             
                    
            } else {
                
                $this->set_is_countable(false);
                
                $this->set_unit($this->get_original_unit());
                
                $this->set_quantity($this->get_original_quantity()); 
                
                $this->set_quantity_by_servingamt($this->get_original_quantity()); 
                
                $this->set_unit_by_servingamt($this->get_original_unit()); 
                
                $this->set_quantity_by_servingamt_by_metric($this->get_original_quantity()); 
                
                $this->set_unit_by_servingamt_by_metric($this->get_original_unit());
                
                return false;
                
            }
            
            
        }
            
        
    }
    
    
    public function convert_matric_when_unit_available()
    {
        
        //$serving_count = ($this->get_servingamt_req() / $this->get_servingamt_ori()) * 100 ;
        $serving_count = $this->get_servingamt_req() / $this->get_servingamt_ori() ;
        
        $serving_count = number_format($serving_count, 2);
        
        $is_convertable = false;
        
        if($this->is_convertable() == true && ($this->get_metric() == 'metric'||$this->get_metric() == 'us')) {
			$is_convertable = true;
			if ($this->get_metric() == 'us')
				$metricterm = $this->metric_to_us;
			else
				$metricterm = $this->us_to_metric;
        }
        
        $last_quantity = false;
        
        $last_quantity_by_matric = false;
        
        /**
         * Check condition '/' only, sample : 1/2
         **/
         
         if(strpos((trim($this->get_quantity())),'/') !== false && strpos((trim($this->get_quantity())),' ') === false && strpos((trim($this->get_quantity())),'-') === false && strpos((trim($this->get_quantity())),'.') === false && strpos((trim($this->get_quantity())),'&') === false)
         {
            
            $last_quantity = @$this->m->evaluate($this->get_quantity());
            
            $last_quantity = trim($last_quantity);
            
            if($is_convertable == true)
            {
                $current_unit = $this->get_unit();
                
                $comparison_value = (float)key($metricterm[$current_unit]);
                
                $last_quantity_by_matric = $last_quantity * $comparison_value;
                
            } else {
                
                $last_quantity_by_matric = $last_quantity;
                
            }
            
            $this->set_quantity($last_quantity);
            
            // $this->quantity_by_servingamt = $last_quantity * $serving_count;
            
            $this->set_quantity_by_servingamt($last_quantity * $serving_count);
            
            if($is_convertable == true)
            {
            
                $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count));
                
            } else {
            
                $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                
            }
            
            $this->set_is_countable(true);
//            echo '<pre>';
//            var_dump($last_quantity);
//            echo '</pre>'; 
            
         }
        
        /**
         * Check condition ' ' and '/' only, sample : 1 1/2
         **/
         
         if(strpos((trim($this->get_quantity())),' ') !== false && strpos((trim($this->get_quantity())),'/') !== false && strpos((trim($this->get_quantity())),'-') === false && strpos((trim($this->get_quantity())),'.') === false && strpos((trim($this->get_quantity())),'&') === false)
         {
            
            $temp_quantity = explode(' ',$this->get_quantity());
            
            $temp_quantity2 = $this->trimArray($temp_quantity);
            
            $last_quantity = false;
            
            foreach($temp_quantity2 as $k => $v)
            {
                $last_quantity += trim(@$this->m->evaluate($v));
            }
            
            unset($temp_quantity);     
            unset($temp_quantity2);   
            
            if($is_convertable == true)
            {
                
                $current_unit = $this->get_unit();
                
                $comparison_value = (float)key($metricterm[$current_unit]);
                
                $last_quantity_by_matric = $last_quantity * $comparison_value;
                
            } else {
                
                $last_quantity_by_matric = $last_quantity;
                
            }
            
            $this->set_quantity($last_quantity);
            
            // $this->quantity_by_servingamt = $last_quantity * $serving_count;
            
            $this->set_quantity_by_servingamt($last_quantity * $serving_count);
            
            if($is_convertable == true)
            {
            
                $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count));
                
            } else {
            
                $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                
            }
            
            $this->set_is_countable(true);
            
            
//            echo '<pre>';
//            var_dump($last_quantity);
//            echo '</pre>';   
            
         }
        
        /**
         * Check condition '.' and '/' only, sample : 1.1/2
         **/
         
         if(strpos((trim($this->get_quantity())),'.') !== false && strpos((trim($this->get_quantity())),'/') !== false)
         {
            
            $temp_quantity = explode('.',$this->get_quantity());
            
            $temp_quantity2 = $this->trimArray($temp_quantity);
            
            $last_quantity = false;
            
            foreach($temp_quantity2 as $k => $v)
            {
                $last_quantity += trim(@$this->m->evaluate($v));
            }
            
            unset($temp_quantity);     
            unset($temp_quantity2);   
            
            if($is_convertable == true)
            {
                $current_unit = $this->get_unit();
                
                $comparison_value = (float)key($metricterm[$current_unit]);
                
                $last_quantity_by_matric = $last_quantity * $comparison_value;
                
            } else {
                
                $last_quantity_by_matric = $last_quantity;
                
            }
            
            $this->set_quantity($last_quantity);
            
            // $this->quantity_by_servingamt = $last_quantity * $serving_count;
            
            $this->set_quantity_by_servingamt($last_quantity * $serving_count);
            
            if($is_convertable == true)
            {
            
                $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count));
                
            } else {
            
                $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                
            }
            
            $this->set_is_countable(true);
            
            
//            echo '<pre>';
//            var_dump($last_quantity);
//            echo '</pre>';   
            
         }
        
        /**
         * Check condition '-' only, sample : 1-1/2 or 1 - 1/2
         **/
         
         if(strpos((trim($this->get_quantity())),'-') !== false )
         {
            
            $temp_quantity = explode('-',$this->get_quantity());
            
            $temp_quantity2 = $this->trimArray($temp_quantity);
            
            $last_quantity = false;
            
            $last_quantity_array = false;
            
            $last_quantity_array_by_matric = false;
            
            $last_quantity_by_servingamt_array = false;
            
            $last_quantity_by_servingamt_array_by_matric = false;
            
            foreach($temp_quantity2 as $k => $v)
            {
                $last_quantity_array[$k] = trim(@$this->m->evaluate($v));
            
                if($is_convertable == true)
                {
                    $current_unit = $this->get_unit();
                    
                    $comparison_value = (float)key($metricterm[$current_unit]);
                    
                    $last_quantity_array_by_matric[$k] = $last_quantity_array[$k] * $comparison_value;
                    
                    unset($current_unit);
                    
                    unset($comparison_value);
                }
            
                $last_quantity_by_servingamt_array[$k] = $last_quantity_array[$k] * $serving_count;
                
                
                if($is_convertable == true)
                {
                    $last_quantity_by_servingamt_array_by_matric[$k] = round($last_quantity_array_by_matric[$k] * $serving_count);
                    
                } else {
            
                    $last_quantity_by_servingamt_array_by_matric[$k] = $last_quantity_array_by_matric[$k] * $serving_count;
                    
                }
            }
            
            
            $last_quantity = implode(' - ',$last_quantity_array);           
            
            //$this->quantity_by_servingamt = implode(' - ',$last_quantity_by_servingamt_array);
            
            $this->set_quantity_by_servingamt(implode(' - ',$last_quantity_by_servingamt_array));
            
            $this->set_quantity_by_servingamt_by_metric(implode(' - ',$last_quantity_by_servingamt_array_by_matric));
                       
            
            unset($temp_quantity);     
            unset($temp_quantity2);   
            unset($last_quantity_array);  
            unset($last_quantity_array_by_matric);  
            unset($last_quantity_by_servingamt_array);  
            unset($last_quantity_by_servingamt_array_by_matric);  
            
            $this->set_quantity($last_quantity);
            
            $this->set_is_countable(true);
            
//            echo '<pre>';
//            var_dump($last_quantity);
//            echo '</pre>';  
            
         }
         
        
        /**
         * Check condition '&' sample : 1 & 1/2
         **/
         
         if(strpos(trim($this->get_quantity()),'&') !== false)
         {
            $temp_quantity = explode('&',$this->get_quantity());
            
            $temp_quantity2 = $this->trimArray($temp_quantity);
            
            $last_quantity = false;
            
            foreach($temp_quantity2 as $k => $v)
            {
                $last_quantity += trim(@$this->m->evaluate($v));
            }
            
            unset($temp_quantity);     
            unset($temp_quantity2);   
            
            if($is_convertable == true)
            {
                $current_unit = $this->get_unit();
                
                $comparison_value = (float)key($metricterm[$current_unit]);
                
                $last_quantity_by_matric = $last_quantity * $comparison_value;
                
            } else {
                
                $last_quantity_by_matric = $last_quantity;
                
            }
            
            $this->set_quantity($last_quantity);
            
            // $this->quantity_by_servingamt = $last_quantity * $serving_count;
            
            $this->set_quantity_by_servingamt($last_quantity * $serving_count);
            
            if($is_convertable == true)
            {
            
                $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count));
                
            } else {
            
                $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                
            }
            
            
            $this->set_is_countable(true);
            
//            echo '<pre>';
//            var_dump($last_quantity);
//            echo '</pre>';         
         }
         
         
         if($last_quantity == false)
         {
            $last_quantity = trim($this->get_quantity());
            
            if($last_quantity != false && $this->is_numeric_regex($last_quantity) == true)
            {
                $this->set_is_countable(true);
                
                if($is_convertable == true)
                {
                    $current_unit = $this->get_unit();
                    
                    $comparison_value = (float)key($metricterm[$current_unit]);
                    
                    $last_quantity_by_matric = $last_quantity * $comparison_value;
                    ;
                } else {
                    
                    $last_quantity_by_matric = $last_quantity;
                    
                }
            
            } else {
                $this->set_is_countable(false);
            }
            
            if($last_quantity == false)
            {
                $last_quantity = trim($this->get_original_quantity());
            }
            
            $this->set_quantity($last_quantity);
            
            //$this->quantity_by_servingamt = $last_quantity * $serving_count;
            
            $this->set_quantity_by_servingamt($last_quantity * $serving_count);
            
            if($is_convertable == true)
            {
            
                $this->set_quantity_by_servingamt_by_metric(round($last_quantity_by_matric * $serving_count,2));
                
            } else {
            
                $this->set_quantity_by_servingamt_by_metric($last_quantity_by_matric * $serving_count);
                
            }
            
         
         }    
         
         
         $serving_division = $this->get_servingamt_req() / $this->get_servingamt_ori();
         
         
        
        $check_if_all_is_have_number = preg_replace("/[^0-9]/", "", $this->get_quantity());
        
         
         if(is_int($serving_division))
         {
            if($serving_division == 1)
            {
                $serving_division = '';
                
            } else {
                
                 if(!$check_if_all_is_have_number || $check_if_all_is_have_number == '' || is_null($check_if_all_is_have_number) || count($check_if_all_is_have_number) == 0)
                 {
                    
                    $serving_division = '';
                         
                 } else {
                    
                    $serving_division = ' x '.$serving_division;
                    
                 }
                
            }
            
         } else if(is_float($serving_division)) {
            
            if($serving_division > 1)
            {
                
                 if(!$check_if_all_is_have_number || $check_if_all_is_have_number == '' || is_null($check_if_all_is_have_number) || count($check_if_all_is_have_number) == 0)
                 {
                    
                    $serving_division = '';
                         
                 } else {
                    
                    $serving_division = ' x '.$serving_division;
                    
                 }
                
            } else {
                
                $serving_division_obj = new Math_Fraction($this->get_servingamt_req(),$this->get_servingamt_ori());
                
                 if(!$check_if_all_is_have_number || $check_if_all_is_have_number == '' || is_null($check_if_all_is_have_number) || count($check_if_all_is_have_number) == 0)
                 {
                    
                    $serving_division = '';
                         
                 } else {
                    
                    $serving_division = ' x '.$serving_division_obj->toString();
                    
                 }
                
            }
            
            unset($serving_division_obj);
            
         }
         
         
		if($this->get_quantity_by_servingamt() == 0 && $this->get_is_countable() == false)
		{
			//$this->quantity_by_servingamt = '';
			
			$this->set_quantity_by_servingamt($this->get_quantity().$serving_division);
		
		}
		
		if($this->get_quantity_by_servingamt_by_metric() == 0 && $this->get_is_countable() == false)
		{
			//$this->quantity_by_servingamt = '';
			
			$this->set_quantity_by_servingamt_by_metric( $this->get_quantity().$serving_division);
		
		}
         
        if($is_convertable == true)
        {
            if(!isset($current_unit))
            {
                $current_unit = $this->get_unit();
            }
            
            foreach($metricterm[$current_unit] as $x => $y)
            {
                
                $this->set_unit_by_servingamt_by_metric($y);
                
            }
            
        } else {
            $this->set_unit_by_servingamt_by_metric($this->get_unit());
        }
            
        $this->set_unit_by_servingamt($this->get_unit());
        
        
    }
    
    function trimArray($Input){
     
        if (!is_array($Input))
            return trim($Input);
     
        return array_map(array($this, 'trimArray'), $Input);
    }    
    
    public function check_original_unit()
    {
        
        if($this->get_original_unit() != ''  )
        {
            $this->is_have_original_unit = true;
        }
    }
    
    public function is_convertable()
    {
        
        $unit = $this->get_unit();
        
        $is_in_alias = false;
        
        $is_in_alias = $this->getParentStackComplete($unit, $this->metrics_alias);
        
        if($is_in_alias !== false)
        {

            $unit = key($is_in_alias);
            
            $this->set_unit($unit);
            
        }
        if ($this->metric=='us')
	    $is_in_alias = array_key_exists($unit, $this->metric_to_us);
	else
	    $is_in_alias = array_key_exists($unit, $this->us_to_metric);
	return $is_in_alias;
    }
    
    public function is_convertable_by_unit($unit)
    {
        
        $is_in_alias = false;
        
        $is_in_alias = $this->getParentStackComplete($unit, $this->metrics_alias);
        
        if($is_in_alias == false)
        {
            $is_in_alias = array_key_exists($unit, $this->us_to_metric);
            
            return $is_in_alias;
            
        } else {
            
            $unit = key($is_in_alias);
            
            $unit_converted = array_values($this->us_to_metric[$unit]);
                    
            $return = array(
		'unit' => $unit,
		'unit_converted' => $unit_converted[0]
            );
            
            return $return;
            
        }
        
    }
    
    
    /**
     * From http://www.php.net/manual/en/function.array-search.php#100057
     **/
     
    /**
     * Gets the parent stack of a string array element if it is found within the 
     * parent array
     * 
     * This will not search objects within an array, though I suspect you could 
     * tweak it easily enough to do that
     *
     * @param string $child The string array element to search for
     * @param array $stack The stack to search within for the child
     * @return array An array containing the parent stack for the child if found,
     *               false otherwise
     */
    function getParentStack($child, $stack) {
        foreach ($stack as $k => $v) {
            if (is_array($v)) {
                // If the current element of the array is an array, recurse it and capture the return
                $return = $this->getParentStack($child, $v);
                
                // If the return is an array, stack it and return it
                if (is_array($return)) {
                    return array($k => $return);
                }
            } else {
                // Since we are not on an array, compare directly
                if ($v == $child) {
                    // And if we match, stack it and return it
                    return array($k => $child);
                }
            }
        }
        
        // Return false since there was nothing found
        return false;
    }
    
    /**
     * Gets the complete parent stack of a string array element if it is found 
     * within the parent array
     * 
     * This will not search objects within an array, though I suspect you could 
     * tweak it easily enough to do that
     *
     * @param string $child The string array element to search for
     * @param array $stack The stack to search within for the child
     * @return array An array containing the parent stack for the child if found,
     *               false otherwise
     */
    function getParentStackComplete($child, $stack) {
        $return = array();
        foreach ($stack as $k => $v) {
            if (is_array($v)) {
                // If the current element of the array is an array, recurse it 
                // and capture the return stack
                $stack = $this->getParentStackComplete($child, $v);
                
                // If the return stack is an array, add it to the return
                if (is_array($stack) && !empty($stack)) {
                    $return[$k] = $stack;
                }
            } else {
                // Since we are not on an array, compare directly
                if ($v == $child) {
                    // And if we match, stack it and return it
                    $return[$k] = $child;
                }
            }
        }
        
        // Return the stack
        return empty($return) ? false: $return;
    }
    
    
    private function set_is_dev_done($value)
    {
            $this->is_dev_done = $value;
    }
    
    public function get_is_dev_done()
    {
         return $this->is_dev_done;
    }
    
    
    private function set_is_countable($value)
    {
            $this->is_countable = $value;
    }
    
    public function get_is_countable()
    {
         return $this->is_countable;
    }
    
    
    private function set_unit_by_servingamt_by_metric($value)
    {
        $this->unit_by_servingamt_by_metric = $value;
    }
    
    public function get_unit_by_servingamt_by_metric()
    {
         return $this->unit_by_servingamt_by_metric;
    }
    
    
    private function set_unit_by_servingamt($value)
    {
        $this->unit_by_servingamt = $value;
    }
    
    public function get_unit_by_servingamt()
    {
         return $this->unit_by_servingamt;
    }
    
    
    
    private function set_quantity_by_servingamt_by_metric($value)
    {
        $this->quantity_by_servingamt_by_metric = $value;
    }
    
    public function get_quantity_by_servingamt_by_metric()
    {
         return $this->quantity_by_servingamt_by_metric;
    }
    
    
    
    private function set_quantity_by_servingamt($value)
    {
        $this->quantity_by_servingamt = $value;
    }
    
    public function get_quantity_by_servingamt()
    {
         return $this->quantity_by_servingamt;
    }
    
    
    
    private function set_original_unit_by_metric($value)
    {
        $this->original_unit_by_metric = $value;
    }    
    
    
    public function get_original_unit_by_metric()
    {
         return $this->original_unit_by_metric;
    }
    
    
    private function set_original_unit($value)
    {
        $this->original_unit = $value;
    }
    
    public function get_original_unit()
    {
         return $this->original_unit;
    }
    
    
    private function set_original_quantity($value)
    {
        $this->original_quantity = $value;
    }
    
    public function get_original_quantity()
    {
         return $this->original_quantity;
    }
    
    
    private function set_description($value)
    {
        $this->description = $value;
    }
    
    public function get_description()
    {
         return $this->description;
    }
    
    
    private function set_metric($value)
    {
        $this->metric = $value;
    }
    
    public function get_metric()
    {
         return $this->metric;
    }
    
    
    private function set_unit($value)
    {
        $this->unit = $value;
    }
    
    public function get_unit()
    {
         return $this->unit;
    }
    
    
    private function set_quantity($value)
    {
        $this->quantity = $value;
    }
    
    public function get_quantity()
    {
         return $this->quantity;
    }
    
    
    private function set_servingamt_req($value)
    {
        $this->servingamt_req = $value;
    }
    
    public function get_servingamt_req()
    {
         return $this->servingamt_req;
    }
    
    
    private function set_servingamt_ori($value)
    {
        if($value == '')
        {
            
            $this->servingamt_ori = 1;
            
        } else {
            
            $this->servingamt_ori = $value;
            
        }
    }
    
    public function get_servingamt_ori()
    {
         return $this->servingamt_ori;
    }
    
    
    // Do maintain
    
    private function isHaveSpace($value)
    {
        $value = trim($value);
        
        $check = explode(' ',$value);
        
        if(count($check) > 1) 
        {
            
            return true;
            
        } else {
            
            return false;
            
        }
    }
    
    
    public function get_all_metric()
    {
	
    }
    
    
    private function filter_quantity_and_unit()
    {
        $this->check_original_unit();
        
        
        $unit = $this->get_original_unit();
        
        $quantity = trim($this->get_original_quantity());
        
        $quantity = str_replace('1-~A? 1/2','1 - 1/2',$quantity);
        
        $quantity = str_replace('1lb 21-26','1lb',$quantity);
        
        
        
        $quantity = str_replace('(about 8 li',' (8 li)',$quantity);
        
        $quantity = str_replace('bunch -','bunch',$quantity);
        
        $quantity = str_replace('cup or more','cup',$quantity);
        
        $quantity = str_replace(' 1-inch',' inch',$quantity);
        
        $quantity = str_replace('1 12-ounce package','1 (12 ounce) package',$quantity);
        
        $quantity = str_replace('large-sized','large sized',$quantity);
        
        $quantity = str_replace('1 to taste','1 taste',$quantity);
        
        $quantity = str_replace('1-inch piece','1 inch piece',$quantity);
        
        $quantity = str_replace('2-inch','2 inch',$quantity);
        
        $quantity = str_replace('3 8-ounce packages','3 (8 ounce) packages',$quantity);
        
        $quantity = str_replace('3-inch stick of','3 inch stick of',$quantity);
        
        $quantity = str_replace('4-6 okras','4-6okras',$quantity);
        
        $quantity = str_replace('5-inch','5 inch',$quantity);
        
        $quantity = str_replace('(14-ounce)','(14 ounce)',$quantity);
        
        $quantity = str_replace('(14.5oz)','(14.5 oz)',$quantity);
        
        $quantity = str_replace('(16oz)','(16 oz)',$quantity);
        
        $quantity = str_replace('(8oz)','(8 oz)',$quantity);
        
        $quantity = str_replace('(50z)','(5 oz)',$quantity);
        
        $quantity = str_replace('0z','oz',$quantity);
        
        $quantity = str_replace('460 g/1lb','460g/1lb',$quantity);
        
        $quantity = str_replace('five 64 oz','5 (64 oz)',$quantity);
        
        $quantity = str_replace('1 175g pkt','1 (175 g) pkt',$quantity);
        
        $quantity = str_replace('1,8','1.8',$quantity);
        
        $quantity = str_replace('125g/4oz','125 g (4 oz)',$quantity);
        
        $quantity = str_replace('5cm/2inch','5 cm (2 inch)',$quantity);
        
        
        
        
        $quantity = str_replace('1 1-inch','1 inch',$quantity);
        
        
        
        $quantity = str_replace(array('about', 'About','\"',' (per se','u.s.',' (roughly'),'',$quantity);
        
        $quantity = str_replace(' tablespoons (1 1/',' tablespoons',$quantity);
        
        $quantity = str_replace('1inch pc','1 inch pc',$quantity);
        
        
            
        if($quantity == '1)' || $quantity == '2)' || $quantity == '3)' || $quantity == '4)' || $quantity == '5)'  || $quantity == '0')
        {
            $quantity = '';
        }
        
        $quantity = str_replace('&',' & ',$quantity);
        
        $quantity = preg_replace('!\s+!', ' ', $quantity); // Remove double space
                
        /**
         * 
         * Get condition if original unit available, if no do input treatments
         * 
         **/
        
        if(!$unit || $unit == '')
        {
            $isHaveSpace = $this->isHaveSpace($quantity);
    
            $num = null;
    
            $alpha = null;
            
            $quantity = str_replace('-',' - ',$quantity);
            
            $quantity = str_replace(' and ',' - ',$quantity);
            
            $quantity = str_replace(' to ',' - ',$quantity);
            
            $quantity = str_replace(' or ',' - ',$quantity);
            
            $quantity = str_replace('~',' - ',$quantity);
            
            $quantity = str_replace('plus',' + ',$quantity);
            
            $quantity = str_replace('X',' x ',$quantity);
        
            $quantity = preg_replace('!\s+!', ' ', $quantity); // Remove double space
            
            
            
            // Remove '.' on first place, sample : .200cc
            
            if(substr($quantity,0,1) == '.')
            {
                $quantity = substr($quantity,1);
            }
            
            if(substr($quantity, -1) == '.')
            {
                $quantity = substr($quantity,0,-1);
            }
            
            if(substr($quantity, -1) == '-')
            {
                $quantity = substr($quantity,0,-1);
            }
            
            
            $is_numeric = $this->is_numeric_regex($quantity);
            
//            if($isHaveSpace && $this->is_debug)
//            {
//                    echo '<pre>';
//                    print_r('isHaveSpace --------------------');
//                    echo '</pre>';                
//            }
            /**
             * Check if original quantity is empty
             **/
            
            if($quantity == '' || is_null($quantity))
            {
                
                $this->set_unit($unit);
                
                $this->set_quantity($quantity);
                
//                $this->set_original_unit($unit);
//                
//                $this->set_original_quantity($quantity);
                
                $this->set_quantity_by_servingamt($quantity);      
                
                $this->set_unit_by_servingamt($unit);      
                
                $this->set_quantity_by_servingamt_by_metric($quantity);      
                
                $this->set_unit_by_servingamt_by_metric($unit);
                
                $this->set_is_countable(false); 
                
                
                $this->set_is_dev_done(true);
                
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('quantity_is_empty --------------------');
                    echo '</pre>';
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('quantity_is_empty --------------------');
                    echo '</pre>';
                }
                
                return false;                
                
            }
            
            
            if(!$is_numeric || $isHaveSpace)
            {
                //$check_if_all_is_ = ereg_replace("[[:alpha:]]", "", $quantity);
                
                //$check_if_all_is_ = preg_replace("/[^0-9,.]/", "", $quantity);
                
                /**
                 * Check if original quantity don't have numeric value, sampel : "Few"
                 **/
                
                $check_if_all_is_have_number = preg_replace("/[^0-9]/", "", $quantity);
               
                 if(!$check_if_all_is_have_number || $check_if_all_is_have_number == '' || is_null($check_if_all_is_have_number) || count($check_if_all_is_have_number) == 0)
                 {
                        
                        $this->set_unit($unit);
                        
                        $this->set_quantity($quantity);
                        
                        $this->set_original_unit($unit);
                        
                        $this->set_original_quantity($quantity);
        
                        $this->set_quantity_by_servingamt($quantity);      
                        
                        $this->set_unit_by_servingamt($unit);      
                        
                        $this->set_quantity_by_servingamt_by_metric($quantity);      
                        
                        $this->set_unit_by_servingamt_by_metric($unit);
                        
                        $this->set_is_countable(false); 
                        
                        
                        $this->set_is_dev_done(true);
                        
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_number --------------------');
                            echo '</pre>';
                    
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_number --------------------');
                            echo '</pre>';
                        }
                        
                        return false;     
                         
                 }
                 
                
                /**
                 * Check if original quantity only have round bracket value, sample : (10)
                 **/
                 
                 //$check_if_all_is_have_bracket = preg_replace('`\[[^\]]*\]`','',$quantity);
                 
                 $check_if_all_is_have_round_bracket = preg_replace('#\(.*\)#','',$quantity);
                 
                 //$check_if_all_is_have_round_bracket = preg_replace('#\(.+\)\s*#','',$quantity);
                 if(!$check_if_all_is_have_round_bracket || $check_if_all_is_have_round_bracket == '' || is_null($check_if_all_is_have_round_bracket) || count($check_if_all_is_have_round_bracket) == 0)
                 {
                        
                        $this->set_unit($unit);
                        
                        $this->set_quantity($check_if_all_is_have_round_bracket);
                        
                        $this->set_original_unit($unit);
                        
                        $this->set_original_quantity($quantity);
        
                        $this->set_quantity_by_servingamt($check_if_all_is_have_round_bracket);      
                        
                        $this->set_unit_by_servingamt($unit);      
                        
                        $this->set_quantity_by_servingamt_by_metric($check_if_all_is_have_round_bracket);      
                        
                        $this->set_unit_by_servingamt_by_metric($unit);
                        
                        $this->set_is_countable(false); 
                        
                        
                        $this->set_is_dev_done(true);
                        
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_round_bracket --------------------');
                            echo '</pre>';
                    
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_round_bracket --------------------');
                            echo '</pre>';
                        }
                        
                        return false;     
                         
                 }
                 
                 
                 
                 if(strlen($check_if_all_is_have_round_bracket) != strlen($quantity))
                 {
                        
                
                        /**
                         * Check if original quantity only have round bracket with only numbers value on first position, sample : (3) 4 oz
                         **/
                  
                        if(strpos($quantity,'(') == (int)0)
                        {
                            
                            $this->set_unit($unit);
                            
                            $this->set_quantity($check_if_all_is_have_round_bracket);
                            
                            $this->set_original_unit($unit);
                            
                            $this->set_original_quantity($quantity);
            
//                            $this->set_quantity_by_servingamt($check_if_all_is_have_round_bracket);      
//                            
//                            $this->set_unit_by_servingamt($unit);      
//                            
//                            $this->set_quantity_by_servingamt_by_metric($check_if_all_is_have_round_bracket);      
//                            
//                            $this->set_unit_by_servingamt_by_metric($unit);
                            
                            $this->set_is_countable(true); 
                            
                            $this->split_single_space_to_quantity_and_unit($this->get_quantity());
                            
                            
                            $this->set_is_dev_done(true);
                        
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_all_is_have_round_bracket - "with only numbers value on first position" --------------------');
                                echo '</pre>';
                    
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_all_is_have_round_bracket - "with only numbers value on first position" --------------------');
                                echo '</pre>';
                            }
                            
                            return false;
                        }
                    
                 }
                 
                 
                 
                $split_quantity_by_space = explode(' ',trim($quantity));
                
                /**
                 * Check if original quantity only have one space split, sample : 1 knob
                 **/
                 
                 if($isHaveSpace && (count($split_quantity_by_space) == 2))
                 {
                        $this->split_single_space_to_quantity_and_unit($quantity);
                        
                        return false;
                 }
                 
                 
        
                /**
                 * Check if original quantity only have bracket value, sample : [5]
                 **/
                 
                 //preg_match('/\[[^\]]*\]/', $quantity, $x); 
                 
                 $check_if_all_is_have_bracket = preg_replace('/\[.*\]/','',$quantity);
                 if(!$check_if_all_is_have_bracket || $check_if_all_is_have_bracket == '' || is_null($check_if_all_is_have_bracket) || count($check_if_all_is_have_bracket) == 0)
                 {
                        
                        $this->set_unit($unit);
                        
                        $this->set_quantity($check_if_all_is_have_bracket);
                        
                        $this->set_original_unit($unit);
                        
                        $this->set_original_quantity($quantity);
        
                        $this->set_quantity_by_servingamt($check_if_all_is_have_bracket);      
                        
                        $this->set_unit_by_servingamt($unit);      
                        
                        $this->set_quantity_by_servingamt_by_metric($check_if_all_is_have_bracket);      
                        
                        $this->set_unit_by_servingamt_by_metric($unit);
                        
                        $this->set_is_countable(false); 
                        
                        
                        $this->set_is_dev_done(true);
                        
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_bracket - "[]" --------------------');
                            echo '</pre>';
                    
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_bracket - "[]" --------------------');
                            echo '</pre>';
                        }
                        
                        return false;     
                         
                 }
                 
                 
                 
                 if(strlen($check_if_all_is_have_bracket) != strlen($quantity))
                 {
                
                        /**
                         * Check if original quantity only have [] bracket with only numbers value on first position, sample : [1]1
                         **/
                  
                        if(strpos($quantity,'[') == (int)0)
                        {
                            
                            $this->set_quantity($check_if_all_is_have_bracket);
                            
                            if($this->is_numeric_regex($check_if_all_is_have_bracket))
                            {
                            
                                $this->set_is_countable(true); 
                                
                            }
                            
                            
                            
                            $this->set_is_dev_done(true);
                        
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_all_is_have_bracket - "with only numbers value on first position" --------------------');
                                echo '</pre>';
                    
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                echo '<pre>';
                                print_r('check_if_all_is_have_bracket - "with only numbers value on first position" --------------------');
                                echo '</pre>';
                            }
                            
                            return false;
                        }
                    
                 }
        
                /**
                 * Check if original quantity only have number and alpha, no pace, sample : 100g
                 **/
                 
                 $split_by_number_and_alpha = preg_split('#(?<=\d)(?=[a-z])#i', $quantity);
                 
                 if(!$isHaveSpace && count($split_by_number_and_alpha) == 2)
                 {
                    
                    if(strpos(@$split_by_number_and_alpha[0],'-'))
                    {
                        $possible_split_minus_quantity_array = explode(' - ',@$split_by_number_and_alpha[0]);
                        
                        $possible_split_minus_unit_array = array(@$split_by_number_and_alpha[1],@$split_by_number_and_alpha[1]);
                
                        
                        $this->set_quantity($possible_split_minus_quantity_array);
                        
                        $this->set_unit($possible_split_minus_unit_array);
                        
                        $this->set_is_countable(true);
                        
                        $this->set_is_dev_done(true);
                        
                        $this->format_pattern = 'n-nx';
                        
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_bracket - "condition  only have number and alpha, no pace but have \'-\' split" --------------------');
                            echo '</pre>';
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
            
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('check_if_all_is_have_bracket - "condition  only have number and alpha, no pace but have \'-\' split" --------------------');
                            echo '</pre>'; 
                        }
                 
                        return false;
                    }
                    
                    
                    $this->set_quantity(@$split_by_number_and_alpha[0]);
                    
                    $this->set_unit(@$split_by_number_and_alpha[1]);
                    
                    if($this->is_numeric_regex($split_by_number_and_alpha[0]) || @$this->m->evaluate($split_by_number_and_alpha[0]))
                    {
                    
                        $this->set_is_countable(true); 
                        
                    }
                    
                    // $split_by_number_and_alpha[0]
                    $this->set_is_dev_done(true);
                
                    if($this->is_debug == true && $this->get_is_dev_done() == false) 
                    {
                        
                        echo '<pre>';
                        print_r('check_if_all_is_have_bracket - "with only numbers value on first position" --------------------');
                        echo '</pre>';
            
                        echo '<pre>';
                        print_r('---- DONE ----');
                        echo '</pre>';
                        
                    }
                
                    if($this->is_debug == true && $this->get_is_dev_done() == true) 
                    {
                        echo '<pre>';
                        print_r('check_if_all_is_have_bracket - "with only numbers value on first position" --------------------');
                        echo '</pre>';
                    }
                    
                    return false;
                    
                    
                    
                 }
                 
                 if(strpos(trim($quantity),'/') !== false && strpos(trim($quantity),' ') === false && strpos(trim($quantity),'-') === false && strpos(trim($quantity),'.') === false && strpos(trim($quantity),'&') === false)
                 {
                        
                        $temp_fraction = @$this->m->evaluate($quantity);
                        
                        if($temp_fraction != false)
                        {
                            
                            $this->set_quantity($temp_fraction);
                            
                        }
                        
    
                        $this->set_is_countable(true); 
                        
                        $this->set_is_dev_done(true);
                    
                                
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'/\' only" --------------------');
                            echo '</pre>'; 
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'/\' only" --------------------');
                            echo '</pre>'; 
                        }
                        
                        return false;
                    
                 }
                 
                 
                
                $split_quantity_by_and_char = explode('&',trim($quantity));
                
                /**
                 * Check if original quantity only have one & split, sample : 1 & 1/2
                 **/
                 
                 if(count($split_quantity_by_and_char) == 2)
                 {
                    if(@$this->m->evaluate(trim($split_quantity_by_and_char[0])) && @$this->m->evaluate(trim($split_quantity_by_and_char[1])))
                    {
                         $sum = false;
                         
                         foreach($split_quantity_by_and_char as $c => $d)
                         {
                            $sum += @$this->m->evaluate(trim($d));
                         }
                         
                        
                        
                        $this->set_quantity($sum);
    
                        $this->set_is_countable(true); 
                        
                        $this->set_is_dev_done(true);
                    
                                
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'&\' only" --------------------');
                            echo '</pre>'; 
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'&\' only" --------------------');
                            echo '</pre>'; 
                        }
                        
                         
                        unset($sum);
                         
                        return false;
                    }
                 
                   
                 }
                     
                     
                /**
                 * Check if original quantity have '&' and space , sample : 1 & 1/2 inch
                 **/
             
                 if(strpos(trim($quantity),'&') !== false && count($split_quantity_by_space) == 4)
                 {
                
                    if(@$this->m->evaluate(trim($split_quantity_by_space[0])) && @$this->m->evaluate(trim($split_quantity_by_space[2])))
                    {
                         $sum = @$this->m->evaluate(trim($split_quantity_by_space[0])) + @$this->m->evaluate(trim($split_quantity_by_space[2]));
                        
                        
                        $this->set_quantity($sum);
                        
                        $this->set_unit($split_quantity_by_space[3]);
    
                        $this->set_is_countable(true); 
                        
                        $this->set_is_dev_done(true);
                    
                                
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_char_and_fraction - "condition \'&\' only" --------------------');
                            echo '</pre>'; 
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            echo '<pre>';
                            print_r('only_quantity_with_char_and_fraction - "condition \'&\' only" --------------------');
                            echo '</pre>'; 
                        }
                        
                         
                        unset($sum);
                         
                        return false;
                    }
                 
                    
                 }
                     
                     
                $split_quantity_by_plus_char = explode('+',trim($quantity));
                
                /**
                 * Check if original quantity only have one + split, sample : 1 + 1/2
                 **/
                 
                 if(count($split_quantity_by_plus_char) == 2)
                 {
                    if(@$this->m->evaluate(trim($split_quantity_by_plus_char[0])) && @$this->m->evaluate(trim($split_quantity_by_plus_char[1])))
                    {
                         $sum = false;
                         
                         foreach($split_quantity_by_plus_char as $c => $d)
                         {
                            $sum += @$this->m->evaluate(trim($d));
                         }
                         
                        
                        
                        $this->set_quantity($sum);
    
                        $this->set_is_countable(true); 
                        
                        $this->set_is_dev_done(true);
                    
                                
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'+\' only" --------------------');
                            echo '</pre>'; 
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            echo '<pre>';
                            print_r('only_quantity_with_fraction - "condition \'+\' only" --------------------');
                            echo '</pre>'; 
                        }
                         
                        unset($sum);
                         
                        return false;
                        
                    } elseif(@$this->m->evaluate(trim($split_quantity_by_plus_char[0])) == false && @$this->m->evaluate(trim($split_quantity_by_plus_char[1])) == false)
                    {
                        $temp_plus_split = false;
                        
                        foreach($split_quantity_by_plus_char as $k => $v)
                        {
                        
                            $temp_plus_split[$k] = trim($v);
                            
                        }
                        
                        unset($k);
                        unset($v);
                        
                        /**
                         * Manage Split content
                         **/
                         
                        foreach($temp_plus_split as $k => $v)
                        {
                            
                            $temp_plus_split_only_number[$k] = trim(ereg_replace("[[:alpha:]]", "", $v));
                            
                            $temp_plus_split_only_alpha[$k] = trim(str_replace($temp_plus_split_only_number[$k],'',$v));
                            
                            if(strpos(trim($temp_plus_split_only_number[$k]),'-') == false && @$this->m->evaluate(str_replace(' ',' + ',$temp_plus_split_only_number[$k])) != false)
                            {
                                $temp_plus_split_only_number[$k] = @$this->m->evaluate(str_replace(' ',' + ',$temp_plus_split_only_number[$k]));
                                
                                $this->set_is_countable(true); 
                                
                            } else {
                                
                                if(strpos(trim($temp_plus_split_only_number[$k]),'-'))
                                {
                                    
                                    $temp_split_minus_array = explode('-',$temp_plus_split_only_number[$k]);
                                    
                                    foreach($temp_split_minus_array as $k => $v)
                                    {
                                        if(@$this->m->evaluate($v) === false)
                                        {
                                            
                                            $this->set_is_countable(false);
                                            
                                            continue;
                                            
                                        } else {
                                            
                                            $this->set_is_countable(true);
                                            
                                        }
                                    }
                                    
                                    unset($temp_split_minus_array);
                                    unset($k);
                                    unset($v);
                                    
                                } else {
                                    
                                    $this->set_is_countable(false); 
                                }
                                
                                continue;
                                
                            }
                            
                        }
                 
                        
                    
            
                        $this->set_quantity($temp_plus_split_only_number);
                        
                        $this->set_unit($temp_plus_split_only_alpha);
                        
                        unset($k);
                        
                        unset($v);
                        
                        unset($temp_plus_split_only_number);
                        
                        unset($temp_plus_split_only_alpha);
                        
                    
                        $this->set_is_dev_done(true);
                        
                        $this->format_pattern = 'n x + n x';
                                
                        if($this->is_debug == true && $this->get_is_dev_done() == false) 
                        {
                            
                            echo '<pre>';
                                print_r('only_quantity_with_fraction - "condition \'+\' only with possible 2 quantity & 2 unit , increase mode " --------------------');
                            echo '</pre>'; 
                            
                            echo '<pre>';
                            print_r('---- DONE ----');
                            echo '</pre>';
                            
                        }
                    
                        if($this->is_debug == true && $this->get_is_dev_done() == true) 
                        {
                            
                            echo '<pre>';
                                print_r('only_quantity_with_fraction - "condition \'+\' only with possible 2 quantity & 2 unit , increase mode " --------------------');
                            echo '</pre>'; 
                        }
                        
                        return false;
                    }
                   
                 }
                 
                 
                
                /**
                 * Check if original quantity have round bracket '(any)' and multiple values, sample : 1 (10 pound)
                 **/
                 
                
                 preg_match('#\(.*\)#',$quantity, $check_if_quantity_have_round_bracket_and_multi_value);
                 
                 if(count($check_if_quantity_have_round_bracket_and_multi_value) == (int)1)
                 {
                    $temp_one = trim(str_replace($check_if_quantity_have_round_bracket_and_multi_value[0],'',$quantity));
                    
                    $temp_one = trim(str_replace('  ',' ',$temp_one));
                    
                    $temp_one_only_number = trim(ereg_replace("[[:alpha:]]", "", $temp_one));
                    
                    $temp_one_rest = trim(ereg_replace($temp_one_only_number, "", $temp_one));
                    
                    $temp_one_is_numeric = $this->is_numeric_regex($temp_one_only_number);
                    
                    
                    
                    $possible_quantity_one = $temp_one_only_number;
                    
                    $possible_unit_one = $temp_one_rest;
                    
                    
                    
                    if(strpos((trim($temp_one)),'-') !== false)
                    {
                        $possible_final_quantity_one = $temp_one_only_number;
                        
                    } else {
                    
                        $possible_final_quantity_one = ($this->is_numeric_regex($temp_one_only_number) == true ? $temp_one_only_number : @$this->m->evaluate(str_replace(' ','+',$temp_one_only_number)));
                        
                    }
                    
                    $possible_final_unit_one = $temp_one_rest;
                    
                    
                    $temp_two = trim($check_if_quantity_have_round_bracket_and_multi_value[0]);
                    
                    $temp_two = trim(str_replace(array('(',')'),'',$temp_two));
                    
                    $temp_two = trim(str_replace('  ',' ',$temp_two));
                    
                    $temp_two = trim(str_replace('-ounce',' ounce',$temp_two));
                    
                    
                    $temp_two_only_number = trim(ereg_replace("[[:alpha:]]", "", $temp_two));
                    
                    $temp_two_rest = trim(@ereg_replace($temp_two_only_number, "", $temp_two));
                    
                    
//                    $possible_quantity_two = $temp_two_only_number;
//                    
//                    $possible_unit_two = $temp_two_rest;
                    
                    
                    
                    if(strpos((trim($temp_two)),'-') !== false)
                    {
                        
                        if($temp_two_rest == $temp_two)
                        {
                            
                            
                            $result_quantity_explode = explode(' - ',$temp_two_only_number);
                            
                            $possible_final_quantity_two = $result_quantity_explode;
                            
                            unset($result_quantity_explode);
                            
                            unset($result_unit);
                            
                            
    
                            $result_unit = preg_replace("/[^a-zA-Z\-]+/", "", $temp_two);
                            
                            $result_unit_explode = explode('-',$result_unit);
                            
                            
                            $possible_final_unit_two = $result_unit_explode;
                            
                            unset($result_unit_explode);
                            
                            unset($result_unit);
                            
                            
                        }
                        
                    } else {
                    
                        $possible_final_quantity_two = ($this->is_numeric_regex($temp_two_only_number) == true ? $temp_two_only_number : @$this->m->evaluate(str_replace(' ','+',$temp_two_only_number)));
                    
                        $possible_final_unit_two = $temp_two_rest;
                        
                    }
                    
                    if($possible_final_quantity_one == '' || $possible_final_quantity_one == false)
                    {
                            
                        if($possible_final_quantity_two == '' || $possible_final_quantity_two == false)
                        {
                            
                            
                            $this->set_quantity($this->get_original_quantity());
                            
                            $this->set_unit($this->get_original_unit());
                            
                            $this->set_is_countable(false);
                            
                            $this->set_is_dev_done(true);
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                            }
                            
                            return false;
                    
                        } else {                            
                            
                            $this->set_quantity($this->get_original_quantity());
                            
                            $this->set_unit($this->get_original_unit());
                            
                            $this->set_is_countable(false);
                            
                            $this->set_is_dev_done(true);
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                            }
                            
                            return false;
                        }
                                            
                    } else {
                        
                    
                        if($possible_final_quantity_two == '' || $possible_final_quantity_two == false)
                        {
                        
                            
                            $possible_final_quantity = $possible_final_quantity_one;
                            
                            $possible_final_unit = $possible_final_unit_one;
                            
                            
                            $this->set_quantity($possible_final_quantity);
                            
                            $this->set_unit($possible_final_unit);
                            
                            $this->set_is_countable(true);
                            
                            $this->set_is_dev_done(true);
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 1 first array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 1 first array quantity & unit" --------------------');
                                echo '</pre>';  
                            }
                            
                            return false;
                            
                        } else {
                        
                            
                            $possible_final_quantity[0] = $possible_final_quantity_one;
                            
                            $possible_final_quantity[1] = $possible_final_quantity_two;
                            
                            $possible_final_unit[0] = $possible_final_unit_one;
                            
                            $possible_final_unit[1] = $possible_final_unit_two;
                            
                            
                            
                            
                            $this->set_quantity($possible_final_quantity);
                            
                            $this->set_unit($possible_final_unit);
                            
                            $this->set_is_countable(true);
                            
                            $this->set_is_dev_done(true);
                            
                            $this->format_pattern = 'n (n x) x';
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 2 array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('check_if_quantity_have_round_bracket_and_multi_value - "condition (), result 2 array quantity & unit" --------------------');
                                echo '</pre>'; 
                            }
                     
                            return false;
                            
                        }
                        
                        unset($possible_final_quantity);
                        
                        unset($possible_final_unit);
                        
                        
                    }                   
                        
                    unset($temp_one);
                    unset($temp_one_only_number);
                    unset($temp_one_rest);
                    unset($temp_one_is_numeric);
                    unset($possible_quantity_one);
                    unset($possible_unit_one);
                    unset($possible_final_quantity_one);
                    unset($possible_final_unit_one);
                    
                    unset($temp_two);
                    unset($temp_two_only_number);
                    unset($temp_two_rest);
                    unset($temp_two_is_numeric);
                    unset($possible_quantity_two);
                    unset($possible_unit_two);
                    unset($possible_final_quantity_two);
                    unset($possible_final_unit_two);
                     
                    return false;
                    
                    
                    
                 }
                 
                 
                
                /**
                 * Check if original quantity have '-' and space ' ', sample : 1 - 1 1/2 tsp
                 **/
                if(strpos((trim($quantity)),'-') !== false)
                {
                    
                    $temp_quantity = str_replace('-',' - ',$quantity);
                    $temp_quantity = str_replace('  ',' ',$temp_quantity);
                    
                    $temp_quantity_number_only =  trim(ereg_replace("[[:alpha:]]", "", $temp_quantity));
                    
                    $temp_quantity_number_only = str_replace('  ',' ',$temp_quantity_number_only);
                    
                    $temp_quantity_rest =  trim(str_replace($temp_quantity_number_only,'',$temp_quantity));
                    
                    if($temp_quantity_rest == $temp_quantity)
                    {
                        $result = preg_replace("/[^a-zA-Z\-]+/", "", $temp_quantity);
                        
                        $result_explode = explode('-',$result);
                        
//                        $result_final = array_unique($result_explode);
//                        
//                        if(count($result_final) == 1)
//                        {
//                            $temp_quantity_rest = $result_explode[0];
//                        }
//                        
//                        
//                        unset($result_final);
                        
                        $temp_quantity_rest = $result_explode;
                        
                        unset($result_explode);
                        
                        unset($result);
                    }
                    
                    $temp_quantity_number_only_explode_by_minus = explode(' - ',$temp_quantity_number_only);
                    
                        
                    $quantity_number_only_array = false;
                    
                    foreach($temp_quantity_number_only_explode_by_minus as $k => $v)
                    {
                        $v = str_replace('&',' ',$v);
                        $v = str_replace('   ',' ',$v);
                        $v = str_replace('  ',' ',$v);
                        
                        $v_explode = explode(' ',$v);
                        
                        if(count($v_explode) > 1)
                        {
                            $v_explode_combine = implode(' + ',$v_explode);
                            
                            $v_explode_combine_count = @$this->m->evaluate($v_explode_combine);
                            
                            if($v_explode_combine_count)
                            {
                                $v = $v_explode_combine_count;
                            }
                            
                        } else {
                            
                            $v = @$this->m->evaluate($v);
                            
                        }
                        
                        $quantity_number_only_array[] = $v;
                        
                    }
                    
                    //$quantity_number_only = implode(' - ',$quantity_number_only_array);
                    
                    $quantity_number_only = $quantity_number_only_array;
                    
                    foreach($quantity_number_only_array as $kkk => $vvv)
                    {
                        if($this->is_numeric_regex($vvv) == false)
                        {
                            $this->set_is_countable(false); 
                            
                            continue;
                        } else {
                            
                            $this->set_is_countable(true); 
                            
                        }
                    }
                    
        
                    $this->set_quantity($quantity_number_only);
                    
                    $this->set_unit($temp_quantity_rest);
                
                    $this->set_is_dev_done(true);
                    
                    $this->format_pattern = 'n - n x';
                            
                    if($this->is_debug == true && $this->get_is_dev_done() == false) 
                    {
                        
                        echo '<pre>';
                        print_r('check_if_original_quantity_is_have_minus_split "Check if original quantity have \'-\' and space \' \'"--------------------');
                        echo '</pre>'; 
                        
                        echo '<pre>';
                        print_r('---- DONE ----');
                        echo '</pre>';
                        
                    }
                
                    if($this->is_debug == true && $this->get_is_dev_done() == true) 
                    {
                        
                        echo '<pre>';
                        print_r('check_if_original_quantity_is_have_minus_split "Check if original quantity have \'-\' and space \' \'"--------------------');
                        echo '</pre>'; 
                    }
                    
                    unset($quantity_number_only_array);
                    unset($kkk);
                    unset($vvv);
                    unset($quantity_number_only);
                    unset($v);
                    unset($k);
                    unset($v_explode_combine_count);
                    unset($v_explode_combine);
                    unset($v_explode);
                    
                    
                    
                    unset($temp_quantity);
                    unset($temp_quantity_number_only);
                    unset($temp_quantity_rest);
                    unset($temp_quantity_number_only_explode_by_minus);
                    unset($temp_quantity_number_only);
                    
                    return false;
                    
                }
                
                 
                
                /**
                 * Check if original quantity have space ' ' more than 2, sample : '1 1/2 bowls'
                 **/
                 
                 if(count($split_quantity_by_space) == 3 && @$this->m->evaluate($split_quantity_by_space[0]) != false  && @$this->m->evaluate($split_quantity_by_space[1]) != false )
                 {
                            $temp_quantity = @$this->m->evaluate($split_quantity_by_space[0]) + @$this->m->evaluate($split_quantity_by_space[1]);
                            if(@$this->m->evaluate($split_quantity_by_space[2]) == false)
                            {
                                
                                $this->set_unit($split_quantity_by_space[2]); 
                            
                                $this->set_quantity($temp_quantity);
                               
                            } else {
                                
                                $temp_quantity = $temp_quantity + @$this->m->evaluate($split_quantity_by_space[2]);
                            
                                $this->set_quantity($temp_quantity);
                                
                            }
                            
                            unset($temp_quantity);
                            
                            $this->set_is_countable(true); 
                            
                            $this->set_is_dev_done(true);
                        
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_space = 3 - "with only numbers value on first & two position" --------------------');
                                echo '</pre>';
                    
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                        
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_space = 3 - "with only numbers value on first & two position" --------------------');
                                echo '</pre>';
                            }
                            
                            return false;
                 
                    
                 }
                 
                 
                
                /**
                 * Check if original quantity have space ' ' more than 2 and possible unit have space , sample : "1 cup of"
                 **/
                 
                $quantity_filter_alpha = ereg_replace("[[:alpha:]]", "", $quantity);
                
                $quantity_filter_numeric = str_replace(trim($quantity_filter_alpha), '', $quantity);
                
                $quantity_filter_alpha = trim($quantity_filter_alpha);
                
                $quantity_filter_numeric = trim($quantity_filter_numeric);
                
                
                if($quantity_filter_numeric != $quantity)
                {
                    
                    
                    if(strpos($quantity_filter_alpha,'%') !== false)
                    {
                        $quantity_filter_alpha = trim(str_replace('%','/100',$quantity_filter_alpha));
                    }
                    
                    
                    if(strpos($quantity_filter_alpha,':') !== false)
                    {
                        $quantity_filter_alpha = trim(str_replace(':','',$quantity_filter_alpha));
                    }
                    
                    if(strpos($quantity_filter_alpha,' ') !== false)
                    {
                        $quantity_filter_alpha = trim(str_replace(' ','+',$quantity_filter_alpha));
                    }
                    
                    
                    $quantity_filter_alpha = preg_replace('!\s+!', ' ', $quantity_filter_alpha); // Remove double space
                    
                    if(@$this->m->evaluate($quantity_filter_alpha))
                    {
                    
                        $quantity_filter_alpha = @$this->m->evaluate($quantity_filter_alpha);
                        
                    }
                    
                    $quantity_filter_numeric = preg_replace('!\s+!', ' ', $quantity_filter_numeric); // Remove double space
                                        
                    $this->set_quantity($quantity_filter_alpha);
                        
                    $this->set_unit($quantity_filter_numeric);
                    $this->set_is_countable(true); 
                    
                    $this->set_is_dev_done(true);
                    
                    
                    
                    unset($quantity_filter_alpha);
                    
                    unset($quantity_filter_numeric);
                    
                            
                    if($this->is_debug == true && $this->get_is_dev_done() == false) 
                    {
                        
                        echo '<pre>';
                        print_r('is_have_space_more_than_two - "Check if original quantity have space \' \' more than 2 and possible unit have space" --------------------');
                        echo '</pre>'; 
                        
                        echo '<pre>';
                        print_r('---- DONE ----');
                        echo '</pre>';
                        
                    }
            
                    if($this->is_debug == true && $this->get_is_dev_done() == true) 
                    {
                        
                        echo '<pre>';
                        print_r('is_have_space_more_than_two - "Check if original quantity have space \' \' more than 2 and possible unit have space" --------------------');
                        echo '</pre>'; 
                    }
                     
                    return false;
                }
                 
                
                /**
                 * Check if original quantity have ' X ' or '/' and multiple values, sample : 1 X 10 pound or 1ml/12g
                 **/
                
                /**
                 * Base on : http://www.php.net/manual/en/function.explode.php#102590
                 **/
                 
                
                 
                $split_quantity_by_x = explode(' x ',trim($quantity));
                
                $slash_pattern = '/[:\/]/';  // colon (:), pipe (escaped '\|'), slashes (escaped '\\\\' and '\/'), white space (\s)
                 
                $split_quantity_by_slash = $this->multi_explode($slash_pattern, trim($quantity));
                
                
                $split_array = false;
                
                if(count($split_quantity_by_x) == 2)
                {
                    $split_array = $split_quantity_by_x;
                }
                
                if(count($split_quantity_by_slash) == 2)
                {
                    $split_array = $split_quantity_by_slash;
                }
                
                
                if($split_array)
                {
                    
                    // TEMP ONE START
                    
                    $split_array_temp_one = trim($split_array[0]);
                    
                    $split_array_temp_one_only_number = trim(ereg_replace("[[:alpha:]]", "", $split_array_temp_one));
                    
                    $split_array_temp_one_rest = trim(ereg_replace($split_array_temp_one_only_number, "", $split_array_temp_one));
                    
                    
                    $split_array_temp_one_is_numeric = $this->is_numeric_regex($split_array_temp_one_only_number);
                    
                    
                    
                    
                    $possible_quantity_one = $split_array_temp_one_only_number;
                    
                    $possible_unit_one = $split_array_temp_one_rest;
                    
                    
                    if(strpos((trim($split_array_temp_one)),'-') !== false)
                    {
                        $possible_final_quantity_one = $split_array_temp_one_only_number;
                        
                    } else {
                    
                        $possible_final_quantity_one = ($this->is_numeric_regex($split_array_temp_one_only_number) == true ? $split_array_temp_one_only_number : @$this->m->evaluate(str_replace(' ','+',$split_array_temp_one_only_number)));
                        
                    }
                    
                    $possible_final_unit_one = $split_array_temp_one_rest;
                    
                    
//                    echo '<pre>';
//                    var_dump($possible_final_quantity_one);
//                    echo '</pre>';
//                    
//                    echo '<pre>';
//                    var_dump($possible_final_unit_one);
//                    echo '</pre>';
//                    
//                    
//                    echo '<hr/>';
                    
                    // TEMP ONE END
                    
                    // TEMP START TWO
                    
                    
                    $split_array_temp_two = trim($split_array[1]);
                    
                    $split_array_temp_two_only_number = trim(ereg_replace("[[:alpha:]]", "", $split_array_temp_two));
                    
                    $split_array_temp_two_rest = trim(ereg_replace($split_array_temp_two_only_number, "", $split_array_temp_two));
                    
                    
                    $split_array_temp_two_is_numeric = $this->is_numeric_regex($split_array_temp_two_only_number);
                    
                    
                    
                    
                    $possible_quantity_two = $split_array_temp_two_only_number;
                    
                    $possible_unit_two = $split_array_temp_two_rest;
                    
                    
                    if(strpos((trim($split_array_temp_two)),'-') !== false)
                    {
                        $possible_final_quantity_two = $split_array_temp_two_only_number;
                        
                    } else {
                    
                        $possible_final_quantity_two = ($this->is_numeric_regex($split_array_temp_two_only_number) == true ? $split_array_temp_two_only_number : @$this->m->evaluate(str_replace(' ','+',$split_array_temp_two_only_number)));
                        
                    }
                    
                    $possible_final_unit_two = $split_array_temp_two_rest;
                    
//                    echo '<pre>';
//                    var_dump($possible_final_quantity_two);
//                    echo '</pre>';
//                    
//                    echo '<pre>';
//                    var_dump($possible_final_unit_two);
//                    echo '</pre>';
//                    
//                    
//                    echo '<hr/>';
                    
                    // TEMP START END
                    
                    
                    if($possible_final_quantity_one == '' || $possible_final_quantity_one == false)
                    {
                            
                        if($possible_final_quantity_two == '' || $possible_final_quantity_two == false)
                        {
                            
                            
                            $this->set_quantity($this->get_original_quantity());
                            
                            $this->set_unit($this->get_original_unit());
                            
                            $this->set_is_countable(false);
                            
                            $this->set_is_dev_done(true);
                            
                            $this->format_pattern = 'n x X n x';
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>';
                            }
                            
                            return false;
                    
                        } else {                            
                            
                            $this->set_quantity($this->get_original_quantity());
                            
                            $this->set_unit($this->get_original_unit());
                            
                            $this->set_is_countable(false);
                            
                            $this->set_is_dev_done(true);
                            
                            $this->format_pattern = 'n x X n x';
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                            }
                            
                            return false;
                        }
                                            
                    } else {
                        
                    
                        if($possible_final_quantity_two == '' || $possible_final_quantity_two == false)
                        {
                        
                            
                            $possible_final_quantity = $possible_final_quantity_one;
                            
                            $possible_final_unit = $possible_final_unit_one;
                            
                            
                            $this->set_quantity($possible_final_quantity);
                            
                            $this->set_unit($possible_final_unit);
                            
                            $this->set_is_countable(true);
                            
                            $this->set_is_dev_done(true);
                            
                            $this->format_pattern = 'n x X n x';
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>';
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>';  
                            }
                            
                            return false;
                            
                        } else {
                        
                            
                            $possible_final_quantity[0] = $possible_final_quantity_one;
                            
                            $possible_final_quantity[1] = $possible_final_quantity_two;
                            
                            $possible_final_unit[0] = $possible_final_unit_one;
                            
                            $possible_final_unit[1] = $possible_final_unit_two;
                            
                            
                            $this->set_quantity($possible_final_quantity);
                            
                            $this->set_unit($possible_final_unit);
                            
                            $this->set_is_countable(true);
                            
                            $this->set_is_dev_done(true);
                            
                            $this->format_pattern = 'n x X n x';
                            
                            if($this->is_debug == true && $this->get_is_dev_done() == false) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>';
                                
                                echo '<pre>';
                                print_r('---- DONE ----');
                                echo '</pre>';
                                
                            }
                
                            if($this->is_debug == true && $this->get_is_dev_done() == true) 
                            {
                                
                                echo '<pre>';
                                print_r('split_quantity_by_x or split_quantity_by_slash - "condition  \' X \' or \'/\', result 0 array quantity & unit" --------------------');
                                echo '</pre>'; 
                            }
                     
                            return false;
                            
                        }
                        
                        unset($possible_final_quantity);
                        
                        unset($possible_final_unit);
                        
                        
                    }                   
                        
                    unset($split_array_temp_one);
                    unset($split_array_temp_one_only_number);
                    unset($split_array_temp_one_rest);
                    unset($split_array_temp_one_is_numeric);
                    unset($possible_quantity_one);
                    unset($possible_unit_one);
                    unset($possible_final_quantity_one);
                    unset($possible_final_unit_one);
                    
                        
                    unset($split_array_temp_two);
                    unset($split_array_temp_two_only_number);
                    unset($split_array_temp_two_rest);
                    unset($split_array_temp_two_is_numeric);
                    unset($possible_quantity_two);
                    unset($possible_unit_two);
                    unset($possible_final_quantity_two);
                    unset($possible_final_unit_two);
                     
                    return false;
                    
                }
                
                
                /**
                 * If condition else, non countable
                 **/
                 
                    
                $this->set_quantity($this->get_original_quantity());
                    
                $this->set_is_countable(false); 
                
                $this->set_is_dev_done(true);
                        
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('original_quantity_not_countable --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('original_quantity_not_countable --------------------');
                    echo '</pre>'; 
                }
                
                return false;
            } else {
                    
                $this->set_quantity($quantity);
                    
                $this->set_is_countable(true); 
                
                $this->set_is_dev_done(true);
                        
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('normal_numeric_input --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('normal_numeric_input --------------------');
                    echo '</pre>'; 
                }
                
                return false;
            }
            
        } else {
                
                // Clean quantity
                
                
                $item_quantity = ereg_replace("[[:alpha:]]", "", $quantity);
                
                if(substr($item_quantity,0,1) == '.')
                {
                    $item_quantity = substr($item_quantity,1);
                }
                
                if(substr($item_quantity, -1) == '.')
                {
                    $item_quantity = substr($item_quantity,0,-1);
                }
                
                $item_unit = $unit;
        
                $this->set_quantity($item_quantity);
                
                $this->set_unit($item_unit);
                
                $this->set_is_dev_done(true);
                        
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('normal_numeric_input --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('normal_numeric_input --------------------');
                    echo '</pre>';
                }
                
                return false;
                
        }
        
    }
    
    /**
     * Based on : http://www.php.net/manual/en/function.explode.php#102590
     **/
     
    // function to explode on multiple delimiters
    function multi_explode($pattern, $string, $standardDelimiter = ':')
    {
        // replace delimiters with standard delimiter, also removing redundant delimiters
        $string = preg_replace(array($pattern, "/{$standardDelimiter}+/s"), $standardDelimiter, $string);
    
        // return the results of explode
        return explode($standardDelimiter, $string);
    }
    
    public function split_single_space_to_quantity_and_unit($value)
    {
        if(!$this->isHaveSpace($value))
        {
            return false;
        }
        
        $temp = explode(' ',trim($value));
        $is_numeric = false;
        
        $is_numeric = @$this->is_numeric_regex(trim(@$temp[0]));
        
        if($is_numeric)
        {
            
        } elseif(strpos(trim(@$temp[0]),'/') !== false && @$this->m->evaluate(trim(@$temp[0])))
        {
                        
            $is_numeric = true;
        } elseif(@$this->m->evaluate(trim(@$temp[0])) == false)
        {
            if(strpos(trim($value),'/') !== false)
            {
        
                $temp_split_forward_slash = explode('/',trim($value));
                
                foreach($temp_split_forward_slash as $k => $v)
                {
                    $quantity_filter_alpha_array[$k] = trim(ereg_replace("[[:alpha:]]", "", trim($v)));
                    $quantity_filter_numeric_array[$k] = trim(str_replace(trim($quantity_filter_alpha_array[$k]), '', trim($v)));
                }
                
                
                            
                $this->set_quantity($quantity_filter_alpha_array);
                
                $this->set_unit($quantity_filter_numeric_array);
                
                $this->set_is_countable(true);
                
                $this->set_is_dev_done(true);
                
                $this->format_pattern = 'n x / n x';
                            
                
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "Split space not auto get unit and \'/\' is real split" --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                     echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "Split space not auto get unit and \'/\' is real split" --------------------');
                    echo '</pre>';
                }
                 
                return false;
                
            } else {
                
                $quantity_filter_alpha = ereg_replace("[[:alpha:]]", "", trim($value));
                
                $quantity_filter_numeric = str_replace(trim($quantity_filter_alpha), '', trim($value));
                
                $quantity_filter_alpha = trim($quantity_filter_alpha);
                
                $quantity_filter_numeric = trim($quantity_filter_numeric);
                
                
                    
                $quantity_filter_alpha = preg_replace('!\s+!', ' ', $quantity_filter_alpha); // Remove double space
                
                if(@$this->m->evaluate($quantity_filter_alpha))
                {
                
                    $quantity_filter_alpha = @$this->m->evaluate($quantity_filter_alpha);
                    
                }
                
                $quantity_filter_numeric = preg_replace('!\s+!', ' ', $quantity_filter_numeric); // Remove double space
                                    
                $this->set_quantity($quantity_filter_alpha);
                    
                $this->set_unit($quantity_filter_numeric);
                $this->set_is_countable(true); 
                
                $this->set_is_dev_done(true);
                
                
                
                unset($quantity_filter_alpha);
                
                unset($quantity_filter_numeric);
                
                        
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "Split space not auto get unit" --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "Split space not auto get unit" --------------------');
                    echo '</pre>'; 
                }
                 
                return false;
                
            }
                    
        }
        
        
        $this->set_is_countable($is_numeric); 
            
        if(count($temp) == 2 && $is_numeric)
        {
            $is_temp_is_countable = @$this->m->evaluate(trim(@$temp[1]));
            
            if($is_temp_is_countable)
            {
                $this->set_is_dev_done(true);
                            
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "one space & all split countable" --------------------');
                    echo '</pre>';
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                
                        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "one space & all split countable" --------------------');
                    echo '</pre>';
                }
                
                $new_quantity = $is_temp_is_countable + @$this->m->evaluate(trim(@$temp[0]));
            
                $this->set_quantity((float)$new_quantity); 
                
                return false;
                
            } else {
                
                $this->set_is_dev_done(true);
                            
                if($this->is_debug == true && $this->get_is_dev_done() == false) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "one space" --------------------');
                    echo '</pre>'; 
                    
                    echo '<pre>';
                    print_r('---- DONE ----');
                    echo '</pre>';
                    
                }
                        
                if($this->is_debug == true && $this->get_is_dev_done() == true) 
                {
                    
                    echo '<pre>';
                    print_r('split_single_space_to_quantity_and_unit - "one space" --------------------');
                    echo '</pre>'; 
                }
                
                $this->set_quantity(@$this->m->evaluate(trim(@$temp[0])));  
                
                $this->set_unit(trim(@$temp[1]));
                
                return false;
                
            } 
            
        }
    }
    
    /**
     * =================================================================================== *
     * ================================== CONVERT UNITS ================================== *
     * =================================================================================== */
    
    public static function SortingMetricToUs( $metric_to_us, $original_unit )
    {
	$index = array_search( $original_unit, array_keys($metric_to_us) );
	
	$m1 = array_slice( $metric_to_us, 0, $index );
	$m2 = array_slice( $metric_to_us, $index );
	$c = array_merge($m1, $m2);
    
	$sorting = array();
	foreach( $c as $k => $v )
	{
	    if( $k == $original_unit ){
		$sorting[$original_unit] = $v;
	    }
	    
	    $_unit = array_values( $v ); $unit = $_unit[0];
	    if( array_key_exists( $unit, $sorting ) ){
		
		$sorting[$k] = $v;
	
	    } else {
		
		$sorting[$unit] = $c[$unit];
		
	    }
	}
	
	return $sorting;
    }
    
    public function get_all_unit_converter()
    {
	$units = array();
	
	$original_unit = $this->get_original_unit();
	$metric_unit   = $this->get_unit_by_servingamt_by_metric();
	$metric_qty    = $this->get_quantity_by_servingamt_by_metric();
	
	if ( $this->get_metric() == 'us' && array_key_exists( $metric_unit, $this->metric_to_us ) )
	{
	    $metric_to_us = sortingMetricToUs( $this->metric_to_us, $original_unit );
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
		    // do convert
		    $qty = floatval( $_qty ) / floatval( key($metric_to_us[$unit]) );
		    $units[$unit] = _number_format( $qty );
		    
		}
	    }
	
	    // remove original n metric units
	    unset( $units[$original_unit] );
	    unset( $units[$metric_unit] );
	}
	
	return $metric_to_us;
    }
    
    /* http://www.php.net/manual/en/function.is-numeric.php#86796 */
    
    public function is_numeric_regex($str) { 
        $str    = "{$str}"; 
    
        if (in_array($str[0], array('-', '+')))    $str = "{$str[0]}0" . substr($str, 1); 
        else $str = "0{$str}"; 
    
        $eng    = preg_match ("/^[+,-]{0,1}([0-9]+)(,[0-9][0-9][0-9])*([.][0-9]){0,1}([0-9]*)$/" , $str) == 1; 
        $world    = preg_match ("/^[+,-]{0,1}([0-9]+)(.[0-9][0-9][0-9])*([,][0-9]){0,1}([0-9]*)$/" , $str) == 1; 
    
        return ($eng or $world); 
    } 
    
    
    public function debug($var, $type = 'var_dump') {
        echo '<pre>';
        
        if($type == 'var_dump') {
            var_dump($var);
        } 
		else {
            print_r($var);
        }
        
        echo '</pre>';
    }
    
    
}


?>