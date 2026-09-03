<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/">
    <html>
    <head>
      <title>Styled Sitemap</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background-color: #f2f2f2; }
      </style>
    </head>
    <body>
      <h1>Website Sitemap</h1>
      <table>
        <tr>
          <th>URL</th>
          <th>Last Modified</th>
        </tr>
        <xsl:for-each select="sitemap:urlset/sitemap:url">
          <tr>
            <td><xsl:value-of select="sitemap:loc"/></td>
            <td><xsl:value-of select="sitemap:lastmod"/></td>
          </tr>
        </xsl:for-each>
      </table>
    </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
