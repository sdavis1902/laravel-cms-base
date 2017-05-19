var EditUser = (function(){
    'use strict';

	function _createEventListeners(){
		$('#datatables-users').DataTable({
			responsive: true
		});

		$('.user-delete').click(function(e){
			var url = $(this).attr('href');
			e.preventDefault();
			bootbox.confirm('Are are you sure you want to delete this user?', function(result){
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
jQuery( EditUser.init() );
