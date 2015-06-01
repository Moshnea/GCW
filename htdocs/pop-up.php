<div id="openModal" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<br><br>
				<form action="" method="post" class="contact-form">
					<h1>Contact Form 
						<span>Please fill all the texts in the fields.</span>
					</h1>
					<label>
						<span>Your username:</span>
						<input id="name" type="text" name="user" size="20" placeholder="Your username" />
					</label>
					
					<label>
						<span>Message :</span>
						<textarea id="message" name="message" placeholder="Your Message to Us"></textarea>
					</label> 
					<label>
						<span>&nbsp;</span> 
						<input type="submit" class="button" value="Send" title="Send" /> 
					</label>    
				</form>
	</div>
</div>

<div id="openLog" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<br><br>
				<form action="login.php" method="post" class="contact-form">
					<h1>Log in 
						<span>Please fill all the texts in the fields.</span>
					</h1>
					
					<label>
						<span>Username: </span>
						<input id="message" type="text" name="username" size="20" placeholder="Username" />
					</label>
					<br>
					<label>
						<span>Password: </span>
						<input id="message" type="password"  name="password" size="20" placeholder="Password" />
					</label> 
					<br>
					 <label>
						<span>&nbsp;</span> 
						<input type="submit" class="button" value="Login" title="Login" /> 
					</label>    
					<br>
				</form>
	</div>
</div>

<div id="openReg" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<br><br>
				<form action="register.php" method="post" class="contact-form">
					<h1>Register
						<span>Please fill all the texts in the fields.</span>
					</h1>
					<label>
						<span>Username: </span>
						<input id="mssage" type="text" name="username" size="20" placeholder="Username" />
					</label>					
					<br>
					<label>
						<span>Name: </span>
						<input id="message" type="text"  name="nume" size="20" placeholder="Name" />
					</label> 
					<br>
					<label>
						<span>Last Name: </span>
						<input id="message" type="text"  name="prenume" size="20" placeholder="Last Name" />
					</label>
					<br>
					<label>
						<span>Password: </span>
						<input id="message" type="password"  name="password" size="20" placeholder="Password" />						
					</label>
					<br>
					<label>
						<span>Confirm Password: </span>
						<input id="message" type="password"  name="cpassword" size="20" placeholder="Confirm Password" />
					</label> 
					<br>
					<label>
						<span>&nbsp;</span> 
						<input type="submit" class="button" value="Register" title="Register" /> 
					</label>    
				</form>
	</div>
</div>


<script type="text/javascript">
	function openContact(){
		document.getElementById("contact").style.display = "block";
		document.getElementById("contact").style.overflow = "hidden";
	}

	document.getElementById("contactClsBtn").onclick = function(){
		document.getElementById("contact").style.display = "none";	
	}
</script>