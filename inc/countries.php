<?php

function get_country( $code ){
    $country = '';
    if( $code == 'en' ) $country = 'English';
    if( $code == 'cz' ) $country = 'Czech';
    if( $code == 'dk' ) $country = 'Danish';
    if( $code == 'de' ) $country = 'German';
    if( $code == 'au' ) $country = 'Australian';
    if( $code == 'eo' ) $country = 'Esperanto';
    if( $code == 'es' ) $country = 'Spanish';
    if( $code == 'fi' ) $country = 'Finnish';
    if( $code == 'fr' ) $country = 'French';
    if( $code == 'hu' ) $country = 'Hungarian';
    if( $code == 'it' ) $country = 'Italian';
    if( $code == 'jp' ) $country = 'Japanese';
    if( $code == 'jbo' ) $country = 'Lojban';
    if( $code == 'lt' ) $country = 'Lithuanian';
    if( $code == 'lv' ) $country = 'Latvian';
    if( $code == 'no' ) $country = 'Norwegian';
    if( $code == 'nl' ) $country = 'Dutch';
    if( $code == 'pl' ) $country = 'Polish';
    if( $code == 'br' ) $country = 'Brazilian';
    if( $code == 'pt' ) $country = 'Portuguese';
    if( $code == 'ru' ) $country = 'Russian';
    if( $code == 'sk' ) $country = 'Slovak';
    if( $code == 'tw' ) $country = 'Taiwanese';

    if( $country == '') $country = $code;
    return $country;
}
