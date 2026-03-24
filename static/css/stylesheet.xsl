<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="2.0">

    <xsl:output method="html" encoding="iso-8859-1" indent="yes"/>

    <xsl:strip-space elements="*"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
				<title>Framing Red Power</title>

                <link rel="stylesheet" type="text/css" href="http://www.framingredpower.org/css/style.css"/>
                
                <base href="http://www.framingredpower.org/" />

            </head>

            <body>     

	<div id="mainBody">

	
  <div id="banner"> <span>Framing Red Power: Newspaper Coverage and the Trail of Broken Treaties</span> </div>

		<div id="menu">
				
		    <ul id="main_menu"	>
		      <li><a href="/" title="Return to the frontpage">HOME</a></li>
		      <li><a href="/narrative/" title="Analysis of material">ANALYSIS</a></li>
		      <li id="current"><a href="/archive/types.php" title="Digital archive of documents">DOCUMENTS</a></li>
		      <li><a href="/sources/" title="Browse the project">BROWSE</a></li>
		    </ul>
		
		</div>


	<div id="content">
	<div class="bodyContent">
	
	
                            <!-- main content goes here -->

                            <xsl:apply-templates/>

</div>
</div>


	<div id="footcolor">
		<div id="footer">

		<ul>
			<li><a href="/secondary/" title="Relevant historiography">Historiography</a></li>
			<li><a href="/about/" title="About the project">About the Project</a></li>
			<li><a href="mailto:jason.heppler@huskers.unl.edu" title="Contact">Comments?</a></li>
		</ul>

 			<p class="copyStmt"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/80x15.png" /></a></p>

		</div>
	</div>
</div>
</body>
</html>

    </xsl:template>

    <xsl:template match="head">
        <xsl:choose>
            <xsl:when test="preceding::head">
                <br/>
                <h3>
                    <xsl:apply-templates/>
                </h3>
            </xsl:when>
            <xsl:otherwise>
                <br/>
                <h2>
                    <xsl:apply-templates/>
                </h2>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>


    <xsl:template match="lb">
        <xsl:apply-templates/>
        <br/>
    </xsl:template>

    <xsl:template match="hi[@rend='underline']">
        <u>
            <xsl:apply-templates/>
        </u>
    </xsl:template>

    <xsl:template match="hi[@rend='italic']">
        <i>
            <xsl:apply-templates/>
        </i>
    </xsl:template>
    

    <xsl:template match="sic[@corr]">
        <xsl:value-of select="@corr"/>
    </xsl:template>

    <xsl:template match="teiHeader"/>

    <xsl:template match="p">

        <xsl:choose>
            <xsl:when test="child::note">
                <xsl:value-of select="child::note/@n"/>
                <a>
                    <xsl:attribute name="name">
                        <xsl:value-of select="child::note/@id"/>
                    </xsl:attribute>

                </a>
                <xsl:apply-templates/> [<a>
                    <xsl:attribute name="href">
                        <xsl:text>#</xsl:text>
                        <xsl:value-of select="child::note/@id"/>
                        <xsl:text>b</xsl:text>
                    </xsl:attribute>
                    <xsl:text>back to text</xsl:text>
                </a>] <br/>
            </xsl:when>
            <xsl:otherwise>
                <p>
                    <xsl:apply-templates/>
                </p>
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>

    <xsl:template match="note">
        <p>
            <xsl:apply-templates/>
        </p>
    </xsl:template>

    <xsl:template match="bibl">
        <xsl:apply-templates/>
        <br/>
        <br/>
    </xsl:template>

    <xsl:template match="milestone">
        <hr width="70%"/>
    </xsl:template>

    <xsl:template match="lg">
        <blockquote>
            <xsl:apply-templates/>
        </blockquote>
        <br/>
        <br/>
    </xsl:template>

    <xsl:template match="l">
        <xsl:apply-templates/>
        <br/>
    </xsl:template>

    <xsl:template match="seg | closer | signed">
        <xsl:apply-templates/>
        <br/>
    </xsl:template>

    <xsl:template match="pb"/>

    <xsl:template match="gap"> [<xsl:value-of select="@reason"/>] </xsl:template>



    <xsl:template match="xref">
        <a>
            <xsl:attribute name="href">
                <xsl:value-of select="@url"/>
            </xsl:attribute>
            <xsl:attribute name="target">
                <xsl:text>_new</xsl:text>
            </xsl:attribute>
            <xsl:value-of select="@url"/>
        </a>
    </xsl:template>

    <xsl:template
        match="title | pubPlace | publisher | date | author | div1 | div2 |div3 |div4 | biblScope | editor | byline | orig | unclear">
        <xsl:apply-templates/>
    </xsl:template>

    <!--<xsl:template
        match="*[not(name()='head')]
        [not(name()='text')]
        [not(name()='p')]
        [not(name()='hi')]
        [not(name()='teiHeader')]
        [not(name()='TEI')]
        [not(name()='sic')]
        [not(name()='lb')]
        [not(name()='body')]
        [not(name()='bibl')]
        [not(name()='title')]
        [not(name()='div1')]
        [not(name()='div2')]
        [not(name()='div3')]
        [not(name()='div4')]
        [not(name()='pubPlace')]
        [not(name()='publisher')]
        [not(name()='date')]
        [not(name()='author')]
        [not(name()='editor')]
        [not(name()='note')]
        [not(name()='biblScope')]
        [not(name()='milestone')]
        [not(name()='byline')]
        [not(name()='lg')]
        [not(name()='l')]
        [not(name()='seg')]
        [not(name()='orig')]
        [not(name()='unclear')]
        [not(name()='gap')]
        [not(name()='xref')]
        [not(name()='closer')]
        [not(name()='signed')]
        [not(name()='pb')]
        [not(name()='list')]
        [not(name()='item')]
        [not(name()='ref')]
        [not(name()='note')]
        [not(name()='figure')]
        [not(name()='table')]
        [not(name()='row')]
        [not(name()='cell')]
        [not(name()='quote')]
