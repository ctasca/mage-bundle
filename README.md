# ctasca/mage-bundle

## Installation
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

## Available commands
```bash
bin/magento magebundle:module:create
```
Creates a skeleton Magento module in app/code directory, generating the required registration.php and etc/module.xml files

```bash
bin/magento magebundle:controller:create
```
Creates a Controller namespace and Action class in specified module.
Developer is prompted to choose a router (either standard or admin), and an Action template from the ones available in <code>mage-bundle/http-controller</code> or <code>mage-bundle/adminhtml-http-controller</code> directories.

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

```bash
bin/magento magebundle:customer-data:create
```
Creates a CustomerData class in specified Company/Module. 

```bash
bin/magento magebundle:view-model:create
```
Creates a View Model class in specified Company/Module.

```bash
bin/magento magebundle:observer:create
```
Creates an Observer class in specified Company/Module.

```bash
bin/magento magebundle:plugin:create
```
Creates a Plugin class in specified Company/Module.

```bash
bin/magento magebundle:cron:create
```
Creates a Cron class in specified Company/Module.

```bash
bin/magento magebundle:console-command:create
```
Creates a Console Command class in specified Company/Module.

```bash
bin/magento magebundle:data-patch:create
```
Creates a Setup Data Patch class in specified Company/Module.

```bash
bin/magento magebundle:schema-patch:create
```
Creates a Setup Schema Patch class in specified Company/Module.

```bash
bin/magento magebundle:api-interface:create
```
<p>Creates an API interface in specified Company/Module. Templates can be chosen after specifying the area where the template applies to.</p>
<p>For functional API interfaces, the generated file will be created in the Company/Module/Api directory</p>
<p>For data API interfaces, the generated file will be created in the Company/Module/Api/Data directory</p>

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

