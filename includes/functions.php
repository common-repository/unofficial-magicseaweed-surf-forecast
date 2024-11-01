<?php 
//Use Wordpress Curl Function
function get_web_page( $url){
	$content=wp_remote_get($url);
   return json_decode($content['body']);
	}
//Forecast Graph Function
function forecast_graph($times,$arrows,$bars,$arrowColor,$barColor,$title,$units,$timelabels,$overlayBars,$options){
	
		//graph
	$graphHtml.='<div class="upperContainer">';
	$graphHtml.=titleGraph($title);
	
	if (is_array($barColor[0])){
		$scales = color_scales($barColor[0][0],$barColor[0][1],$barColor[0][2]);
		$graphHtml.=color_map_key($scales);	
	}
	$graphHtml.='</div>';
	$graphHtml.=beginGraph();		
	$graphHtml.=grid(max($bars),max($bars),$units);	
	$graphHtml.=grid(max($bars),min($bars),$units);	
	if($overlayBars!=null){
		$graphHtml.=grid(max($bars),min($overlayBars),$units);	
	}
	
	$graphHtml.='<div class="graphContainer">';

	for ($i=0; $i<(count($times));$i++){
		//bars
		$graphHtml.=addBars(count($times),$bars[$i],$i,max($bars));
		//labels
		if($options[1]=='yes'){
			if($bars[$i]!=$bars[$i-1]){
			$graphHtml.=label($i,$bars);
			}
		}
		//arrows
		if ($arrows!=null){
			if ($options[2]=='more'){
				$graphHtml.=arrows($arrows,$arrowColor,$i);}
			elseif(($options[2]=='less')&&(floor($i/2)==$i/2)){
				$graphHtml.=arrows($arrows,$arrowColor,$i);}
			}
		//bar colors - color scaled or not
		if (isset($scales)){
			$colorOne=color_scaler($scales,$barColor[0][2][$i]);}
		else{
			$colorOne=$barColor[0];}
		$colorTwo=$barColor[1];
		$graphHtml.=barColor($colorOne,$colorTwo);
		//overlay bars or not
		if($overlayBars!=null){
			$graphHtml.=overlayBars($overlayBars,max($bars),$i,$arrowColor,$barColor[1]);}
		//
	$graphHtml.='</div>';
	}
		//close graphContainer div
	$graphHtml.='</div>';
		//close surfGraph div
	$graphHtml.='</div>';
		//add time and day axis
	$graphHtml.=timeLabels($times,$options[0]);
		//return the graph!
	return $graphHtml;
	}

function timeLabels($times,$labelOption){
	if ($labelOption!=24){
		if ($labelOption==6){$o=2;}elseif( $labelOption==12){$o=4;}
		$timeLabelsHtml.='<div class="timesContainer">';
		for ($i=0; $i<(count($times));$i++){		
			$timeLabelsHtml.='<text class="times" style="width:'.floor(100/count($times)).'%;">';
			if(floor($i/$o)==($i/$o)){
				$timeLabelsHtml.=$times[$i][0];}
			$timeLabelsHtml.=' </text>';
		}
		$timeLabelsHtml.='</div>';
	}
	$timeLabelsHtml.='<div class="daysContainer">';
	//days if its a new day
	for ($i=0; $i<(count($times));$i++){		
		$timeLabelsHtml.='<text class="days" style="width:'.floor(100/count($times)).'%;">';
			if ($times[$i][1]!=$times[($i-1)][1]){
				$timeLabelsHtml.=$times[$i][1];
			}
		$timeLabelsHtml.=' </text>';
	}
	$timeLabelsHtml.='</div>';
	return $timeLabelsHtml;
	}
function addBars($number, $bar,$i,$scale){
	$barHtml.='<div class="bar" style="
		left:'.($i*floor(100/$number)).'%;
		width:'.floor(100/$number).'%;
		height:'.(($bar/$scale)*4).'em;">';
	return $barHtml;
	}

