function getTimer( count ){
    var timer = new Object();
    timer.seconds = count % 60;
    count = (count - timer.seconds) / 60;
    timer.minutes = count % 60;
    count = (count - timer.minutes) / 60;
    timer.hours = count % 24;
    timer.days = (count - timer.hours) / 24;
    return timer;
}

// Вывод таймера
function showTimer( timer ){
    $('#timer').text(
        timer.days.toString() +
        ':' +
        (timer.hours < 10 ? '0' : '') +
        timer.hours.toString() +
        ':' +
        (timer.minutes < 10 ? '0' : '') +
        timer.minutes.toString() +
        ':' +
        (timer.seconds < 10 ? '0' : '') +
        timer.seconds.toString()
    );
}

// поехали
$(document).ready( function(){
    var now =     new Date();                                        // Здесь нужно получить точное время
    var newyear = new Date(now.getFullYear() + 1, 0, 1, 0, 0, 0, 0); 
    var counter = newyear.getTime() - now.getTime();                // милисекунд до НГ
    var timeout = counter % 1000;                                    // Милисекунды до синхронного вывода целых секунд
    counter = (counter - timeout) / 1000;                            // Кол-во секунд до Нового Года   
    showTimer(getTimer(counter + 1));                                // Вывод ближайшей целой секунды
    setTimeout(function(){
        showTimer(getTimer(counter));                                // Синхронный вывод 1-й целой секунды
        var intervalID = setInterval(function(){
            counter--;
            if( counter > 0 ){
                showTimer(getTimer(counter));                        // Синхронный вывод n-й целой секунды
            }else{
                clearInterval(intervalID);
                $('#timer').text('Ура!');
            }
        }, 1000);
    }, timeout);
    
});