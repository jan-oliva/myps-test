<xsl:stylesheet	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>
    <xsl:decimal-format decimal-separator="." grouping-separator="," />
    <xsl:template match="pmd-cpd">
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0"  />
                <link rel="stylesheet" href="/silex/resources/css/bootstrap.min.css"  />
                <title>PHPCPD Audit</title>
            </head>
            <body>
                <div class="container">
                    <a name="top"></a>
                    <h1 class='text-right'>PHPCPD Audit</h1>

                    <!-- Summary part -->
                    <xsl:apply-templates select="." mode="summary"/>

                    <!-- For each package create its part -->
                    <xsl:apply-templates select="duplication" />
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="pmd-cpd" mode="summary">
        <h3>Souhrnný přehled</h3>
        <table class="table table-striped table-hover">
            <colgroup>
                <col class='col-md-3' />
                <col class='col-md-3' />
                <col class='col-md-3' />
                <col class='col-md-3' />
            </colgroup>
            <thead>
                <tr>
                    <th>Duplicit</th>
                    <th>Duplicitních řádků</th>
                    <th>Duplicitních tokenů</th>
                    <th>Duplicitního kódu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <xsl:value-of select="count(duplication)"/>
                    </td>
                    <td>
                        <xsl:value-of select="sum(duplication/@lines)"/>
                    </td>
                    <td>
                        <xsl:value-of select="sum(duplication/@tokens)"/>
                    </td>
                    <td>~<xsl:value-of select="sum(duplication/@tokens) * 4"/>b</td>
                </tr>
            </tbody>
        </table>
    </xsl:template>

    <xsl:template match="duplication">
        <h3>Duplicita <xsl:value-of select="position()"/></h3>
        <table class="table table-striped table-hover">
            <colgroup>
                <col class='col-md-10' />
                <col class='col-md-1' />
                <col class='col-md-1' />
            </colgroup>
            <thead>
                <tr>
                    <th>Soubory</th>
                    <th>Řádků</th>
                    <th>Tokenů</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <xsl:for-each select="file">
                            <xsl:value-of select="@path"/>:<xsl:value-of select="@line"/>
                            <br />
                        </xsl:for-each>
                        <textarea rows="10" cols="100" readonly="readonly">
                            <xsl:value-of select="codefragment"/>
                        </textarea>
                    </td>
                    <td>
                        <xsl:value-of select="@lines"/>
                    </td>
                    <td>
                        <xsl:value-of select="@tokens"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <a href="#top">Nahoru</a>
    </xsl:template>
</xsl:stylesheet>