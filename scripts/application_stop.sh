#!/bin/bash
isExistApp = `pgrep php-fpm`
if [[ -n  $isExistApp ]]; then
    service php-fpm stop
fi
