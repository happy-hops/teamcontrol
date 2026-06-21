FROM httpd:2.4

RUN set -eux; \
  apt-get update \
  && apt-get install -y --no-install-recommends curl ca-certificates \
  && rm -rf /var/lib/apt/lists/*;

RUN set -eux; \
    openssl req -subj '/CN=tc.local/O=Race4Hospiz e.V./C=DE' \
      -new -newkey rsa:2048 -days 365 -nodes -x509 \
      -keyout /etc/ssl/private/ssl-cert-snakeoil.key \
      -out /etc/ssl/certs/ssl-cert-snakeoil.pem; \
    rm -rf /var/lib/apt/lists/*;

RUN set -eux; \
  sed -i 's/^#LoadModule ssl_module/LoadModule ssl_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule http2_module/LoadModule http2_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule proxy_module/LoadModule proxy_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule headers_module/LoadModule headers_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule rewrite_module/LoadModule rewrite_module/' /usr/local/apache2/conf/httpd.conf || true; \
  sed -i 's/^#LoadModule status_module/LoadModule status_module/' /usr/local/apache2/conf/httpd.conf || true; \
  grep -q '^Listen 443' /usr/local/apache2/conf/httpd.conf || echo 'Listen 443' >> /usr/local/apache2/conf/httpd.conf; \
  echo 'Include /usr/local/apache2/conf/extra/vhost.conf' >> /usr/local/apache2/conf/httpd.conf; \
  echo 'Include /usr/local/apache2/conf/extra/httpd-info.conf' >> /usr/local/apache2/conf/httpd.conf \
  && sed -ri 's/Require host .example.com/Require local/' /usr/local/apache2/conf/extra/httpd-info.conf \
  && sed -ri 's/Require ip 127/# &/' /usr/local/apache2/conf/extra/httpd-info.conf \
  && sed -ri 's|^#ExtendedStatus On|ExtendedStatus On|' /usr/local/apache2/conf/extra/httpd-info.conf
