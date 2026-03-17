<?php 
if(isset($_POST['partner_submit'])){
$to = "info.aimdigi@gmail.com"; // this is your Email address
$from = $_POST['email']; // this is the sender's Email address
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$company_name = $_POST['company_name'];
$subject = "Request for Payroll Software Demo";
$message = "Name- " . $name . "\n" . "Email- " . $email . "\n" . "Phone- " . $phone . "\n" . "Company Name- " . $company_name . "\n" . " ";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo "<p class='mail_sent'>Mail Sent. Thank you " . $name . ", we will contact you shortly.</p>";
// You can also use header('Location: thank_you.php'); to redirect to another page.
}
?>
            </div>
            <div style="width: 143px; margin-left: 16px;" class="hide-in-mobile">
              <img src="images_partner/gartner-banner-img.png" alt="gartner-banner-image" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div
        style="background-image:url(images_partner/pastelBlue7.svg);background-repeat:no-repeat;background-position:0% center;background-color:#FFF">
        <div
          style="background-image:url(images_partner/pastelPink7.svg);background-repeat:no-repeat;background-position:100% top">
          <div class="flex-container">
            <div class="modules-section">
              <div class="module-box image-left-module-box">
                <div class="module-image-wrapper"><img src="images_partner/Payroll-Management.svg" alt="Payroll Management"
                    class="module-image">
                </div>
                <div class="module-text">
                  <div class="module-heading">Payroll Management</div>
					<div class="orange-text module-heading-sub">Faster processing, <br>
				    accurate payouts</div>
					<div class="styled-description">
              <p>Bid farewell to cumbersome Excel sheets and payroll computation errors. Welcome 100% statutory compliance, on-time disbursement of salaries and stress-free month ends.</p>
            </div>
                  <ul>
                    
                    <li>Process payroll in minutes&nbsp;</li>
                    <li>Checklists, reconciliation, and validation tools ensure payroll accuracy&nbsp;</li>
                    <li>Highly reliable software with 99.9% uptime&nbsp;</li>
                    <li>Highly customizable payroll engine&nbsp;</li>
                    <li>Unlimited salary components&nbsp;</li>
                    <li>Integrated payroll inputs from HR, Leave &amp; Attendance modules.&nbsp;</li>
                    <li>Direct-debit facility, direct salary transfer from within the application&nbsp;</li>
                    <li>Hundreds of ready-made MIS and compliance reports</li>
                  </ul>
                </div>
              </div>
              <div class="module-box image-right-module-box">
                <div class="module-image-wrapper" style="text-align: right;"><img src="images_partner/statutory-compliance-new.svg" alt="Statutory Compliance"
                    class="module-image"></div>
                <div class="module-text">
                  <div class="module-heading">100% statutory<br>compliance,</div>
					<div class="orange-text module-heading-sub">Zero stress</div>
					<div class="styled-description">
              <p>When your payroll is running on greytHR, 100% statutory compliance is guaranteed. greytHR is updated with the latest compliance updates - tax slabs, deductions, labour law etc.</p>
            </div>
                  <ul>
                      <li>PF calculations with ECR generation&nbsp;</li>
                    <li>ESI computations &amp; challans&nbsp;</li>
                    <li>PT with all state-specific rules built-in&nbsp;</li>
                    <li>Comprehensive TDS (IT) calculations &amp; eTDS returns&nbsp;</li>
                    <li>One-click Form 24Q generation with automatic FVU validation&nbsp;</li>
                    <li>Digitally signed Form 16 &amp; 12BA generation&nbsp;</li>
                    <li>Bonus calculations &amp; reporting&nbsp;</li>
                      <li>Labour Welfare Fund calculation &amp; deductions</li>
                  </ul>
                </div>
              </div>
              <div class="module-box image-left-module-box">
                <div class="module-image-wrapper"><img src="images_partner/HRMS-new.svg" alt="Core HR" class="module-image">
                </div>
                <div class="module-text">
                  <div class="module-heading">HRMS</div>
					<div class="orange-text module-heading-sub">It’s HR Made Simple</div>
					<div class="styled-description">
              <p>Bring simplicity, speed and efficiency to all repetitive HRMS functions. Deliver a world-class employee experience. Get operational HR out of the way, and make time for high-value work.</p>
            </div>
                  <ul>
                      <li>Centralised Employee Database eliminates inconsistencies, duplication &amp; clutter&nbsp;</li>
                    <li>Employee Lifecycle Management - letters, documents, background verification, ID proof, salary revisions, income tax etc., all in one place&nbsp;</li>
                    <li>Instant access to up-to-date information, reports &amp; dashboards&nbsp;</li>
                    <li>Organization Chart for better visibility of the organisation’s hierarchy</li>
                    <li>Workflow, Task Management &amp; Checklist improves process quality &amp; timeliness&nbsp;</li>
                    <li>Social feeds, mass email/SMS, automated greetings, configurable events &amp; reminders to enhance employee engagement&nbsp;</li>
                      <li>Bella, the friendly chatbot &amp; Employee Helpdesk for timely resolution of queries &amp; grievances</li>
                  </ul>
                </div>
              </div>
              <div class="module-box image-right-module-box">
                <div class="module-image-wrapper" style="text-align: right;"><img src="images_partner/Leave-new.svg" alt="Leave Management"
                    class="module-image"></div>
                <div class="module-text">
                  <div class="module-heading">Leave management</div>
					<div class="orange-text module-heading-sub">Leave it to greytHR</div>
					<div class="styled-description">
              <p>Leave types. Leave tracking. Leave applications. Leave policy. And more. If it’s to do with leave management, consider it done with greytHR.</p>
            </div>
                  <ul>
                      <li>Multiple leave schemes &amp; policies for different employee groups &amp; businesses&nbsp;</li>
                    <li>Streamlined monthly payroll inputs&nbsp;</li>
                    <li>One-click leave year-end processing&nbsp;</li>
                    <li>Ability to support leave encashment&nbsp;</li>
                    <li>Employee Self Service for automatic leave management&nbsp;</li>
                    <li>Dashboards, reports &amp; analytics enables employee wellness monitoring &amp; curbs revenue leakage&nbsp;</li>
                    <li>Detailed records &amp; reports as per government rules&nbsp;</li>
                      <li>Customised comprehensive holiday list</li>
                  </ul>
                </div>
              </div>
              <div class="module-box image-left-module-box">
                <div class="module-image-wrapper"><img src="images_partner/attendance-new.svg" alt="Attendance Management"
                    class="module-image"></div>
                <div class="module-text">
                  <div class="module-heading">Attendance management</div>
					<div class="orange-text module-heading-sub">Real time is the real deal</div>
					<div class="styled-description">
              <p>Real time automation of attendance enables smooth operations and improves productivity.</p>
            </div>
                  <ul>
                      <li>Gather real-time attendance from multiple devices &amp; locations&nbsp;</li>
                    <li>Customisable attendance policy for varying business needs&nbsp;</li>
                    <li>Shift &amp; overtime management&nbsp;</li>
                    <li>Enables self-attendance marking, tracking and regularisation for employees&nbsp;</li>
                      <li>Easy statutory compliance - muster roll management, overtime register accuracy &amp; attendance register maintenance</li>
                  </ul>
                </div>
              </div>
              <div class="module-box image-right-module-box">
                <div class="module-image-wrapper" style="text-align: right;"><img src="images_partner/ESS-new.svg" alt="Employee Self Service"
                    class="module-image">
                </div>
                <div class="module-text">
                  <div class="module-heading">Employee Self Service</div>
					<div class="orange-text module-heading-sub">Give employees <br>
				    the power to help themselves</div>
					<div class="styled-description">
              <p>Empower employees with anytime, anywhere access to personal organisational information. Enable greater transparency, improved communication, and engagement.</p>
            </div>
                  <ul>
                      <li>Direct employee access to attendance, payslips, IT forms, holiday calendar, leave application, attendance information &amp; more&nbsp;</li>
                    <li>Direct employee access to HR letters, company policies, &amp; documents, expense claims submission &amp; the helpdesk&nbsp;</li>
                    <li>Built-in workflows to streamline transactions between employees, managers &amp; administrators&nbsp;</li>
                    <li>Zero effort collection of onboarding documents &amp; investment declaration&nbsp;</li>
                    <li>Easy updation of employee information without HR intervention&nbsp;</li>
                    <li>Streamlined workflows between employees &amp; managers&nbsp;</li>
                    <li>Standardised processes &amp; uniform policy enforcement&nbsp;</li>
                      <li>Mobile App for any time, anywhere access</li>
                  </ul>
                </div>
              </div>
				<div class="module-box image-left-module-box">
                <div class="module-image-wrapper"><img style="width: 400px" src="images_partner/greythr-new.svg" alt="Attendance Management"
                    class="module-image"></div>
                <div class="module-text">
                  <div class="module-heading">Easy integrations,</div>
					<div class="orange-text module-heading-sub">unlimited convenience</div>
					<div class="styled-description">
              <p>The various modules of greytHR are seamlessly integrated to simplify all core HR tasks. Data flow in real time between the modules (leave, attendance, payroll, expense claims etc.) ensures the availability of up to date information to employees and management alike. greytHR can also be easily integrated with a host of third party software - accounting, ERP solutions, verification services and more.</p>
            </div>
					 <ul>
                      <li>SAP Success factors</li>
                    <li>ICICI Bank</li>
                    <li>Matrix Biometrics Systems</li>
                    <li>Zoho Recruit</li>
                    <li>Skills2Talent</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div style="background-image:url(images_partner/phobos.svg);background-repeat:no-repeat;background-color:#FFF">
        <div class="container">
          <div style="display: flex; flex-direction: column;align-items: center;">
            <h2 class="styled-heading" style="text-align: center; margin-bottom: 97px;">You’ve heard us.<span
                class="orange-text">Now hear it
                from our customers.</span>
            </h2>
            <div class="slider">
              <div class="slide-track">
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Prafulata-Borde.png" alt="Prafulata Borde"
                          style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Prafulata Borde</div>
                        <div class="testimonial-emp-designation">HR Manager</div>
                        <div class="css-vurnku">Kamalraj Properties</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Thank you greytHR for the service and
                          support
                          in employee management functions.</p>
                        <p style="margin: 0;">Employee onboarding and employee information management are the best
                          features of greytHR. Their self service features aid effectiveness in HR operations.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Manichandra-Kanda.png" alt="Manichandra Kanda"
                          style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Manichandra Kanda</div>
                        <div class="testimonial-emp-designation">HR Executive</div>
                        <div class="css-vurnku">D Cube Analytics Pvt Ltd</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Amazing HRMS application with all
                          elements.
                        </p>
                        <p style="margin: 0;">It is a very good HRMS tool which covers all HRMS elements, tasks
                          and
                          processes.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Amar-Joshi.png" alt="Amar Joshi" style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Amar Joshi</div>
                        <div class="testimonial-emp-designation">Sr Executive People Empowerment</div>
                        <div class="css-vurnku">Intellore Systems Private Limited</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Really greyt HRMS Software for leave
                          management.</p>
                        <p style="margin: 0;">It is a very user-friendly software. Videos and guides at each step
                          makes processes easier. Automation of crucial tasks like leave computation and
                          management
                          comes handy with greytHR.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Pushpa-Latha.png" alt="Pushpa Latha" style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Pushpa Latha</div>
                        <div class="testimonial-emp-designation">Operations Manager</div>
                        <div class="css-vurnku">Pqube Business Solutions Private Limited</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Superb payroll processing, management and
                          compliance solutions.</p>
                        <p style="margin: 0;">greytHR is such a user friendly &amp; intuitive application that
                          does
                          not require training to use. The payroll solutions and ESS feature are very useful.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Prafulata-Borde.png" alt="Prafulata Borde"
                          style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Prafulata Borde</div>
                        <div class="testimonial-emp-designation">HR Manager</div>
                        <div class="css-vurnku">Kamalraj Properties</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Thank you greytHR for the service and
                          support
                          in employee management functions.</p>
                        <p style="margin: 0;">Employee onboarding and employee information management are the best
                          features of greytHR. Their self service features aid effectiveness in HR operations.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Manichandra-Kanda.png" alt="Manichandra Kanda"
                          style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Manichandra Kanda</div>
                        <div class="testimonial-emp-designation">HR Executive</div>
                        <div class="css-vurnku">D Cube Analytics Pvt Ltd</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Amazing HRMS application with all
                          elements.
                        </p>
                        <p style="margin: 0;">It is a very good HRMS tool which covers all HRMS elements, tasks
                          and
                          processes.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Amar-Joshi.png" alt="Amar Joshi" style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Amar Joshi</div>
                        <div class="testimonial-emp-designation">Sr Executive People Empowerment</div>
                        <div class="css-vurnku">Intellore Systems Private Limited</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Really greyt HRMS Software for leave
                          management.</p>
                        <p style="margin: 0;">It is a very user-friendly software. Videos and guides at each step
                          makes processes easier. Automation of crucial tasks like leave computation and
                          management
                          comes handy with greytHR.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="slide testimonial">
                  <div class="testimonial-content">
                    <div>
                      <div>
                        <img src="images_partner/Pushpa-Latha.png" alt="Pushpa Latha" style="width: 79px; height: 79px;" />
                      </div>
                      <div style="margin-top: 16px;">
                        <div class="testimonial-emp-name">Pushpa Latha</div>
                        <div class="testimonial-emp-designation">Operations Manager</div>
                        <div class="css-vurnku">Pqube Business Solutions Private Limited</div>
                      </div>
                    </div>
                    <div style="margin-top: 8px;">
                      <div>
                        <p style="font-weight: 600; margin: 0 0 7px 0;">Superb payroll processing, management and
                          compliance solutions.</p>
                        <p style="margin: 0;">greytHR is such a user friendly &amp; intuitive application that
                          does
                          not require training to use. The payroll solutions and ESS feature are very useful.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
		
	
		
		
    </div>
    <div>
      <div
        style="background-color:#FFF">
        <div class="container">
          <div class="flex-container">
            <h2 class="styled-heading" style="text-align: center;">Pick your <span class="only-orange-text">plan</span>
            </h2>
            <div class="feature-section-description">No matter what the size of your company or the extent of your HR needs,<br>greytHR has a plan that's just right for you.</div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="pricing-bg hidden-mobile">
			  <table class="table table-hover table-bordered table-condensed cashflow_report">
    <thead>
        <tr>
            <th width="350px" class="name row0"></th>
            <th width="165px"  class="row1">
				<img alt="greythr" src="images_partner/bike-icon.png">
				<p>Essential</p>
				<button class="pricing-cta">START TRIAL</button>
			</th>
            <th width="165px"  class="row2">
				<img alt="car" src="images_partner/car-icon.png">
				<p>Growth</p>
				<button class="pricing-cta">START TRIAL</button>
			</th>
			 <th width="165px"  class="row3">
				<img alt="air" src="images_partner/airplane-icon.png">
				<p>Enterprise</p>
				<button class="pricing-cta">START TRIAL</button>
			</th>
			
   
        </tr>
		<tr>
            <th width="450px" class="name row01"><p>Minimum Monthly Cost</p></th>
            <th width="200px" class="row1 pricing-cost"><p><sup>₹</sup>3495<span style="font-size: 16px">/month</span></p></th>
            <th width="200px" class="row2 pricing-cost"><p><sup>₹</sup>5495<span style="font-size: 16px">/month</span></p></th>
            <th width="200px" class="row3 pricing-cost"><p><sup>₹</sup>7495<span style="font-size: 16px">/month</span></p></th>
   
        </tr>
		<tr>
            <th width="450px" class="name row0-sub">Number of Employees</th>
            <th width="300px" class="row0-sub1">Unlimited</th>
            <th width="300px" class="row0-sub1">Unlimited</th>
			 <th width="300px" class="row0-sub1">Unlimited</th>
   
        </tr>
		<tr>
            <th width="450px" class="name row0-sub">Per Employee Cost</th>
            <th width="200px" class="row0-sub1">₹30/ month</th>
            <th width="200px" class="row0-sub1">₹60/ month</th>
			 <th width="200px" class="row0-sub1">₹100/ month</th>
   
        </tr>
    </thead>
    
   
    
 <tbody>
    <tr class="level2" parent="inflow" rowname="in-ct-1" state="collapsed">
     <td class="name">  <span onclick="toggle_rows(this, 'in-ct-1');"><span class="accordion-icon">+</span> Core HR </span></td>
    <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
    <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
	  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
	 </tr>
 </tbody>
    
    
 <tbody >
     <tr class="level3"  parent="in-ct-1" rowname="in-tt-1" state="collapsed" style="display: none;">
     <td class="name"><span onclick="toggle_rows(this, 'in-tt-1');"><span class="accordion-icon">+</span> Employee Information Management </span></td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		
            </tr>
        
    <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> An extensive employee database as a system of record</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	  <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> 20+ employee data categories</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	  <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> Reporting hierarchy with Org Chart</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	  <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> Employee directory</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	  <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> HR and CEO dashboards</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	  <tr class="level4" parent="in-tt-1" state="leaf" style="display: none;">
		<td class="name"> Employee assets tracking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 
	  <tr class="level3" parent="in-ct-1" rowname="in-tt-2" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-2');">&nbsp;<span class="accordion-icon">+</span> Know Your Employee (KYE)</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       <tr class="level4" parent="in-tt-2" state="leaf" style="display: none;">
		<td class="name">Store various employee identity information</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-2" state="leaf" style="display: none;">
		<td class="name">Easy online facility to collect identity data (Data Drives)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-2" state="leaf" style="display: none;">
		<td class="name">Track verified status (Aadhar verified, PAN verified, etc.)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
       
	 
	 
	 <tr class="level3" parent="in-ct-1" rowname="in-tt-3" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-3');">&nbsp;<span class="accordion-icon">+</span> Employee Documents Management</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       <tr class="level4" parent="in-tt-3" state="leaf" style="display: none;">
		<td class="name">Anti-virus scanning for all uploaded documents</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-3" state="leaf" style="display: none;">
		<td class="name">Automatic filing of generated letters to the document store	check</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-3" state="leaf" style="display: none;">
		<td class="name">Online access to all issued letters and documents</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-3" state="leaf" style="display: none;">
		<td class="name">Store digital or scanned copies of employee documents</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-3" state="leaf" style="display: none;">
		<td class="name">Bulk document upload</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 
	 
	 <tr class="level3" parent="in-ct-1" rowname="in-tt-4-1" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-1');">&nbsp;<span class="accordion-icon">+</span> Employee Communication</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       <tr class="level4" parent="in-tt-4-1" state="leaf" style="display: none;">
		<td class="name">Mass Communication to groups of employees by mail / SMS</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-4-1" state="leaf" style="display: none;">
		<td class="name">Social HR - employee messaging communication</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level4" parent="in-tt-4-1" state="leaf" style="display: none;">
		<td class="name">Group-wise targeting of communication</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
		</tr>
	 <tr class="level3" parent="in-ct-1" rowname="in-tt-4-2" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-2');">&nbsp;<span class="accordion-icon">+</span> Reminders and Alerts</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
        <tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  Automated greeting cards</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  Notifications by social feeds</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  100+ pre-built system and employee lifecycle events</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  Highly customizable reminders and alerts system</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 <tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  Fully configurable notification templates (Build Your Own Templates)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	   <tr class="level4" parent="in-tt-4-2" state="leaf" style="display: none;">
            <td class="name">  SMS and mobile push notifications</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 <tr class="level3" parent="in-ct-1" rowname="in-tt-4-3" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-3');">&nbsp;<span class="accordion-icon">+</span> HR Reports</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
       <tr class="level4" parent="in-tt-4-3" state="leaf" style="display: none;">
            <td class="name">  Ready-made HR MIS reports</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 <tr class="level4" parent="in-tt-4-3" state="leaf" style="display: none;">
            <td class="name">  User-defined & Customized Report Builder</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level3" parent="in-ct-1" rowname="in-tt-4-5" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-5');"> Extensive Labour Law reports (Shops Act, Factories Act, Maternity Benefit, Contract Labour Act, etc.)</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 
	<tr class="level3" parent="in-ct-1" rowname="in-tt-4-6" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-6');">&nbsp;<span class="accordion-icon">+</span> Letters and Mail Merge</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Employee letter preparation in a few clicks</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Emailing and automatic letter filing in employee records</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Automatic serial numbering of letters</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Custom fields for maximum flexibility</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Letter gallery with prebuilt formats</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-6" state="leaf" style="display: none;">
            <td class="name">  Mail merge</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-1" rowname="in-tt-4-7" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-4-7');">&nbsp;<span class="accordion-icon">+</span> Company Policies and Forms</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
       <tr class="level4" parent="in-tt-4-7" state="leaf" style="display: none;">
            <td class="name">  Publish all company policies and employee handbook</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-7" state="leaf" style="display: none;">
            <td class="name">  Publish all commonly required forms & templates</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-4-7" state="leaf" style="display: none;">
            <td class="name">  Group-wise targeting of published documents</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  
       
