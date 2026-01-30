<html>
  <head>
  
  	<link rel="stylesheet" href="http://www.framingredpower.org/css/style.css">
	
	<base href="http://www.framingredpower.org" />
	
     <script src="http://simile.mit.edu/timeline/api/timeline-api.js" type="text/javascript"></script>

<title>Framing Red Power</title></head>

<!--BEGINNING OF TIMELINE SCRIPT-->
		<script>
		var tl;
		 function onLoad() {
		   var eventSource = new Timeline.DefaultEventSource();
		   var bandInfos = [
			 Timeline.createBandInfo({
				 eventSource:    eventSource,
				 date:           "Nov 02 1972 00:00:00 GMT",
				 width:          "70%", 
				 intervalUnit:   Timeline.DateTime.DAY, 
				 intervalPixels: 100
			 }),
			 Timeline.createBandInfo({
				 showEventText:  false,
				 trackHeight:    0.5,
				 trackGap:       0.2,
				 eventSource:    eventSource,
				 date:           "Nov 06 1972 00:00:00 GMT",
				 width:          "30%", 
				 intervalUnit:   Timeline.DateTime.MONTH, 
				 intervalPixels: 200
			 })
		   ];
		   bandInfos[1].syncWith = 0;
		   bandInfos[1].highlight = true;
		   bandInfos[1].eventPainter.setLayout(bandInfos[0].eventPainter.getLayout());
		   
		   tl = Timeline.create(document.getElementById("t1"), bandInfos);
		   Timeline.loadXML("http://www.framingredpower.org/xml/papercoverage.xml", function(xml, url) { eventSource.loadXML(xml, url); });
		 }
		</script>
<!--END OF TIMELINE SCRIPT-->

 <body onLoad="onLoad();" onResize="onResize();">
 
		   <center>
		  <table width="400" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td><table width="0" border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="16"><img src="http://simile.mit.edu/timeline/api/images/blue-circle.png"></td>
					<td width="60"> 
					  <div align="left">Newspaper Report</div></td>
				</tr>
			  </table></td>
			<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td width="16"><img src="http://simile.mit.edu/timeline/api/images/dark-green-circle.png"></td>
					<td width="60">
		<div align="left">Editorial</div></td>
				</tr>
			  </table></td>
			<td><table border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="16"><img src="http://simile.mit.edu/timeline/api/images/dark-red-circle.png"></td>
					<td width="60">
		<div align="left">Television Report</div></td>
				</tr>
			  </table></td>
		  </tr>
			<tr>
			  <td colspan="4" align="center">Click, hold, and drag your mouse to move through the timeline.  Individual points can be clicked on to navigate to the corresponding newspaper article in the archive.</td>
		    </tr>
		  </table>
     </center>
 
  <!-- TIMELINE BODY CONTENT BEGINS -->
	<div id="t1" class="timeline-default" style="height: 680px; margin: 2em;">
	</div>
  <!-- TIMELINE BODY CONTENT ENDS -->

  
  <!--BEGINNING OF FOOTER-->
	<div id="footcolor">
		<div id="footer">
		
	 		  <p><a href="http://www.framingredpower.org/xml/papercoverage.xml" style="float:right;background:orange;color:white;text-transform:none;text-decoration:none;"><strong>&nbsp;XML&nbsp;</strong></a></p>
		
		<p class="logo"><a href="http://www.framingredpower.org/" title="Framing Red Power"><img src="/images/frp_small.jpg" alt="Framing Red Power"/></a></p>
		
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="/narrative/">Overview</a></li>
			<li><a href="/archive/types.php">Documents</a></li>
			<li><a href="/sources/">Browse</a></li>
			<li><a href="/secondary/">Historiography</a></li>
			<li><a href="/about/">About the Project</a></li>
			<li><a href="mailto:jason.heppler@huskers.unl.edu">Comments?</a></li>
			
		</ul>

 			<p class="copyStmt"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/80x15.png" /></a></p>

		</div>
	</div>

<!--END OF FOOTER-->
  
  </body>
</html>
