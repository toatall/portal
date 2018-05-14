// изображение загрузчика
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
 * @param url ссылка
 * @param data даные
 * @param container 
 * @returns
 */
function ajaxNews(url, data, container, append)
{
	return ajaxGET(url, data, container, IMAGE_LOADER, append);
}

/**
 * Проверка наличия параметра в текущей ссылке
 * @param name
 * @returns
 */
function getURLParameter(name) {
	return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
}

/**
 * Изменение параметра ссылки
 * @param param параметр
 * @param value значение параметра
 * @returns
 * @uses getJson()
 */
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

/**
 * Удаление параметра со значение из ссылки
 * @param url ссылка
 * @param parameter параметр, который необходимо удалить
 * @returns
 * @uses removeParametrDialog()
 */
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

/**
 * Удаление параметра w из ссылки
 * @returns
 */
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
 * Load document...
 * @returns
 */
$(document).ready(function() {
	
	/**
	 * Плавающее главное меню
	 */
		
	// высота шапки	
	var h_hght = 200;
	
	// отступ когда шапка уже не видна
	var h_mrg = 0;
	
    var elem = $('#top_nav');
    var top = $(this).scrollTop();
     
    if(top > h_hght){
        elem.css('top', h_mrg);
    }           
     
    $(window).scroll(function(){
        top = $(this).scrollTop();
         
        if (top+h_mrg < h_hght) {
            elem.css('top', (h_hght-top));
        } else {
            elem.css('top', h_mrg);
        }
    });
		
    /**
     * Удаление параметра w из адреса при закрытии диалогового окна
     */
	$('#modalPreview').on('hide', function() {
        removeParametrDialog();
    });
	
	/**
	 * Повесить на все ссылки с sw_dlg событие 'click' для отркытия диалогового окна
	 */
    $(document).on('click', '.sw_dlg', function() {    		
    	getJson($(this).attr('href'));
		$('#modalPreview').modal('show');
		return false;
	});
    
    /**
     * Отключение прокрутки при открытии модального окна
     */
	$('.modal')
		.on('shown', function(){ 
			$('body').css({overflow: 'hidden'}); 
		}) 
		.on('hidden', function(){ 
			$('body').css({overflow: ''}); 
		});
	
	/**
	 * Загрузка событий на сегодня
	 */
	loadAjaxConferenceToday();
	
	/**
	 * Если в адресной строке присутсвует параметр 'w',
	 * то окрываем диалоговое окно с адресом = значение параметра
	 */
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
	