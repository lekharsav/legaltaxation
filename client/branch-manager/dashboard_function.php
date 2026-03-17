<?php

// *** Lead Source Count ***//
$ref_leads = 0;
$fb_leads = 0;
$google_leads = 0;
$linkedin_leads = 0;
$google_ads_leads = 0;
$office_db_leads = 0;
$cold_call_leads = 0;
$total_leads_gen = 0;

$qry = $con->query("select * from client");
while($rws = $qry->fetch_assoc()){
	if(date('m-Y',strtotime($current_date)) == date('m-Y',strtotime($rws['created']))){
		if($rws['source'] == "Reference"){
			$ref_leads++;
		}if($rws['source'] == "Facebook"){
			$fb_leads++;
		}if($rws['source'] == "Google"){
			$google_leads++;
		}if($rws['source'] == "Linkedin"){
			$linkedin_leads++;
		}if($rws['source'] == "Google Ads"){
			$google_ads_leads++;
		}if($rws['source'] == "Office Database"){
			$office_db_leads++;
		}if($rws['source'] == "Cold Calling"){
			$cold_call_leads++;
		}
		$total_leads_gen++;
	}
}
// *** End ***//

// *** Outstanding Calculation ***//
$outs_amt = 0;
$coll_amt = 0;
$deal_amt = 0;
$qry = $con->query("select * from client_order");
while($rws = $qry->fetch_assoc()){
	$deal_amt += $rws['total'];
}
$qry = $con->query("select * from client_txn");
while($rws = $qry->fetch_assoc()){
	$coll_amt += $rws['amt'];
}
$outs_amt = $deal_amt-$coll_amt;

// *** End ***//
?>