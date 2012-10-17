<?php get_header() ?>

<?php
global $wpdb, $global_order, $global_limit;

$args = array(
    'taxonomy' 	  => 'ingredient_taxonomy',
    'post_type'	  => 'ingredient',
    'post_status' => 'publish',
    'orderby'     => 'title',
    'order'	  => 'ASC',
    'posts_per_page' => $post_per_page
);

// no-conflict wp-smart-sort plugin hook filter
$global_order = $wpdb->posts . '.post_title ASC';
$global_limit = $post_per_page;
// end no-conflict

// query posts
query_posts($args);

?>

<div class="template-full-noborder">
    <div id="container">
	 <div id="ingredient-wrapper">
	    <div id="ingredient-top-part">
		<div id="ingredient-search">
		    <div id="ingredient-search-bar">
			<input name="" id="ing-search" type="text" placeholder="Search here.." />									
			<!-- input hidden for response of autocomplete -->
			<input type="hidden" id="autocomplete-value" name="slug" value="" />
		    </div>
		    <div id="ingredient-search-button">
			<input name="" type="button" id="ing-btn" style="cursor:pointer" />
		    </div>
		    <div class="clear"></div>
		</div>
		<div id="ingredient-tab">
		    <!-- tab subtitutions -->
		    <div id="ingredient-tab-left">
			<span class="tab-label">Filter By</span>								
			<ul id="tab-ingredient">
			    <li><a href="#" data-toggle="column-alternative" class="alternative" >Alternative</a></li>
			    <li><a href="#" data-toggle="column-healthy" class="healthy">Healthy</a></li>
			    <li><a href="#" data-toggle="column-vegan" class="vegan">Vegan</a></li>
			    <li><a href="#" data-toggle="column-all" class="viewall active">Show All</a></li>
			</ul>
		    </div>
		    <!-- end tabs -->
		    <!-- show pages -->
		    <div id="ingredient-tab-right">
			<span id="show-label">Show</span>
			<div class="showpick">
			    <select id="ingredient-show" name="" data-param="ALL">
				<?php
				for($i=1; $i<=$count; $i++) :
				    $page = $i * $post_per_page; ?>
				    <option value="<?php echo $page ?>"><?php echo $page ?> Per Page</option>
				<?php endfor; ?>
			    </select>
			</div>
		    </div>
		    <!-- end show pages -->
		    <div class="clear"></div>                    
		</div>
			<!-- list alphabets -->
		<div id="ingredient-letter">
		    <ul id="list-alphabet">
			<li><a href="#" class="blank" id="all">ALL</a></li>
			<li><a href="#">#</a></li>
			<?php 
			// get alphabeth
			$terms = get_alphabeth();
			foreach($terms as $term) : ?>
			    <li><a href="#"><?php echo $term->name ?></a></li>
			<?php endforeach; ?>
		    </ul>
		    <div class="clear"></div>
		</div>
			<!-- end list alphabets -->
	    </div>
	    <div id="subtitution-result" class="hide">Substitutions for : <a href="#"></a></div>
	    <div id="result-table">
		<div id="ajax-loader"></div>
		<table width="984" border="0" cellspacing="0" cellpadding="0" id="ingredient-table">
		    <thead>
			<tr>
			    <th width="194" class="most-left">INGREDIENT</td>
			    <th width="26%" class="column-alternative">ALTERNATIVE </td>
			    <th width="26%" class="column-healthy">HEALTHY</td>
			    <th width="26%" class="column-vegan">VEGAN</td>
			</tr>
		    </thead>
		    <tbody>
		    <?php
		    if( have_posts() ) : 
		    
			while(have_posts()) : 
			    
			    the_post(); 
			
			    include( 'loop-ingredient.php' );
			
			endwhile;
			
			wp_reset_postdata();;
			
		    endif; 
		    ?>
		    </tbody>
		</table>
	    </div>
	 </div>
    </div>
</div>

<?php get_footer() ?>