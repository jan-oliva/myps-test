/**
 * Confirm dialog for datagrid
 * @returns {undefined}
 */



$(function(){
	removeConfirm = function (buttonSelector){

		this.setHref = function(href){
			$(buttonSelector).attr('data-href',href);
		};
		
	};
	$(document).on('click','i.fa-eraser',function(e){
		var href = $(this).parent().attr('href');
		$(this).attr('href',href);
		$('#modal_btn_ok_removeAclDialog').attr('data-href',href);
		//e.preventDefault() ;
	});

	$(document).on('click','#modal_btn_ok_removeAclDialog',function(e){
		var link = $(this).attr('data-href');
		window.location.href = link;
	});
});