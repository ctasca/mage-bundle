# ctasca/mage-bundle
### Installation
```bash
composer require --dev ctasca/mage-bundle
```
### To move skeleton files to Magento root dev directory
<p>This should happen automatically after installing the module.</p>
<p>If it doesn't, it is possible to manually execute the composer post install command with the following:</p>

```bash
cd <magento_root>/vendor/ctasca/mage-bundle && composer run-script post-install-cmd
```

### Available commands
```bash
bin/magento magebundle:module:create
```
This command creates a skeleton Magento module in app/code directory, generating the required registration.php and etc/module.xml files

### Templates Data Provider
<p>It is possible to define your own templates as well as the data that are passed when these are generated.</p>
<p>In order to do so you, simply create a php file in the MAGENTO_ROOT/dev/mage-bundle/custom-data directory, naming the file exactly as the template file that is being generated and returning an array with setter methods as keys and values.</p>
<p>Values can be a string or an array</p>

#### Example
```php
<?php
return [
   'setMyCustomValue' => 'custom_value'
];
```
#### Note:
<p>After doing so it will be possible to use the placeholder <code>{{my_custom_value}}</code> in a template file.</p>
<p>If <code>setMyCustomValue</code> provides an array this will be imploded with <code>PHP_EOL</code> separator.</p>
<p>Additional tabs characters should be defined in the array value like so:</p>

```php
<?php
return [
   'setMyCustomValue' => ["test", "\ttest2"]
];
```