</tbody>
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-2" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-2');"><span class="accordion-icon">+</span> Payroll </span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
   <tr class="level3"  parent="in-ct-2" rowname="in-tt-4" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-4');"><span class="accordion-icon">+</span> Configurable Salary Structure </span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-4" state="leaf" style="display: none;">
    <td class="name"> Highly customizable salary structure for any industry</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-4" state="leaf" style="display: none;">
    <td class="name"> Unlimited salary components</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-4" state="leaf" style="display: none;">
    <td class="name"> Off-the-shelf building blocks for complex payroll scenarios</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	<tr class="level3" parent="in-ct-2" rowname="in-tt-5" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-5');">&nbsp;<span class="accordion-icon">+</span> Payroll Inputs</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
       <tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name">  Automatic leave inputs from other greytHR modules</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Automatic attendance inputs from other greytHR modules</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	  <tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Increments</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> One-time payments and deductions</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Final settlements</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> LOP and LOP reversals</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Full-fledged arrears processing</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Stop payment with release feature</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-5" state="leaf" style="display: none;">
            <td class="name"> Salary analytics</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-6" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-6');">&nbsp;<span class="accordion-icon">+</span> Loans and Salary Advances</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
       <tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Salary advance with auto deduction in next payroll</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Manage company loans to employees</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Fixed rate interest, EMI, no interest, reducing balance</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Auto calculation and deductions in payroll</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Pause loan deductions for a specified period</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Automatic closure on completion of repayment</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Ability to report on principal and interest portions</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Loan prepayment and balloon payment features</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level4" parent="in-tt-6" state="leaf" style="display: none;">
            <td class="name">  Automatic calculation of loan perquisite</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-7" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-7');">&nbsp;<span class="accordion-icon">+</span> Payroll Reimbursements & Expenses</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
       
       <tr  class="level4" parent="in-tt-7" state="leaf" style="display: none;">
            <td class="name">  Extensive reimbursement configurations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 
       <tr  class="level4" parent="in-tt-7" state="leaf" style="display: none;">
            <td class="name">  Monthly / annual entitlements</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 
       <tr  class="level4" parent="in-tt-7" state="leaf" style="display: none;">
            <td class="name">  Claim processing with limit checking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 
       <tr  class="level4" parent="in-tt-7" state="leaf" style="display: none;">
            <td class="name">  Excess claims tracking and set-off feature</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 
       <tr  class="level4" parent="in-tt-7" state="leaf" style="display: none;">
            <td class="name">  Online reimbursement claim workflow</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	 <tr class="level3" parent="in-ct-2" rowname="in-tt-71" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-71');">&nbsp; Flexible Benefit Plan (FBP) Declaration</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-8" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-8');">&nbsp;<span class="accordion-icon">+</span> Payroll Processing</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-8" state="leaf" style="display: none;">
            <td class="name">  Single click payroll process</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-8" state="leaf" style="display: none;">
            <td class="name">  Guided payroll processing with checklist</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-8" state="leaf" style="display: none;">
            <td class="name">  Lock feature to close payroll processing</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-9" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-9');">&nbsp;<span class="accordion-icon">+</span> Verification and Reconciliations</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-9" state="leaf" style="display: none;">
            <td class="name">  Easy export to Excel facility</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-9" state="leaf" style="display: none;">
            <td class="name">  Highly customizable salary register and payroll statements</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-9" state="leaf" style="display: none;">
            <td class="name">  Lock feature to close payroll processing</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-9" state="leaf" style="display: none;">
            <td class="name">  Extensive reconciliations tools</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-9" state="leaf" style="display: none;">
            <td class="name">  Payroll comparison and difference analysis</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-10" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-10');">&nbsp;<span class="accordion-icon">+</span> Statutory Compliance</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  PF calculations with ECR generation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  ESI computations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Professional Tax with all state specific rules built in</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Labour Welfare Fund calculation and deductions</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Comprehensive TDS (IT) calculations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Digitally signed Form 16 generation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Easy Form 24Q generation and automatic FVU validation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-10" state="leaf" style="display: none;">
            <td class="name">  Bonus calculations and reporting</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-11" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-11');">&nbsp;<span class="accordion-icon">+</span> Payslip Generation and Distribution</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-11" state="leaf" style="display: none;">
            <td class="name">  Payslip gallery with multiple payslip formats</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-11" state="leaf" style="display: none;">
            <td class="name">  One-click payslip distribution</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-11" state="leaf" style="display: none;">
            <td class="name">  Download payslips into a single or multiple PDF files and distribute by email</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-11" state="leaf" style="display: none;">
            <td class="name">  Distribute payslips via employee portal or mobil</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-11" state="leaf" style="display: none;">
            <td class="name">  Separate reimbursement payslips</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-12" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-12');">&nbsp;<span class="accordion-icon">+</span> Payroll Reports</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  MIS reports</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Reconciliation reports</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Customizable payroll statement / salary register / wage register</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Ad-hoc report builder</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Salary analytics</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Professional Tax reports</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Reports under Shops and Establishment Acts of various states</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Reports under CLRA Act of various states</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-12" state="leaf" style="display: none;">
            <td class="name">  Digitally signed Form 16 and Form 24Q files with FVU validation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-13" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-13');">&nbsp;<span class="accordion-icon">+</span> Accounts JV</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-13" state="leaf" style="display: none;">
            <td class="name">  Extensive Excel output capabilities</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-13" state="leaf" style="display: none;">
            <td class="name">  Inbuilt formats for Tally and QBO</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-13" state="leaf" style="display: none;">
            <td class="name">  Highly configurable Accounts JV with split by cost center/employees</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	
	<tr class="level3" parent="in-ct-2" rowname="in-tt-14" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-14');">&nbsp;<span class="accordion-icon">+</span> PayNow - Direct Salary Transfer to Employee Bank Account</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-14" state="leaf" style="display: none;">
            <td class="name">  Direct salary transfers to employees</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-14" state="leaf" style="display: none;">
            <td class="name">  Secured OTP based Authentication</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-14" state="leaf" style="display: none;">
            <td class="name">  Real time transaction & account balance status</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-14" state="leaf" style="display: none;">
            <td class="name">  Reports & Audit Trail</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr class="level3" parent="in-ct-2" rowname="in-tt-15" state="collapsed" style="display: none;">
            <td class="name">   <span onclick="toggle_rows(this, 'in-tt-15');">&nbsp;<span class="accordion-icon">+</span> Payout and Disbursements</span></td>
            <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
            </tr>
	 <tr  class="level4" parent="in-tt-15" state="leaf" style="display: none;">
            <td class="name">  Handle multiple payment modes - cash, cheque, bank transfer</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-15" state="leaf" style="display: none;">
            <td class="name">  All major bank transfer electronic formats built-in</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-15" state="leaf" style="display: none;">
            <td class="name">  Facility to release payments in batches</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	<tr  class="level4" parent="in-tt-15" state="leaf" style="display: none;">
            <td class="name">  Facility to track status for cash and cheque payments</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
	  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
        </tr>
	
	