function barColor($colorOne,$colorTwo){
	$barColorHtml='<div class="barColor" style="'.gradient_bar($colorOne,$colorTwo).';"></div>';
	return($barColorHtml);
	}

function label($i,$labels){
	$labelHtml.='<text class="barLabel">'.$labels[$i].'</text>';
	return($labelHtml);
	}

function beginGraph(){
	$beginHtml.='<div class="surfGraph">';
	return($beginHtml);
	}

function grid($topline, $line, $units){
	$gridHtml.='<div class="gridLine" style="
		height:'.(($line/$topline)*4).'em;">
		</div>';
	$gridHtml.='<text class="gridLabel" style="
		margin-bottom:'.(($line/$topline)*8).'em;"> '.$line.' '.$units.'
		</text>';
	return($gridHtml);
	}

function titleGraph($title){
	$titleHtml.='<text class="graphTitle" style="">'.$title.'</text>';
return $titleHtml;
}

function overlayBars($overlayBars,$topline,$i,$bottomColor,$topColor){	
	$overlayBarsHtml.='<div class="overlayBar" style="
		height:'.(($overlayBars[$i]/$topline)*4).'em;">';
	$overlayBarsHtml.=barColor($bottomColor,$topColor);
	$overlayBarsHtml.='</div>';
	return($overlayBarsHtml);
}

function arrows($arrows,$arrowColor,$i){
	$arrowHtml='<img class="arrow" src="/wp-content/plugins/unofficial-magicseaweed-surf-forecast/includes/'.$arrowColor.'_arrow.png" 
					style="transform:rotate('.$arrows[$i].'deg);
					-ms-transform:rotate('.$arrows[$i].'deg);
					-webkit-transform:rotate('.$arrows[$i].'deg);"/>';			
	return($arrowHtml);
	}

function split_hex($color){
	return(array_reverse(str_split(substr($color,1,strlen($color)),2)));
	}

function dec_range($a,$b){
	$colorDivs=array();
	for ($i=0;$i<3;$i++){
		$colorDivs[$i]=dividers(hexdec($a[$i]),hexdec($b[$i]),4);
	}
	return($colorDivs);
	}

function dividers($min,$max,$num){
	$range=$max-$min;
	$dividers= array();
	for ($i=0;$i<$num+1;$i++){
		array_push($dividers,$i*($range/$num)+$min);
	}
	return($dividers);
	}

function comb_hex($a){
	foreach ($a as $key=> $b){
		if ($b>16){
			$a[$key]=dechex($b);}
		else{
			$a[$key]='0'.dechex($b);}
	}
	return('#'.implode($a));
} 

function code_colors($colorBins){
	$returnColors=array();
	for ($i=0;$i<5;$i++){
		$colors=array();
		for($j=0;$j<3;$j++){
			array_unshift($colors,$colorBins[$j][$i]);
		}
		$color=comb_hex($colors);
		$returnColors[$i]=$color;
	}
	return($returnColors);
}

function color_scaler($scales,$value){
	for($p=0;$p<5;$p++){
		if($value<=$scales['value'][$p+1]){
			return($scales['color'][$p]);
			$p=6;
		}
	}
}

function color_scales($colorA,$colorB,$values){
	$codesA=split_hex($colorA);
	$codesB=split_hex($colorB);
	$colorBins =dec_range($codesA,$codesB);
	$colorBins=code_colors($colorBins);
	$valueBins=dividers(min($values),max($values),5);
	$scales=array('color'=>$colorBins,'value'=>$valueBins);
	return $scales;
	}

