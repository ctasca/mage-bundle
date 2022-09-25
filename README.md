# mage-bundle
### Installation
```bash
composer require --dev ctasca/mage-bundle
```
### To move skeleton files to app/media directory
<p>This should happen automatically after installing the module.</p>
<p>If it doesn't it is possible to manually execute the composer post install command with the following:</p>

```bash
cd /var/www/html/vendor/ctasca/mage-bundle && composer run-script post-install-cmd
```

### Available commands
```bash
bin/magento magebundle:module:create
```
This command creates a skeleton Magento module in app/code directory, generating the required registration.php and etc/module.xml files