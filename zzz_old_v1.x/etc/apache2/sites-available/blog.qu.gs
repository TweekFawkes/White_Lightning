<VirtualHost *>
  ServerName blog.qu.gs

  #Use incoming Host HTTP request header for proxy request
  #ProxyPreserveHost on
  
  # Enables forward (standard) proxy requests
  #ProxyRequests off
  #<Proxy *>
  #  Order allow,deny
  #  Allow from all
  #</Proxy>

  #Maps remote servers into the local server URL-space
  ProxyPass / http://10.191.53.90:805/
  
  #Adjusts the URL in HTTP response headers sent from a reverse proxied server
  ProxyPassReverse / http://10.191.53.90:805/
  <Location />
    Order allow,deny
    Allow from all
  </Location>
</VirtualHost>