function color_map_key($scales){
	$colorMapHtml.='<div class="colorKeyTitle">Period in Seconds</div>';
	$colorMapHtml.='<div class="colorKeyLabel">';
		$colorMapHtml.='<text>'.$scales['value'][0].'</text>';
		for ($i=1;$i<6;$i++){
			$colorMapHtml.='<text>'.$scales['value'][$i].'</text>';				
		}
	$colorMapHtml.='</div></br>';
	$colorMapHtml.='<div class="colorKey">';
		for ($i=0;$i<5;$i++){
			$colorMapHtml.='<div class="colorKeyBox" style="
				background-color:'.$scales['color'][$i].'">
			</div>';
		}
	$colorMapHtml.='</div>';
	return($colorMapHtml);
}

function gradient_bar($bottomColor,$topColor){
	$gradientBarHtml='background-image: -ms-linear-gradient(bottom, '.$bottomColor.' 50%, '.$topColor.' 100%);
		background-image: -moz-linear-gradient(bottom, '.$bottomColor.' 50%, '.$topColor.' 100%);
		background-image: -o-linear-gradient(bottom, '.$bottomColor.' 50%, '.$topColor.' 100%);
		background-image: -webkit-gradiet(linear, left bottom, left top, color-stop(.50, '.$bottomColor.'), color-stop(1, '.$topColor.'));
		background-image: -webkit-linear-gradient(bottom, '.$bottomColor.' 50%, '.$topColor.' 100%);
		background-image: linear-gradient(to top, '.$bottomColor.' 50%, '.$topColor.' 100%);';
	return($gradientBarHtml);
	}	
	
function issue($timeStamp){
	$issueHtml.='<div id="issue">';
	$logo='<a href="http://magicseaweed.com"><img src="http://im-1-uk.msw.ms/msw_powered_by.png"></a></br>';
	$issueHtml.=$logo;
	$issueHtml.='<text id="issueTime" class="text">Issued: '.date('m/d/Y', $timeStamp).'</br> '.date('h', $timeStamp).':'.date('ia', $timeStamp).'</text>';
	$issueHtml.='</div>';
	return($issueHtml);
}
	
