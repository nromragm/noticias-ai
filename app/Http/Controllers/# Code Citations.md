# Code Citations

## License: MIT
https://github.com/phucngodev/SimpleMVC/tree/8e803f6e391554b59d4454fb0eaae004d3f46461/README.md

```
c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L
```


## License: unknown
https://github.com/mrbudbud/Remove-URL-public-in-Laravel/tree/ac240fb878cc2bdbcf67683398bded4dafb3d059/README.md

```
...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME}
```

