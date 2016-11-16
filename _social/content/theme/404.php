<?php
if($_Oli->isExistAccountInfos('ACCOUNTS', $_Oli->getUrlParam(1), false)) header('Location: ' . $_Oli->getUrlParam(0) . 'user/' . $_Oli->getUrlParam(1));
else header('Location: ' . $_Oli->getUrlParam(0) . 'search/' . $_Oli->getUrlParam(1));
?>