function basic_surf_report($spotId, $params) {
	//query MagicSeaweed data
	$secret=base64__decode(get_option('api_secret'));
	$key=get_option('api_key');
	$url='http://magicseaweed.com/api/'.$key.'/forecast/';
	$secretString=	'secret='.$secret;
	$spotString=	'spot_id='.$spotId;
	$timestampString='timestamp='.time();
	
	$signatureUrl=	$url.'?'.$secretString.'&'.$spotString.'&'.$timestampString;
	$signature=		'signature='.hash_hmac('sha256',$signatureUrl,$secret);
	$requestUrl=	$url.'?'.$spotString.'&'.$signature;
	//echo $requestUrl;

	$surf_info=get_web_page($requestUrl);
	//Your Requests are powered with MagicSeaweed Data. 
	//Display of the MagicSeaweed Logo is REQUIRED in order to use their API.
	//Display Logo and data issue date.
	$returnHtml.=issue($surf_info[0]->{'issueTimestamp'});
	
	//declare arrays to be graphed
	$timestamps = array();
	$maxheights = array();
	$minheights=array();
	$bestsurfs = array();
	$times = array();
	$windspeeds = array();
	$winddirections=array();
	$combSwellDir = array();
	$primSwellDir = array();
	$secSwellDir = array();
	$tertSwellDir = array();
	$combSwellHeight = array();
	$primSwellHeight = array();
	$secSwellHeight = array();
	$tertSwellHeight = array();
	$combSwellPer = array();
	$primSwellPer = array();
	$secSwellPer = array();
	$tertSwellPer = array();


//pull data from report array into graphing arrays
	foreach ($surf_info as $entry){
		array_push($timestamps, $entry->{'timestamp'});
		array_push($times, array(date('ga',$entry->{'localTimestamp'}),date('D',$entry->{'localTimestamp'})));
		array_push($maxheights, $entry->{'swell'}->{'maxBreakingHeight'});
		array_push($minheights, $entry->{'swell'}->{'minBreakingHeight'});
		array_push($bestsurfs, ($entry->{'solidRating'}-$entry->{'fadedRating'}));
		array_push($winddirections, ($entry->{'wind'}->{'direction'}));
		array_push($windspeeds, ($entry->{'wind'}->{'speed'}));
		array_push($combSwellDir,($entry->{'swell'}->{'components'}->{'combined'}->{'direction'}));
		array_push($primSwellDir,($entry->{'swell'}->{'components'}->{'primary'}->{'direction'}));
		array_push($secSwellDir,($entry->{'swell'}->{'components'}->{'secondary'}->{'direction'}));
		array_push($tertSwellDir,($entry->{'swell'}->{'components'}->{'tertiary'}->{'direction'}));
		array_push($combSwellHeight,($entry->{'swell'}->{'components'}->{'combined'}->{'height'}));
		array_push($primSwellHeight,($entry->{'swell'}->{'components'}->{'primary'}->{'height'}));
		array_push($secSwellHeight,($entry->{'swell'}->{'components'}->{'secondary'}->{'height'}));
		array_push($tertSwellHeight,($entry->{'swell'}->{'components'}->{'tertiary'}->{'height'}));
		array_push($combSwellPer,($entry->{'swell'}->{'components'}->{'combined'}->{'period'}));
		array_push($primSwellPer,($entry->{'swell'}->{'components'}->{'primary'}->{'period'}));
		array_push($secSwellPer,($entry->{'swell'}->{'components'}->{'secondary'}->{'period'}));
		array_push($tertSwellPer,($entry->{'swell'}->{'components'}->{'tertiary'}->{'period'}));
	}
	
	//Begin the report HTML string to be returned to the widget
	
	//Issue and Forecast Date


	//Specify Colormap colors
	$periodColorMap=array('#2d059c','#14af55',$combSwellPer);
	$periodColorMap1=array('#20B2AA','#048f35',$primSwellPer);
	$periodColorMap2=array('#54ff9f','#048f35',$secSwellPer);
	$periodColorMap3=array('#b9dbb9','#698b69',$tertSwellPer);

	//Create Graphs
	$options=array($params['timeLabels'],$params['barLabels'],$params['arrows']);
	if((bool)$params['swell']){
		if(array_filter($combSwellDir)&&array_filter($combSwellHeight)){
		$returnHtml.= forecast_graph($times,$combSwellDir,$combSwellHeight,'blue',array($periodColorMap,'lightblue'),'Combined Swell','ft',1,null,$options);
		}
	}
	if ((bool)$params['break']){
	if(array_filter($maxheights)){
		$returnHtml.= forecast_graph($times,null,$maxheights,'purple',array('rgb(45,5,156)','rgb(105,170,255)'),'Break Heights','ft',1,$minheights,$options);
		}
	}
	if((bool)$params['wind']){
		if(array_filter($winddirections)&&array_filter($windspeeds)){
		$returnHtml.= forecast_graph($times,$winddirections,$windspeeds,'red',array('#969690','#FC0808'),'Wind','mph',1,null,$options);
		}
	}
	if((bool)$params['swells']){
		if(array_filter($primSwellDir)&&array_filter($primSwellHeight)){
		$returnHtml.= forecast_graph($times,$primSwellDir,$primSwellHeight,'lightblue',array($periodColorMap1,'lightblue'),'Primary Swell','ft',0,null,$options);
		}
		if(array_filter($secSwellDir)&&array_filter($secSwellHeight)){
		$returnHtml.= forecast_graph($times,$secSwellDir,$secSwellHeight,'grey',array($periodColorMap2,'gray'),'Secondary Swell','ft',0,null,$options);
		}
		if(array_filter($tertSwellDir)&&array_filter($tertSwellHeight)){
		$returnHtml.= forecast_graph($times,$tertSwellDir,$tertSwellHeight,'white',array($periodColorMap3,'lightgray'),'Tertiary Swell','ft',1,null,$options);
		}
	}
	//print_r($params);
	
	return array('text'=>$returnHtml);
	}

?>