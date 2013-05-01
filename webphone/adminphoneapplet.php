<?php
$serveraddress=$_GET['serveraddress'];
$phoneuser=$_GET['username'];
$phonepass=$_GET['password'];
?>

<p><b>RCS Web Phone</b></p>
<object
  type="application/x-java-applet"
  width    = "300"  
  height   = "330"
  hspace   = "0"
  vspace   = "0"
  align= "middle"
  name = "webphone">
    <param name="code" value="webphone.webphone.class" />
    <param name="archive" value="webphone.jar" />
    <param name="codebase" value="webphone" />
    <param name="hasaudio" value="true" />
    <param name="serveraddress" value="<?php echo $serveraddress; ?>" />
    <param name="multilinegui" value="false" />
    <param name="username" value="<?php echo $phoneuser; ?>" />
    <param name="password" value="<?php echo $phonepass; ?>" />
    <param name="register" value="true" />
    <param name="hasaudio" value="true" />
    <param name="displaysipusername" value="false" />
    <param name="hideusernamepwdinput" value="true" />
    <param name="hasconnect" value="false" />
    <param name="haschat" value="0" />
    <param name="autoaccept" value="true" />
    <param name="use_pcmu" value="3" />



<b>Java is currently not installed or not enabled.</b>

</object>

</p>

<p>
Need Java? <br> Click Here <b><a href="http://www.java.com/en/download/index.jsp">here</a></b>.<br>
</p>