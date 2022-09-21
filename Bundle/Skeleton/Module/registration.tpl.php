<?= "<?php\n" ?>
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    "<?= $module; ?>",
    __DIR__
);