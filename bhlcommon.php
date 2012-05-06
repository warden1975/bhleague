<?php

@$bhlOGL = stripslashes(@$_COOKIE['bhlOGL']);
$bhlOGL = json_decode($bhlOGL);
if (isset($bhlOGL) && strlen($bhlOGL->id) > 0) $gameday = $bhlOGL->id;
else $gameday = 1;