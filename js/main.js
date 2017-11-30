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


/**
 * Загрузка ВКС и собраний
 * @returns
 */
function loadAjaxConferenceToday()
{
	$('#container-conference-today').html('<img src="../images/loading.gif" />');
	$.get('/conference/today.html')
		.done(function(data){
			$('#container-conference-today').html(data);
		});
}




/**
 * Универсальная функция для ajax-загрузки (get) 
 * @param url
 * @param data
 * @param container
 * @param gif
 * @returns
 */
function ajaxGET(url, data, container, gif, append)
{
	gif = gif || '<img src="/images/loading.gif" />';
	gif = '<div id="img_loader">' + gif + '</div>';
	
	if (append == true)
	{
		$(container).append(gif);
	}
	else
	{
		$(container).html(gif);
	}
	
	$.ajax({
		url: url,
		data: data,
	})
	.done(function(data){
		if (append == true)
		{
			$('#img_loader').remove();
			$(container).append(data);
		}
		else
		{
			$(container).html(data);
		}
	})
	.error(function(jqXHR){
		if (append == true)
		{
			$('#img_loader').remove();
			$(container).append('<div class="alert alert-danger">' + jqXHR.statusText + '</div>');
		}
		else
		{
			$(container).html('<div class="alert alert-danger">' + jqXHR.statusText + '</div>');
		}
	});
}

/**
 * Загрузчик для новостей
 * @param url
 * @param data
 * @param container
 * @returns
 */
function ajaxNews(url, data, container, append)
{
	return ajaxGET(url, data, container, '<img src="/images/loader.gif" />', append);
}




function getURLParameter(name) {
	return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

function changeUrlParam (param, value) 
{
	var currentURL = window.location.href+'&';
	var change = new RegExp('('+param+')=(.*)&', 'g');
	var newURL = currentURL.replace(change, '$1='+value+'&');
	if (getURLParameter(param) !== null){
		try {
			window.history.replaceState('', '', newURL.slice(0, - 1) );
		} catch (e) {
			console.log(e);
		}
	} else {
		var currURL = window.location.href;
		if (currURL.indexOf("?") !== -1){
			window.history.replaceState('', '', currentURL.slice(0, - 1) + '&' + param + '=' + value);
		} else {
			window.history.replaceState('', '', currentURL.slice(0, - 1) + '?' + param + '=' + value);
		}
	}
}

	