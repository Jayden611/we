<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<br/><a href="login.php"
        style="color: black">Login</a>
<div class="container">
	<h1 class="page-header text-center">Registration</h1>
	<div id="register">
		<div class="col-md-4">
			
			<div class="panel panel-primary">
			  	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> User Registration</div>
			  	<div class="panel-body">
			    	<label>User Name:</label>
			    	<input type="text" class="form-control" ref="email" v-model="regDetails.userName" v-on:keyup="keymonitor">
			    	<div class="text-center" v-if="errorUserName">
			    		<span style="font-size:13px;">{{ errorUserName }}</span>
			    	</div>
					<label>Email:</label>
			    	<input type="text" class="form-control" ref="email" v-model="regDetails.email" v-on:keyup="keymonitor">
			    	<div class="text-center" v-if="errorEmail">
			    		<span style="font-size:13px;">{{ errorEmail }}</span>
			    	</div>
					<label>ETH Address:</label>
			    	<input type="text" class="form-control" ref="email" v-model="regDetails.ethAddress" v-on:keyup="keymonitor">
			    	<div class="text-center" v-if="errorEthAddress">
			    		<span style="font-size:13px;">{{ errorEthAddress }}</span>
			    	</div>
			    	<label>Password:</label>
			    	<input type="password" class="form-control" ref="password" v-model="regDetails.password" v-on:keyup="keymonitor">
			    	<div class="text-center" v-if="errorPassword">
			    		<span style="font-size:13px;">{{ errorPassword }}</span>
			    	</div>
					<label>Confirm Password:</label>
			    	<input type="password" class="form-control" ref="cPassword" v-model="regDetails.cPassword" v-on:keyup="keymonitor">
			    	<div class="text-center" v-if="errorcPassword">
			    		<span style="font-size:13px;">{{ errorCPassword }}</span>
			    	</div>
			  	</div>
			  	<div class="panel-footer">
			  		<button class="btn btn-primary btn-block" @click="userReg();"><span class="glyphicon glyphicon-check"></span> Sign up</button>
			  	</div>
			</div>

			<div class="alert alert-danger text-center" v-if="errorMessage">
				<button type="button" class="close" @click="clearMessage();"><span aria-hidden="true">&times;</span></button>
				<span class="glyphicon glyphicon-alert"></span> {{ errorMessage }}
			</div>

			<div class="alert alert-success text-center" v-if="successMessage">
				<button type="button" class="close" @click="clearMessage();"><span aria-hidden="true">&times;</span></button>
				<span class="glyphicon glyphicon-check"></span> {{ successMessage }}
			</div>

		</div>

		<div class="col-md-8">
			<h3>Users Table</h3>
			<table class="table table-bordered table-striped">
				<thead>
					<th>User Name</th>
					<th>Email</th>
					<th>ETH Address</th>
					<th>Join Date</th>
					<th></th>
				</thead>
				<tbody>
					<tr v-for="user in users">
						<td>{{ user.userName }}</td>
						<td>{{ user.email }}</td>
						<td>{{ user.ethAddress }}</td>
						<td>{{ user.creationDate }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="vue.js"></script>
<script src="axios.js"></script>
<script src="register.js"></script>
</body>
</html>