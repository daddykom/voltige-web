from fabric.api import *
from fabric import state
import imp
import string
from random import choice


state.output['debug'] = True


@task
@hosts('collinsc@collins.ch')
def log():
    with cd('public_html/voltige/public'):
        run('cat phpar.log')
    retransfer()
    
@task
@hosts('collinsc@collins.ch')
def phinx_create(name):
    with cd('public_html/voltige'):
        run('vendor/bin/phinx create %s' % name)
    retransfer()
    
@task
@hosts('collinsc@collins.ch')
def phinx_createseed(name):
    with cd('public_html/voltige'):
        run('vendor/bin/phinx see:create %s' % name)
    retransfer()
    
@task
def phinx_migrate():
    with cd('public_html/voltige'):
        run('vendor/bin/phinx migrate -e %s' % version )
        
@task
def phinx_seed(*arg):
    with cd('public_html/voltige'):
        if( len(arg) ): run('vendor/bin/phinx seed:run -e %s -s %' % (version,arg[0]) )
        else: run('vendor/bin/phinx seed:run -e %s' % version )

@task
@hosts('collinsc@collins.ch')
def retransfer():
    local('/bin/sh /Users/daddykom/Dropbox/Developement/Voltige-Less/scripts/retransfer_voltige_test.sh')

@task
def design():
    global suffix, tmppath, madpath, version
    suffix = 'voltigedesign' 
    tmppath = 'tmp'
    version = 'testing'
    madpath = 'public_html/voltigedesign/'
    env.hosts.append('collinsc@collins.ch')
    
@task
def devel():
    global suffix, tmppath, madpath, version
    version = 'development'
    env.hosts.append('collinsc@collins.ch')

@task
def deploy():
    chars = string.letters + string.digits + string.punctuation.replace("'","")
    with cd(tmppath):
        run('rm -fR voltige') 
        run('hg clone ssh://hg@bitbucket.org/daddykom/voltige/  voltige')
        run('find ./ -name "*++%s++*" -exec rename "++%s++" "''"  {} \;' % (suffix,suffix))
        run('find ./ -name "*++??++*" -exec rm {} \;')
        run('rsync -av --exclude "*++*++.*" voltige/. ../%s' % madpath)
    with cd(madpath):
        run('chmod -R 755 *' )
        run('bower install')
        run('bower update')
        run('composer.phar install')
        run('vendor/bin/phinx migrate -e %s' % version )
        run("echo '%s' > secret.txt" % ''.join(choice(chars) for _ in range(50)))
    with cd(tmppath):
        run('rm -fR voltige') 
