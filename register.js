var app = new Vue({
	el: '#register',
	data:{
		successMessage: "",
		errorMessage: "",
		errorEmail: "",
		errorPassword: "",
		errorcPassword: "",
		errorUserName: "",
		errorEthAddress: "",
		users: [],
		regDetails: {userName: '', email: '', ethAddress: '', password: '', cPassword: ''},
	},

	mounted: function(){
		this.getAllUsers();
	},

	methods:{
		getAllUsers: function(){
			axios.get('api.php')
				.then(function(response){
					if(response.data.error){
						app.errorMessage = response.data.message;
					}
					else{
						app.users = response.data.users;
					}
				});
		},

		userReg: function(){
			var regForm = app.toFormData(app.regDetails);
			axios.post('api.php?action=register', regForm)
				.then(function(response){
					console.log(response);
					if(response.data.userName){
						app.errorUserName = response.data.message;
						app.focusUserName();
						app.clearMessage();
					}
					else if(response.data.email){
						app.errorEmail = response.data.message;
						app.focusEmail();
						app.clearMessage();
					}
					else if(response.data.ethAddress){
						app.errorEthAddress = response.data.message;
						app.focusEthAddress();
						app.clearMessage();
					}
					else if(response.data.password){
						app.errorPassword = response.data.message;
						app.errorEmail='';
						app.focusPassword();
						app.clearMessage();
					}					
					else if(response.data.cPassword){
						app.errorCPassword = response.data.message;
						app.focusCPassword();
						app.clearMessage();
					}					
					else if(response.data.error){
						app.errorMessage = response.data.message;
						app.errorEmail='';
						app.errorPassword='';
					}
					else{
						app.successMessage = response.data.message;
					 	app.regDetails= {userName: '', email: '', ethAddress: '', password: '', cPassword: ''},
					 	app.errorEmail='';
						app.errorPassword='';
					 	app.getAllUsers();
					}
				});
		},

		focusEmail: function(){
			this.$refs.email.focus();
		},

		focusPassword: function(){
			this.$refs.password.focus();
		},

		focusCPassword: function(){
			this.$refs.cPassword.focus();
		},

		focusUserName: function(){
			this.$refs.userName.focus();
		},

		focusEthAddress: function(){
			this.$refs.ethAddress.focus();
		},

		keymonitor: function(event) {
       		if(event.key == "Enter"){
         		app.userReg();
        	}
       	},

		toFormData: function(obj){
			var form_data = new FormData();
			for(var key in obj){
				form_data.append(key, obj[key]);
			}
			return form_data;
		},

		clearMessage: function(){
			app.errorMessage = '';
			app.successMessage = '';
		}

	}
});