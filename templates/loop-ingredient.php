<tr>
    <?php
    $index = $post->ID;
    
    /* Amount */
    // unserialize post meta(@return array)		
    $amount = unserialize(get_post_meta($index, 'ingredient-amount', true)); 
    $ingredient = array(
        'qty' => $amount['amount'] . ' ' . $amount['size'],
        'name' => ucwords($post->post_title)
    );
	
    /* Alternative */	
    $_alt = get_post_meta($index, 'ingredient-alternative', true);
    $alternative = unserialize($_alt);
    
    if( $alternative ){
	foreach($alternative as $item) {
	    $qty_alt[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
	    $indgnt_alt[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
	}
    }
    
    /* Healthy */
    $_hlt = get_post_meta($index, 'ingredient-healthy', true);
    $healthy = unserialize($_hlt);
    
    if( $healthy ){
	foreach($healthy as $item) {
	    $qty_hlt[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
	    $indgnt_hlt[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
	}
    }
    
    /* Vegan */
    $_vgn = get_post_meta($index, 'ingredient-vegan', true);
    $vegan = unserialize($_vgn);
    
    if( $vegan ){
	foreach($vegan as $item) {
	    $qty_vgn[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
	    $indgnt_vgn[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
	}
    }
    
    // show html   
    include( 'row-ingredient.php' );		
    ?>		
</tr>