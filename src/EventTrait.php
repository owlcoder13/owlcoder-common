<?php

namespace Owlcoder\Common;

trait EventTrait
{
    public $_events = [];

    public function addEventListener($eventName, $callback)
    {
        if ( ! isset($this->_events[$eventName]) ||
            ! is_array($this->_events[$eventName])) {
            $this->_events[$eventName] = [];
        }

        $this->_events[$eventName][] = $callback;
    }

    public function triggerEvent($eventName, &...$params)
    {
        if (isset($this->_events[$eventName]) && is_array($this->_events[$eventName])) {
            foreach ($this->_events[$eventName] as $event) {
                call_user_func_array($event, $params);
            }
        }
    }

}
