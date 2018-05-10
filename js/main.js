
const IMAGE_LOADER = '<img src="/images/loader_fb.gif" style="width:40px;" />';

/**
 * Загрузка ВКС и собраний
 * @returns
 */
function loadAjaxConferenceToday()
{
	$('#container-conference-today').html(IMAGE_LOADER);
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
	gif = gif || IMAGE_LOADER;
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
		data: data		
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
	.fail(function(jqXHR){
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

function ajaxJSON(url, containers, gif)
{	
	gif = gif || IMAGE_LOADER;
	gif = '<div id="img_loader">' + gif + '</div>';
	
	// заголовок
	if (('title' in containers))
	{
		$(containers['title']).html(gif);
	}
	
	// контент
	if (('content' in containers))
	{
		$(containers['content']).html(gif);
	}
	
	$.ajax({
		url: url,		
		dataType: "json"
	})
	.done(function(data){
		
		// заголовок
		if (data.hasOwnProperty('title') && ('title' in containers))
		{
			$(containers['title']).html(data.title);
		}
		
		// контент
		if (data.hasOwnProperty('content') && ('content' in containers))
		{
			$(containers['content']).html(data.content);
		}
		
	})
	.fail(function(jqXHR){
		//return '<div class="alert alert-danger">' + jqXHR.statusText + '</div>';
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
	return ajaxGET(url, data, container, IMAGE_LOADER, append);
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

function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
        return url;
    } else {
        return url;
    }
}

function removeParametrDialog()
{
	window.history.replaceState({}, document.title, removeURLParameter(window.location.href, 'w'));
}

/**
 * Загрузка документа
 * @param url
 * @param title
 * @param hash
 * @returns
 */
function getJson(url)
{				
	ajaxJSON(url, {
		title: '#modal-title-preview',
		content: '#modal-content-preview'
	});
	changeUrlParam('w', url);
	return false;
}


/**
 * Run afrer load document
 * @returns
 */
$(document).ready(function() {
	
	// disable scroll in background if open modal
	$('.modal')
		.on('shown', function(){ 
			$('body').css({overflow: 'hidden'}); 
		}) 
		.on('hidden', function(){ 
			$('body').css({overflow: ''}); 
		});
	
	
	// load container 
	loadAjaxConferenceToday();
	
	// check ulr parametr 'w'
	url_w = getURLParameter('w');
    if (url_w!=null)
    {
	    $('#modalPreview').modal('show');	    
		ajaxJSON(url_w, {
			title: '#modal-title-preview',
			content: '#modal-content-preview'
		});
	}
	
	
});
	