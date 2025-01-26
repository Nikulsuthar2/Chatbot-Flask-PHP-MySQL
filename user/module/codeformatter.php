<?php


function CodeFormat($botmsg = "")
{
    if(str_contains($botmsg,"<CODELN-PY>")){
        $botmsg = str_replace("<CODELN-PY>","",$botmsg);
        $botmsg = str_replace("<CODESTART>","<div class='code'>",$botmsg);
        $botmsg = str_replace("<CODEEND>","</div>",$botmsg);
        $botmsg = str_replace("\\t","&nbsp;&nbsp;&nbsp;&nbsp;",$botmsg);
        $botmsg = str_replace("\\n","<br>",$botmsg);
        $botmsg = str_replace("def","<span style='color:royalblue;'>def</span>",$botmsg);
        $botmsg = str_replace("for","<span style='color:violet;'>for</span>",$botmsg);
        $botmsg = str_replace("if","<span style='color:violet;'>if</span>",$botmsg);
        $botmsg = str_replace("elif","<span style='color:violet;'>elif</span>",$botmsg);
        $botmsg = str_replace("else","<span style='color:violet;'>else</span>",$botmsg);
        $botmsg = str_replace("print(","<span style='color:yellow;'>print</span><span>(</span>",$botmsg);
        $botmsg = str_replace("int(","<span style='color:#57ff57;'>int</span><span>(</span>",$botmsg);
        $botmsg = str_replace("str(","<span style='color:#57ff57;'>str</span><span>(</span>",$botmsg);
        $botmsg = str_replace("<COMMA>",",",$botmsg);
    }
    else if(str_contains($botmsg,"<CODELN-HTML>"))
    {
        $botmsg = str_replace("<CODELN-HTML>","",$botmsg);
        $botmsg = str_replace("</","&lt;/",$botmsg);
        $botmsg = str_replace("<","&lt;",$botmsg);
        $botmsg = str_replace(">","&gt;",$botmsg);
        $botmsg = str_replace("&lt;CODESTART&gt;","<div class='code'>",$botmsg);
        $botmsg = str_replace("&lt;CODEEND&gt;","</div>",$botmsg);
        $botmsg = str_replace("\\t","&nbsp;&nbsp;&nbsp;&nbsp;",$botmsg);
        $botmsg = str_replace("\\n","<br>",$botmsg);
        $botmsg = str_replace("body","<span style='color:royalblue;'>body</span>",$botmsg);
        $botmsg = str_replace("html","<span style='color:royalblue;'>html</span>",$botmsg);
        $botmsg = str_replace("head","<span style='color:royalblue;'>head</span>",$botmsg);
        $botmsg = str_replace("title","<span style='color:royalblue;'>title</span>",$botmsg);
    }
    return $botmsg;
}
?>