<?php
    function get_currency($currency_code, $format) {

        $date = date('d/m/Y'); // Текущая дата
        $cache_time_out = '3600'; // Время жизни кэша в секундах
    
        $file_currency_cache = __DIR__.'/XML_daily.asp'; // Переменная директории подключенного файла
    
        if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {
    
            $ch = curl_init(); // Инициализирует сеанс cURL
    
            curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$date);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_HEADER, 0);
    
            $out = curl_exec($ch); // Выполняет запрос cURL
    
            curl_close($ch); // Завершает сеанс cURL
    
            file_put_contents($file_currency_cache, $out); // Пишет данные в файл
    
        }
    
        $content_currency = simplexml_load_file($file_currency_cache); // Интерпретирует XML-файл в объект
    
        return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);
    }   

    function get_currency_yesterday($currency_code, $format) {

        $yesterday = date("d/m/Y", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))); // Дата вчера
        $cache_time_out = '3600'; // Время жизни кэша в секундах
    
        $file_currency_cache = __DIR__.'/XML_yesterday.asp'; // Переменная директории подключенного файла
    
        if(!is_file($file_currency_cache) || filemtime($file_currency_cache) < (time() - $cache_time_out)) {
    
            $ch = curl_init(); // Инициализирует сеанс cURL
    
            curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$yesterday);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_HEADER, 0);
    
            $out = curl_exec($ch); // Выполняет запрос cURL
    
            curl_close($ch); // Завершает сеанс cURL
    
            file_put_contents($file_currency_cache, $out); // Пишет данные в файл
    
        }
    
        $content_currency = simplexml_load_file($file_currency_cache); // Интерпретирует XML-файл в объект
    
        return number_format(str_replace(',', '.', $content_currency->xpath('Valute[CharCode="'.$currency_code.'"]')[0]->Value), $format);
    }   
        
        $currency_usd_day = get_currency('USD', 4);
        $currency_usd_yesterday = get_currency_yesterday('USD', 4);

        $currency_eur_day = get_currency('EUR', 4);
        $currency_eur_yesterday = get_currency_yesterday('EUR', 4);
        
        echo "Курс доллара на сегодня: ".$currency_usd_day;

        if ($currency_usd_day > $currency_usd_yesterday) {
            echo "</br>исходя из вчерашнего дня, курс вырос";
        }
        else {
            echo "</br>исходя из вчерашнего дня, курс упал";
        } 

        echo "<hr>";

        echo "Курс  евро на сегодня: ".$currency_eur_day;

        if ($currency_eur_day > $currency_eur_yesterday) {
            echo "</br>исходя из вчерашнего дня, курс вырос";
        }
        else {
            echo "</br>исходя из вчерашнего дня, курс упал";
        } 

    
?>