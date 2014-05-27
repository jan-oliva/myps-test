<xsl:stylesheet	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>
    <xsl:decimal-format decimal-separator="." grouping-separator="," />
    <xsl:key name="files" match="file" use="@name" />
    <xsl:template match="checkstyle">
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0"  />
                <link rel="stylesheet" href="/silex/resources/css/bootstrap.min.css"  />
                <title>Checkstyle Audit</title>
            </head>
            <body>
                <div class="container">
                    <a name="top"></a>
                    <h1 class='text-right'>Checkstyle Audit</h1>

                    <!-- Summary part -->
                    <xsl:apply-templates select="." mode="summary"/>

                    <!-- Package List part -->
                    <xsl:apply-templates select="." mode="filelist"/>

                    <!-- For each package create its part -->
                    <xsl:apply-templates select="file[@name and generate-id(.) = generate-id(key('files', @name))]" />
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="checkstyle" mode="filelist">
        <h3>Soubory</h3>
        <table class="table table-striped table-hover">
            <colgroup>
                <col class='col-md-11' />
                <col class='col-md-1' />
            </colgroup>
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Chyby</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="file[@name and generate-id(.) = generate-id(key('files', @name))]">
                    <xsl:sort data-type="number" order="descending" select="count(key('files', @name)/error)"/>
                    <xsl:variable name="errorCount" select="count(error)"/>
                    <tr>
                        <td>
                            <a href="#f-{@name}">
                                <xsl:value-of select="@name"/>
                            </a>
                        </td>
                        <td>
                            <xsl:value-of select="$errorCount"/>
                        </td>
                    </tr>
                </xsl:for-each>
            </tbody>
        </table>
    </xsl:template>

    <xsl:template match="file">
        <a name="f-{@name}"></a>
        <h3>Soubor <xsl:value-of select="@name"/></h3>

        <table class="table table-striped table-hover">
            <colgroup>
                <col class='col-md-11' />
                <col class='col-md-1' />
            </colgroup>
            <thead>
                <tr>
                    <th>Popis chyby</th>
                    <th>Řádek</th>
                </tr>
            </thead>
            <tbody>
                <xsl:for-each select="key('files', @name)/error">
                    <xsl:sort data-type="number" order="ascending" select="@line"/>
                    <tr>
                        <td>
                            <xsl:value-of select="@message"/>
                        </td>
                        <td>
                            <xsl:value-of select="@line"/>
                        </td>
                    </tr>
                </xsl:for-each>
            </tbody>
        </table>
        <a href="#top">Nahoru</a>
    </xsl:template>

    <xsl:template match="checkstyle" mode="summary">
        <h3>Souhrnný přehled</h3>
        <xsl:variable name="fileCount" select="count(file[@name and generate-id(.) = generate-id(key('files', @name))])"/>
        <xsl:variable name="errorCount" select="count(file/error)"/>
        <table class="table table-striped table-hover">
            <colgroup>
                <col class='col-md-11' />
                <col class='col-md-1' />
            </colgroup>
            <thead>
                <tr>
                    <th>Soubory</th>
                    <th>Chyby</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <xsl:value-of select="$fileCount"/>
                    </td>
                    <td>
                        <xsl:value-of select="$errorCount"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </xsl:template>
</xsl:stylesheet>