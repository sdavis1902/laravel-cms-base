var SiteIndex = (function(){
    'use strict';

	function _createEventListeners(){
		$('.page-delete').click(function(e){
			var url = $(this).attr('href');
			e.preventDefault();
			bootbox.confirm('Are are you sure you want to delete this page?', function(result){
				if(result){
					window.location.href = url;
				}
			});
		});

		$('.folder-delete').click(function(e){
			var url = $(this).attr('href');
			e.preventDefault();
			bootbox.confirm('Are are you sure you want to delete this folder?', function(result){
				if(result){
					window.location.href = url;
				}
			});
		});
	}

	function init(){
        _createEventListeners();
    }

    return {
        init: init
    };
})();
jQuery( SiteIndex.init() );
