# beaver
some useful class for laravel

# CacheLock：
lock function :  \Hamdon\Beaver\CacheLock::lock('lock_name', 1);

unlock function : \Hamdon\Beaver\CacheLock::unLock('lock_name');

check function : \Hamdon\Beaver\CacheLock::check('lock_name');

setDriver function : \Hamdon\Beaver\CacheLock::setDriver('redis');

setDriver parameter : apc、array、database、file、memcached、redis