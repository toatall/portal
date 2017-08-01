/**
 * 
 */

$(function() {
	// load container 
	loadAjaxConferenceToday();
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