">

        <xsl:variable name="unstyledElement">
            <xsl:value-of select="name()"/>
        </xsl:variable>

        <font color="red">
            <xsl:text>&lt;</xsl:text>
            <xsl:value-of select="$unstyledElement"/>
            <xsl:text>&gt;</xsl:text>
            <xsl:apply-templates/>
            <xsl:text>&lt;/</xsl:text>
            <xsl:value-of select="$unstyledElement"/>
            <xsl:text>&gt;</xsl:text>
        </font>

    </xsl:template>-->

    <xsl:template match="list">
        <ul>
            <xsl:apply-templates/>
        </ul>
    </xsl:template>

    <xsl:template match="item">
        <li>
            <xsl:apply-templates/>
        </li>
    </xsl:template>

    <xsl:template match="ref">
        <a>
            <xsl:attribute name="name">
                <xsl:value-of select="@target"/>
                <xsl:text>b</xsl:text>
            </xsl:attribute>
        </a>
        <a>
            <xsl:attribute name="href">
                <xsl:text>#</xsl:text>
                <xsl:value-of select="@target"/>
            </xsl:attribute> [<xsl:value-of select="@n"/>] </a>
    </xsl:template>

    <xsl:template match="note"/>

    <xsl:template match="figure">
        <xsl:choose>
            <xsl:when test="attribute::entity">
                <a>
                    <xsl:attribute name="href">
                        <xsl:text>figures/</xsl:text>
                        <xsl:value-of select="@entity"/>
                        <xsl:text>.jpg</xsl:text>
                    </xsl:attribute>
                    <img>
                        <xsl:attribute name="src">
                            <xsl:text>figures/thumbs/</xsl:text>
                            <xsl:value-of select="@entity"/>
                            <xsl:text>.jpg</xsl:text>
                        </xsl:attribute>
                    </img>

                </a>
            </xsl:when>
            <xsl:otherwise/> 

        </xsl:choose>
    </xsl:template>



    <xsl:template match="table">
        <table>
            <xsl:apply-templates/>
        </table>
    </xsl:template>

    <xsl:template match="row">
        <tr>
            <xsl:apply-templates/>
        </tr>
    </xsl:template>

    <xsl:template match="cell">
        <td>
            <xsl:apply-templates/>
        </td>
    </xsl:template>

    <xsl:template match="quote">
        <blockquote>
            <xsl:apply-templates/>
        </blockquote>
    </xsl:template>




</xsl:stylesheet>
