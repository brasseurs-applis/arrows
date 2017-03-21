
from __future__ import with_statement
import time
from fabric.api import *

env.hosts = ['root@vps']

deploy_dir = '/home/deploy/arrows'
config_dir = '/home/deploy/config/arrows'
release_dir = '/home/websites/arrows'
keep_nb = 5


@task
def deploy(git_hash='master'):
    print('>>>>> deploy')

    current_release = str(int(time.time())) + '-' + git_hash
    target_dir = release_dir + '/' + current_release

    try:
        install(git_hash, target_dir)
        build(target_dir)
        release(target_dir)
        start()
    except:
        rollback(target_dir)

    cleanup(current_release)


def install(git_hash, target_dir):
    print('>>>>> install')

    run('mkdir %s' % target_dir)

    with cd(deploy_dir):
        run('git reset --hard')
        run('git fetch origin')
        run('git checkout %s' % git_hash)
        run('git pull --rebase origin %s' % git_hash)
        run('git clean -dfx')
        run('git archive %s | tar -x -C %s' % (git_hash, target_dir))
        run('chown -R www-data:www-data %s' % target_dir)


def build(target_dir):
    print('>>>>> build')

    with cd(target_dir):
        run('composer install --no-dev --apcu-autoloader --optimize-autoloader')
        run ('cp %s %s' % (config_dir + '/app-config.php', target_dir + '/config/app-config.php'))
        run ('./vendor/bin/doctrine-migrations migrations:migrate -n')


def release(target_dir):
    print('>>>>> release')

    run('ln -sfn %s %s/current' % (target_dir, release_dir))


def cleanup(current_release):
    print('>>>>> clean-up')

    releases = get_releases()

    nb_delete = len(releases) - keep_nb

    if nb_delete > 0:
        nb_deleted = 0
        release_iterator = iter(releases)

        while nb_deleted < nb_delete:
            release = next(release_iterator, None)

            if not release:
                print('no more release to loop on, stop !')
                break

            try:
                delete_release(release, current_release)
                nb_deleted += 1
            except:
                print("Couldn't delete release '%s'" % release)


def start():
    print('>>>>> run')

    run('supervisorctl restart arrows')


def rollback(target_dir = None):
    print('>>>>> rollback')

    if (target_dir is not None):
        run('rm -Rf %s' % target_dir)

    release(release_dir + '/' + get_releases()[-1]);


def get_releases():
    with hide('running', 'stdout'):
        with cd(release_dir):
            raw_list = run('ls -1 -d -- */')

    folder_list = raw_list.split('\n')
    folder_list.sort()

    out = list()
    for elem in folder_list:
        release = elem[:-1]
        if (release != 'current'):
            out.append(release)

    return out


def delete_release(release, current_release):
    with cd(release_dir):
        if release == current_release:
            raise Exception("Can't delete current version")
        else:
            run("rm -rf -- %s" % release)