</tbody>
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-3" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-3');"><span class="accordion-icon">+</span> Leave Management </span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
   <tr class="level3"  parent="in-ct-3" rowname="in-tt-3-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-3-1');"><span class="accordion-icon">+</span> Fully Customizable Leave Policies </span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-3-1" state="leaf" style="display: none;">
    <td class="name"> Unlimited leave types (annual, privilege, maternity, etc.)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-1" state="leaf" style="display: none;">
    <td class="name"> Holiday lists - Location and Project based</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-1" state="leaf" style="display: none;">
    <td class="name"> Restricted (optional) holidays support</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-1" state="leaf" style="display: none;">
    <td class="name"> Multiple leave policies for different group of employees</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-1" state="leaf" style="display: none;">
    <td class="name"> Customizable leave policy for each leave type</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level3"  parent="in-ct-3" rowname="in-tt-3-2" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-3-2');"><span class="accordion-icon">+</span> Manage Balance and Transactions</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-3-2" state="leaf" style="display: none;">
    <td class="name"> Automatic tracking of leave balances</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-2" state="leaf" style="display: none;">
    <td class="name"> Easy year end processing (lapsing, carry-forward, etc.)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-3-2" state="leaf" style="display: none;">
    <td class="name"> Online leave application and review with Multi-Level Approval Workflow</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
</tbody>
	
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-4" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-4-01');"><span class="accordion-icon">+</span> Employee Workflows for Process Automation</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Limited</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	
   <tr class="level3"  parent="in-ct-4-01" rowname="in-tt-4-02" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-4-02');"><span class="accordion-icon">+</span> Employee Helpdesk (Query and Resolution Workflow)</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-4-02" state="leaf" style="display: none;">
    <td class="name"> Multiple categories of queries</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-02" state="leaf" style="display: none;">
    <td class="name"> Category-based reviewers</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-02" state="leaf" style="display: none;">
    <td class="name"> Online employee query logging & resolution</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-4-02" state="leaf" style="display: none;">
    <td class="name"> SLA tracking & extensive reporting</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
    <tr class="level3"  parent="in-ct-4-01" rowname="in-tt-4-03" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-4-03');"><span class="accordion-icon">+</span> Confirmation Workflow</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-4-03" state="leaf" style="display: none;">
    <td class="name"> Auto-initiated confirmations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-03" state="leaf" style="display: none;">
    <td class="name"> Easy confirmation & probation extension</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-03" state="leaf" style="display: none;">
    <td class="name"> Confirmation letters</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level3"  parent="in-ct-4-01" rowname="in-tt-4-04" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-4-04');"><span class="accordion-icon">+</span> Loan Apply & Approval Workflow</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-4-04" state="leaf" style="display: none;">
    <td class="name"> Customized loan and salary advance policies</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-04" state="leaf" style="display: none;">
    <td class="name"> Apply for and approve loans online</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-04" state="leaf" style="display: none;">
    <td class="name"> Confirmation letters</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-4-04" state="leaf" style="display: none;">
    <td class="name"> Multiple levels of reviewers</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   
   
</tbody>
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-5" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-5');"><span class="accordion-icon">+</span> Attendance Management</span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	<tr class="level3"  parent="in-ct-5" rowname="in-tt-5-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-5-1');"><span class="accordion-icon">+</span> Swipe Capture from Varied Sources</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> Online attendance marking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> Mobile attendance marking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> Geo Fencing - Attendance Marking from pre-defined locations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> Integration with attendance recording devices</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> Map-based mobile attendance marking with Location Tracking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
   <tr class="level4" parent="in-tt-5-1" state="leaf" style="display: none;">
    <td class="name"> AI-based Facial Recognition-Based Attendance Marking</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	<tr class="level3"  parent="in-ct-5" rowname="in-tt-5-2" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-5-2');"><span class="accordion-icon">+</span> Extensive Shift Management</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-5-2" state="leaf" style="display: none;">
    <td class="name"> Support for multiple shifts</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-2" state="leaf" style="display: none;">
    <td class="name"> Shift management with automatic rotation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-2" state="leaf" style="display: none;">
    <td class="name"> Easy shift rostering by line managers for their teams</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-2" state="leaf" style="display: none;">
    <td class="name"> Integration with attendance recording devices</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-2" state="leaf" style="display: none;">
    <td class="name"> Sophisticated business rules for attendance exceptions</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  
	
	<tr class="level3"  parent="in-ct-5" rowname="in-tt-5-3" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-5-3');"><span class="accordion-icon">+</span> Highly Configurable Policies</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-5-3" state="leaf" style="display: none;">
    <td class="name"> Penalize unauthorized absence, late in, early out, shortfall, etc.</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-3" state="leaf" style="display: none;">
    <td class="name"> Flexi hours supportn</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-3" state="leaf" style="display: none;">
    <td class="name"> Multiple attendance policies for different groups</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-3" state="leaf" style="display: none;">
    <td class="name"> Integration with attendance recording devices</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  <tr class="level4" parent="in-tt-5-3" state="leaf" style="display: none;">
    <td class="name"> Customizable weekends</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	<tr class="level3"  parent="in-ct-5" rowname="in-tt-5-4" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-5-4');"><span class="accordion-icon">+</span> Attendance Processing</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Automatic daily attendance processing</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Attendance regularization workflow</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Manual override facility</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Continuous absence alerts</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Month-end HR review and finalization facility</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-5-4" state="leaf" style="display: none;">
    <td class="name"> Attendance muster generation</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	<tr class="level3"  parent="in-ct-5" rowname="in-tt-5-4A" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-5-4A');"><span class="accordion-icon">+</span> Overtime Management</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
   <tr class="level4" parent="in-tt-5-4A" state="leaf" style="display: none;">
    <td class="name"> Flexible overtime payouts and eligibility policies</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-5-4A" state="leaf" style="display: none;">
    <td class="name"> Assign policies to employees by categories (department, location etc.)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-5-4A" state="leaf" style="display: none;">
    <td class="name"> Apply Overtime in bulk or for individual employee</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-5-4A" state="leaf" style="display: none;">
    <td class="name"> Comprehensive Overtime register with hours and earnings</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-5-4A" state="leaf" style="display: none;">
    <td class="name"> Detailed Overtime payslips with hours and earnings breakup</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	  
	</tbody>
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-6" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-6');"><span class="accordion-icon">+</span> Employee Self Onboarding</span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	<tr class="level3-1"  parent="in-ct-6" rowname="in-tt-6-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-6-1');">&nbsp; Personalised & Paperless Onboarding of Employees</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-6" rowname="in-tt-6-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-6-1');">&nbsp; Workflows for Admin Review</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-6" rowname="in-tt-6-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-6-1');">&nbsp; Alerts and Reminders for Employees and Admin</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-6" rowname="in-tt-6-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-6-1');">&nbsp; Policy Publish & Acknowledgment</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 
	</tbody>
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-61" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-61');"><span class="accordion-icon">+</span> Comprehensive Employee Exit Managment</span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	<tr class="level3-1"  parent="in-ct-61" rowname="in-tt-61-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-61-1');"> Online Resignation Application & Approval</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-61" rowname="in-tt-61-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-61-1');"> Customizable Multi-Department Clearance</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-61" rowname="in-tt-61-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-61-1');"> Exit Dashboard</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-61" rowname="in-tt-61-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-61-1');"> Integration with Full & Final Settlement Process</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	</tbody>
	
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-7" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-7');"><span class="accordion-icon">+</span> Employee Portal (Web and Mobile app)</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	<tr class="level3"  parent="in-ct-7" rowname="in-tt-7-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-7-1');"><span class="accordion-icon">+</span> Employee Portal - Core HR</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Social HR</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Employee directory</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Policy Publish & Acknowledgment</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Access to own documents and letters</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Access to company policies, handbook, forms, etc.</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Access to own employee information</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-1" state="leaf" style="display: none;">
    <td class="name"> Mobile app for employees and managers</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	<tr class="level3"  parent="in-ct-7" rowname="in-tt-7-2" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-7-2');"><span class="accordion-icon">+</span> Employee Portal - Leave</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-7-2" state="leaf" style="display: none;">
    <td class="name"> Leave application and review</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-2" state="leaf" style="display: none;">
    <td class="name"> Leave cancellation workflow</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-2" state="leaf" style="display: none;">
    <td class="name"> Online leave balances and details</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-2" state="leaf" style="display: none;">
    <td class="name"> Team leave information</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-2" state="leaf" style="display: none;">
    <td class="name"> Leave grant, comp-off grant workflows</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	</tbody>
	
	<tbody>
	<tr class="level3"  parent="in-ct-7" rowname="in-tt-7-3" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-7-3');"><span class="accordion-icon">+</span> Employee Portal - Payroll</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Online payslips</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> IT calculation statement</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> IT proof of investments</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> IT savings and declarations</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Flexible Benefit Plan (FBP) Declaration</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Reimbursement claims</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Reimbursement statements</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Online reimbursement claims and review</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-3" state="leaf" style="display: none;">
    <td class="name"> Payroll information like loan statement, YTD, PF, etc.</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	</tbody>
	
	
	<tbody>
	<tr class="level3"  parent="in-ct-7" rowname="in-tt-7-4" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-7-4');"><span class="accordion-icon">+</span> Employee Portal - Attendance</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-7-4" state="leaf" style="display: none;">
    <td class="name"> Attendance regularization workflows</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-4" state="leaf" style="display: none;">
    <td class="name"> Team attendance information for managers</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-7-4" state="leaf" style="display: none;">
    <td class="name"> Detailed attendance information</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level4" parent="in-tt-7-4" state="leaf" style="display: none;">
    <td class="name"> Real time attendance status (who's in-who's late)</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
		<tr class="level3"  parent="in-ct-7" rowname="in-tt-7-5" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-7-5');"> Single Sign-on (Employee Logins with Google)</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	</tbody>
	
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-8" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-8');"><span class="accordion-icon">+</span> Automated Checklists for Task Management</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-8" rowname="in-tt-8-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-8-1');"> Built-in & User-configurable Checklists</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-8" rowname="in-tt-8-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-8-1');"> Real-time Collaboration Across Departments</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr><tr class="level3-1"  parent="in-ct-8" rowname="in-tt-8-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-8-1');"> Alerts and Reminders on Pending Tasks</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	</tbody>
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-9" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-9');"><span class="accordion-icon">+</span> Advanced Analytics & Reporting</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-9" rowname="in-tt-9-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-9-1');"> Built-in & User-configurable Checklists</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-9" rowname="in-tt-9-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-9-1');"> Extensive, Custom & Configurable Reports</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-9" rowname="in-tt-9-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-9-1');"> Employee Analytics Hub with Graphs & Charts</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-9" rowname="in-tt-9-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-9-1');"> Custom Notifications, Including SMS</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	</tbody>
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-10" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-10');">&nbsp;AI-Powered Chatbot</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-11" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-11');"><span class="accordion-icon">+</span> Access & User Management</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> Standard Access Management</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> User-Definable Roles & Permissions</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> Unlimited Users</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> Password Policy Configuration</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> Detailed Audit Logging & Reporting of All Activities</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-11" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-11-1');"> IP Restriction for Access Control to Application</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		
	</tbody>
	
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-12" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-12');">&nbsp;Extensive Excel import & Export Facility</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
		<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-13" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-13');"><span class="accordion-icon">+</span> Onboarding Support & Support plans</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-13" rowname="in-tt-13-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-13-1');"> Standard Access Management</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-13" rowname="in-tt-13-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-13-1');"> Product Training</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-13" rowname="in-tt-13-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-13-1');"> Community Access</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-13" rowname="in-tt-13-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-13-1');"> Onboarding Support</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		<tr class="level3-1"  parent="in-ct-13" rowname="in-tt-11-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-13-1');"> Ticket Support</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
		
	</tbody>
	
	
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-14" state="collapsed">
   <td class="name"><span onclick="toggle_rows(this, 'in-ct-14');"><span class="accordion-icon">+</span> Business Expense Claims Management</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span>
