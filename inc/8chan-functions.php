<?php
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );  
define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );  
define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );  

function _n($x, $y, $z) {
	if ($z > 1) {
		return $y;
	} else {
		return $x;
	}
}

function human_time_diff( $from, $to = '' ) {
	if ( empty( $to ) )
		$to = time();

	$diff = (int) abs( $to - $from );

	switch(TRUE){
	case ( $diff < HOUR_IN_SECONDS ) :
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 ){
			$mins = 1;
		}
		/* translators: min=minute */
		$since = sprintf( _n( '%s min', '%s mins', $mins ), $mins );

	case ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) :
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 ){
			$hours = 1;
		}
		$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
		
	case ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) :
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 ){
			$days = 1;
		}
		$since = sprintf( _n( '%s day', '%s days', $days ), $days );
		
	case ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ):
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 ){
			$weeks = 1;
		}
		$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
		
	case ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) :
		$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
		if ( $months <= 1 ){
			$months = 1;
		}
		$since = sprintf( _n( '%s month', '%s months', $months ), $months );
	case ( $diff >= YEAR_IN_SECONDS ) :
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 ){
			$years = 1;
		}
		$since = sprintf( _n( '%s year', '%s years', $years ), $years );
	}

	return $since;
}
