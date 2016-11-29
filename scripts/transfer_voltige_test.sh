#!/bin/sh
# Transfer all Programs To Test Server 
/usr/bin/rsync -av --del --exclude '.hg' --exclude '/public/gallery' --exclude '/public/vendor' --exclude '/vendor' /Users/daddykom/Dropbox/Developement/Voltige-Less/ collins.ch:/home/collinsc/public_html/voltige
 