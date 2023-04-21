# ctasca/mage-bundle
[![License](http://poser.pugx.org/phpstan/phpstan/license)](https://packagist.org/packages/phpstan/phpstan) [![PHP Version Require](http://poser.pugx.org/badges/poser/require/php)](https://packagist.org/packages/badges/poser)
### Easily create Magento2/AdobeCommerce PHP/XML/JS files from a set of templates via the command-line.
<p>Allows you to define your own templates as well as the data provided at files' creation time.</p>

## Installation
```bash
composer require --dev ctasca/mage-bundle
```

<p><strong>Note: If you are getting the following composer error, it is due to your minimum-stability settings.</strong></p>

```
Could not find a version of package ctasca/mage-bundle matching your minimum-stability (stable). Require it with an explicit version constraint allowing its desired stability
```

### Workaround

```bash
composer require --dev ctasca/mage-bundle:v3.x.x-BETA
```
or

```bash
composer require --dev ctasca/mage-bundle:dev-main
```

### Copy template files to Magento root dev/ directory

```bash
cd <magento_root>/vendor/ctasca/mage-bundle && composer run-script post-install-cmd
```
<p>After executing the above command, check that <code>dev/</code> directory of your Magento installation contains a <code>mage-bundle/</code> directory.</p>

### Enable the module
```bash
bin/magento module:enable Ctasca_MageBundle
```

### Run <code>setup:upgrade</code> command
```bash
bin/magento setup:upgrade
```

### Run <code>setup:di:compile</code> command (optional)
<p>If Magento is running in <code>production</code> mode you will need to also run:</p>

```bash
bin/magento setup:di:compile
```

## Available commands
```bash
bin/magento magebundle:module:create
```
#### Shortcut
```bash
bin/magento m:modu:c
```
<p>Creates a skeleton Magento module in app/code directory, generating the required registration.php and etc/module.xml files</p>

---

```bash
bin/magento magebundle:controller:create
```
#### Shortcut
```bash
bin/magento m:cont:c
```
<p>Creates a Controller namespace and Action class in specified module.
Developer is prompted to choose a router (either standard or admin), and an Action template from the ones available in <code>mage-bundle/http-controller</code> or <code>mage-bundle/adminhtml-http-controller</code> directories.</p>

---

```bash
bin/magento magebundle:etc:xml:create
```
#### Shortcut
```bash
bin/magento m:e:x:c
```
<p>Creates an XML file in Company/Module/etc directory. Templates can be chosen after specifying the area where the template applies to.</p>

---

```bash
bin/magento magebundle:model:set:create
```
#### Shortcut
```bash
bin/magento m:m:s:c
```
<p>Creates a Model, Resource Model and Collection classes in specified Company/Module.</p>

---

```bash
bin/magento magebundle:repository:create
```
#### Shortcut
```bash
bin/magento m:r:c
```
<p>Creates all the required class for a Repository in specified Company/Module.</p>
<p><strong>IMPORTANT:</strong> In order to create a repository a model implementing an interface must exist.</p>
<p>If you need a Repository when creating a model-set with the command <code>magebundle:model:set:create</code> you can choose a template that will also create a Model implementing an interface</p>
<p>This command creates the following:</p>
<ul>
<li>An API Interface for the repository</li>
<li>An API Data Interface for the model (if it doesn't exist)</li>
<li>An API Data Search Result Interface</li>
<li>The repository Model implementing the repository Interface</li>
</ul>

<p>Do not forget to add the preferences to your di.xml for the repository classes once created. For example:</p>

```xml
    <preference for="Company\Module\Api\Data\$MODEL_NAMEInterface" type="Company\Module\Model\$MODEL_NAME" />
    <preference for="Company\Module\Api\$MODEL_NAMERepositoryInterface" type="Company\Module\Model\$MODEL_NAMERepository" />
    <preference for="Company\Module\Api\Data\$MODEL_NAMESearchResultInterface" type="Company\Module\Model\$MODEL_NAMESearchResult" />
```

---

```bash
bin/magento magebundle:model:create
```
#### Shortcut

```bash
bin/magento m:mode:c
```
<p>Creates a Model class in specified Company/Module. There is also the template to create an interface instead of a class.</p>

---

```bash
bin/magento magebundle:block:create
```
#### Shortcut
```bash
bin/magento m:b:c
```
<p>Creates a template Block class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:helper:create
```
#### Shortcut
```bash
bin/magento m:h:c
```
<p>Creates a Helper class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:customer-data:create
```
#### Shortcut
```bash
bin/magento m:cu:c
```
<p>Creates a CustomerData class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:view-model:create
```
#### Shortcut
```bash
bin/magento m:v:c
```
<p>Creates a View Model class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:observer:create
```
#### Shortcut
```bash
bin/magento m:o:c
```
<p>Creates an Observer class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:plugin:create
```
#### Shortcut
```bash
bin/magento m:p:c
```
<p>Creates a Plugin class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:cron:create
```
#### Shortcut
```bash
bin/magento m:cr:c
```
<p>Creates a Cron class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:console-command:create
```
#### Shortcut
```bash
bin/magento m:cons:c
```
<p>Creates a Console Command class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:data-patch:create
```
#### Shortcut
```bash
bin/magento m:d:c
```
<p>Creates a Setup Data Patch class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:schema-patch:create
```
#### Shortcut
```bash
bin/magento m:s:c
```
<p>Creates a Setup Schema Patch class in specified Company/Module.</p>

---

```bash
bin/magento magebundle:api-interface:create
```
#### Shortcut
```bash
bin/magento m:a:c
```
<p>Creates an API interface in specified Company/Module. Templates can be chosen after specifying the area where the template applies to.</p>
<p>For functional API interfaces, the generated file will be created in the Company/Module/Api directory</p>
<p>For data API interfaces, the generated file will be created in the Company/Module/Api/Data directory</p>

---

```bash
bin/magento magebundle:jquery-widget:create
```
#### Shortcut
```bash
bin/magento m:j:c
```
<p>Creates a JQuery widget file in specified Company/Module. JS file will be created in the specified module's <code>view/$AREA/web/js</code> directory.</p>

---

```bash
bin/magento magebundle:ui-component:create
```
#### Shortcut
```bash
bin/magento m:u:c
```
<p>Creates an Ui Component JS file in specified Company/Module. JS file will be created in the specified module's <code>view/$AREA/web/js</code> directory.</p>

---

```bash
bin/magento magebundle:logger:create
```
#### Shortcut
```bash
bin/magento m:l:c
```
<p>Creates a Logger Handler and Logger classes files in specified Company/Module.</p>
<p>Log filename can be specified when executing this command.</p>

---

```bash
bin/magento magebundle:js:mixin:create
```
#### Shortcut
```bash
bin/magento m:j:m:c
```
<p>Creates a JS mixin file in specified Company/Module. JS file will be created in the specified module's <code>view/$AREA/web/js</code> directory.</p>

---

```bash
bin/magento magebundle:exception:create
```
#### Shortcut
```bash
bin/magento m:e:c
```
<p>Creates an Exception class in specified Company/Module.</p>


## About template files:
<p>Template files are written in PHP version 8.1.</p>
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

<p>To set your own templates just place them in the <code>MAGENTO_ROOT/dev/mage-bundle/$TEMPLATES_DIRECTORY/</code> directory and choose the template when executing the magebundle create commands.</p>
<p><strong>Note:</strong> When executing the <code>magebundle:etc:xml:create</code> command, files are generated with the same name as the template file.<br>To create your own templates, use the filename for the xml file to be generated appending __$STRING to the template filename.<br>For example to define your own global acl.xml template, create, for example, the template file naming like so: acl__custom.tpl.xml. Then simply place it in the dev/mage-bundle/etc/global directory and select it when executing the create command.</p>
<p><strong>IMPORTANT:</strong> If a filename already exists in a module's directory, the create command will not be executed and an error is output to the console.<br>This is to prevent overwriting an existing file.</p>

<p>For a list of the templates defined within the module go to...</p>

[The repository wiki page](https://github.com/ctasca/mage-bundle/wiki)

## Templates Data Provider
<p>It is possible to define your own templates as well as the data that are passed when these are generated.</p>
<p>In order to do so, simply create a JSON file in the <code>MAGENTO_ROOT/dev/mage-bundle/custom-data/#path-to-template#</code> directory, naming the file exactly as the template file that is being generated and defining a JSON Object with setter methods as keys and their corresponding values.</p>
<p>As an example, for XML files generated in <code>Company/Module/etc</code> directories, custom data should be stored in <code>MAGENTO_ROOT/dev/mage-bundle/custom-data/etc/#area#/#template_name#.tpl.json</code></p>

#### Example
```json
{
  "setTestNamespace" : "\\Ctasca\\MageBundle\\Test",
  "setCustomDataArray" : ["First Value", "Second Value"]
}
```
<p>After creating this JSON file, it will be possible to use the placeholder <code>{{test_namespace}}</code> in a template file.</p>
<p>As <code>setCustomDataArray</code> provides an array, this will be imploded with <code>PHP_EOL</code> separator. To use it in your template files you would use the placeholder: <code>{{custom_data_array}}</code></p>

