{function name=render_site_url level=0 parenturl=''}{foreach from=$items key=k item=it}
<url>
  <loc>{$parenturl}/{$it.cache_url}</loc>
  <lastmod>{$it.cache_modified|date_format:"%Y-%m-%dT%H:%M:%S-9:30"}</lastmod>';
  <changefreq>weekly</changefreq>
  <priority>0.5</priority>
</url>
{/function}
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
{call name=render_site_url items=$pages parenturl="/diy"}
</urlset>