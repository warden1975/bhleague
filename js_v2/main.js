jQuery('document').ready(function($){		
		
	/* Placeholder cross browser, NEED placehoder.js */	
	if($('input[placeholder], textarea[placeholder]').length > 0){
		$('input[placeholder], textarea[placeholder]').placeholder();
	}

});
