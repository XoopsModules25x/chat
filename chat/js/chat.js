// Автор: andrey3761
// Копирайт: xoops.ws

$(document).ready(function () {
    $("#pac_form").submit(Send); // вешаем на форму с именем и сообщением событие которое срабатывает кодга нажата кнопка "Отправить" или "Enter"
    $("#pac_text").focus(); // по поле ввода сообщения ставим фокус
	ChatLoad(); // Загружаем содержимое чата
    setInterval( "ChatLoad();", chat_config_interval ); // создаём таймер который будет вызывать загрузку сообщений каждые 10 секунды (10000 милесукунд)
});    

// Функция для отправки сообщения
function Send() {
    // Выполняем запрос к серверу с помощью jquery ajax: $.post(адрес, {параметры запроса}, функция которая вызывается по завершению запроса)
    $.post("ajax.php",  
	{
        act: "send",  // указываем скрипту, что мы отправляем новое сообщение и его нужно записать
        name: $("#pac_name").val(), // имя пользователя
        text: $("#pac_text").val() //  сам текст сообщения
    },
     ChatLoad ); // по завершению отправки вызвовем функцию загрузки новых сообщений Load()

    $("#pac_text").val(""); // очистим поле ввода сообщения
    $("#pac_text").focus(); // и поставим на него фокус
    
    return false; // очень важно из Send() вернуть false. Если этого не сделать то произойдёт отправка нашей формы, те страница перезагрузится
}

var last_message_id = 0; // номер последнего сообщения, что получил пользователь
var load_in_process = false; // можем ли мы выполнять сейчас загрузку сообщений. Сначала стоит false, что значит - да, можем
// AJAX индикатор
//var chat_loading = $("#chat_loading");

// Функция для загрузки сообщений
function ChatLoad() {
    // Проверяем можем ли мы загружать сообщения. Это сделанно для того, что бы мы не начали загрузку заново, если старая загрузка ещё не закончилась.
    if(!load_in_process)
    {
	    load_in_process = true; // загрузка началась
		// Включаем индикатор
		$("#chat_loading").fadeIn();
	    // отсылаем запрос серверу, который вернёт нам javascript
    	$.post("ajax.php", 
    	{
      	    act: "load", // указываем на то что это загрузка сообщений
      	    last: last_message_id, // передаём номер последнего сообщения который получил пользователь в прошлую загрузку
      	    rand: (new Date()).getTime()
    	},
   	    function (result) { // в эту функцию в качестве параметра передаётся javascript код, который мы должны выполнить
			// *************
			// Список онлайн
			// *************
			
			if( result["online"] ) {
			
				var chat_onlinearr = result["online"];
				// Список пользователей онлайна
				var chat_onlielist = "";
				// Открываем список
				chat_onlielist += "<ul>";
				//
				for ( i = 0; i < chat_onlinearr.length; i++ ) {
					// Генерируем запись списка
					chat_onlielist += "<li><span class='" + chat_onlinearr[i]["uclass"] + "' onclick='chat_addText( \"" + chat_onlinearr[i]["uname"] + ":\" )'>" + chat_onlinearr[i]["uname"] + "</span></li>";
				
				}
				// Закрываем список
				chat_onlielist += "</ul>";
				
				// Очищаем список онлайн
				$("#chat_online").empty();
				// Добавляем
				$("#chat_online").append( chat_onlielist );
			
			}
			
			// **************
			// Сообщения чата
			// **************
			
			// Последняя мессага
			if( result["lastmessid"] ) {
				last_message_id = result["lastmessid"];
			}
			// Новые сообщения
			if( result["message"] ) {
				// Массив сообщений
				var chat_messarr = result["message"];
				// Список сообщений
				var chat_messlist = "";
				// Кнопка удалить сообщения
				var chat_messdel = "";
				
				// Перебираем все сообщения
				for ( i = 0; i < chat_messarr.length; i++ ) {
					
					// Можно ли редактировать данное сообщение
					if ( chat_config_isremove ) {
						chat_messdel = "<img title='" + chat_lang_delete + "' onclick='ChatDeleteMessage( \"" + chat_messarr[i]["messid"] + "\" )' src='./images/close.png' />";
					// Если это сообщение текущего польователя
					} else if ( chat_config_uid == chat_messarr[i]["uid"] ) {
						chat_messdel = "<img title='" + chat_lang_delete + "' onclick='ChatDeleteMessage( \"" + chat_messarr[i]["messid"] + "\" )' src='./images/close.png' />";
					} else {
						chat_messdel = "";
					}
					
					chat_messlist += "<span id='messid-" + chat_messarr[i]["messid"] + "' class='block " + chat_messarr[i]["bg"] + "'>" + chat_messdel + "<span onclick='chat_addText(\"|" + chat_messarr[i]["time"] + "|\")'>|" + chat_messarr[i]["time"] + "|</span> <span class='" + chat_messarr[i]["uclass"] + "' onclick='chat_addText( \"" + chat_messarr[i]["uname"] + ":\" )'>" + chat_messarr[i]["uname"] + "</span>: " + chat_messarr[i]["message"] + "</span>";
					
				}
				// Добавляем
				$("#chat_area").append( chat_messlist );
				// Прокручиваем сообщения вниз
				$(".chat1").scrollTop($(".chat1").get(0).scrollHeight);
				
			}
			
			// **********************
			// Сообщения для удаления
			// **********************
			if( result["delmess"] ) {
				
				// Массив сообщений для удаления
				var chat_delmess = result["delmess"];
				// Перебираем все сообщения для удаления
				for ( i = 0; i < chat_delmess.length; i++ ) {
					// Удаляем элемент
					$("#messid-" + chat_delmess[i]).remove();
					
				}
				
				
			}
			
			
			// Прокручиваем сообщения вниз
		    //$(".chat1").scrollTop($(".chat1").get(0).scrollHeight);
			// Отключаем индикатор
			$("#chat_loading").fadeOut();
			//
		    load_in_process = false; // говорим что загрузка закончилась, можем теперь начать новую загрузку
    	}, "json");
    }
}