</div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
  
  
<tbody>
	<tr class="level3"  parent="in-ct-14" rowname="in-tt-14-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-14-1');"><span class="accordion-icon">+</span> Highly Customizable Business Rules</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-14-1" state="leaf" style="display: none;">
    <td class="name"> Configure multiple claim heads</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-1" state="leaf" style="display: none;">
    <td class="name"> Extensive support for business rules on limits & reviewers</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-1" state="leaf" style="display: none;">
    <td class="name"> Multiple claims form support</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	<tr class="level3"  parent="in-ct-14" rowname="in-tt-14-2" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-14-2');"><span class="accordion-icon">+</span> Expense Claims Processing</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	 <tr class="level4" parent="in-tt-14-2" state="leaf" style="display: none;">
    <td class="name"> Tour advances request and review</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-2" state="leaf" style="display: none;">
    <td class="name"> Online workflow for claims by employee</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-2" state="leaf" style="display: none;">
    <td class="name"> Multiple approval matrix based on claim types and amounts</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-2" state="leaf" style="display: none;">
    <td class="name"> Batch payment options</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	 <tr class="level4" parent="in-tt-14-2" state="leaf" style="display: none;">
    <td class="name"> Multiple modes of payments</td>
       <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
    <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
    </tr>
	
	</tbody>
		
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-15" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-15');"> Group Company Support</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-16" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-16');"><span class="accordion-icon">+</span> Enterprise Features</span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹25 per emp/mo</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹25 per emp/mo</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/dubble-check.png"></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-16" rowname="in-tt-16-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-16-1');"> Single Sign-on (SAML)</span></td>
     <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo</span>
</div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo</span>
</div></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-16" rowname="in-tt-16-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-16-1');"> REST API access</span></td>
     <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span>
</div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span>
</div></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	
</tbody>
	
	
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-17" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-17');"><span class="accordion-icon">+</span> GeoMark+ (Map-Based Attendance Marking with Location Tagging)</span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹50 Per user per month. Charged based on the number of users, not the total number of employees.</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹50 Per user per month. Charged based on the number of users, not the total number of employees.</span></div></span></td>
 </tr>
</tbody>
	
	<tbody>
	<tr class="level3-1"  parent="in-ct-17" rowname="in-tt-17-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-17-1');"> GPS-based Attendance Marking for Distributed Workforce</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-17" rowname="in-tt-17-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-16-1');"> Workflows for Manager Reviews</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-17" rowname="in-tt-17-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-17-1');"> Attendance Scheme-level Customizations</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	<tr class="level3-1"  parent="in-ct-17" rowname="in-tt-17-1" state="collapsed" style="display: none;">
   <td class="name"><span onclick="toggle_rows(this, 'in-tt-17-1');"> Geo Swipe Reports for Due Diligence</span></td>
     <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check"  src="images_partner/single-check.png"></span></td>  
   </tr>
	
</tbody>
	
			
	<tbody>
  <tr class="level2" parent="inflow" rowname="in-ct-18" state="collapsed">
   <td class="name">  <span onclick="toggle_rows(this, 'in-ct-18');"> Visage (AI-powered Facial Recognition-Based Attendance Marking)</span></td>
  <td class="name single-check"><span onclick="toggle_rows(this, 'inflow');"><img alt="check" src="images_partner/no-sign.png"></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹20 Per user per month. Charged based on the number of users, not the total number of employees</span></div></span></td>
  <td class="name dubble-check"><span onclick="toggle_rows(this, 'inflow');">Add-on <div class="tooltip"><img alt="info-icon" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹20 Per user per month. Charged based on the number of users, not the total number of employees.</span></div></span></td>
 </tr>
</tbody>
</table>
</div>	  
		
<div class="pricing-bg mobileshow">
  <table class="table table-hover table-bordered table-condensed cashflow_report">
    <thead>
      <tr>
        <th width="300px">
          
        </th>
      </tr>
    </thead>
	  
    <tbody>
      <tr>
        <td colspan="2" class="row1" style="text-align: center">
		   <img alt="greythr" style="margin-top: 20px" src="images_partner/mbbike-icon.png">
          <p>Essential</p>
          <div class="row1 pricing-cost">
            <p><sup>₹</sup>3495<span style="font-size: 16px">/month</span></p>
          </div>
          <p>(Includes 50 Employees)<br>+₹30/month per additional employee</p>
          <button class="pricing-cta">START TRIAL</button>
		  
		  </td>
      </tr>
    </tbody>
    <tbody>
      <tr parent="inflow" rowname="in-ct-0" state="collapsed">
        <td colspan="2" class="name mb-name"> <span onclick="toggle_rows(this, 'in-ct-0');"> Show Features <span style="float: none;" class="accordion-icon">+</span></span></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-0-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-0-1');"><img alt="check" src="images_partner/dubble-check.png"> Core HR <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-2');"><img alt="check" src="images_partner/single-check.png"> Employee Information Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>An extensive employee database as a system of record</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> 20+ employee data categories</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reporting hierarchy with Org Chart</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> HR and CEO dashboards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee assets tracking</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-3');"><img alt="check" src="images_partner/single-check.png"> Know Your Employee (KYE)<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Store various employee identity information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy online facility to collect identity data (Data Drives)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Track verified status (Aadhar verified, PAN verified, etc.)</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-4');"><img alt="check" src="images_partner/single-check.png"> Employee Documents Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Anti-virus scanning for all uploaded documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic filing of generated letters to the document store</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online access to all issued letters and documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Store digital or scanned copies of employee documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bulk document upload</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-5');"><img alt="check" src="images_partner/single-check.png"> Employee Communication<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Mass Communication to groups of employees by mail / SMS</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Social HR - employee messaging communication</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Group-wise targeting of communication</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-6');"><img alt="check" src="images_partner/single-check.png"> Reminders and Alerts<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automated greeting cards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Notifications by social feeds</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>100+ pre-built system and employee lifecycle events</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable reminders and alerts system</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fully configurable notification templates (Build Your Own Templates)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> SMS and mobile push notifications</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-7" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-7');"><img alt="check" src="images_partner/single-check.png"> HR Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ready-made HR MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> User-defined & Customized Report Builder</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-8" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-8');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Extensive Labour Law reports (Shops Act, Factories Act, Maternity Benefit, Contract Labour Act, etc.)</p></span> </td>
      </tr>
    </tbody>
	  
	       <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-9" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-9');"><img alt="check" src="images_partner/single-check.png"> Letters and Mail Merge<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee letter preparation in a few clicks</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Emailing and automatic letter filing in employee records</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic serial numbering of letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Custom fields for maximum flexibility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Letter gallery with prebuilt formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mail merge</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1" rowname="in-tt-0-10" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-10');"><img alt="check" src="images_partner/single-check.png"> Company Policies and Forms<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all company policies and employee handbook</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all commonly required forms & templates</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Group-wise targeting of published documents</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-1-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-1-1');"><img alt="check" src="images_partner/dubble-check.png">Payroll <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-2');"><img alt="check" src="images_partner/single-check.png"> Configurable Salary Structure<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary structure for any industry</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Unlimited salary components</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Off-the-shelf building blocks for complex payroll scenarios</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-3');"><img alt="check" src="images_partner/single-check.png"> Payroll Inputs<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic leave inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic attendance inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Increments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-time payments and deductions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Final settlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> LOP and LOP reversals</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Full-fledged arrears processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Stop payment with release feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-4');"><img alt="check" src="images_partner/single-check.png"> Loans and Salary Advances<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Salary advance with auto deduction in next payroll</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Manage company loans to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fixed rate interest, EMI, no interest, reducing balance</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Auto calculation and deductions in payroll</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Pause loan deductions for a specified period</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic closure on completion of repayment</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Ability to report on principal and interest portions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Loan prepayment and balloon payment features</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic calculation of loan perquisite</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-5');"><img alt="check" src="images_partner/single-check.png"> Payroll Reimbursements & Expenses<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Extensive reimbursement configurations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Monthly / annual entitlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Claim processing with limit checking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Excess claims tracking and set-off feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Online reimbursement claim workflow</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-6');"><img alt="check" src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-7" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-7');"><img alt="check" src="images_partner/single-check.png"> Payroll Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Single click payroll process</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Guided payroll processing with checklist</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Lock feature to close payroll processing</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-8" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-8');"><img alt="check" src="images_partner/single-check.png"> Verification and Reconciliations<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Easy export to Excel facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary register and payroll statements</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive reconciliations tools</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payroll comparison and difference analysis</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-9" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-9');"><img alt="check" src="images_partner/single-check.png"> Statutory Compliance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> PF calculations with ECR generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> ESI computations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Professional Tax with all state specific rules built in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Labour Welfare Fund calculation and deductions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Comprehensive TDS (IT) calculations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Digitally signed Form 16 generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy Form 24Q generation and automatic FVU validation</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bonus calculations and reporting</td>
      </tr>
    </tbody>
	  
	  
	  
	       <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-10" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-10');"><img alt="check" src="images_partner/single-check.png"> Payslip Generation and Distribution<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payslip gallery with multiple payslip formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-click payslip distribution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Download payslips into a single or multiple PDF files and distribute by email</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Distribute payslips via employee portal or mobile</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Separate reimbursement payslips</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-11" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-11');"><img alt="check" src="images_partner/single-check.png"> Payroll Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reconciliation reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable payroll statement / salary register / wage register</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ad-hoc report builder</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Professional Tax reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Reports under Shops and Establishment Acts of various states</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports under CLRA Act of various states</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Digitally signed Form 16 and Form 24Q files with FVU validation</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-12" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-12');"><img alt="check" src="images_partner/single-check.png"> Accounts JV<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive Excel output capabilities</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Inbuilt formats for Tally and QBO</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly configurable Accounts JV with split by cost center/employees</p></td>
      </tr>
    </tbody>
	  
	   
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-13" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-13');"><img  alt="check" src="images_partner/single-check.png"> PayNow-Direct Salary Transfer to Employee Bank<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Direct salary transfers to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Secured OTP based Authentication</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Real time transaction & account balance status</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports & Audit Trail</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1" rowname="in-tt-1-14" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-14');"><img alt="check" src="images_partner/single-check.png"> Payout and Disbursements<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Handle multiple payment modes - cash, cheque, bank transfer</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>All major bank transfer electronic formats built-in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to release payments in batches</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to track status for cash and cheque payments</p></td>
      </tr>
    </tbody>
	  
   <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-2-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-2-1');"><img alt="check" src="images_partner/dubble-check.png"> Leave Management <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-1" rowname="in-tt-2-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-2');"><img alt="check" src="images_partner/single-check.png"> Fully Customizable Leave Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Unlimited leave types (annual, privilege, maternity, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Supports multiple types of leave transactions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Holiday lists - Location and Project based</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Restricted (optional) holidays support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-1" rowname="in-tt-2-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-3');"><img alt="check" src="images_partner/single-check.png"> Manage Balance and Transactions<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic tracking of leave balances</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy year end processing (lapsing, carry-forward, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online leave application and review with Multi-Level Approval Workflow</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>

      <tr class="level3" parent="in-ct-0" rowname="in-tt-3-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-3-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;" src="images_partner/single-check.png"> Employee Workflows for Process Automation  <div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Limited</span></div><span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-3-1" rowname="in-tt-3-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-2');"><img alt="check" src="images_partner/single-check.png"> Employee Helpdesk Query & Resolution Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple categories of queries</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Category-based reviewers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online employee query logging & resolution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> SLA tracking & extensive reporting</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
	   <tbody>
      <tr class="level4" parent="in-tt-3-1" rowname="in-tt-3-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-3');"><img alt="check" src="images_partner/no-sign.png"> Confirmation Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Auto-initiated confirmations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Easy confirmation & probation extension</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no.png"> Confirmation letters</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-3-1" rowname="in-tt-3-4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-4');"><img alt="check" src="images_partner/no-sign.png"> Loan Apply & Approval Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Customized loan and salary advance policies</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Apply for and approve loans online</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Multiple levels of reviewers</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-4-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-4-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;" src="images_partner/no-sign.png"> Attendance Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-4-1" rowname="in-tt-4-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-2');"><img alt="check" src="images_partner/no-sign.png"> Swipe Capture from Varied Sources<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Online attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Mobile attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Geo Fencing - Attendance Marking from pre-defined locations</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Integration with attendance recording devices</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Map-based mobile attendance marking with Location Tracking</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>AI-based Facial Recognition-Based Attendance Marking</p></td>
      </tr>
    </tbody>
    
	  
    <tbody>
      <tr class="level4" parent="in-tt-4-1" rowname="in-tt-4-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-3');"><img alt="check" src="images_partner/no-sign.png"> Extensive Shift Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Support for multiple shifts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Shift management with automatic rotation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Easy shift rostering by line managers for their teams</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Sophisticated business rules for attendance exceptions</p></td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-4-1" rowname="in-tt-4-4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-4');"><img alt="check" src="images_partner/no-sign.png"> Highly Configurable Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Penalize unauthorized absence, late in, early out, shortfall, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Flexi hours support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/no-sign.png"> <p>Multiple attendance policies for different groups</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Customizable weekends</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-1" rowname="in-tt-4-5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-5');"><img alt="check" src="images_partner/no-sign.png"> Attendance Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Automatic daily attendance processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Manual override facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Continuous absence alerts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Month-end HR review and finalization facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/no-sign.png"> Attendance muster generation</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-1" rowname="in-tt-4-6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-6');"><img alt="check" src="images_partner/no-sign.png"> Overtime Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/no-sign.png"> <p>Flexible overtime payouts and eligibility policies</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/no-sign.png"> <p>Assign policies to employees by categories (department, location etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/no-sign.png"> <p>Apply Overtime in bulk or for individual employee</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/no-sign.png"> <p>Comprehensive Overtime register with hours and earnings</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/no-sign.png"> <p>Detailed Overtime payslips with hours and earnings breakup</p></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-5-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-5-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;" src="images_partner/no-sign.png"> Employee Self Onboarding<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-5-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/no-sign.png"> <p>Personalised & Paperless Onboarding of Employees</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img alt="check" src="images_partner/no-sign.png"> Workflows for Admin Review</span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/no-sign.png"> <p>Alerts and Reminders for Employees and Admin</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img alt="check" src="images_partner/no-sign.png"> Policy Publish & Acknowledgment</span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-6-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-6-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;"  src="images_partner/no-sign.png"> Comprehensive Employee Exit Managment<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-6-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2');"><img alt="check" src="images_partner/no-sign.png"> Online Resignation Application & Approval</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2');"><img alt="check" src="images_partner/no-sign.png"> Customizable Multi-Department Clearance</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2');"><img alt="check" src="images_partner/no-sign.png"> Exit Dashboard</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/no-sign.png"> <p>Integration with Full & Final Settlement Process</p></span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-7-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-7-1');"><img alt="check" src="images_partner/dubble-check.png"> Employee Portal (Web and Mobile app)<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-7-1" rowname="in-tt-7-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-2');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Core HR<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Social HR</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own documents and letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Access to company policies, handbook, forms, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own employee information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mobile app for employees and managers</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-7-1" rowname="in-tt-7-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-3');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Leave<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave application and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave cancellation workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online leave balances and details</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team leave information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave grant, comp-off grant workflows</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-1" rowname="in-tt-7-4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-4');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Payroll<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online payslips</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT calculation statement</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT proof of investments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT savings and declarations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement claims</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement statements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online reimbursement claims and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Payroll information like loan statement, YTD, PF, etc.</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-1" rowname="in-tt-7-5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-5');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Attendance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team attendance information for managers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Detailed attendance information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"   style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Real time attendance status (who's in-who's late)</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-8-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-8-1');"><img alt="check" src="images_partner/dubble-check.png"> Single Sign-on (Employee Logins with Google)</span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-9-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-9-1');"><img alt="check" src="images_partner/dubble-check.png"> Automated Checklists for Task Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-9-1" rowname="in-tt-9-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2');"><img alt="check" src="images_partner/single-check.png"> Built-in & User-configurable Checklists</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-1" rowname="in-tt-9-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2');"><img alt="check" src="images_partner/single-check.png"> Real-time Collaboration Across Departments</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-1" rowname="in-tt-9-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2');"><img alt="check" src="images_partner/single-check.png"> Alerts and Reminders on Pending Tasks</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-10-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-10-1');"><img alt="check" src="images_partner/dubble-check.png"> Advanced Analytics & Reporting<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-10-1" rowname="in-tt-10-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2');"><img alt="check" src="images_partner/single-check.png"> Extensive, Custom & Configurable Reports</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-1" rowname="in-tt-10-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2');"><img alt="check" src="images_partner/single-check.png"> Employee Analytics Hub with Graphs & Charts</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-1" rowname="in-tt-10-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2');"><img alt="check" src="images_partner/single-check.png"> Custom Notifications, Including SMS</span> </td>
      </tr>
    </tbody>
	  
	  <tbody>
     <tr class="level4" parent="in-tt-10-1" rowname="in-tt-10-2" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-11-1');"><img alt="check" src="images_partner/dubble-check.png"> Dashboards<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-11-1" rowname="in-tt-11-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-11-2');"><img alt="check" src="images_partner/single-check.png"> Operational dashboards</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-11-1" rowname="in-tt-10-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-11-2');"><img alt="check" src="images_partner/single-check.png"> Configurable, Analytical dashboards</span> </td>
      </tr>
    </tbody>
	  
	    <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-12-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-12-1');"><img alt="check" src="images_partner/dubble-check.png"> AI-Powered Chatbot</span> </td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-13-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-13-1');"><img alt="check" src="images_partner/dubble-check.png"> Access & User Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img alt="check" src="images_partner/single-check.png"> Standard Access Management</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img alt="check" src="images_partner/single-check.png"> User-Definable Roles & Permissions</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img alt="check" src="images_partner/single-check.png"> Unlimited Users</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img alt="check" src="images_partner/single-check.png"> Password Policy Configuration</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img  style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Detailed Audit Logging & Reporting of All Activities</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2');"><img alt="check" src="images_partner/single-check.png"> IP Restriction for Access Control to Application</span> </td>
      </tr>
    </tbody>
	 
	  
	    <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-14-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-14-1');"><img alt="check" src="images_partner/dubble-check.png"> Extensive Excel import & Export Facility</span> </td>
      </tr>
    </tbody>
	  
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-15-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-15-1');"><img alt="check" src="images_partner/dubble-check.png"> Onboarding Support & Support plans<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-15-1" rowname="in-tt-15-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2');"><img alt="check" src="images_partner/single-check.png"> Product Training</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1" rowname="in-tt-15-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2');"><img alt="check" src="images_partner/single-check.png"> Community Access</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1" rowname="in-tt-15-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2');"><img alt="check" src="images_partner/single-check.png"> Onboarding Support</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1" rowname="in-tt-15-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2');"><img alt="check" src="images_partner/single-check.png"> Ticket Support</span> </td>
      </tr>
    </tbody>
	  
	  <tbody>

      <tr class="level3" parent="in-ct-0" rowname="in-tt-16-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-16-1');"><img alt="check" src="images_partner/dubble-check.png"> Business Expense Claims Management <div class="tooltip"><img alt="info-icon" style="padding-top: 5px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span></div> <span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-1" rowname="in-tt-16-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-2');"><img alt="check" src="images_partner/single-check.png"> Highly Customizable Business Rules<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Configure multiple claim heads</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Extensive support for business rules on limits & reviewers</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple claims form support</td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-1" rowname="in-tt-16-3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-3');"><img alt="check" src="images_partner/single-check.png"> Expense Claims Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Tour advances request and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online workflow for claims by employee</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Multiple approval matrix based on claim types and amounts</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Batch payment options</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple modes of payments</td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-17-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-17-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;" src="images_partner/single-check.png"> Group Company Support <div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo </span></div> </span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-18-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-18-1');"><img alt="check" style="padding-top: 5px; width: 13px; margin-right: 8px;" src="images_partner/single-check.png"> Enterprise Features <div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹25 per emp/mo </span></div> <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-18-1" rowname="in-tt-18-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-2');"><img alt="check" src="images_partner/single-check.png"> Single Sign-on (SAML) <div class="tooltip"><img alt="info-icon" style="padding-top: 5px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo </span></div> </span> </td>
      </tr>
      <tr class="level4" parent="in-tt-18-1" rowname="in-tt-18-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-2');"><img alt="check" src="images_partner/single-check.png"> REST API access <div class="tooltip"><img alt="info-icon" style="padding-top: 5px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo </span></div> </span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-19-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-19-1');"><img style="float: left; padding-top: 5px; width: 15px; margin-right: 8px;" alt="check" src="images_partner/no-sign.png"> GeoMark+ (Map-Based Attendance Marking <br>with Location Tagging)	<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-19-1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/no-sign.png"> <p>GPS-based Attendance Marking for Distributed Workforce</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2');"><img alt="check" src="images_partner/no-sign.png">Workflows for Manager Reviews</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2');"><img alt="check" src="images_partner/no-sign.png"> Attendance Scheme-level Customizations</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2');"><img alt="check" src="images_partner/no-sign.png">Geo Swipe Reports for Due Diligence</span> </td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-0" rowname="in-tt-20-1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-20-1');"><img style="float: left; padding-top: 5px; 5px; width: 15px; margin-right: 8px;" alt="check" src="images_partner/no-sign.png"> <p>Visage (AI-powered Facial Recognition-Based Attendance Marking)</p></span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  
	</table>				  

			  
			  
			  
