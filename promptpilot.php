<?php

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

LLM PROMPT PILOT PROJECT
DATA 78000 TOPICS DATA ANLYSIS/VISLZATION - LARGE LANGUAGE MODELS AND CHAT GPT
https://github.com/michellejm/LLMs-fall-23

Matthew Stanton
pingstanton@gmail.com
mstanton@gradcenter.cuny.edu
New York, USA

December 6, 2023

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 VARIABLES FROM THE USER'S FORM INPUT
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

$prompt = $_POST["prompt"];
$actas = $_POST["actas"];
$emotion = $_POST["emotion"];
$voice = $_POST["voice"];
$style = $_POST["style"];
$atmosphere = $_POST["atmosphere"];
$length = $_POST["length"];
$audience = $_POST["audience"];
$length = $_POST["length"];
$readinglevel = $_POST["readinglevel"];
$pcmedium = $_POST["pcmedium"];
$model = $_POST["model"];
$temperature = $_POST["temperature"];
$diversity_penalty = $_POST["diversity_penalty"];
$max_tokens = $_POST["max_tokens"];
$hyperparameters = $_POST["hyperparameters"];
$stopwords = $_POST["stopwords"];
$updatecheck = mt_rand(1, 1000000);

/* prep prompt stuff */
$blank = "no";
if ($prompt == "") {$blank = "yes";}
$prompt = utf8_decode($prompt); /* removing Word issues with dumb brute force */
$charactercount = mb_strlen($prompt); 
$prompt = str_replace ("“", "&quot;", $prompt);
$prompt = str_replace ("”", "&quot;", $prompt);
$prompt = str_replace ("\"", "&quot;", $prompt);
$prompt = str_replace ("’", "&#039;", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
/* if asked to remove stopwords - not sure this step is of any use... */
if ($stopwords == "yes") {
	$stopwordslist01 = array(" i ", " me ", " my ", " myself ", " we ", " our ", " ours ", " ourselves ", " you ", " your ", " yours ", " yourself ", " yourselves ", " he ", " him ", " his ", " himself ", " she ", " her ", " hers ", " herself ", " it ", " its ", " itself ", " they ", " them ", " their ", " theirs ", " themselves ", " what ", " which ", " who ", " whom ", " this ", " that ", " these ", " those ", " am ", " is ", " are ", " was ", " were ", " be ", " been ", " being ", " have ", " has ", " had ", " having ", " do ", " does ", " did ", " doing ", " a ", " an ", " the ", " and ", " but ", " if ", " or ", " because ", " as ", " until ", " while ", " of ", " at ", " by ", " for ", " with ", " about ", " against ", " between ", " into ", " through ", " during ", " before ", " after ", " above ", " below ", " to ", " from ", " up ", " down ", " in ", " out ", " on ", " off ", " over ", " under ", " again ", " further ", " then ", " once ", " here ", " there ", " when ", " where ", " why ", " how ", " all ", " any ", " both ", " each ", " few ", " more ", " most ", " other ", " some ", " such ", " no ", " nor ", " not ", " only ", " own ", " same ", " so ", " than ", " too ", " very ", " s ", " t ", " can ", " will ", " just ", " don ", " should ", " now ");
	$stopwordslist02 = array(". I ", ". Me ", ". My ", ". Myself ", ". We ", ". Our ", ". Ours ", ". Ourselves ", ". You ", ". Your ", ". Yours ", ". Yourself ", ". Yourselves ", ". He ", ". Him ", ". His ", ". Himself ", ". She ", ". Her ", ". Hers ", ". Herself ", ". It ", ". Its ", ". Itself ", ". They ", ". Them ", ". Their ", ". Theirs ", ". Themselves ", ". What ", ". Which ", ". Who ", ". Whom ", ". This ", ". That ", ". These ", ". Those ", ". Am ", ". Is ", ". Are ", ". Was ", ". Were ", ". Be ", ". Been ", ". Being ", ". Have ", ". Has ", ". Had ", ". Having ", ". Do ", ". Does ", ". Did ", ". Doing ", ". A ", ". An ", ". The ", ". And ", ". But ", ". If ", ". Or ", ". Because ", ". As ", ". Until ", ". While ", ". Of ", ". At ", ". By ", ". For ", ". With ", ". About ", ". Against ", ". Between ", ". Into ", ". Through ", ". During ", ". Before ", ". After ", ". Above ", ". Below ", ". To ", ". From ", ". Up ", ". Down ", ". In ", ". Out ", ". On ", ". Off ", ". Over ", ". Under ", ". Again ", ". Further ", ". Then ", ". Once ", ". Here ", ". There ", ". When ", ". Where ", ". Why ", ". How ", ". All ", ". Any ", ". Both ", ". Each ", ". Few ", ". More ", ". Most ", ". Other ", ". Some ", ". Such ", ". No ", ". Nor ", ". Not ", ". Only ", ". Own ", ". Same ", ". So ", ". Than ", ". Too ", ". Very ", ". S ", ". T ", ". Can ", ". Will ", ". Just ", ". Don ", ". Should ", ". Now ");
	$prompt = str_replace("\n", " ", $prompt);
	$prompt = str_replace("\r", " ", $prompt);
	foreach ($stopwordslist01 as $word01) {
	    $prompt = str_replace($word01, " ", $prompt);
	}
	foreach ($stopwordslist02 as $word02) {
	    $prompt = str_replace($word02, ". ", $prompt);
	}
	$prompt = str_replace ("  ", " ", $prompt);
}
if ($voice != "0" OR $style != "0" OR $atmosphere != "0" OR $emotionformat = "0" OR $actasformat != "0") {$prompt = "Use this text/topic: $prompt";}

/* TARGET AUDIENCE AND READING LEVEL */
$audienceleveltext = "";
if ($audience != "0" AND $readinglevel == "0") {$audienceleveltext = "Reply for a target audience of users $audience."; $prompt = "$audienceleveltext $prompt";}
if ($audience == "0" AND $readinglevel != "0") {$audienceleveltext = "Reply for a target audience $readinglevel reading level."; $prompt = "$audienceleveltext $prompt";}
if ($audience != "0" AND $readinglevel != "0") {$audienceleveltext = "Reply for a target audience of users $audience $readinglevel reading level."; $prompt = "$audienceleveltext $prompt";}

/* LENGTH - This block is temporary kludge since MAX TOKEN cannot be used. */
if ($length != "auto") {
	$lengthtext = "";
	if ($length == "40char") {$lengthtext = "Limit your reply to under 80 characters.";}
	elseif ($length == "280char") {$lengthtext = "Limit your reply to under 280 characters.";}
	elseif ($length == "50words") {$lengthtext = "Limit your reply to only 50 words.";}
	elseif ($length == "300words") {$lengthtext = "Limit your reply to only 300 words.";}
	elseif ($length == "500words") {$lengthtext = "Limit your reply to only 500 words.";}
	elseif ($length == "1000words") {$lengthtext = "Limit your reply to only 1,000 words.";}
	elseif ($length == "3000words") {$lengthtext = "Limit your reply to only 3,000 words.";}
	elseif ($length == "5000words") {$lengthtext = "Limit your reply to only 5,000 words.";}
	$prompt = "$lengthtext $prompt";
}

/* VOICE, STYLE, AND ATMOSPHERE */
$voicestyletext = "";
if ($voice != "0" AND $style == "0" AND $atmosphere == "0") {$voicestyletext = "Use $voice voice and style."; $prompt = "$voicestyletext $prompt";}
if ($voice == "0" AND $style != "0" AND $atmosphere == "0") {$voicestyletext = "Use $style voice and style."; $prompt = "$voicestyletext $prompt";}
if ($voice == "0" AND $style == "0" AND $atmosphere != "0") {$voicestyletext = "Respond using $atmosphere atmosphere."; $prompt = "$voicestyletext $prompt";}
if ($voice != "0" AND $style != "0" AND $atmosphere == "0") {$voicestyletext = "Use $voice and $style voice and style."; $prompt = "$voicestyletext $prompt";}
if ($voice == "0" AND $style != "0" AND $atmosphere != "0") {$voicestyletext = "Use $style voice and style, with $atmosphere atmosphere."; $prompt = "$voicestyletext $prompt";}
if ($voice != "0" AND $style == "0" AND $atmosphere != "0") {$voicestyletext = "Use $voice voice and style, with $atmosphere atmosphere."; $prompt = "$voicestyletext $prompt";}
if ($voice != "0" AND $style != "0" AND $atmosphere != "0") {$voicestyletext = "Use $voice and $style voice and style, with $atmosphere atmosphere."; $prompt = "$voicestyletext $prompt";}

/* MOOD / EMOTION */
$emotionformat = mt_rand(1, 2);
if ($emotion == "0") {$emotiontext = "";}
elseif ($emotion != "0" AND $emotionformat == "1") {
	$emotiontext = "Provide a response with $emotion tone and emotion.";
	$prompt = "$emotiontext $prompt";
}
elseif ($emotion != "0" AND $emotionformat == "2") {
	$emotiontext = "I'm looking for the tone of the answer to be $emotion.";
	$prompt = "$emotiontext $prompt";
}

/* INTENDED MEDIUM */

if ($pcmedium != "none") {
	$mediumtext = "";
	if ($pcmedium == "blog") {$mediumtext = "Create a blog post about the text provided here.";}
	elseif ($pcmedium == "social") {$mediumtext = "Create a social media post between 40 to 80 words in length, focused abut the text provided here. Include appropriate hashtags.";}
	elseif ($pcmedium == "ig") {$mediumtext = "Create an Instagram photo caption, focused about the text provided here. Include appropriate hashtags.";}
	elseif ($pcmedium == "maxseo") {$mediumtext = "Rewrite the text provided here. Seamlessly incorporate relevant keywords. Optimize the content with a clear and organized structure, utilizing H1 tags for headings and H2 tags for subheadings to facilitate better search engine crawling. Include a bullet list of the top 5 key points.";}
	elseif ($pcmedium == "ux") {$mediumtext = "Reply as if writing UX documentation, highly focused and coherent while avoiding repetitive phrases.";}
	elseif ($pcmedium == "tweet") {$mediumtext = "Create a tweet for Twitter about the text provided here. Include appropriate hashtags.";}
	elseif ($pcmedium == "video30") {$mediumtext = "Create a video production script about the text provided here. Include read lines for a narrator and suggestions for visuals and transitions.";}
	elseif ($pcmedium == "audio15") {$mediumtext = "Create a 15-second radio news script read about the text provided here.";}
	$prompt = "$mediumtext $prompt";
}

/* ACT AS... */
$actasformat = mt_rand(1, 2);
if ($actas != "0" AND $actasformat == "1") {
	$actastext = "I want you to act as $actas.";
	$prompt = "$actastext $prompt";
}
elseif ($actas != "0" AND $actasformat == "2") {
	$actastext = "You are $actas.";
	$prompt = "$actastext $prompt";
}


/* API REFERENCE SITES - planned legacy code for when APIs are working in production environment */

if ($model == "openai35") {
	$llmmodeltext = "OpenAI's ChatGPT-3.5";
	$llmmodelurl = "https://chat.openai.com/";
	$llmmodelapidocs = "platform.openai.com/docs/api-reference";
}
elseif ($model == "google") {
	$llmmodeltext = "Google Bard";
	$llmmodelurl = "https://bard.google.com/";
	$llmmodelapidocs = "labs.google/";
}
elseif ($model == "anthropic") {
	$llmmodeltext = "Anthrop\c Claude";
	$llmmodelurl = "https://claude.ai/";
	$llmmodelapidocs = "support.anthropic.com/en/collections/4078533-api-prompt-design";
}

$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$prompt = str_replace ("  ", " ", $prompt);
$promptfinal01 = "$prompt";

$copybutton = "";
$copyscript =  "";

if ($blank == "no") {
$bgcolor = "#009966";
$hilitecolor = "#43ab86";
$promptoutputs = "<div id=\"promptset01\" name=\"promptset01\" class=\"promptset\">
<h3><strong class=\"nmbr\">1.</strong> HERE IS YOUR REVISED PROMPT SUGGESTION...</h3>
<p>...copy-paste for entering into a language model-powered tool.</p>
<textarea id=\"prompt01\" name=\"prompt01\" cols=\"60\" rows=\"10\">$promptfinal01</textarea>
</div>
";
$copybutton = "<h3><strong class=\"nmbr\">2.</strong>
<button class=\"navbtn\" onclick=\"copyPrompt()\">Click to Copy Your Revised Prompt to Clipboard</button></h3>
<br clear=\"all\" />
<h3><strong class=\"nmbr\">3.</strong> TRY IT OUT...</h3>
<div style=\"margin: 0; padding: 0px 5px; align: center\" align=\"center\">
<button class=\"navbtn\" onclick=\"gotoChatGPT()\">Go to OpenAI's ChatGPT</button>
<button class=\"navbtn\" onclick=\"gotoGoogleBard()\">Go to Google Bard</button>
<button class=\"navbtn\" onclick=\"gotoClaude()\">Go to Anthropic's Claude</button>
";
$copyscript = "<script>

function copyPrompt() {
	var copyText = document.getElementById(\"prompt01\");
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	navigator.clipboard.writeText(copyText.value);
}

function gotoChatGPT() {
	window.open(\"https://chat.openai.com/\", \"_blank\");
}

function gotoGoogleBard() {
	window.open(\"https://bard.google.com/\", \"_blank\");
}

function gotoClaude() {
	window.open(\"https://claude.ai/\", \"_blank\");
}

</script>";
}

if ($blank == "yes") {
$bgcolor = "#ff0000";
$hilitecolor = "#ff0000";
$promptoutputs = "<div id=\"promptset01\" name=\"promptset01\" class=\"promptset\">
<h3><strong class=\"nmbr\">!</strong> <a href=\"#\" onclick=\"history.back()\">&#8617; MISSING PROMPT SUGGESTION</a></h3>
<p>Whoops...</p>
<textarea id=\"prompt01\" name=\"prompt01\" cols=\"60\" rows=\"10\">There was no initial prompt entered in the previous screen. Please go back and enter some information about what you want to generate in a response. \n\n&#128512;</textarea>
</div>
";}

echo "<!DOCTYPE html>
<html>
<head>
<title>LLM Prompt Pilot</title>
<meta name=\"robots\" content=\"index,follow,noarchive\" />
<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">
<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
<link href=\"https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&display=swap\" rel=\"stylesheet\">
<meta name=\"description\" content=\"OpenAI ChatGPT interface tool for multiple reformatting of news articles across various media and social channels.\" />
<meta name=\"keywords\" content=\"large language models, chatgpt, gpt, llm, cuny graduate center, data analysis program, fall 2023, matthew stanton\" />
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<style type=\"text/css\"><!--

body {
	background-color: $bgcolor; 
	padding: 10px 0px 40px 0px; 
	margin: 0; 
	color: #444; 
	font: normal 14px/1.2em verdana, arial, helvetica, sans-serif;
	line-height: 1.0;
	text-align: center;
	}

.wrp01 {
	width: 60%; min-width: 500px;
	margin-right:auto; margin-left:auto; margin-top: 5px; margin-bottom: 10px;
	padding: 0; 
	text-align: left;
	}

.wrp02 {
	border-radius: 30px;#43ab86
	margin: 2px 2px 10px 2px; padding: 10px 15px 15px 15px; 
	text-align: left; 
	background-color: #eeeeee; 
	box-shadow: 5px 5px 100px #000;
	}

a:link {color: $hilitecolor; text-decoration: none; border-bottom: 0px solid #fff;}
a:visited {color: $hilitecolor; text-decoration: none; border-bottom: 0px solid #fff;}
a:hover  {color: #EC9C1D; text-decoration: none; border-bottom: 0px solid #EC9C1D;}
a:active {color: $hilitecolor; text-decoration: none; border-bottom: 0px solid #fff;}

a.btn:link {text-decoration: none; border: 0px;}
a.btn:visited {text-decoration: none; border: 0px;}
a.btn:hover {text-decoration: none; border: 0px;}
a.btn:active {text-decoration: none; border: 0px;}

.intro {margin: 0 0 15px 0; padding: 0px 20px 2px 20px; font: italic 12px/1.2em verdana, arial, helvetica, sans-serif; color: #666; text-align: right;}

.promptset {margin: 0 0 10px 0; padding: 0;}
.promptset p {margin: 2px 0 2px 50px; padding: 0; font: italic 13px/1.2em verdana, arial, helvetica, sans-serif;}

.navbtn {margin: 7px 5px; padding: 3px 15px; 
	border-radius: 10px;
	font: bold 16px/1.2em calibri, arial, helvetica, sans-serif; 
	color: $hilitecolor; background-color: #fff;
	border: 0px solid #fff;		
	-webkit-box-shadow: 2px 2px 3px 3px rgba(0,0,0,0.15); 
	box-shadow: 2px 2px 3px 3px rgba(0,0,0,0.15);	
	}

.navbtn:hover {
	transition: 0.9s;
	border: 0px solid $hilitecolor;		
	background-color: $hilitecolor; color: #fff;
	-webkit-box-shadow: 2px 2px 3px 3px rgba(0,0,0,0.15); 
	box-shadow: 2px 2px 3px 3px rgba(0,0,0,0.15);
}

.nmbr {border-radius: 9px; background: $hilitecolor; padding: 5px 15px; margin: 0 5px 4px 0; font: normal 15px/1.2em 'Josefin Sans', sans-serif; color: #fff;}

.update {margin: 200px 10px 10px 10px; padding: 0; color: #0c0; text-align: right; font: normal 12px/1.0em courier, 'courier new';}

h1 {margin: 3px 0 0 20px; pading: 0; font: normal 30px/1.2em 'Josefin Sans', sans-serif; letter-spacing: -1px; text-align: left;}

h3 {font: bold 13px/1.2em arial; color: $hilitecolor; margin: 3px 0 3px 0; padding: 0; clear: all;}

img {border: 0; margin: 0;}
form {border: 0; margin: 0;}

textarea {width: 90%; margin: 5px 20px 0 0; padding: 15px; font: normal 1.0em/1.2em courier, 'courier new', monospace; color: #000;}

hr {
    margin: 10px 0 5px 0;
    border: 0;
    height: 1px;
    background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.33), rgba(0,0,0,0)); 
    background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.33), rgba(0,0,0,0)); 
    background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.33), rgba(0,0,0,0)); 
    background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.33), rgba(0,0,0,0)); 
}

--></style>
</head>

<body><div class=\"wrp01\">
<img src=\"http://chimaboo.com/coursework/cunygc30.png\" alt=\"CUNY Graduate Center :: Stanton\'s Stuff\" border=\"0\" />
<div class=\"wrp02\">

<h1>LLM PROMPT PILOT v0.2</h1>

<div id=\"intro\" name=\"intro\" class=\"intro\">
<a href=\"#\" onclick=\"history.back()\"><strong>&laquo; Back to edit previous screen</strong></a>
</div>

$promptoutputs


$copybutton
</div>

</div><div class=\"update\">$updatecheck</div>
$copyscript
</body>
</html>";


?>