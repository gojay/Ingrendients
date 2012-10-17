    <td class="coloumn-center">
        <?php echo $ingredient['qty']; ?> 
		<a href="#"><?php echo $ingredient['name'] ?></a>
	</td>		
	<td class="split-coloumn column-alternative">
		<div class="inner-tabel">
			<div class="inner-table-left">
				<?php if( $qty_alt[$index] ) echo join('<br>', $qty_alt[$index]) ?>
			</div>
			<div class="inner-table-right">
				<?php if( $indgnt_alt[$index] ) echo join('', $indgnt_alt[$index]) ?>
			</div>
			<div class="clear"></div>
		</div>
	</td>
	<td class="split-coloumn column-healthy">
		<div class="inner-tabel">
			<div class="inner-table-left">
				<?php if( $qty_hlt[$index] ) echo join('<br>', $qty_hlt[$index]) ?>
			</div>
			<div class="inner-table-right">
				<?php if( $indgnt_hlt[$index] ) echo join('', $indgnt_hlt[$index]) ?>
			</div>
			<div class="clear"></div>
		</div>
	</td>	
	<td class="split-coloumn column-vegan">
		<div class="inner-tabel">
			<div class="inner-table-left">
				<?php if( $qty_vgn[$index] ) echo join('<br>', $qty_vgn[$index]) ?>
			</div>
			<div class="inner-table-right">
				<?php if( $indgnt_vgn[$index] ) echo join('', $indgnt_vgn[$index]) ?>
			</div>
			<div class="clear"></div>
		</div>
	</td>