<div class="table-space">			  
<table class="table table-hover table-bordered table-condensed cashflow_report ">
    <thead>
      <tr>
        <th width="300px">
          
        </th>
      </tr>
    </thead>
	  
    <tbody>
      <tr>
        <td colspan="2" class="row2" style="text-align: center">
		   <img alt="greythr" src="images_partner/mbcar-icon.png">
          <p>Growth</p>
          <div class="row2 pricing-cost">
            <p><sup>₹</sup>5495<span style="font-size: 16px">/month</span></p>
          </div>
          <p>(Includes 50 Employees)<br>+₹60/month per additional employee</p>
          <button class="pricing-cta">START TRIAL</button>
		  
		  </td>
      </tr>
    </tbody>
    <tbody>
      <tr parent="inflow" rowname="in-ct-1" state="collapsed">
        <td colspan="2" class="name mb-name"> <span onclick="toggle_rows(this, 'in-ct-1');"> Show Features <span style="float: none;" class="accordion-icon">+</span></span></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-0-A1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-0-A1');"><img alt="check" src="images_partner/dubble-check.png"> Core HR <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A2');"><img alt="check" src="images_partner/single-check.png"> Employee Information Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>An extensive employee database as a system of record</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> 20+ employee data categories</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reporting hierarchy with Org Chart</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> HR and CEO dashboards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A2" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee assets tracking</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A3');"><img alt="check" src="images_partner/single-check.png"> Know Your Employee (KYE)<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Store various employee identity information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy online facility to collect identity data (Data Drives)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Track verified status (Aadhar verified, PAN verified, etc.)</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A4');"><img alt="check" src="images_partner/single-check.png"> Employee Documents Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Anti-virus scanning for all uploaded documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic filing of generated letters to the document store</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online access to all issued letters and documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Store digital or scanned copies of employee documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bulk document upload</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A5');"><img alt="check" src="images_partner/single-check.png"> Employee Communication<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Mass Communication to groups of employees by mail / SMS</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Social HR - employee messaging communication</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Group-wise targeting of communication</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A6');"><img alt="check" src="images_partner/single-check.png"> Reminders and Alerts<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automated greeting cards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Notifications by social feeds</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>100+ pre-built system and employee lifecycle events</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable reminders and alerts system</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fully configurable notification templates (Build Your Own Templates)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> SMS and mobile push notifications</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A7" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A7');"><img alt="check" src="images_partner/single-check.png"> HR Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ready-made HR MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> User-defined & Customized Report Builder</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A8" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-8');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Extensive Labour Law reports (Shops Act, Factories Act, Maternity Benefit, Contract Labour Act, etc.)</p></span> </td>
      </tr>
    </tbody>
	  
	       <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A9" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A9');"><img alt="check" src="images_partner/single-check.png"> Letters and Mail Merge<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee letter preparation in a few clicks</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Emailing and automatic letter filing in employee records</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic serial numbering of letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Custom fields for maximum flexibility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Letter gallery with prebuilt formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mail merge</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-A1" rowname="in-tt-0-A10" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A10');"><img alt="check" src="images_partner/single-check.png"> Company Policies and Forms<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all company policies and employee handbook</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all commonly required forms & templates</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Group-wise targeting of published documents</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr parent="in-ct-1" class="level3" rowname="in-tt-0-A1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-1-A1');"><img alt="check" src="images_partner/dubble-check.png">Payroll <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A1" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A2');"><img alt="check" src="images_partner/single-check.png"> Configurable Salary Structure<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary structure for any industry</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Unlimited salary components</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Off-the-shelf building blocks for complex payroll scenarios</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A3');"><img alt="check" src="images_partner/single-check.png"> Payroll Inputs<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic leave inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic attendance inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Increments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-time payments and deductions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Final settlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> LOP and LOP reversals</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Full-fledged arrears processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Stop payment with release feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A4');"><img alt="check" src="images_partner/single-check.png"> Loans and Salary Advances<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Salary advance with auto deduction in next payroll</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Manage company loans to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fixed rate interest, EMI, no interest, reducing balance</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Auto calculation and deductions in payroll</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Pause loan deductions for a specified period</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic closure on completion of repayment</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Ability to report on principal and interest portions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Loan prepayment and balloon payment features</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic calculation of loan perquisite</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A5');"><img alt="check" src="images_partner/single-check.png"> Payroll Reimbursements & Expenses<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Extensive reimbursement configurations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Monthly / annual entitlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Claim processing with limit checking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Excess claims tracking and set-off feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Online reimbursement claim workflow</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A6');"><img alt="check" src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A7" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A7');"><img alt="check" src="images_partner/single-check.png"> Payroll Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Single click payroll process</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Guided payroll processing with checklist</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A7" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Lock feature to close payroll processing</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A8" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A8');"><img alt="check" src="images_partner/single-check.png"> Verification and Reconciliations<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Easy export to Excel facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary register and payroll statements</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive reconciliations tools</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A8" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payroll comparison and difference analysis</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A9" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A9');"><img alt="check" src="images_partner/single-check.png"> Statutory Compliance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> PF calculations with ECR generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> ESI computations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Professional Tax with all state specific rules built in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Labour Welfare Fund calculation and deductions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Comprehensive TDS (IT) calculations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Digitally signed Form 16 generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy Form 24Q generation and automatic FVU validation</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A9" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bonus calculations and reporting</td>
      </tr>
    </tbody>
	  
	  
	  
	       <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A10" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A10');"><img alt="check" src="images_partner/single-check.png"> Payslip Generation and Distribution<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payslip gallery with multiple payslip formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-click payslip distribution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Download payslips into a single or multiple PDF files and distribute by email</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Distribute payslips via employee portal or mobile</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A10" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Separate reimbursement payslips</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A11" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A11');"><img alt="check" src="images_partner/single-check.png"> Payroll Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reconciliation reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable payroll statement / salary register / wage register</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ad-hoc report builder</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Professional Tax reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Reports under Shops and Establishment Acts of various states</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports under CLRA Act of various states</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A11" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Digitally signed Form 16 and Form 24Q files with FVU validation</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A12" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A12');"><img alt="check" src="images_partner/single-check.png"> Accounts JV<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive Excel output capabilities</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Inbuilt formats for Tally and QBO</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A12" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly configurable Accounts JV with split by cost center/employees</p></td>
      </tr>
    </tbody>
	  
	   
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A13" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A13');"><img  alt="check" src="images_partner/single-check.png"> PayNow-Direct Salary Transfer to Employee Bank<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Direct salary transfers to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Secured OTP based Authentication</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Real time transaction & account balance status</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A13" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports & Audit Trail</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-A1" rowname="in-tt-1-A14" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-A14');"><img alt="check" src="images_partner/single-check.png"> Payout and Disbursements<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Handle multiple payment modes - cash, cheque, bank transfer</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>All major bank transfer electronic formats built-in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to release payments in batches</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-A14" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to track status for cash and cheque payments</p></td>
      </tr>
    </tbody>
	  
   <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-0-B1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-2-B1');"><img alt="check" src="images_partner/dubble-check.png"> Leave Management <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-B1" rowname="in-tt-2-B2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-B2');"><img alt="check" src="images_partner/single-check.png"> Fully Customizable Leave Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Unlimited leave types (annual, privilege, maternity, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Supports multiple types of leave transactions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Holiday lists - Location and Project based</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Restricted (optional) holidays support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-B1" rowname="in-tt-2-B" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-B3');"><img alt="check" src="images_partner/single-check.png"> Manage Balance and Transactions<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic tracking of leave balances</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy year end processing (lapsing, carry-forward, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-B3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online leave application and review with Multi-Level Approval Workflow</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>

      <tr class="level3" parent="in-ct-1" rowname="in-tt-3-C1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-3-C1');"><img alt="check" src="images_partner/dubble-check.png"> Employee Workflows for Process Automation<span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-3-C1" rowname="in-tt-3-C2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-2');"><img alt="check" src="images_partner/single-check.png"> Employee Helpdesk Query & Resolution Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple categories of queries</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Category-based reviewers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online employee query logging & resolution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> SLA tracking & extensive reporting</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
	   <tbody>
      <tr class="level4" parent="in-tt-3-C1" rowname="in-tt-3-C3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-C3');"><img alt="check" src="images_partner/single-check.png"> Confirmation Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Auto-initiated confirmations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Easy confirmation & probation extension</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Confirmation letters</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-3-C1" rowname="in-tt-3-C4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-C4');"><img alt="check" src="images_partner/single-check.png"> Loan Apply & Approval Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Customized loan and salary advance policies</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Apply for and approve loans online</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-C4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple levels of reviewers</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-4-D1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-4-D1');"><img alt="check" src="images_partner/dubble-check.png"> Attendance Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-4-D1" rowname="in-tt-4-D2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-D2');"><img alt="check" src="images_partner/single-check.png"> Swipe Capture from Varied Sources<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mobile attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Geo Fencing - Attendance Marking from pre-defined locations</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Integration with attendance recording devices</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Map-based mobile attendance marking with Location Tracking</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>AI-based Facial Recognition-Based Attendance Marking</p></td>
      </tr>
    </tbody>
    
	  
    <tbody>
      <tr class="level4" parent="in-tt-4-D1" rowname="in-tt-4-D3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-D3');"><img alt="check" src="images_partner/single-check.png"> Extensive Shift Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Support for multiple shifts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Shift management with automatic rotation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy shift rostering by line managers for their teams</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Sophisticated business rules for attendance exceptions</p></td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-4-D1" rowname="in-tt-4-D4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-D4');"><img alt="check" src="images_partner/single-check.png"> Highly Configurable Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Penalize unauthorized absence, late in, early out, shortfall, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Flexi hours support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Multiple attendance policies for different groups</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Customizable weekends</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-D1" rowname="in-tt-4-D5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-D5');"><img alt="check" src="images_partner/single-check.png"> Attendance Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic daily attendance processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Manual override facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Continuous absence alerts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Month-end HR review and finalization facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance muster generation</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-D1" rowname="in-tt-4-D6" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-D6');"><img alt="check" src="images_partner/single-check.png"> Overtime Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Flexible overtime payouts and eligibility policies</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Assign policies to employees by categories (department, location etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Apply Overtime in bulk or for individual employee</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Comprehensive Overtime register with hours and earnings</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-D6" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Detailed Overtime payslips with hours and earnings breakup</p></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-5-E1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-5-E1');"><img alt="check" src="images_partner/dubble-check.png"> Employee Self Onboarding<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-5-E1" rowname="in-tt-5-E2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/single-check.png"> <p>Personalised & Paperless Onboarding of Employees</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-E1" rowname="in-tt-5-E2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img alt="check" src="images_partner/single-check.png"> Workflows for Admin Review</span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-E1" rowname="in-tt-5-E2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/single-check.png"> <p>Alerts and Reminders for Employees and Admin</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-E1" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2');"><img alt="check" src="images_partner/single-check.png"> Policy Publish & Acknowledgment</span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-6-F1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-6-F1');"><img alt="check" src="images_partner/dubble-check.png"> Comprehensive Employee Exit Managment<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-6-F1" rowname="in-tt-5-F2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-F2');"><img alt="check" src="images_partner/single-check.png"> Online Resignation Application & Approval</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-F1" rowname="in-tt-5-F2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-F2');"><img alt="check" src="images_partner/single-check.png"> Customizable Multi-Department Clearance</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-F1" rowname="in-tt-5-F2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-F2');"><img alt="check" src="images_partner/single-check.png"> Exit Dashboard</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-F1" rowname="in-tt-5-F2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-F2');"><img   alt="check" src="images_partner/single-check.png"> Integration with Full & Final Settlement Process</span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-7-G1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-7-G1');"><img alt="check" src="images_partner/dubble-check.png"> Employee Portal (Web and Mobile app)<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-7-G1" rowname="in-tt-7-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-G2');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Core HR<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Social HR</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own documents and letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Access to company policies, handbook, forms, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own employee information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mobile app for employees and managers</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-7-G1" rowname="in-tt-7-G3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-G3');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Leave<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave application and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave cancellation workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online leave balances and details</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team leave information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave grant, comp-off grant workflows</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-G1" rowname="in-tt-7-G4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-4');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Payroll<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online payslips</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT calculation statement</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT proof of investments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT savings and declarations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement claims</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement statements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online reimbursement claims and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Payroll information like loan statement, YTD, PF, etc.</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-G1" rowname="in-tt-7-G5" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-G5');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Attendance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team attendance information for managers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Detailed attendance information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-G5" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"   style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Real time attendance status (who's in-who's late)</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-8-H1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-8-H1');"><img alt="check" src="images_partner/dubble-check.png"> Single Sign-on (Employee Logins with Google)</span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-9-I1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-9-I1');"><img alt="check" src="images_partner/dubble-check.png"> Automated Checklists for Task Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-9-I1" rowname="in-tt-9-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-I2');"><img alt="check" src="images_partner/single-check.png"> Built-in & User-configurable Checklists</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-I1" rowname="in-tt-9-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2');"><img alt="check" src="images_partner/single-check.png"> Real-time Collaboration Across Departments</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-I1" rowname="in-tt-9-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2');"><img alt="check" src="images_partner/single-check.png"> Alerts and Reminders on Pending Tasks</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-10-I1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-10-I1');"><img alt="check" src="images_partner/dubble-check.png"> Advanced Analytics & Reporting<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-10-I1" rowname="in-tt-10-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-I2');"><img alt="check" src="images_partner/single-check.png"> Extensive, Custom & Configurable Reports</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-I1" rowname="in-tt-10-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-I2');"><img alt="check" src="images_partner/single-check.png"> Employee Analytics Hub with Graphs & Charts</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-I1" rowname="in-tt-10-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-I2');"><img alt="check" src="images_partner/single-check.png"> Custom Notifications, Including SMS</span> </td>
      </tr>
    </tbody>
	
    <tbody>
      <tr class="level4" parent="in-tt-10-I1" rowname="in-tt-10-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-I2');"><img alt="check" src="images_partner/single-check.png"> Dashboards<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-10-I2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" src="images_partner/single-check.png"> Operational dashboards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-10-I2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" src="images_partner/single-check.png"> Configurable, Analytical dashboards</td>
      </tr>
		
	    </tbody>
	
	    <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-12-J1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-12-J1');"><img alt="check" src="images_partner/dubble-check.png"> AI-Powered Chatbot</span> </td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-13-K1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-13-K1');"><img alt="check" src="images_partner/dubble-check.png"> Access & User Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> Standard Access Management</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> User-Definable Roles & Permissions</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> Unlimited Users</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> Password Policy Configuration</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img  style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Detailed Audit Logging & Reporting of All Activities</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> IP Restriction for Access Control to Application</span> </td>
      </tr>
    </tbody>
	 
	  
	    <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-14-L1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-14-L1');"><img alt="check" src="images_partner/dubble-check.png"> Extensive Excel import & Export Facility</span> </td>
      </tr>
    </tbody>
	  
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-15-M1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-15-M1');"><img alt="check" src="images_partner/dubble-check.png"> Onboarding Support & Support plans<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-15-M1" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-M2');"><img alt="check" src="images_partner/single-check.png"> Product Training</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-M1" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-M2');"><img alt="check" src="images_partner/single-check.png"> Community Access</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-M1" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-M2');"><img alt="check" src="images_partner/single-check.png"> Onboarding Support</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-M1" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-M2');"><img alt="check" src="images_partner/single-check.png"> Ticket Support</span> </td>
      </tr>
    </tbody>
	  
	  <tbody>

      <tr class="level3" parent="in-ct-1" rowname="in-tt-16-N1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-16-N1');"><img alt="check" src="images_partner/dubble-check.png"> Business Expense Claims Management <div class="tooltip"><img alt="info-icon" style="padding-top: 5px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo</span></div> <span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-N1" rowname="in-tt-16-N2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-N2');"><img alt="check" src="images_partner/single-check.png"> Highly Customizable Business Rules<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Configure multiple claim heads</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Extensive support for business rules on limits & reviewers</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple claims form support</td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-N1" rowname="in-tt-16-N3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-N3');"><img alt="check" src="images_partner/single-check.png"> Expense Claims Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Tour advances request and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online workflow for claims by employee</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Multiple approval matrix based on claim types and amounts</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Batch payment options</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-N3" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple modes of payments</td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-17-O1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-17-O1');"><img alt="check" style="padding-top: 5px; width: 15px; margin-right: 8px;" src="images_partner/single-check.png"> Group Company Support <div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo </span></div> </span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-18-P1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-18-P1');"><img alt="check" style="padding-top: 5px; width: 15px; margin-right: 8px;" src="images_partner/single-check.png"> Enterprise Features <div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹25 per emp/mo </span></div> <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-18-P1" rowname="in-tt-18-P2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-P2');"><img alt="check" src="images_partner/single-check.png"> Single Sign-on (SAML) <div class="tooltip"><img alt="info-icon" style="padding-top: 5px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹10 per emp/mo </span></div> </span> </td>
      </tr>
      <tr class="level4" parent="in-tt-18-P1" rowname="in-tt-18-P2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-P2');"><img alt="check" src="images_partner/single-check.png"> REST API access <div class="tooltip"><img alt="info-icon" style="padding-top: 5px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add-on: ₹15 per emp/mo </span></div> </span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-19-Q1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-19-Q1');"><img style="float: left; padding-top: 5px; width: 16px; margin-right: 8px;" alt="check" src="images_partner/single-check.png"> GeoMark+ (Map-Based Attendance Marking <br>with Location Tagging)	<div class="tooltip"><img alt="info-icon" style="padding-top: 0px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹50 Per user per month. Charged based on the number of users, not the total number of employees.</span></div><span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-19-Q1" rowname="in-tt-19-Q2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-Q2');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>GPS-based Attendance Marking for Distributed Workforce</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-Q1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-Q2');"><img alt="check" src="images_partner/single-check.png"> Workflows for Manager Reviews</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-Q1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-Q2');"><img alt="check" src="images_partner/single-check.png"> Attendance Scheme-level Customizations</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-Q1" rowname="in-tt-19-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-Q2');"><img alt="check" src="images_partner/single-check.png"> Geo Swipe Reports for Due Diligence</span> </td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-1" rowname="in-tt-20-R1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-20-R1');"><img style="float: left; padding-top: 5px; 5px; width: 16px; margin-right: 8px;" alt="check" src="images_partner/single-check.png"> <p>Visage (AI-powered Facial Recognition-Based Attendance Marking)</p></span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  
	</table>
