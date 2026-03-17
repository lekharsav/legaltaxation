<?php
include("db.php");

$lid = $_GET['cid'];
$qid = $_GET['qid'];
$sql = $con->query("select * from client where id='$lid'");
$row = $sql->fetch_assoc();
$exe = $row['uploaded_by'];

$sql2 = $con->query("select * from quote where quote_id='$qid'");
$row2 = $sql2->fetch_assoc();

$exe_name = "Demo";
$exe_cont = "Demo";
$exe_email = "Demo";
?>
<!doctype html>
<html>
    <head>
        <title>Quotation</title>
        <meta charset="utf-8">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.1.223/styles/kendo.common.min.css" />
        <script src="https://kendo.cdn.telerik.com/2017.1.223/js/jquery.min.js"></script>
        <script src="https://kendo.cdn.telerik.com/2017.1.223/js/jszip.min.js"></script>
        <script src="https://kendo.cdn.telerik.com/2017.1.223/js/kendo.all.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .pdf-page{
                border: 01px solid white;
                max-width: 960px;
                margin:auto;
                /*box-shadow: 0px 0px 2px 0px grey;*/
            }   
            .form_box{
                box-shadow: 0px 0px 2px 0px grey;
                padding: 20px;
                max-width: 800px;
                margin: 20px auto;
                border-radius: 2px;
                border: 5px solid #66401f;
            }
            label{
                /*text-transform: uppercase;*/
            }
            .header{
                border-bottom: 1px solid grey;
            }
            .header a{
                text-decoration: none;
            }
            .header label{
                color: #000;
                font-size: 30px;
            }.header span{
                position: relative;
                top: -20px;
                left: 25px;
                font-weight: bold;
                font-size: 9pt;
            }
            .comp_details p{
                font-size: 10pt;
                margin-bottom: 01px !important;
            }
            .item-table{
                font-size: 10pt;
            }
            .item-table .total-text{
                font-weight: bold;
            }
            .middle_align{
                vertical-align: middle;
                text-align: center;
            }
            .gst_box td{
                padding: 0px 10px;
            }
            /*td{*/
            /*    white-space: nowrap;*/
            /*}*/
        </style>
    </head>
    <body>
        
        <div class="pdf-page size-a4">
        <div class="form_box">
            <div class="row ">

                <div class="col-lg-12">
                   <div class="justify-content-end header text-center">
                       <a href="https://aimdigitalise.in/">
                            <img src="../../images/logo.png" class="mb-4 <?php if($row2['gst'] == 2){echo"d-none";}?>" style="width: 50px; position: relative; top: 20px;" alt="" data-no-retina="">&nbsp;
                            <label class=""><?php if($row2['gst_type'] == 2){echo"AIM D";}else{echo"AIM Digitalise";}?></label>
                            <span class="text-dark d-block">Quotation / Proforma Invoice</span>
                        </a>
                   </div> 
                </div>
                <div class="col-lg-7 col-7 comp_details mb-3 mt-3">
                    <?php if($row2['gst_type'] == 0){
                        ?>
                    <p><b>M/S: AIM XXXXXXXXXX</b></p>

                    <p>#XXX XXXXX XX, XXXXXXX XXX, XXXXXX, XXXXX,XX XXXXXX XXXXXXXX, XXXXXX - XXXXXXX</p>
                    <p><b>GSTIN/UIN:</b> XXXXXXXXXXXXX</p>
                    <p><b>State Name:</b> XXXX XXXXXX XXXX</p>
                    <p><b>Email:</b> XXXXXXXXXXXXXXXXXXXXX</p>
                    <p><b>Website:</b> <a href="#" target="blank">XXXXXXXXXXXXXXXX</a></p>
                    <p><b>Phone No:</b> <a href="#">XXX XXXX XXXX</a></p>
                        <?php
                    }else{
                        ?>
                    <p><b>M/S: AIM Digitalise</b></p>
                    <p>#528, Gate No 2, 5th Floor, Poddarcourt, 18 Rabindra Sarani, Kolkata-700001</p>
                    <p><b>GSTIN/UIN:</b> 19BDVPH5079K1ZN</p>
                    <p><b>State Name:</b> West Bengal , Code:19</p>
                    <p><b>Email:</b> sales@aimdigitalise.in</p>
                    <p><b>Website:</b> <a href="https://aimdigitalise.in/" target="blank">www.aimdigitalise.in</a></p>
                    <p><b>Phone No:</b> <a href="tel:+033 6618 2659">033 6618 2659</a></p>
                        <?php
                    }?>
                    
                    <hr>
                    <p>Buyer Details</p>
                    <hr>
                    <p><b>M/S: <?php echo $row['company'] ?></b></p>
                    <p><?php echo $row['address']; ?></p>
                    <p><b>GSTIN/UIN: </b><?php echo $row['gst'] ?></p>
                    <!-- <p><b>State Name:</b> West Bengal , Code:19</p> -->
                    <p><b><b>Contact Person:</b></b> <?php echo $row['person']; ?></p>
                    <p><b>Contact Number:</b> <?php echo $row['contact'] ?></p>
                    <p><b>Email ID:</b> <?php echo $row['email'] ?></p>
                </div>
                <div class="col-lg-5 col-5 comp_details mb-3 mt-3 text-center">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <p><b>Quotation No:</b></p>
                                    <p><?php echo $row2['quote_id'] ?></p>
                                </td>
                                <td>
                                    <p><b>Quotation Date:</b></p>
                                    <p><?php echo date('d-M-Y',strtotime($row2['created'])) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Sales Executive</b></p>
                                    <p><?php echo $exe_name ?></p>
                                </td>
                                <td>
                                    <p><b>Exec. Contact</b></p>
                                    <p><?php echo $exe_cont ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Supplier's Ref.</b></p>
                                    <p><?php echo $row2['sup_ref']; ?></p>
                                </td>
                                <td>
                                    <p><b>Other Reference(s)</b></p>
                                    <p><?php echo $row2['oth_ref'] ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Buyer's Order No(PO)</b></p>
                                    <p><?php echo $row2['order_no'] ?></p>
                                </td>
                                <td>
                                    <p><b>Order Date(PO)</b></p>
                                    <p><?php echo $row2['order_date'] ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Despatch Document No.</b></p>
                                    <p><?php echo $row2['disp_no']; ?></p>
                                </td>
                                <td>
                                    <p><b>Despatched Through</b></p>
                                    <p><?php echo $row2['disp_th']; ?></p>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td colspan="2">
                                    <p><b>Bill of Lading/LR-RR No:</b> <?php echo $row2['bill'] ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><b>Terms Of Delivery: </b><?php echo $row2['terms'] ?></p>
                                </td>
                            </tr>
                            -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!--<hr>-->
            <div class="row mb-3">
                <div class="col-sm-3 mb-3">
                    <div class="form-group">
                        <h5>Product Details</h5>
                    </div>
                </div>
                <div class="col-sm-9 mb-3">
                    <div class="form-group">
                        <hr>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <table class="table table-bordered item-table">
                        <thead>
                            <tr>
                                <th>SL</th><th>Item Name</th><th>HNS/SAC</th><th>Qty</th><th>Per</th><th>Unit Price</th><th>Disc %</th><th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $total = 0;
                            $sql6 = $con->query("select * from quote_item where qid='$qid'");
                            while($row6 = $sql6->fetch_assoc()){
                                $price = $row6['price'] - ($row6['price'] * ($row6['dis'] / 100));
                                $total += $price*$row6['qty'];
                                ?>
                            <tr>
                                <td><?php echo $sl ?></td>
                                <td>
                                    <b><?php echo $row6['name']; ?></b><br>
                                    <span style="font-size: 7pt;"><?php echo $row6['des']; ?></span>
                                </td>
                                <td><?php echo $row6['hsn']; ?></td>
                                <td><?php echo $row6['qty']; ?></td>
                                <td><?php echo $row6['per']; ?></td>
                                <td>₹<?php echo $row6['price']; ?></td>
                                <td><?php echo $row6['dis'] ?></td>
                                <td>₹<?php echo round($price*$row6['qty'])?>.00</td>
                            </tr>
                                <?php
                                $sl++;
                            }
                            $total = round($total);
                            ?>
                            <tr>
                                <td colspan="7" style="text-align: right;" class="total-text">Total</td>

                                <td class="total-text">₹<?=$total?>.00</td>
                            </tr>
                            <tr class="gst_box">
                                <td colspan="2" rowspan="3" class="text-center" style="vertical-align: middle; text-align: center;">
                                    Tax Rate
                                </td>
                                <?php
                                if($row2['gst_type']==1){
                                    ?>
                                <td>SGST</td>
                                <td colspan="4">9%</td>
                                <td>₹<?php echo round((9 / 100) * $total);?>.00</td>
                                    <?php
                                }else{
                                    ?>
                                <td>SGST</td>
                                <td colspan="4">0%</td>
                                <td>₹<?php echo (9 / 100) * 0;?>.00</td>
                                    <?php
                                }
                                ?>
                                
                            </tr>
                            <tr class="gst_box">
                                <?php
                                if($row2['gst_type']==1){
                                    ?>
                                <td>CGST</td>
                                <td colspan="4">9%</td>
                                <td>₹<?php echo round((9 / 100) * $total);?>.00</td>
                                    <?php
                                }else{
                                    ?>
                                <td>CGST</td>
                                <td colspan="4">0%</td>
                                <td>₹<?php echo (9 / 100) * 0;?>.00</td>
                                    <?php
                                }
                                ?>

                            </tr>
                            <tr class="gst_box">
                                <?php
                                if($row2['gst_type']==2){
                                    ?>
                                <td>IGST</td>
                                <td colspan="4">18%</td>
                                <td>₹<?php echo round((18 / 100) * $total);?>.00</td>
                                    <?php
                                }else{
                                    ?>
                                <td>IGST</td>
                                <td colspan="4">0%</td>
                                <td>₹0.00</td>
                                    <?php
                                }
                                ?>
                                
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align: right;" class="total-text">Grand Total</td>

                                <td class="total-text">₹<?php  if($row2['gst_type'] == 0){echo $grand_total = $total;}else{echo $grand_total = $total+(round((18 / 100) * $total));} ?>.00</td>
                            </tr>
                            <tr>
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="fw-bold">
                                    In Word: <span class="text-danger">
                                    <?php
                                    $number = round($grand_total);
                                   $no = floor($number);
                                   $point = round($number - $no, 2) * 100;
                                   $hundred = null;
                                   $digits_1 = strlen($no);
                                   $i = 0;
                                   $str = array();
                                   $words = array('0' => '', '1' => 'one', '2' => 'two',
                                    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                                    '7' => 'seven', '8' => 'eight', '9' => 'nine',
                                    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                                    '13' => 'thirteen', '14' => 'fourteen',
                                    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                                    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
                                    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                                    '60' => 'sixty', '70' => 'seventy',
                                    '80' => 'eighty', '90' => 'ninety');
                                   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
                                   while ($i < $digits_1) {
                                     $divider = ($i == 2) ? 10 : 100;
                                     $number = floor($no % $divider);
                                     $no = floor($no / $divider);
                                     $i += ($divider == 10) ? 1 : 2;
                                     if ($number) {
                                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                        $str [] = ($number < 21) ? $words[$number] .
                                            " " . $digits[$counter] . $plural . " " . $hundred
                                            :
                                            $words[floor($number / 10) * 10]
                                            . " " . $words[$number % 10] . " "
                                            . $digits[$counter] . $plural . " " . $hundred;
                                     } else $str[] = null;
                                  }
                                  $str = array_reverse($str);
                                  $result = implode('', $str);
                                  $points = ($point) ?
                                    "." . $words[$point / 10] . " " . 
                                          $words[$point = $point % 10] : '';
                                  echo strtoupper($result . "Rupees  " . $points . "only");
                                    ?></span> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <?php echo $row2['d_des'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">Payment Term:  <?php echo $row2['p_terms']?></td>
                            </tr>
                            <tr class="middle_align <?php if($row2['gst'] == 2){echo"d-none";}?>">
                                <td colspan="2">
                                    <p>Bank Details: "AIM Digitalise"</p>
                                    <p>Bank Name: State Bank of India</p>
                                    <p>Branch: Satitara ADB</p>
                                    <p>A/C No: 41541042687</p>
                                    <p>IFSC Code: SBIN0004781</p>
                                </td>
                                <td colspan="3">
                                    <p>PhonePe- +91 9110642507</p>
                                    <p>Google Pay- +91 9110642507</p>
                                    <p>UPI ID- 9110642507@ybl</p>
                                </td>
                                <td colspan="3">
                                    <p>AIM Digitalise</p>
                                    <img src="./assets/img/signature.png">
                                    <p>Sabyasachi Pal</p>
                                    <p>Authorised Signatory</p>
                                </td>
                            </tr>
                            <tr class="<?php if($row2["anex"] == 0){echo"d-none";}?>">
                                <td colspan="8">
                                    <p>* Rate may change in future, It's a limited offer</p>
                                    <p>* The validity of Domain and Hosting subscription is 1 yr, Yearly Renewal Required .</p>
                                    <p>* 1 Years Website Mainance and Support service is Entitled .</p>
                                    <p>* Quotation with GST included</p>
                                    <p>Thanks a lot for showing your interest in our concern services. We are happy to help you to bring your business to the next level. </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div >
                        <?php 
                        if($row2['if_anex'] == 1){
                            ?>
                            <div style="border: 1px solid black; margin-bottom: 15px;"></div>
                            <?php
                            echo$row2['anex'];
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
        </div>
        </div>
        <center>
            <a href="dashboard.php?src=client_details.php&id=<?php echo $lid ?>" class="export-pdf k-button btn btn-success mb-3 <?php if(isset($_GET['user'])){echo"d-none";}?>">Go Back</a>
            &nbsp;
            <a href="https://api.whatsapp.com/send?phone=<?php echo $row['contact'] ?>&text=Please click the link for view your quotation: https://aimdigitalise.com/Draft/LMS/inv.php?qid=<?=$qid?>" class="btn btn-outline-success mb-3">Whatsapp <i class="fa fa-whatsapp"></i></a>
            <button class="export-pdf k-button btn btn-outline-primary mb-3" onclick="getPDF('.pdf-page')">Download as PDF <i class="fa fa-download"></i></button>
            &nbsp;
            <a href="mail/index.php?email=<?=$row['email']?>&qid=<?=$qid?>&lid=<?=$lid?>&cc=<?=$exe_email?>" class="btn btn-warning btn-sm mb-3 <?php if(isset($_GET['user'])){echo"d-none";}?>">Send to Mail</a>
        </center>
        <style>
            /*
                Use the DejaVu Sans font for display and embedding in the PDF file.
                The standard PDF fonts have no support for Unicode characters.
            */
            .pdf-page {
                font-family: "DejaVu Sans", "Arial", sans-serif;
            }
        </style>

    <script>
        // Import DejaVu Sans font for embedding

        // NOTE: Only required if the Kendo UI stylesheets are loaded
        // from a different origin, e.g. cdn.kendostatic.com
        kendo.pdf.defineFont({
            "DejaVu Sans"             : "https://kendo.cdn.telerik.com/2016.2.607/styles/fonts/DejaVu/DejaVuSans.ttf",
            "DejaVu Sans|Bold"        : "https://kendo.cdn.telerik.com/2016.2.607/styles/fonts/DejaVu/DejaVuSans-Bold.ttf",
            "DejaVu Sans|Bold|Italic" : "https://kendo.cdn.telerik.com/2016.2.607/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf",
            "DejaVu Sans|Italic"      : "https://kendo.cdn.telerik.com/2016.2.607/styles/fonts/DejaVu/DejaVuSans-Oblique.ttf"
        });
    </script>

    <!-- Load Pako ZLIB library to enable PDF compression -->
    <script src="../content/shared/js/pako.min.js"></script>

    <script>
        /*
      function getPDF(selector) {
        kendo.drawing.drawDOM($(selector)).then(function(group){
          kendo.drawing.pdf.saveAs(group, "<?php echo $qid."-".$row['person'].".pdf" ?>");
        });
      }
    */

      function getPDF(selector) {
        kendo.drawing.drawDOM($(selector), {
            forcePageBreak: ".pdf-export-page-break",
            multiPage: true,
            paperSize: "A4",
            margin: {
              top: "1cm",
              left: "1cm",
              right: "1cm",
              bottom: "1cm"
            },
            vertical: true,
            scale: 0.5
          }).then(function(group){
          kendo.drawing.pdf.saveAs(group, "<?php echo $_GET['qid']."-".$row['person'].".pdf" ?>");
        });
      }
    
    /*
      $("#export-pdf").kendoButton({
        click: function(e) {
          kendo.drawing.drawDOM("#div-wrapper", {
            forcePageBreak: ".pdf-export-page-break",
            multiPage: true,
            paperSize: "A4",
            margin: {
              top: "2cm",
              left: "1cm",
              right: "1cm",
              bottom: "1cm"
            },
            landscape: true,
            scale: 0.8
          })
            .then(function(group) {
            kendo.drawing.pdf.saveAs(group, "test.pdf");
          });
        }
      });
      */
    </script>
    </body>
</html>