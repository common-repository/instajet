<?php

/**
 * Class IJ_Journey_factory
 *
 * Factory Method for IJ_Journey
 *
 */
class IJ_Journey_factory {

    public function __construct() {

    }

    public function build( $journey ) {
        return new IJ_Journey( $journey );
    }

}

new IJ_Journey_factory();