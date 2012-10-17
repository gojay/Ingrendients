<!-- looping substitutions of ingredients -->
<?php $index = 0; foreach( $ingredients['substitutions'] as $subs ) : ?>
	
	<!-- [header] original ingredient -->
	<?php if( $_ingredient = $subs['ori'] ) : ?>
	
		<tr style="background-color:#eee">
		
			<td class="coloumn-center" colspan="4">
				<?php echo $_ingredient['qty'] . ' ' . $_ingredient['unit'] ; ?> 
				<a href="#"><?php echo $_ingredient['desc'] ?></a>
			</td>
			
		</tr>
		
	<?php endif; // end header
	
	// substitutions ingredient
	if($subs['subs']) :	
		
		// looping substitutions
		foreach( $subs['subs'] as $subtitution ) : ?>
	
		<tr <?php if( isset($subtitution['ratio']) ) { echo 'style="background-color:#F0F2FF"'; } ?>>
			<?php                         
				// amount
				$amount = $subtitution['amount'];
				$ingredient = array(
					'qty' => (($amount['amount']) ? $amount['amount'] : 'N/A') . ' ' . $amount['size'],
					'name' =>  ucwords($subtitution['ingredient'])
				);
				
				// alternative
				foreach($subtitution['alternative'] as $item) {
					$qty_alt[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
					$indgnt_alt[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
				}
				
				// healthy
				foreach($subtitution['healthy'] as $item) {
					$qty_hlt[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
					$indgnt_hlt[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
				}
				
				// vegan
				foreach($subtitution['vegan'] as $item) {
					$qty_vgn[$index][] = ($item['qty']) ? $item['qty'] : 'N/A' ;
					$indgnt_vgn[$index][] = sprintf('<div class="fittext">%s</div>', $item['size'] . ' ' . $item['val']);
				}
				
				// showing table row
				include( 'row-ingredient.php' );
			?>	
		</tr>
		
		<?php

		$index++;
		
		endforeach; // end substitutions
	
	endif;

endforeach; ?>
<!-- end substitutions of ingredients -->

<!-- looping unsubstituted -->

<?php if( $ingredients['unsubstituted']) : foreach( $ingredients['unsubstituted'] as $ingredient ) : ?>
	<tr style="background-color:#eee">
		
		<td class="coloumn-center" colspan="4">
			<?php echo $ingredient['qty'] . ' ' . $ingredient['unit'] ; ?> 
			<a href="#"><?php echo $ingredient['desc'] ?></a>
		</td>
		
	</tr>
<?php endforeach; endif; ?>
<!-- end unsubstituted -->

<!-- hidden row -->
<tr id="search-title" class="hide" data-title="<?php echo get_the_title($ID) ?>"></tr>