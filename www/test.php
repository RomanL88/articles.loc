<?

/**
 * @param $a
 * @param $a
 * @return int
 */

function sum($a, $b) {
    return $a + $b;
}

$sumReflector = new ReflectionFunction('sum');

echo "<pre>" . $sumReflector->getDocComment() . "</pre>";

?>