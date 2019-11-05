# 42_camagru
Instagram-like Image Sharing website

### httpd.conf
The following is recommended to add to your httpd.conf or apache2.conf:

```conf
Listen 80

<VirtualHost *:80>
	DocumentRoot "/ROOT_PATH/camagru/site"
</VirtualHost>

<Directory "/ROOT_PATH/camagru/site">
	Options +Multiviews
	Options -FollowSymlinks -Indexes
	AllowOverride None
	Require all granted
</Directory>
```
