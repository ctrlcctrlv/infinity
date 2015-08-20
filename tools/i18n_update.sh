#!/bin/sh
#cd inc/locale; tx pull; cd -
cd inc/locale; K=`ls`; cd -; for i in $K; do tools/i18n_compile.php -l $i; done
