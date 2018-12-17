function gir() {  
	nums = document.getElementById('nums_1').innerHTML; 
	if (nums == 1) { 
		document.getElementById('gir').className='gir_1'; 
		document.getElementById('nums_1').innerHTML='2'; 
	}  
	if (nums == 2) { 
		document.getElementById('gir').className='gir_2'; 
		document.getElementById('nums_1').innerHTML='3'; 
	}  
	if (nums == 3) { 
		document.getElementById('gir').className='gir_3'; 
		document.getElementById('nums_1').innerHTML='1'; 
	}  
}  
setInterval( function() { gir() }, 500);