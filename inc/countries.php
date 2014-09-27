<?php

function get_country( $code ){
    $country = '';
    if( $code == 'en' ) $country = 'English';
    if( $code == 'cs' ) $country = 'Czech';
    if( $code == 'da' ) $country = 'Danish';
    if( $code == 'de' ) $country = 'German';
    if( $code == 'eo' ) $country = 'Esperanto';
    if( $code == 'es' ) $country = 'Spanish';
    if( $code == 'fi' ) $country = 'Finnish';
    if( $code == 'fr' ) $country = 'French';
    if( $code == 'hu' ) $country = 'Hungarian';
    if( $code == 'it' ) $country = 'Italian';
    if( $code == 'ja' ) $country = 'Japanese';
    if( $code == 'jbo' ) $country = 'Lojban';
    if( $code == 'lt' ) $country = 'Lithuanian';
    if( $code == 'lv' ) $country = 'Latvian';
    if( $code == 'nb' ) $country = 'Norwegian';
    if( $code == 'nl' ) $country = 'Dutch';
    if( $code == 'pl' ) $country = 'Polish';
    if( $code == 'pt' ) $country = 'Portuguese';
    if( $code == 'ru' ) $country = 'Russian';
    if( $code == 'sk' ) $country = 'Slovak';
    if( $code == 'zh' ) $country = 'Chinese';

    if( $country == '') $country = $code;
    return $country;
}
