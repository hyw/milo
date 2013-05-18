<?php
$file = $argv[1]; // le file
$lines = file($file); // make an array of each line
foreach ($lines as $line) {
	// le processing
	$line = str_replace("\n", '', $line); // remove new line
	$halves = preg_split('/;/', $line); // split at semi-colon
	$persons = explode(',', $halves[0]); // array of left half, persons
	$things = explode(',', $halves[1]); // array of right half, things
	$num_rows = count($persons); // num rows for matrix, based off num persons
	$num_cols = count($things); // num cols for matrix, based off num things
	// le matrix
	$matrix = array(array()); // create matrix
	for ($i = 0; $i < $num_rows; $i++) { // loop over rows
		for ($j = 0; $j < $num_cols; $j++) { // loop over cols per row
			// remove everything except for alphabetical letters and returns the length
			$itemLen = strlen(preg_replace('/\PL/u', '', $things[$j]));
			// remove all spaces
			$personLen = strlen(preg_replace('/\s+/', '', $persons[$i]));
			if ($itemLen & 1) {
				//odd
				$numCs = preg_match_all('/[bcdfghjklmnpqrstvwxz]/i', $persons[$i], $matches); // num consonants
				$matrix[$i][$j] = $numCs;
			} else {
				//even
				$numVs = preg_match_all('/[aeiouy]/i', $persons[$i], $matches); // num vowels
				$matrix[$i][$j] = $numVs*1.5;
			}
			if (gcd($itemLen, $personLen) != 1) {
				$matrix[$i][$j] = $matrix[$i][$j]*1.5;
			}
		}
	}
	
	// uncomment to see cost matrix before augmentation for maximization
// 	echo $matrix[0][0] . " " . $matrix[0][1] . " " . $matrix[0][2] . "\n";
// 	echo $matrix[1][0] . " " . $matrix[1][1] . " " . $matrix[1][2] . "\n";
// 	echo $matrix[2][0] . " " . $matrix[2][1] . " " . $matrix[2][2] . "\n";
// 	echo "\n";
	
	// le algorithm
	$ss = hungry($matrix);
	$ss = number_format($ss, 2, '.', ''); // format for 2 decimal points
	echo $ss . "\n";
}

// hungarian algorithm to solve matrix assignment
function hungry($matrix) {
	$m = padit($matrix);
	$max = multimax($m);
	$num_rows = count($m);
	$num_cols = count($m[0]);
	
	// augment for maximize
	for ($i = 0; $i < $num_rows; $i++) {
		for ($j = 0; $j < $num_cols; $j++) {
			$m[$i][$j] = $max - $m[$i][$j];
		}
	}
	$bignum = 100000; // arbitrarily big number
	$u = array_pad(array(), $num_rows, 0);
	$v = array_pad(array(), $num_cols, 0);
	$ind = array_pad(array(), $num_cols, -1);
	for ($i = 0; $i < $num_rows; $i++) {
		$links = array_pad(array(), $num_cols, -1);
		$mins = array_pad(array(), $num_cols, $bignum);
		$visited = array_pad(array(), $num_cols, 0);
		$markedI = $i;
		$markedJ = -1;
		$j = 0;
		$done = false;
		while (!$done) {
			$j = -1;
			for ($k = 0; $k < $num_rows; $k++) {
				if ($visited[$k] == 0) {
					$cur = $m[$markedI][$k] - $u[$markedI] - $v[$k];
					if ($cur < $mins[$k]) {
						$mins[$k] = $cur;
						$links[$k] = $markedJ;
					}
					if ($j == -1 || $mins[$k] < $mins[$j]) {
						$j = $k;
					}
				}
			}
			$delta = $mins[$j];
			for ($k = 0; $k < $num_cols; $k++) {
				if ($visited[$k] == 1) {
					$u[$ind[$k]] += $delta;
					$v[$k] -= $delta;
				} else {
					$mins[$k] -= $delta;
				}
			}
			$u[$i] += $delta;
			$visited[$j] = 1;
			$markedJ = $j;
			$markedI = $ind[$j];
			if ($markedI == -1) {
				$done = true;
			}
		}
		$done = false;
		while (!$done) {
			if ($links[$j] != -1) {
				$ind[$j] = $ind[$links[$j]];
				$j = $links[$j];
			} else {
				$done = true;
			}
		}
		$ind[$j] = $i;
	}
	$ss = 0;
	for ($j = 0; $j < $num_cols; $j++) {
		// uncomment to see matrix coordinates
// 		echo $ind[$j] . ", " . $j . "\n";
		$ss += $matrix[$ind[$j]][$j];
	}
	return $ss;
}

// pad matrix if not square
function padit($matrix) {
	$m = $matrix;
	$num_rows = count($m);
	$num_cols = count($m[0]);
	if ($num_rows > $num_cols) { // if tall
		for ($i = 0; $i < $num_rows; $i++) {
			$m[$i] = array_pad($m[$i], $num_rows, 0);
		}
	} elseif ($num_cols > $num_rows) { // if wide
		for ($i = count($m); $i < $num_cols; $i++) {
			$m[$i] = array_pad(array(), $num_cols, 0);
		}
	}
	return $m;
}

// returns maximum value in multidimensional array
function multimax($matrix) {
	$num_rows = count($matrix);
	$max = array();
	for ($i = 0; $i < $num_rows; $i++) {
		$max[$i] = max($matrix[$i]);
	}
	$max = max($max); // max value in entire array
	return $max;
}

// returns greatest common factor of two numbers
function gcd($x, $y) {
	$x = abs($x);
	$y = abs($y);
	if($x + $y == 0) {
		return 0; // not supposed to happen
	} else {
		while($x > 0) {
			$z = $x;
			$x = $y % $x;
			$y = $z;
		}
		return $z;
	}
}
?>