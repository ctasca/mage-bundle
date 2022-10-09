# ctasca/mage-bundle
### Installation
```bash
composer require --dev ctasca/mage-bundle
```
### To move template files to Magento root dev/ directory
<p>This should happen automatically after installing the module.</p>
<p><code>post-install-cmd:</code> occurs after the install command has been executed with a lock file present.</p>
<p>If it doesn't, it is possible to manually execute the composer post install command with the following:</p>

```bash
cd <magento_root>/vendor/ctasca/mage-bundle && composer run-script post-install-cmd
```

#### Note:
<p>Template files are written in PHP version 8.1</p>
<p>For example the <code>http-get-action-json-result.tpl.php</code> contains the following</p>

<pre>
{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;

class {{class_name}} implements HttpGetActionInterface
{
    /**
     * @param RequestInterface $request
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly JsonFactory $jsonFactory
    ){}

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $jsonResponse = $this->jsonFactory->create();
        return $jsonResponse->setData([]);
    }
}
</pre>

<p>To set your own template just place it in the <code>MAGNETO_ROOT/dev/mage-bundle/http-controller</code> directory and choose the template when executing the create controller command.</p>

### Available commands
```bash
bin/magento magebundle:module:create
```
Creates a skeleton Magento module in app/code directory, generating the required registration.php and etc/module.xml files

```bash
bin/magento magebundle:controller:create
```
Creates a Controller namespace and Action class in specified module.
Developer is prompted to choose an Action template from the ones available in <code>mage-bundle/http-controller</code> directory.

```bash
bin/magento magebundle:etc:xml:create
```
Creates an XML file in Company/Module/etc directory. Templates can be chosen after specifying the area where the template applies to.

```bash
bin/magento magebundle:model:set:create
```
Creates a Model, Resource Model and Collection classes in specified Company/Module.

```bash
bin/magento magebundle:model:create
```
Creates a Model class in specified Company/Module. There is also the template to create an interface instead of a class.

```bash
bin/magento magebundle:block:create
```
Creates a template Block class in specified Company/Module.

```bash
bin/magento magebundle:helper:create
```
Creates a Helper class in specified Company/Module.


### Templates Data Provider
<p>It is possible to define your own templates as well as the data that are passed when these are generated.</p>
<p>In order to do so, simply create a php file in the <code>MAGENTO_ROOT/dev/mage-bundle/custom-data/#path-to-template#</code> directory, naming the file exactly as the template file that is being generated and returning an array with setter methods as keys and corresponding values.</p>
<p>For <code>Company/Module/etc</code> directories, these are stored in a sub-directory <code>MAGENTO_ROOT/dev/mage-bundle/custom-data/etc/#area#/#template_name#.tpl.php</code></p>
<p>Values can be a string or an array.</p>

#### Example
```php
<?php
return [
   'setMyCustomValue' => 'custom_value'
];
```
#### Note:
<p>After doing so it will be possible to use the placeholder <code>{{my_custom_value}}</code> in a template file.</p>
<p>If <code>setMyCustomValue</code> provides an array, this will be imploded with <code>PHP_EOL</code> separator.</p>
<p>Additional tabs characters should be defined in the array value like so:</p>


```php
<?php
return [
   'setMyCustomValue' => ["test", "\ttest2"]
];
```