</div>	
<table class="table table-hover table-bordered table-condensed cashflow_report ">
    <thead>
      <tr>
        <th width="300px">
          
        </th>
      </tr>
    </thead>
	  
    <tbody>
      <tr>
        <td colspan="2" class="row3" style="text-align: center">
		   <img alt="greythr" src="images_partner/mbairplane-icon.png">
          <p>Enterprise</p>
          <div class="row3 pricing-cost">
            <p><sup>₹</sup>7495<span style="font-size: 16px">/month</span></p>
          </div>
          <p>(Includes 50 Employees)<br>+₹100/month per additional employee</p>
          <button class="pricing-cta">START TRIAL</button>
		  
		  </td>
      </tr>
    </tbody>
    <tbody>
      <tr parent="inflow" rowname="in-ct-2" state="collapsed">
        <td colspan="2" class="name mb-name"> <span onclick="toggle_rows(this, 'in-ct-2');"> Show Features <span style="float: none;" class="accordion-icon">+</span></span></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-0-1A" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-0-1A');"><img alt="check" src="images_partner/dubble-check.png"> Core HR <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-2A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-2A');"><img alt="check" src="images_partner/single-check.png"> Employee Information Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>An extensive employee database as a system of record</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> 20+ employee data categories</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reporting hierarchy with Org Chart</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> HR and CEO dashboards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-2A" state="leaf" style="display: none;">
		 <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee assets tracking</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-3A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-3A');"><img alt="check" src="images_partner/single-check.png"> Know Your Employee (KYE)<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Store various employee identity information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy online facility to collect identity data (Data Drives)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Track verified status (Aadhar verified, PAN verified, etc.)</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-A4" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-A4');"><img alt="check" src="images_partner/single-check.png"> Employee Documents Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Anti-virus scanning for all uploaded documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic filing of generated letters to the document store</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online access to all issued letters and documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Store digital or scanned copies of employee documents</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-A4" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bulk document upload</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-5A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-5A');"><img alt="check" src="images_partner/single-check.png"> Employee Communication<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Mass Communication to groups of employees by mail / SMS</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Social HR - employee messaging communication</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Group-wise targeting of communication</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-6A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-6A');"><img alt="check" src="images_partner/single-check.png"> Reminders and Alerts<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automated greeting cards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Notifications by social feeds</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>100+ pre-built system and employee lifecycle events</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable reminders and alerts system</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fully configurable notification templates (Build Your Own Templates)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-6A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> SMS and mobile push notifications</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-7A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-7A');"><img alt="check" src="images_partner/single-check.png"> HR Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-7A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ready-made HR MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-7A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> User-defined & Customized Report Builder</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-8A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-8A');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Extensive Labour Law reports (Shops Act, Factories Act, Maternity Benefit, Contract Labour Act, etc.)</p></span> </td>
      </tr>
    </tbody>
	  
	       <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-9A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-9A');"><img alt="check" src="images_partner/single-check.png"> Letters and Mail Merge<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee letter preparation in a few clicks</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Emailing and automatic letter filing in employee records</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic serial numbering of letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Custom fields for maximum flexibility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Letter gallery with prebuilt formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mail merge</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-0-1A" rowname="in-tt-0-10A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-0-10A');"><img alt="check" src="images_partner/single-check.png"> Company Policies and Forms<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all company policies and employee handbook</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Publish all commonly required forms & templates</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-0-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Group-wise targeting of published documents</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr parent="in-ct-2" class="level3" rowname="in-tt-0-1A" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-1-1A');"><img alt="check" src="images_partner/dubble-check.png">Payroll <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-A1" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-2A');"><img alt="check" src="images_partner/single-check.png"> Configurable Salary Structure<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary structure for any industry</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Unlimited salary components</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-2A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Off-the-shelf building blocks for complex payroll scenarios</p></td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-A3" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-3A');"><img alt="check" src="images_partner/single-check.png"> Payroll Inputs<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic leave inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Automatic attendance inputs from other greytHR modules</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Increments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-time payments and deductions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Final settlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> LOP and LOP reversals</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Full-fledged arrears processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Stop payment with release feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-3A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-4A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-4A');"><img alt="check" src="images_partner/single-check.png"> Loans and Salary Advances<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Salary advance with auto deduction in next payroll</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Manage company loans to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Fixed rate interest, EMI, no interest, reducing balance</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Auto calculation and deductions in payroll</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Pause loan deductions for a specified period</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic closure on completion of repayment</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Ability to report on principal and interest portions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Loan prepayment and balloon payment features</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-4A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic calculation of loan perquisite</td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-5A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-5A');"><img alt="check" src="images_partner/single-check.png"> Payroll Reimbursements & Expenses<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Extensive reimbursement configurations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Monthly / annual entitlements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Claim processing with limit checking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Excess claims tracking and set-off feature</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-5A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Online reimbursement claim workflow</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-6A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-6A');"><img alt="check" src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-7A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-7A');"><img alt="check" src="images_partner/single-check.png"> Payroll Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Single click payroll process</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Guided payroll processing with checklist</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-7A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img  alt="check"  src="images_partner/single-check.png"> Lock feature to close payroll processing</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-A8" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-8A');"><img alt="check" src="images_partner/single-check.png"> Verification and Reconciliations<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Easy export to Excel facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly customizable salary register and payroll statements</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive reconciliations tools</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-8A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payroll comparison and difference analysis</td>
      </tr>
    </tbody>
	  
	  
	     <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-9A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-9A');"><img alt="check" src="images_partner/single-check.png"> Statutory Compliance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> PF calculations with ECR generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> ESI computations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Professional Tax with all state specific rules built in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Labour Welfare Fund calculation and deductions</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Comprehensive TDS (IT) calculations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Digitally signed Form 16 generation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy Form 24Q generation and automatic FVU validation</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-9A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Bonus calculations and reporting</td>
      </tr>
    </tbody>
	  
	  
	  
	       <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-10A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-10A');"><img alt="check" src="images_partner/single-check.png"> Payslip Generation and Distribution<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Payslip gallery with multiple payslip formats</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> One-click payslip distribution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Download payslips into a single or multiple PDF files and distribute by email</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Distribute payslips via employee portal or mobile</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-10A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Separate reimbursement payslips</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-11A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-11A');"><img alt="check" src="images_partner/single-check.png"> Payroll Reports<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> MIS reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reconciliation reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable payroll statement / salary register / wage register</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Ad-hoc report builder</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Salary analytics</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Professional Tax reports</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Reports under Shops and Establishment Acts of various states</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports under CLRA Act of various states</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-11A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Digitally signed Form 16 and Form 24Q files with FVU validation</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-12A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-12A');"><img alt="check" src="images_partner/single-check.png"> Accounts JV<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Extensive Excel output capabilities</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Inbuilt formats for Tally and QBO</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-12A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Highly configurable Accounts JV with split by cost center/employees</p></td>
      </tr>
    </tbody>
	  
	   
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-13A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-13A');"><img  alt="check" src="images_partner/single-check.png"> PayNow-Direct Salary Transfer to Employee Bank<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Direct salary transfers to employees</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Secured OTP based Authentication</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Real time transaction & account balance status</td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-13A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reports & Audit Trail</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-1-1A" rowname="in-tt-1-14A" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-1-14A');"><img alt="check" src="images_partner/single-check.png"> Payout and Disbursements<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Handle multiple payment modes - cash, cheque, bank transfer</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>All major bank transfer electronic formats built-in</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to release payments in batches</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-1-14A" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Facility to track status for cash and cheque payments</p></td>
      </tr>
    </tbody>
	  
   <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-0-1B" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-2-1B');"><img alt="check" src="images_partner/dubble-check.png"> Leave Management <span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-1B" rowname="in-tt-2-2B" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-2B');"><img alt="check" src="images_partner/single-check.png"> Fully Customizable Leave Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Unlimited leave types (annual, privilege, maternity, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Supports multiple types of leave transactions</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Holiday lists - Location and Project based</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Restricted (optional) holidays support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-2B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-2-1B" rowname="in-tt-2-3B" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-2-3B');"><img alt="check" src="images_partner/single-check.png"> Manage Balance and Transactions<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic tracking of leave balances</td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy year end processing (lapsing, carry-forward, etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-2-3B" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Online leave application and review with Multi-Level Approval Workflow</p></td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>

      <tr class="level3" parent="in-ct-2" rowname="in-tt-3-1C" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-3-1C');"><img alt="check" src="images_partner/dubble-check.png"> Employee Workflows for Process Automation<span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-3-1C" rowname="in-tt-3-2C" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-2C');"><img alt="check" src="images_partner/single-check.png"> Employee Helpdesk Query & Resolution Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple categories of queries</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Category-based reviewers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online employee query logging & resolution</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> SLA tracking & extensive reporting</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Multiple leave policies for different group of employees</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-2C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Customizable leave policy for each leave type</p></td>
      </tr>
    </tbody>
    
	   <tbody>
      <tr class="level4" parent="in-tt-3-1C" rowname="in-tt-3-3C" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-3C');"><img alt="check" src="images_partner/single-check.png"> Confirmation Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Auto-initiated confirmations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Easy confirmation & probation extension</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-3C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Confirmation letters</td>
      </tr>
    </tbody>
	  
	  <tbody>
      <tr class="level4" parent="in-tt-3-1C" rowname="in-tt-3-4C" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-3-4C');"><img alt="check" src="images_partner/single-check.png"> Loan Apply & Approval Workflow<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Customized loan and salary advance policies</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Apply for and approve loans online</td>
      </tr>
      <tr class="level4-1" parent="in-tt-3-4C" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple levels of reviewers</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-4-1D" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-4-1D');"><img alt="check" src="images_partner/dubble-check.png"> Attendance Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-4-1D" rowname="in-tt-4-2D" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-2D');"><img alt="check" src="images_partner/single-check.png"> Swipe Capture from Varied Sources<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mobile attendance marking</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Geo Fencing - Attendance Marking from pre-defined locations</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Integration with attendance recording devices</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Map-based mobile attendance marking with Location Tracking</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-2D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>AI-based Facial Recognition-Based Attendance Marking</p></td>
      </tr>
    </tbody>
    
	  
    <tbody>
      <tr class="level4" parent="in-tt-4-1D" rowname="in-tt-4-3D" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-3D');"><img alt="check" src="images_partner/single-check.png"> Extensive Shift Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Support for multiple shifts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Shift management with automatic rotation</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Easy shift rostering by line managers for their teams</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-3D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Sophisticated business rules for attendance exceptions</p></td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level4" parent="in-tt-4-1D" rowname="in-tt-4-4D" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-4D');"><img alt="check" src="images_partner/single-check.png"> Highly Configurable Policies<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Penalize unauthorized absence, late in, early out, shortfall, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Flexi hours support</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Multiple attendance policies for different groups</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-4D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Customizable weekends</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-1D" rowname="in-tt-4-5D" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-5D');"><img alt="check" src="images_partner/single-check.png"> Attendance Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Automatic daily attendance processing</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Manual override facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Continuous absence alerts</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Month-end HR review and finalization facility</td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-5D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance muster generation</td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level4" parent="in-tt-4-1D" rowname="in-tt-4-6D" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-4-6D');"><img alt="check" src="images_partner/single-check.png"> Overtime Management<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Flexible overtime payouts and eligibility policies</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Assign policies to employees by categories (department, location etc.)</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Apply Overtime in bulk or for individual employee</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Comprehensive Overtime register with hours and earnings</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-4-6D" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Detailed Overtime payslips with hours and earnings breakup</p></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-5-1E" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-5-1E');"><img alt="check" src="images_partner/dubble-check.png"> Employee Self Onboarding<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-5-1E" rowname="in-tt-5-2E" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2E');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/single-check.png"> <p>Personalised & Paperless Onboarding of Employees</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1E" rowname="in-tt-5-E2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2E');"><img alt="check" src="images_partner/single-check.png"> Workflows for Admin Review</span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1E" rowname="in-tt-5-E2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2E');"><img  style="float: left; padding-top: 5px;"  alt="check" src="images_partner/single-check.png"> <p>Alerts and Reminders for Employees and Admin</p></span></td>
      </tr>
      <tr class="level4" parent="in-tt-5-1E" rowname="in-tt-5-2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-5-2E');"><img alt="check" src="images_partner/single-check.png"> Policy Publish & Acknowledgment</span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-6-1F" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-6-1F');"><img alt="check" src="images_partner/dubble-check.png"> Comprehensive Employee Exit Managment<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-6-1F" rowname="in-tt-5-2F" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2F');"><img alt="check" src="images_partner/single-check.png"> Online Resignation Application & Approval</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1F" rowname="in-tt-5-2F" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2F');"><img alt="check" src="images_partner/single-check.png"> Customizable Multi-Department Clearance</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1F" rowname="in-tt-5-2F" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2F');"><img alt="check" src="images_partner/single-check.png"> Exit Dashboard</span></td>
      </tr>
      <tr class="level4" parent="in-tt-6-1F" rowname="in-tt-5-2F" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-6-2F');"><img   alt="check" src="images_partner/single-check.png"> Integration with Full & Final Settlement Process</span></td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-7-1G" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-7-1G');"><img alt="check" src="images_partner/dubble-check.png"> Employee Portal (Web and Mobile app)<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-7-1G" rowname="in-tt-7-2G" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-2G');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Core HR<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Social HR</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Employee directory</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own documents and letters</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img style="float: left; padding-top: 5px;" alt="check"  src="images_partner/single-check.png"> <p>Access to company policies, handbook, forms, etc.</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Access to own employee information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-2G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Mobile app for employees and managers</td>
      </tr>
    </tbody>
	  
	     <tbody>
      <tr class="level4" parent="in-tt-7-1G" rowname="in-tt-7-3G" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-3G');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Leave<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave application and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave cancellation workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online leave balances and details</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team leave information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-3G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Leave grant, comp-off grant workflows</td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-1G" rowname="in-tt-7-4G" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-4G');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Payroll<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online payslips</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT calculation statement</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT proof of investments</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> IT savings and declarations</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Flexible Benefit Plan (FBP) Declaration</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement claims</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Reimbursement statements</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online reimbursement claims and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-4G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Payroll information like loan statement, YTD, PF, etc.</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-1G" rowname="in-tt-7-5G" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-5G');"><img alt="check" src="images_partner/single-check.png"> Employee Portal - Attendance<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Attendance regularization workflow</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Team attendance information for managers</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Detailed attendance information</td>
      </tr>
      <tr class="level4-1" parent="in-tt-7-5G" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"   style="float: left; padding-top: 5px;" src="images_partner/single-check.png"> <p>Real time attendance status (who's in-who's late)</p></td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level4" parent="in-tt-7-1G" rowname="in-tt-7-5G" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-7-5G');"><img alt="check" src="images_partner/single-check.png"> Single Sign-on (Employee Logins with Google)<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-9-1I" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-9-1I');"><img alt="check" src="images_partner/dubble-check.png"> Automated Checklists for Task Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-9-1I" rowname="in-tt-9-2I" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2I');"><img alt="check" src="images_partner/single-check.png"> Built-in & User-configurable Checklists</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-1I" rowname="in-tt-9-2I" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2I');"><img alt="check" src="images_partner/single-check.png"> Real-time Collaboration Across Departments</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-9-1I" rowname="in-tt-9-2I" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-9-2I');"><img alt="check" src="images_partner/single-check.png"> Alerts and Reminders on Pending Tasks</span> </td>
      </tr>
    </tbody>
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-10-1J" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-10-1J');"><img alt="check" src="images_partner/dubble-check.png"> Advanced Analytics & Reporting<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-10-1J" rowname="in-tt-10-2J" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2J');"><img alt="check" src="images_partner/single-check.png"> Extensive, Custom & Configurable Reports</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-1J" rowname="in-tt-10-2J" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2J');"><img alt="check" src="images_partner/single-check.png"> Employee Analytics Hub with Graphs & Charts</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-10-1J" rowname="in-tt-10-2J" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-2J');"><img alt="check" src="images_partner/single-check.png"> Custom Notifications, Including SMS</span> </td>
      </tr>
    </tbody>
	
    <tbody>
      <tr class="level4" parent="in-tt-10-I1" rowname="in-tt-10-I2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-10-I2');"><img alt="check" src="images_partner/single-check.png"> Dashboards<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-10-I2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" src="images_partner/single-check.png"> Operational dashboards</td>
      </tr>
      <tr class="level4-1" parent="in-tt-10-I2" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check" src="images_partner/single-check.png"> Configurable, Analytical dashboards</td>
      </tr>
		
	    </tbody>
	
	    <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-12-K1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-12-K1');"><img alt="check" src="images_partner/dubble-check.png"> AI-Powered Chatbot</span> </td>
      </tr>
    </tbody>
	  
	   <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-13-1K" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-13-1K');"><img alt="check" src="images_partner/dubble-check.png"> Access & User Management<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-13-1K" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2K');"><img alt="check" src="images_partner/single-check.png"> Standard Access Management</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1K" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2K');"><img alt="check" src="images_partner/single-check.png"> User-Definable Roles & Permissions</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1K" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2K');"><img alt="check" src="images_partner/single-check.png"> Unlimited Users</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-1K" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-2K');"><img alt="check" src="images_partner/single-check.png"> Password Policy Configuration</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img  style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>Detailed Audit Logging & Reporting of All Activities</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-13-K1" rowname="in-tt-13-2K" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-13-K2');"><img alt="check" src="images_partner/single-check.png"> IP Restriction for Access Control to Application</span> </td>
      </tr>
    </tbody>
	 
	  
	    <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-14-1L" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-14-1L');"><img alt="check" src="images_partner/dubble-check.png"> Extensive Excel import & Export Facility</span> </td>
      </tr>
    </tbody>
	  
	  
	  
	   <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-15-1M" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-15-1M');"><img alt="check" src="images_partner/dubble-check.png"> Onboarding Support & Support plans<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-15-1M" rowname="in-tt-15-2M" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2M');"><img alt="check" src="images_partner/single-check.png"> Product Training</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1M" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2M');"><img alt="check" src="images_partner/single-check.png"> Community Access</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1M" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2M');"><img alt="check" src="images_partner/single-check.png"> Onboarding Support</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-15-1M" rowname="in-tt-15-M2" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-15-2M');"><img alt="check" src="images_partner/single-check.png"> Ticket Support</span> </td>
      </tr>
    </tbody>
	  
	  <tbody>

      <tr class="level3" parent="in-ct-2" rowname="in-tt-16-1N" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-16-1N');"><img alt="check" src="images_partner/dubble-check.png"> Business Expense Claims Management<span class="accordion-icon">+</span></span> </td>
      </tr>

    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-1N" rowname="in-tt-16-2N" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-2N');"><img alt="check" src="images_partner/single-check.png"> Highly Customizable Business Rules<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Configure multiple claim heads</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Extensive support for business rules on limits & reviewers</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-2N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple claims form support</td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-16-1N" rowname="in-tt-16-3N" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-16-3N');"><img alt="check" src="images_partner/single-check.png"> Expense Claims Processing<span class="accordion-icon">+</span></span> </td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Tour advances request and review</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Online workflow for claims by employee</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  style="float: left; padding-top: 5px;"  src="images_partner/single-check.png"> <p>Multiple approval matrix based on claim types and amounts</p></td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Batch payment options</td>
      </tr>
      <tr class="level4-1" parent="in-tt-16-3N" state="leaf" style="display: none;">
		  <td class="name single-check" colspan="2" lass="name"><img alt="check"  src="images_partner/single-check.png"> Multiple modes of payments</td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-17-0O1" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-17-0O1');"><img alt="check" style="padding-top: 5px; width: 15px; margin-right: 8px;" src="images_partner/single-check.png"> Group Company Support</span> </td>
      </tr>
    </tbody>
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-18-1P" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-18-1P');"><img alt="check" style="padding-top: 5px; width: 15px; margin-right: 8px;" src="images_partner/single-check.png"> Enterprise Features<span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-18-1P" rowname="in-tt-18-2P" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-2P');"><img alt="check" src="images_partner/single-check.png"> Single Sign-on (SAML)</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-18-1P" rowname="in-tt-18-2P" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-18-2P');"><img alt="check" src="images_partner/single-check.png"> REST API access</span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-19-1Q" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-19-1Q');"><img style="float: left; padding-top: 5px; width: 16px; margin-right: 8px;" alt="check" src="images_partner/single-check.png"> GeoMark+ (Map-Based Attendance Marking <br>with Location Tagging)	<div class="tooltip"><img alt="info-icon" style="padding-top: 0px; width: 20px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹50 Per user per month. Charged based on the number of users, not the total number of employees.</span></div><span class="accordion-icon">+</span></span> </td>
      </tr>
    </tbody>
    
    <tbody>
      <tr class="level4" parent="in-tt-19-1Q" rowname="in-tt-19-2Q" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2Q');"><img style="float: left; padding-top: 5px;" alt="check" src="images_partner/single-check.png"> <p>GPS-based Attendance Marking for Distributed Workforce</p></span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1Q" rowname="in-tt-19-2Q" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2Q');"><img alt="check" src="images_partner/single-check.png"> Workflows for Manager Reviews</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1Q" rowname="in-tt-19-2Q" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2Q');"><img alt="check" src="images_partner/single-check.png"> Attendance Scheme-level Customizations</span> </td>
      </tr>
      <tr class="level4" parent="in-tt-19-1Q" rowname="in-tt-19-2Q" state="collapsed" style="display: none;">
		  <td colspan="2"  class="name single-check"><span onclick="toggle_rows(this, 'in-tt-19-2Q');"><img alt="check" src="images_partner/single-check.png"> Geo Swipe Reports for Due Diligence</span> </td>
      </tr>
    </tbody>
	  
	  	  
	    <tbody>
      <tr class="level3" parent="in-ct-2" rowname="in-tt-20-1R" state="collapsed" style="display: none;">
        <td colspan="2"  class="name dubble-check"><span onclick="toggle_rows(this, 'in-tt-20-1R');"><img style="float: left; padding-top: 5px; 5px; width: 16px; margin-right: 8px;" alt="check" src="images_partner/single-check.png"> Visage (AI-powered Facial Recognition-Based Attendance Marking)<div class="tooltip"><img alt="info-icon" style="padding-top: 0px;" src="images_partner/info-icon.svg"><span class="tooltiptext">Add on ₹20 Per user per month. Charged based on the number of users, not the total number of employees.</span></div> </span> </td>
      </tr>
    </tbody>
	  
	  
	  
	  
	</table>
			  
			  </div>
          </div>
        </div>
      </div>
    </div>
	  
	  
	  	  <div>
      <div
        style="background-color:#FFF">
        <div class="container">
          <div class="flex-container" style="padding-bottom: 0;">
            <h2 class="styled-heading" style="text-align: center;">You're in good company
              <!-- --> 
              <span class="only-orange-text">with greytHR.</span>
            </h2>
            <div class="feature-img-wrapper"><img class="serves-image" src="images_partner/Logos.png" alt="iso"></div>
          </div>
        </div>
      </div>
    </div>
	  
	  
	  <div>
      <div
        style="background-color:#FFF">
        <div class="container">
          <div class="flex-container">
            <h2 class="styled-heading" style="text-align: center; margin-bottom: 0;">Rated 'Leader' on G2 Crowd.
              <!-- --> 
              <span class="only-orange-text">Check out our reviews.</span>
            </h2>
			   <div class="feature-section-description">greytHR has a G2 rating of 4.4 out of 5.</div>
            <div class="feature-img-wrapper"><img class="serves-image" src="images_partner/Badges.png" alt="iso"></div>
			 <img class="serves-image serves-image-1" src="images_partner/company-milestone.png" alt="iso">
          </div>
        </div>
      </div>
    </div>
	  
	   

	  
	  <div>
      <div
        style="background-image:url(images_partner/twainPastel1.svg);background-repeat:no-repeat;background-position:center top;background-color:#FFF">
        <div class="container">
          <div class="flex-container">
            <h2 class="styled-heading" style="text-align: center;  margin-bottom: 0;">Great for HR.
              <!-- --> <span class="only-orange-text">Great beyond HR.</span>
            </h2>
            <div class="feature-section-description">greytHR comes to you from Greytip Software, a pioneer with over 25 years experience in the field of HR automation. Clients also gain immediate access to a vibrant HR community, helpful learning resources and proactive customer service.</div>
            <div class="feature-list">
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/iso.png" alt="iso" class="feature-img"></div>
                <div class="feature-caption">World class ISO certified data security</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/training.png" alt="training" class="feature-img">
                </div>
                <div class="feature-caption">Free training sessions</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/pay.png" alt="pay" class="feature-img"></div>
                <div class="feature-caption">Pay as you use model</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/cloud-platform.png" alt="cloud" class="feature-img">
                </div>
                <div class="feature-caption">Scalable cloud platform</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/service.png" alt="service" class="feature-img"></div>
                <div class="feature-caption">Implementation support &amp; proactive customer service</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/network.png" alt="network" class="feature-img"></div>
                <div class="feature-caption">Network through the greytribe HR community</div>
              </div>
              <div class="feature">
                <div class="feature-img-wrapper"><img src="images_partner/academy.png" alt="academy" class="feature-img"></div>
                <div class="feature-caption">Upskill and grow with the greytHR Academy</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	  
	  

    <div>
      <div
        style="background-image:url(images_partner/twainPastel2.svg);background-repeat:no-repeat;background-position:center top;background-color:#F4873D">
        <div class="container">
          <div class="cta-section">
           <div class="module-box image-left-module-box">
                <div class="module-image-wrapper"><img style="width: 400px" src="images_partner/free-demo.svg" alt="Attendance Management"
                    class="module-image"></div>
                 <div class="cta-content">
              <h2 class="styled-heading">Get hands-on <span style="color: white;">with greytHR.</span></h2>
              <div>
                <a href="#gartner-demo-form-first-field">
                  <button>Request a demo</button>
                </a>
              </div>
            </div>
              </div>
          </div>
        </div>
      </div>
		<div
        style="background-image:url(images_partner/twainPastel2.svg);background-repeat:no-repeat;background-position:center top;background-color:#fff">
        <div class="container">
			<div class="flex-container" style="padding-bottom: 0;">
             <h2 class="styled-heading" style="text-align: center;">Useful Information on
              <!-- --> 
              <span class="only-orange-text">HR and Payroll</span>
            </h2>
			</div>
          <div class="cta-section">
			 
            <div class="module-box image-left-module-box" style="margin-top: 0;">
                <div class="card">
					<img alt="greythr" src="images_partner/attendance-management.png">
					<div class="card-item">
                  <p style="font-size: 20px;">The Ultimate Guide to Leave Management</p>
					<p>Everything you need to know about managing leave for your company</p>
					<a style="color: #F4873D; text-decoration: none;" target="_blank" href="https://www.greythr.com/middle-east/leave-management/ultimate-guide-to-leave-management/">Know more</a>
					</div>
				
                </div>
				
              <div class="card">
					<img alt="greythr" src="images_partner/HRMS.png">
					<div class="card-item">
                  <p style="font-size: 20px;">A Guide to HRMS<br>
                  </p>
					<p>Everything you need to know about Human Resources Management Systems (HRMS)</p>
					<a style="color: #F4873D; text-decoration: none;" target="_blank" href="https://www.greythr.com/middle-east/complete-guide-hrms/">Know more</a>
					</div>
                </div>
				<div class="card">
					<img alt="greythr" src="images_partner/HR-banner.png">
					<div class="card-item">
                  <p style="font-size: 20px;">A Guide to Attendance Management</p>
					<p>Everything you need to know about managing attendance for your company</p>
					<a style="color: #F4873D; text-decoration: none;" target="_blank" href="https://www.greythr.com/middle-east/guide-to-attendance-management/">Know more</a>
					</div>

                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>