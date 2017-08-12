<?php
if (!class_exists('Memcached', false))
{
    class Memcached
    {
        public function addServer() {}
        public function get() {}
        public function set() {}
        public function replace() {}
    }
}
