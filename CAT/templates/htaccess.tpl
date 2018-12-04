# .htaccess generated by BlackCat CMS
<Files ~ "^\.(htaccess|htpasswd)$">
deny from all
</Files>
Options Indexes FollowSymLinks
order deny,allow

AuthUserFile {$filename}
AuthGroupFile /dev/null
AuthName "Protected Directory"
AuthType Basic
require valid-user