<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="jslint">
        <testsuite errors="0" name="JSLint">
            <xsl:attribute name="failures">
                <xsl:number value="count(descendant::issue)"/>
            </xsl:attribute>
            <xsl:attribute name="tests">
                <xsl:number value="count(descendant::file)"/>
            </xsl:attribute>
            <xsl:apply-templates />
        </testsuite>
    </xsl:template>

    <xsl:template match="file">
        <testcase>
            <xsl:attribute name="name">
                <xsl:value-of select="@name"/>
            </xsl:attribute>
            <xsl:apply-templates select="issue"/>
        </testcase>
    </xsl:template>

    <xsl:template match="issue">
        <failure xml:space="preserve"><xsl:attribute name="type"><xsl:value-of select="@reason"/></xsl:attribute><xsl:attribute name="message">Line: <xsl:value-of select="@line"/> Char: <xsl:value-of select="@char"/></xsl:attribute>
            Line: <xsl:value-of select="@line"/> Char: <xsl:value-of select="@char"/>
			<xsl:value-of select="@reason"/>
			<xsl:value-of select="@evidence"/>
        </failure>
    </xsl:template>
</xsl:stylesheet>