// Кликабельные ники
function chat_addText( addText ) {
	//Текущий текст
	var currentMessage = xoopsGetElementById("pac_text").value;
	// Прибавляем к тексту строку
	xoopsGetElementById("pac_text").value = currentMessage + addText + " ";
	// Ставим фокус в поле ввода текста
	$("#pac_text").focus();
	return;
}

//
function ChatFocus() {
	// Выполняем запрос к серверу
    $.post("ajax.php",  
	{
		act: "focus"  // указываем скрипту, что мы отправляем статус
	}, ChatLoad );

    return false; // очень важно из Send() вернуть false. Если этого не сделать то произойдёт отправка нашей формы, те страница перезагрузится
}
function ChatBlur() {
	// Выполняем запрос к серверу
    $.post("ajax.php",  
	{
		act: "blur"  // указываем скрипту, что мы отправляем статус
	},
	function (result) {
		
	});

    return false; // очень важно из Send() вернуть false. Если этого не сделать то произойдёт отправка нашей формы, те страница перезагрузится
}
// Удаление элемента
function ChatDeleteMessage( messid ) {
	// Уникальный идентификатор сообщения
	var messageid = $("#messid-" + messid);
	// Подсвечиваем сообщение
	messageid.addClass('chat-bg-delmsg');
	
	// Подтверждение удаления
	if ( confirm( chat_lang_confirmdelete ) ) {
		
		// Выполняем запрос к серверу
		$.post("ajax.php",  
		{
			act: "remove",  // указываем скрипту, что мы хотим удалить сообщение
			messid: messid // ID сообщения
		},
		function (result) {
			// result - массив с кодом ошибки. Код 0 - ошибок при удалении небыло
			if( result["err"] == 0 ) {
				//alert("");
				// Удаляем элемент из браузера
				messageid.remove();
			} else {
				// Убираем выделение
				messageid.removeClass('chat-bg-delmsg');
				// Выводим сообщение об ошибке
				alert( chat_lang_errors[ result["err"] ] );
			}
			
			
		}, "json");
		
		// Удаляем элемент
		//messageid.remove();
		
		//return true;
	} else {
		// Убираем выделение
		messageid.removeClass('chat-bg-delmsg');
		
		//return false;
	}
	
}

// События
window.onfocus = ChatFocus;
window.onblur = ChatBlur;
