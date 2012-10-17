/*
*
* AJAX jQuery
*
*/

jQuery(function($){	
    
    /**
     * set show/hide ajax loading
     */
    $('#ajax-loader').ajaxStart(function(){
	    $(this).show();
	    $('#ingredient-table').css({opacity:0.3});
    }).ajaxStop(function(){
	    $(this).hide();
	    $('#ingredient-table').css({opacity:1});
    });	
    
    /** ======================== TAB ======================== */
    
    // variable element columns
    var elobj = [ 'column-alternative', 'column-healthy', 'column-vegan' ];
    
    /**
     * check the value in array ?
     * @param String val
     * @param Array arr
     * @return boolean
     */ 
    function isValueInArray(val, arr) {
	inArray = false;
	for (i = 0; i < arr.length; i++)
	    if (val == arr[i])
		    inArray = true;
	return inArray;
    }
    
    /**
     * set active current tab
     * @param String element
     * @return void add element class 'active'
     */ 
    function currentTab(toggle){
	$('ul#tab-ingredient').find('a').each(function(){
	    $(this).removeClass('active');
	})
	console.log(toggle);
	$(toggle).addClass('active');
    }
    
    /**
     * normalize/default tab
     */
    function normalizeTabHeader(){
	$('#ingredient-table').find('.most-left').attr('width', '194');
	$.each(elobj, function(i, val) {				
	    $('.'+val).attr('width', '26%');
	    $('.'+val).show();
	});	
    }
    
    /**
     * event tab click
     */
    $('#tab-ingredient a').click(function(){
	// get element
	var toggle = $(this).attr('data-toggle');
	
	// check this data in tab
	if(isValueInArray(toggle, elobj))
	{
	    $.each(elobj, function(i, val) {
		var column = $('.'+val);
		// hide all columns, except this column and first column
		if(toggle != val){
			column.siblings(':first').attr('width', '171');
			column.hide();
		} else {
			// show this column and set width
			column.attr('width', '621');
			column.show();
		}			
	    });	
	} else {
	    // normalize tab header
	    normalizeTabHeader();
	}
	// set current this tab
	currentTab($(this));
	
	return false;
    })
    
    /** ======================== SEARCH FORM AUTOCOMPLETE ======================== */
    
    /**
     * get source autocomplete
     */
    $( "#ing-search" ).autocomplete({
	source: function( request, response ) {
	$.ajax({
	    type: 'POST',
	    url: Ajax.ajaxurl,
	    dataType: "json",
	    data: {
		action:'AFR-ajax-autocomplete',
		nonce:Ajax.ajaxIngredientNonce,
		term:request.term
	    },
	    success: function( data ) {
		console.log(data); // debug
		response( $.map( data, function( item ) {
		    return {
			label: item.label,
			value: item.label,
			slug: item.slug,
			permalink: item.permalink
		    }
		}));
	    }
	});
	},
	minLength: 2,
	select: function( event, ui ) {
	    // set value with item slug n attr 'data-parameter' with item permalink
	    $('#autocomplete-value').each(function(){
		$(this).val(ui.item.slug);
		$(this).attr('data-parameter', ui.item.permalink);
	    })
	}
    });
    
    /** ======================== SEARCH HANDLER ======================== */
    
    // ajax parameters
    var parameters = { action:'AFR-ajax-search', nonce:Ajax.ajaxIngredientNonce };
    var do_params = function( data, event ){
	for(var key in data){
	    switch( event ){
		case 'add':
		    parameters[key] = data[key];
		    break;
		    
		case 'unset':
		    delete parameters[key];
		    break;
	    }
	}		
    }
    // ajax
    var ajax = function(data, callback){
	// add params
	do_params( data, 'add' );
	    
	$.ajax({
	    type: 'POST',
	    data: parameters,
	    url : Ajax.ajaxurl,
	    success: function(response){
		
		console.log(response); // debug
	    
		normalizeTabHeader();
		
		// unset params
		do_params( data, 'unset' );
		
		callback(null, response);
	    },  
	    error: function(jqXHR, textStatus, errorThrown) {  
	    var error = jqXHR + " :: " + textStatus + " :: " + errorThrown;  
			    callback(error, null);
	    }  
	});
    };
    // load by alphabet
    $('ul#list-alphabet a').click(function(){
	var term   = $(this).html(),
	params = { term:term };
	    
	ajax(params, function(err, response){
	    if(err){
		console.log(err);
	    } else {
		console.log(response);
		$('#ingredient-table').find('tbody').html(response);
		$('#ingredient-show').attr('data-param', term); // change attr data for show page
	    }
	});
	
	return false;
    });
    // show page
    $('#ingredient-show').change(function(){
	var search 	= $('#ing-search').val(),	// search
	    term = $(this).attr('data-param'), 	// term
	    show = $(this).val(),				// show
	    params = { search:search, term:term, show:show };
	    
	ajax(params, function(err, response){
	    if(err){
		    console.log(err);
	    } else {
		    $('#ingredient-table').find('tbody').html(response);
	    }
	});
    });
    
    var ucwords = function(str) {
	return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
	    return $1.toUpperCase();
	});
    }
    
    var autocomplete = $('#autocomplete-value');
    
    // set default field autocomplete
    function setNullAC(){
	autocomplete.each(function(){
	    $(this).val('');
	    $(this).attr('data-parameter', '');
	})
    }
    
    // search
    $('#ing-btn').live('click', function(){
	var search = $('#ing-search').val(),
	    slug =  $('#autocomplete-value').val(),
	    params = { search:search },
	    link, isKeyword = false;
	
	// check if has slug / search event from autocomplete	
	if( slug ){
	    isKeyword = true;
	    params['slug'] = slug; // add parameter slug	    
	    link = autocomplete.attr('data-parameter'); // set link from attribute 'data-parameter'
	} else {	    
	    link = search; // set link from search
	}
	
	ajax(params, function(err, response){
	    if(err) {
		console.log('ERROR');
	    } else {
		// find (filter #search-title) title from html (response from ajax)
		var title = $(response).filter('#search-title').attr('data-parameter');
	
		$('#subtitution-result').each(function(){
		    $(this).show();
		    if( isKeyword ){
			$(this).find('a').attr('href', link);
			$(this).find('a').html(ucwords(search));
		    } else {
			// check if title not empty
			if( title ){                    
			    $(this).find('a').attr('href', link);
			    $(this).find('a').html(title);
			} else
			    $(this).find('a').html(search);
		    }
		    
		    setNullAC();    
		    
		});
		$('#ingredient-table').find('tbody').html(response);				
	    }
	});
    });
	
});