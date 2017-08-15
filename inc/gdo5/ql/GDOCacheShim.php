<?php
if (!class_exists('Memcached', false))
{
    class Memcached
    {
        public function addServer() {}
        public function get() {}
        public function set() {}
        public function flush() {}
        public function delete() {}
        public function replace() {}
    }
}
