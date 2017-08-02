/**
 * 
 */

$(function() {
	// load container 
	loadAjaxConferenceToday();
	
	// disable scroll in background if open modal
	$('.modal')
		.on('shown', function(){ 
			$('body').css({overflow: 'hidden'}); 
		}) 
		.on('hidden', function(){ 
			$('body').css({overflow: ''}); 
		});
});



function loadAjaxConferenceToday()
{
	$.get('/conference/today')
		.done(function(data){
			$('#container-conference-today').html(data);
		});
}




/** ajax functions **/
function ajaxGET(url, data, container)
{
	$(container).html('<img src="/images/loading.gif" />');
	$.ajax({
		url: url,
		data: data,
	})
	.done(function(data){
		$(container).html(data);
	})
	.error(function(jqXHR){
		$(container).html('<div class="alert alert-danger">' + jqXHR.statusText + '</div>');
	});
}