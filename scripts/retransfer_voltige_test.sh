#!/bin/sh
# Transfer all Programs To Test Server  
/usr/bin/rsync -av --exclude '.hg' --exclude phpar.log --exclude '/public/gallery' --exclude '/public/vendor' --exclude '/vendor' --delete-after collins.ch:/home/collinsc/public_html/voltige/ /Users/daddykom/Dropbox/Developement/Voltige-Less
 