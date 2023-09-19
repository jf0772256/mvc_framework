<?php

namespace app\core;

abstract class Migration {
    abstract function up();
    abstract function down();
}