# 42_camagru
Instagram-like Image Sharing website, using Apache2 with PHP

### httpd.conf
The following is recommended to add to your httpd.conf or apache2.conf:

```apache
Listen 80
Listen 443

<VirtualHost *:80>
	DocumentRoot "/ROOT_PATH/camagru/site"
</VirtualHost>
<VirtualHost *:443>
	DocumentRoot "/ROOT_PATH/camagru/site"
	SSLEngine On
	SSLCertificateFile "/MAMP_ROOT/apache2/conf/server.crt"
	SSLCertificateKeyFile "/MAMP_ROOT/apache2/conf/server.key"
</VirtualHost>

<Directory "/ROOT_PATH/camagru/site">
	Options +Multiviews
	Options -FollowSymlinks -Indexes
	AllowOverride None
	Require all granted
</Directory>
```
