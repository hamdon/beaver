# beaver
some useful class for laravel

CacheLock：
lock:  \Hamdon\Beaver\CacheLock::lock('lock_name', 1);
unlock: \Hamdon\Beaver\CacheLock::unLock('lock_name');
check: \Hamdon\Beaver\CacheLock::check('lock_name');
